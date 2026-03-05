# Livewire-Komponenten & Controller

## Übersicht

| Klasse | Route | Auth | Beschreibung |
| --- | --- | --- | --- |
| `App\Livewire\ForecastForm` | `/prognose` | — | Prognose-Formular für Gäste und Nutzer |
| `App\Livewire\Dashboard` | `/dashboard` | auth | Persönliches Dashboard + Gesamtübersicht |
| `App\Livewire\Results` | `/ergebnisse` | — | Öffentliche Ergebnisseite nach Deadline |
| `App\Livewire\Admin\Forecasts` | `/admin/prognosen` | auth | Admin-Übersicht aller Prognosen |
| `App\Http\Controllers\ForecastExportController` | `/dashboard/export` | auth + admin | CSV-Download aller Prognosen |

---

## ForecastForm

**Klasse:** `App\Livewire\ForecastForm`
**View:** `resources/views/livewire/forecast-form.blade.php`
**Route:** `GET /prognose` → `prognose`
**Layout:** `layouts.public` (funktioniert für Gäste und eingeloggte Nutzer)

### Public Properties (Livewire-State)

| Property | Typ | Beschreibung |
| --- | --- | --- |
| `$pseudonym` | `string` | Anzeigename, per `wire:model.live` an Input gebunden |
| `$selectedMayorCandidates` | `array<int>` | IDs der gewählten Kandidaten (max. 2) |
| `$mayorRunoffWinnerId` | `?int` | ID des prognostizierten Stichwahl-Gewinners |
| `$seatDistribution` | `array<int, int>` | `party_id → Sitze`, bei `mount()` mit 0 initialisiert |
| `$saved` | `bool` | Steuert die Erfolgs-Banner-Anzeige |
| `$existingForecastId` | `?int` | ID einer vorhandenen Prognose (nur für eingeloggte Nutzer) |

### Computed Properties (`#[Computed]`)

| Property | Rückgabe | Beschreibung |
| --- | --- | --- |
| `remainingSeats()` | `int` | `TOTAL_SEATS - sum(seatDistribution)` |
| `hasRunoff()` | `bool` | `count(selectedMayorCandidates) === 2` |
| `canEdit()` | `bool` | Neue Prognose: immer `true`. Bestehende: nur wenn eingeloggt & vor Deadline |
| `deadlinePassed()` | `bool` | `existingForecastId !== null` && jetzt nach Deadline |

### Methoden

**`mount(): void`** — Lädt bestehende Prognose des eingeloggten Nutzers vor (Pseudonym, Kandidaten, Sitzverteilung).

**`toggleMayorCandidate(int $candidateId): void`** — An-/Abwahl eines Kandidaten; setzt `$mayorRunoffWinnerId` zurück wenn Kandidat abgewählt.

**`incrementSeats / decrementSeats(int $partyId): void`** — Guards: Increment nur wenn `remainingSeats > 0`, Decrement nur wenn aktueller Wert > 0.

**`submit(): void`** — Validiert, prüft Deadline, speichert `Forecast` + `ForecastSeat`-Einträge.

Validierungsregeln:

```text
pseudonym               → required, string, max:50
selectedMayorCandidates → required, array, min:1, max:2
seatDistribution        → required, array, Summe = 24 (custom Rule)
```

Speichern: Eingeloggte Nutzer per `updateOrCreate(['user_id' => ...])`, Gäste per `create()`. Sitze werden immer gelöscht und neu geschrieben.

### Alpine.js-Integration

Der Sitzverteilungs-Abschnitt nutzt `$wire.seatDistribution` als reaktive Referenz — der verbleibende-Sitze-Counter aktualisiert sich ohne Server-Roundtrip. Der **+**-Button ist per `x-bind:disabled="remaining <= 0"` sofort client-seitig gesperrt.

### View-Struktur

