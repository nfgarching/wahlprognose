# Livewire-Komponenten & Controller

## Übersicht

| Klasse | Route | Auth | Beschreibung |
| --- | --- | --- | --- |
| `App\Livewire\ForecastForm` | `/prognose` | — | Prognose-Formular für Gäste und Nutzer |
| `App\Livewire\RunoffForecastForm` | `/stichwahl` | — | Stichwahl-Prognose: Gewinner-Tipp + optionaler Stimmenanteil, Live-Statistik |
| `App\Livewire\Dashboard` | `/dashboard` | auth | Persönliches Dashboard: Hauptwahl-Prognose + Gesamtübersicht + Stichwahl-Statistik |
| `App\Livewire\Results` | `/ergebnisse` | — | Öffentliche Ergebnisseite nach Deadline |
| `App\Livewire\Admin\Forecasts` | `/admin/prognosen` | auth + admin | Admin-Übersicht aller Hauptwahl-Prognosen |
| `App\Livewire\Admin\Ranking` | `/admin/ranking` | auth + admin | Scoring und Ranking der Hauptwahl-Prognosen |
| `App\Livewire\Admin\RunoffForecasts` | `/admin/stichwahl/prognosen` | auth + admin | Admin-Übersicht aller Stichwahl-Prognosen |
| `App\Livewire\Admin\RunoffRanking` | `/admin/stichwahl/ranking` | auth + admin | Statistik + sortiertes Ranking der Stichwahl-Prognosen |
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

## RunoffForecastForm

**Klasse:** `App\Livewire\RunoffForecastForm`
**View:** `resources/views/livewire/runoff-forecast-form.blade.php`
**Route:** `GET /stichwahl` → `stichwahl`
**Layout:** `layouts.public`

Eigenständiges Formular für die Bürgermeister-Stichwahl — unabhängig vom Erst-Wahlformular. Zeigt nach dem Absenden live die aggregierte Statistik aller bisherigen Prognosen.

### Public Properties (Livewire-State)

| Property | Typ | Beschreibung |
| --- | --- | --- |
| `$pseudonym` | `string` | Anzeigename |
| `$predictedWinner` | `string` | `'gruchmann'` oder `'lemke'` |
| `$gruchmannPercent` | `?int` | Optionaler Stimmenanteil für Gruchmann (0–100) |
| `$saved` | `bool` | Steuert die Erfolgs-Banner-Anzeige |
| `$existingForecastId` | `?int` | ID einer vorhandenen Prognose (nur für eingeloggte Nutzer) |

### Computed Properties (`#[Computed]`)

| Property | Rückgabe | Beschreibung |
| --- | --- | --- |
| `statistics()` | `array` | Aggregierte Live-Statistik: Gesamtanzahl, Gruchmann/Lemke-Zahl + %-Anteil, Durchschnitt `gruchmann_percent` |

`statistics()` gibt zurück:

```php
[
    'total'                 => int,
    'gruchmann'             => int,
    'lemke'                 => int,
    'gruchmann_pct'         => float,   // gerundet auf 1 Stelle
    'lemke_pct'             => float,
    'avg_gruchmann_percent' => float|null,
]
```

### Methoden

**`mount(): void`** — Lädt bestehende Stichwahl-Prognose des eingeloggten Nutzers vor.

**`submit(): void`** — Validiert und speichert. Eingeloggte Nutzer per `updateOrCreate(['user_id' => ...])`, Gäste per `create()`.

Validierungsregeln:

```text
pseudonym        → required, string, max:50
predictedWinner  → required, in:gruchmann,lemke
gruchmannPercent → nullable, integer, min:51, max:100
```

> **Hinweis `min:51`:** Der Stimmenanteil repräsentiert immer den Sieger-Anteil, der zwingend über 50 % liegen muss.

---

## Dashboard

**Klasse:** `App\Livewire\Dashboard`
**View:** `resources/views/livewire/dashboard.blade.php`
**Route:** `GET /dashboard` → `dashboard`
**Layout:** `layouts.app` (Sidebar, nur auth)

### Computed Properties

