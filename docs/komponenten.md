# Livewire-Komponente: ForecastForm

**Klasse:** `App\Livewire\ForecastForm`
**View:** `resources/views/livewire/forecast-form.blade.php`
**Route:** `GET /prognose` → `forecast`
**Layout:** `layouts.public` (funktioniert für Gäste und eingeloggte Nutzer)

---

## Public Properties (Livewire-State)

| Property | Typ | Beschreibung |
|---|---|---|
| `$pseudonym` | `string` | Anzeigename, per `wire:model.live` an Input gebunden |
| `$selectedMayorCandidates` | `array<int>` | IDs der gewählten Kandidaten (max. 2) |
| `$mayorRunoffWinnerId` | `?int` | ID des prognostizierten Stichwahl-Gewinners |
| `$seatDistribution` | `array<int, int>` | `party_id → Sitze`, bei `mount()` mit 0 initialisiert |
| `$saved` | `bool` | Steuert die Erfolgs-Banner-Anzeige |
| `$existingForecastId` | `?int` | ID einer vorhandenen Prognose (nur für eingeloggte Nutzer) |

### Konstanten

```php
const TOTAL_SEATS  = 24;                    // Gesamtzahl der Stadtratsitze
const EDIT_DEADLINE = '2026-03-07 23:59:59'; // Frist für Änderungen
```

---

## Computed Properties (`#[Computed]`)

Computed Properties werden pro Request einmal berechnet und gecacht.

| Property | Rückgabe | Beschreibung |
|---|---|---|
| `remainingSeats()` | `int` | `TOTAL_SEATS - sum(seatDistribution)` |
| `hasRunoff()` | `bool` | `count(selectedMayorCandidates) === 2` |
| `canEdit()` | `bool` | Neue Prognose: immer `true`. Bestehende: nur wenn eingeloggt & vor Deadline |
| `deadlinePassed()` | `bool` | `existingForecastId !== null` && jetzt nach Deadline |

---

## Methoden

### `mount(): void`

Wird einmalig beim ersten Laden aufgerufen.

1. Initialisiert `$seatDistribution` mit `0` für jede Partei aus der DB
2. Prüft ob der eingeloggte Nutzer eine bestehende Prognose hat
3. Falls ja: füllt alle Properties mit den gespeicherten Werten vor (Pseudonym, Kandidaten, Sitzverteilung)

### `toggleMayorCandidate(int $candidateId): void`

Schaltet einen Kandidaten an/ab:
- Ist er bereits gewählt → wird entfernt; `$mayorRunoffWinnerId` wird zurückgesetzt falls er der Stichwahl-Gewinner war
- Ist er nicht gewählt und < 2 Kandidaten aktiv → wird hinzugefügt
- Ist er nicht gewählt und bereits 2 Kandidaten aktiv → nichts passiert (UI blendet Button aus/dimmt ihn)

### `incrementSeats(int $partyId): void` / `decrementSeats(int $partyId): void`

Erhöhen/verringern `$seatDistribution[$partyId]` um 1. Guards:
- Increment: nur wenn `remainingSeats > 0`
- Decrement: nur wenn `seatDistribution[$partyId] > 0`

### `submit(): void`

Validiert, prüft die Deadline, speichert dann in zwei Schritten:

**1. Validierungsregeln:**

```php
'pseudonym'                 → required, string, max:50
'selectedMayorCandidates'   → required, array, min:1, max:2
'seatDistribution'          → required, array, sum === 24 (custom Rule)
```

**2. Speichern:**

```php
// Eingeloggte Nutzer: genau eine Prognose (updateOrCreate)
Forecast::updateOrCreate(['user_id' => Auth::id()], $forecastData);

// Gäste: immer neuer Eintrag
Forecast::create($forecastData);
```

Danach werden alle `ForecastSeat`-Einträge gelöscht und neu geschrieben (delete + re-insert statt upsert, da sich die Partei-Anzahl theoretisch ändern kann).

---

## Alpine.js-Integration

Der Sitzverteilungs-Abschnitt ist in ein `x-data`-Element eingebettet, das den Counter **ohne Server-Roundtrip** aktualisiert:

```html
<div x-data="{
    get remaining() {
        const dist = $wire.seatDistribution;
        const total = Object.values(dist).reduce((sum, n) => sum + Number(n), 0);
        return 24 - total;
    }
}">
```

`$wire.seatDistribution` ist eine reaktive Referenz auf den Livewire-State. Nach jedem `wire:click` (Increment/Decrement) liefert Livewire den neuen State zurück, Alpine.js wertet `remaining` sofort neu aus — kein zusätzlicher Request nötig.

Der **+**-Button nutzt zusätzlich `x-bind:disabled="remaining <= 0"` für sofortiges client-seitiges Feedback (noch bevor der Server antwortet).

---

## Layouts

### `layouts/public.blade.php`

Verwendung: alle öffentlich zugänglichen Seiten (kein `auth()` verpflichtend).

- Navigation zeigt Anmelden/Registrieren-Links für Gäste
- Für eingeloggte Nutzer: Name + Dashboard-Link
- Bindet `@fluxScripts` am Ende des `<body>` ein
- Kein `@fluxAppearance` Dark-Mode-Toggle (Fokus auf Hellmodus für politische Klarheit)

### `layouts/app.blade.php` (bestehend)

Nur für auth-geschützte Seiten wie Dashboard und Settings. Ruft intern `auth()->user()` auf — darf **nicht** für Gäste verwendet werden.

---

## View-Struktur (`forecast-form.blade.php`)

Die View ist in vier nummerierte Sektionen gegliedert:

```
Schritt 1 — Pseudonym          wire:model.live → $pseudonym
Schritt 2 — Bürgermeisterwahl  wire:click → toggleMayorCandidate()
            └ Stichwahl-Panel  wire:click → $set('mayorRunoffWinnerId', ...)
Schritt 3 — Stadtratswahl      x-data Alpine-Wrapper
            ├ Sitz-Counter     x-text (client-seitig via $wire)
            ├ Partei-Zeilen    wire:click → increment/decrementSeats()
            └ Gesamtbalken     PHP-Berechnung (server-seitig, nach Livewire-Response)
Abschnitt  — Submit            Checkliste + wire:click → submit()
```