```text
Schritt 1 — Pseudonym          wire:model.live → $pseudonym
Schritt 2 — Bürgermeisterwahl  wire:click → toggleMayorCandidate()
            └ Stichwahl-Panel  wire:click → $set('mayorRunoffWinnerId', ...)
Schritt 3 — Stadtratswahl      x-data Alpine-Wrapper
            ├ Sitz-Counter     x-text (client-seitig via $wire)
            ├ Partei-Zeilen    wire:click → increment/decrementSeats()
            └ Gesamtbalken     PHP-Berechnung (nach Livewire-Response)
Abschnitt  — Submit            Checkliste + wire:click → submit()
```

---

## Dashboard

**Klasse:** `App\Livewire\Dashboard`
**View:** `resources/views/livewire/dashboard.blade.php`
**Route:** `GET /dashboard` → `dashboard`
**Layout:** `layouts.app` (Sidebar, nur auth)

### Computed Properties

| Property | Beschreibung |
| --- | --- |
| `forecast()` | Eigene Prognose des eingeloggten Nutzers, eager-loaded |
| `parties()` | Alle Parteien |
| `forecastCount()` | Anzahl aller Prognosen |
| `seatSummary()` | Parteien mit `withSum` + `withAvg` über `forecast_seats` |
| `mayorSummary()` | Kandidaten mit Auswahlhäufigkeit und Stichwahl-Favoriten-Zahl |

### Funktionen

- Zeigt die eigene Prognose (Bürgermeister + Sitzverteilung)
- Zeigt die Gesamtübersicht erst, wenn die eigene Prognose abgegeben wurde (Lock-Mechanismus)
- Admins sehen einen **„CSV exportieren"**-Button oben rechts

---

## Results

**Klasse:** `App\Livewire\Results`
**View:** `resources/views/livewire/results.blade.php`
**Route:** `GET /ergebnisse` → `results`
**Layout:** `layouts.public`

Öffentliche Ergebnisseite. Zeigt aggregierte Auswertung aller Prognosen ohne Login-Anforderung. Die Startseite leitet nach Ablauf der Deadline (`config('forecast.edit_deadline')`) automatisch hierher weiter.

---

## Admin\Forecasts

**Klasse:** `App\Livewire\Admin\Forecasts`
**View:** `resources/views/livewire/admin/forecasts.blade.php`
**Route:** `GET /admin/prognosen` → `admin.forecasts`
**Layout:** `layouts.app` (Sidebar)

Admin-Übersicht aller abgegebenen Prognosen. Im Sidebar nur für Nutzer mit `is_admin = true` sichtbar.

---

## ForecastExportController

**Klasse:** `App\Http\Controllers\ForecastExportController`
**Route:** `GET /dashboard/export` → `forecast.export`
**Auth:** `auth + verified`, zusätzlich `abort_unless(auth()->user()->is_admin, 403)`

Streamt einen CSV-Download aller Prognosen. UTF-8 BOM für Excel-Kompatibilität, Semikolon als Trennzeichen.

CSV-Spalten:

| Spalte | Inhalt |
| --- | --- |
| ID, Eingereicht am | Technische Metadaten |
| Pseudonym | Anzeigename |
| Registriert, Name, E-Mail | Userdaten (leer bei Gästen) |
| BM-Kandidat 1/2, Stichwahl-Favorit | Bürgermeisterwahl |
| Sitze [Partei-Kürzel] | Je eine Spalte pro Partei (dynamisch) |

Dateiname: `prognosen-YYYY-MM-DD.csv`

---

## Layouts

### `layouts/public.blade.php`

Alle öffentlich zugänglichen Seiten (Prognose, Ergebnisse, Datenschutz, Impressum).

- Navigation: Anmelden/Registrieren für Gäste, Name + Dashboard-Link für eingeloggte Nutzer
- Footer: Links zu Datenschutzerklärung und Impressum
- Bindet `@fluxScripts` ein

### `layouts/app.blade.php` + `layouts/app/sidebar.blade.php`

Nur für auth-geschützte Seiten (Dashboard, Admin, Settings). Ruft `auth()->user()` auf — **nicht** für Gäste geeignet.

Sidebar-Navigation:

- Platform: Dashboard, Wahlprognose
- Admin (nur `is_admin = true`): Prognosen-Übersicht
- Links zu allen Garchinger Parteien