| Property | Beschreibung |
| --- | --- |
| `forecast()` | Eigene Hauptwahl-Prognose des eingeloggten Nutzers, eager-loaded |
| `parties()` | Alle Parteien |
| `forecastCount()` | Anzahl aller echten Hauptwahl-Prognosen |
| `seatSummary()` | Parteien mit `withSum` + `withAvg` über `forecast_seats` |
| `mayorSummary()` | Kandidaten mit Auswahlhäufigkeit und Stichwahl-Favoriten-Zahl |
| `runoffForecast()` | Eigene Stichwahl-Prognose des eingeloggten Nutzers (`null` wenn keine) |
| `runoffStats()` | Aggregierte Statistik aller Stichwahl-Prognosen: Anzahl + Anteil Gruchmann/Lemke, Ø Stimmenanteil |

### Funktionen

- Zeigt die eigene Hauptwahl-Prognose (Bürgermeister + Sitzverteilung)
- Zeigt die Hauptwahl-Gesamtübersicht erst, wenn die eigene Prognose abgegeben wurde (Lock-Mechanismus)
- Zeigt die Stichwahl-Statistik (Balkendiagramm + eigene Prognose) immer — kein Lock
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

Admin-Übersicht aller abgegebenen Hauptwahl-Prognosen. Suche nach Pseudonym, Filter nach echt/fake, Duplikat-IP-Erkennung, Fake-Toggle.

---

## Admin\Ranking

**Klasse:** `App\Livewire\Admin\Ranking`
**View:** `resources/views/livewire/admin/ranking.blade.php`
**Route:** `GET /admin/ranking` → `admin.ranking`
**Layout:** `layouts.app`

Bewertet alle echten Hauptwahl-Prognosen anhand des offiziellen Ergebnisses (hardcodiert als Konstanten). Scoring: `seat_error + (mayor_error × 3)`, sortiert aufsteigend.

---

## Admin\RunoffForecasts

**Klasse:** `App\Livewire\Admin\RunoffForecasts`
**View:** `resources/views/livewire/admin/runoff-forecasts.blade.php`
**Route:** `GET /admin/stichwahl/prognosen` → `admin.runoff-forecasts`
**Layout:** `layouts.app`

Admin-Übersicht aller Stichwahl-Prognosen. Suche nach Pseudonym, Duplikat-IP-Erkennung. Zeigt Gewinner-Tipp als farbigen Badge (rot = Gruchmann/SPD, blau = Lemke/CSU) und den optionalen Stimmenanteil.

| Computed Property | Beschreibung |
| --- | --- |
| `duplicateIps()` | IP-Adressen mit mehr als einem Eintrag |
| `runoffForecasts()` | Gefilterte Abfrage mit `user` eager-loaded |

---

## Admin\RunoffRanking

**Klasse:** `App\Livewire\Admin\RunoffRanking`
**View:** `resources/views/livewire/admin/runoff-ranking.blade.php`
**Route:** `GET /admin/stichwahl/ranking` → `admin.runoff-ranking`
**Layout:** `layouts.app`

Statistik und sortiertes Ranking aller Stichwahl-Prognosen.

### Konstanten (nach Wahl setzen)

```php
public const OFFICIAL_WINNER = null;            // 'gruchmann' | 'lemke' | null
public const OFFICIAL_GRUCHMANN_PERCENT = null; // int | null
```

Sobald das Wahlergebnis feststeht, diese Werte in der Klasse setzen — das Ranking und die ✓/✗-Spalte aktualisieren sich automatisch.

### Sortierlogik

| Zustand | Primär | Sekundär |
| --- | --- | --- |
| Ergebnis unbekannt | Gruchmann-Tipper zuerst | Stimmenanteil absteigend |
| Ergebnis bekannt | Korrekte Sieger-Vorhersage zuerst | Kleinste Abweichung vom offiziellen Stimmenanteil |

| Computed Property | Beschreibung |
| --- | --- |
| `stats()` | Gibt `Collection` zurück mit Gesamtzahl, Gruchmann/Lemke-Anzahl + Anteil, Ø Stimmenanteil, sortierte Prognosen-Liste |

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
