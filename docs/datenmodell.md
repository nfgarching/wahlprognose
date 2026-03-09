# Datenmodell

## Überblick

```
users ──────────────────────────────────────────────────────┐
  id, name, email, password, ...                             │
                                                             │ 0..1
parties ──────────────┐                                      │
  id                  │                            forecasts │
  name                │ 1                            id      │
  short_name          ├──────────────┐               user_id ┤ (nullable)
  color               │              │               ip_address
  logo_path           │              │               pseudonym
                      │           candidates         mayor_candidate_1_id ──┐
                      │              id              mayor_candidate_2_id ──┤─→ candidates.id
                      │              name            mayor_runoff_winner_id ┘
                      │              party_id ───────┘
                      │              photo_path
                      │
                      │                            forecast_seats
                      │                              id
                      └──────────────────────────── party_id
                                                     forecast_id ─────────→ forecasts.id
                                                     seats (0–24)
```

---

## Tabellen

### `parties`

Stammdaten der kandidierenden Parteien. Wird per Seeder befüllt und nur selten geändert.

| Spalte | Typ | Beschreibung |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | Vollständiger Parteiname |
| `short_name` | string(20) | Kürzel, z. B. `CSU` |
| `color` | string(7) | Hex-Farbe, z. B. `#0066B3` |
| `logo_path` | string nullable | Relativer Storage-Pfad zum Logo |

### `candidates`

Bürgermeisterkandidaten. Wird per Seeder befüllt.

| Spalte | Typ | Beschreibung |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | Vollständiger Name |
| `party_id` | bigint FK nullable | → `parties.id`, `SET NULL` bei Löschung |
| `photo_path` | string nullable | Relativer Storage-Pfad zum Kandidatenfoto |

### `forecasts`

Eine Prognose pro Eintrag. Gäste können beliebig viele abgeben, registrierte Nutzer haben genau eine (via `updateOrCreate` auf `user_id`).

| Spalte | Typ | Beschreibung |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | bigint FK nullable | → `users.id`, `SET NULL` bei Löschung; `null` = Gast |
| `ip_address` | string(45) nullable | IPv4 oder IPv6 zur Identifikation von Gästen |
| `pseudonym` | string(50) | Anzeigename, vom Nutzer frei wählbar |
| `is_fake` | boolean | Markiert Test-/Demo-Einträge; wird per `scopeReal()` ausgefiltert |
| `mayor_candidate_1_id` | bigint FK nullable | Erster (oder einziger) Kandidat |
| `mayor_candidate_2_id` | bigint FK nullable | Zweiter Kandidat → impliziert Stichwahl-Prognose |
| `mayor_runoff_winner_id` | bigint FK nullable | Optionale Prognose des Stichwahl-Gewinners |

**Constraint-Logik:**
- `mayor_candidate_2_id IS NOT NULL` → Nutzer erwartet eine Stichwahl zwischen Kandidat 1 und Kandidat 2
- `mayor_runoff_winner_id` ist nur semantisch sinnvoll wenn `mayor_candidate_2_id IS NOT NULL`
- Alle drei FK → `candidates.id` mit `SET NULL ON DELETE`

### `forecast_seats`

Sitzverteilungs-Details zu einer Prognose. Genau ein Eintrag pro Partei pro Prognose.

| Spalte | Typ | Beschreibung |
|---|---|---|
| `id` | bigint PK | |
| `forecast_id` | bigint FK | → `forecasts.id`, `CASCADE DELETE` |
| `party_id` | bigint FK | → `parties.id`, `CASCADE DELETE` |
| `seats` | tinyint unsigned | Anzahl prognostizierter Sitze (0–24) |

**Unique-Constraint:** `(forecast_id, party_id)` — verhindert doppelte Einträge.

**Invariante:** Die Summe aller `seats` innerhalb einer `forecast_id` muss genau **24** ergeben (wird serverseitig durch Livewire-Validierung sichergestellt, nicht durch DB-Constraint).

---

## Eloquent-Modelle und Relations

```
Party
  hasMany → Candidate
  hasMany → ForecastSeat

Candidate
  belongsTo → Party

Forecast
  belongsTo → User
  belongsTo → Candidate (mayorCandidate1)
  belongsTo → Candidate (mayorCandidate2)
  belongsTo → Candidate (mayorRunoffWinner)
  hasMany   → ForecastSeat

ForecastSeat
  belongsTo → Forecast
  belongsTo → Party
```

---

## Seeder-Reihenfolge

Da `CandidateSeeder` auf `PartySeeder` aufbaut (FK `party_id`), muss die Reihenfolge eingehalten werden:

```bash
php artisan db:seed --class=PartySeeder
php artisan db:seed --class=CandidateSeeder
```

Oder über `DatabaseSeeder`, der beide in der richtigen Reihenfolge aufruft:

```bash
php artisan db:seed
```
