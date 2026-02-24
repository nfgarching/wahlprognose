# Benutzerflüsse

## Übersicht: Gast vs. registrierter Nutzer

```
Nutzer besucht /prognose
        │
        ├── Gast (nicht eingeloggt)
        │       │
        │       ├── Pseudonym eingeben
        │       ├── Kandidaten auswählen
        │       ├── Sitze verteilen
        │       └── Abgeben → neuer Forecast-Eintrag (mit IP gespeichert)
        │               │
        │               └── Kein Update möglich (keine Identität)
        │
        └── Registrierter Nutzer (eingeloggt)
                │
                ├── mount() lädt bestehende Prognose (falls vorhanden)
                │
                ├── [Keine bestehende Prognose]
                │       └── Abgeben → neuer Eintrag, an user_id gebunden
                │
                └── [Bestehende Prognose vorhanden]
                        │
                        ├── vor 07.03.2026 23:59:59
                        │       └── Abgeben → updateOrCreate → überschreibt Eintrag
                        │
                        └── nach 07.03.2026 23:59:59
                                └── Formular wird read-only angezeigt
                                    Submit-Button nicht sichtbar
```

---

## Validierungsregeln (server-seitig, `submit()`)

| Feld | Regel | Fehlermeldung |
|---|---|---|
| `pseudonym` | required, max:50 | "Bitte gib ein Pseudonym an." |
| `selectedMayorCandidates` | required, array, min:1, max:2 | "Bitte wähle mindestens einen Bürgermeisterkandidaten." |
| `seatDistribution` | required, array, Summe = 24 | "Die Sitzverteilung muss genau 24 Sitze ergeben." |

Zusätzlich prüft `submit()` die Deadline über `$this->canEdit`. Bei Verstoß wird ein `general`-Fehler gesetzt.

### Client-seitige Vorab-Validierung (Alpine.js + Livewire)

Der Submit-Button hat `@disabled(...)` und ist deaktiviert, solange:
- `$pseudonym` leer ist
- weniger als 1 Kandidat ausgewählt ist
- `$this->remainingSeats !== 0`

Dies verhindert unnötige Server-Requests, ersetzt aber nicht die server-seitige Validierung.

---

## Deadline-Verhalten im Detail

**Konstante:** `ForecastForm::EDIT_DEADLINE = '2026-03-07 23:59:59'`

| Zustand | `canEdit` | `deadlinePassed` | Ergebnis |
|---|---|---|---|
| Keine bestehende Prognose | `true` | `false` | Formular aktiv |
| Bestehende Prognose, vor Deadline, eingeloggt | `true` | `false` | Formular editierbar, "Prognose aktualisieren" |
| Bestehende Prognose, nach Deadline, eingeloggt | `false` | `true` | Formular read-only, Amber-Hinweistext |
| Gast mit neuer Prognose | `true` | `false` | Formular aktiv, nach Absenden kein Update |

---

## Stichwahl-Logik

```
1 Kandidat gewählt  →  Prognose: Direktsieg
                        mayor_candidate_1_id = X
                        mayor_candidate_2_id = null
                        mayor_runoff_winner_id = null

2 Kandidaten gewählt → Prognose: Stichwahl
                        mayor_candidate_1_id = X
                        mayor_candidate_2_id = Y
                        mayor_runoff_winner_id = null | X | Y  (optional)
```

Das Stichwahl-Panel erscheint automatisch wenn `hasRunoff === true`. Es kann durch Abwahl eines Kandidaten wieder geschlossen werden. Beim Abwählen wird `mayorRunoffWinnerId` automatisch zurückgesetzt.

---

## Sitzverteilungs-Flow

```
mount()
  └── seatDistribution = { party_1: 0, party_2: 0, ..., party_6: 0 }

User klickt +
  └── wire:click → incrementSeats($partyId)
        ├── Guard: remainingSeats > 0
        └── seatDistribution[$partyId]++
              └── Alpine.js: remaining = 24 - sum → Update ohne Server-Roundtrip

User klickt −
  └── wire:click → decrementSeats($partyId)
        ├── Guard: seatDistribution[$partyId] > 0
        └── seatDistribution[$partyId]--

User klickt Abgeben (submit)
  ├── Livewire-Validierung
  ├── Forecast::updateOrCreate / create
  ├── forecast->seats()->delete()
  └── foreach seatDistribution → ForecastSeat::create
```

---

## Flash-Zustände im UI

| Situation | Anzeige |
|---|---|
| Erfolgreiche Speicherung | Grünes Banner "Deine Prognose wurde gespeichert!" |
| Gast nach Speicherung | Hinweis auf Registrierung für spätere Bearbeitung |
| Eingeloggter Nutzer nach Speicherung, vor Deadline | Hinweis "Du kannst sie bis zum 07.03.2026 noch ändern." |
| Deadline abgelaufen, bestehende Prognose | Amber-Banner (read-only Hinweis) |
| Validierungsfehler | Inline unter dem jeweiligen Feld |
| Deadline-Verstoß (submit() bypass) | Roter `general`-Fehler-Banner |
