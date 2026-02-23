# Wahlprognose Garching 2026

Interaktives Bürgerprognosetool für die **Kommunalwahl Garching bei München am 15. März 2026**.

Besucher können ohne Registrierung ihre persönliche Einschätzung zur Bürgermeisterwahl und zur Sitzverteilung im Stadtrat abgeben. Registrierte Nutzer können ihre Prognose bis zum **7. März 2026** jederzeit anpassen.

---

## Features

- **Bürgermeisterwahl** — Auswahl von 1 Kandidaten (Direktsieg) oder 2 Kandidaten (Stichwahl-Prognose), mit optionaler Angabe des Stichwahl-Gewinners
- **Stadtratswahl** — Verteilung von genau 24 Sitzen auf 6 Parteien über +/−-Buttons; Sitz-Counter aktualisiert sich sofort via Alpine.js
- **Keine Pflichtregistrierung** — Pseudonym genügt zum Abgeben einer Prognose
- **Bearbeitungsfenster** — Registrierte Nutzer können bis 07.03.2026 ihre Prognose ändern
- **Visuelle Auswertung** — Fortschrittsbalken pro Partei und gestapelter Gesamtbalken

## Tech-Stack

| Schicht      | Technologie                          |
|--------------|--------------------------------------|
| Backend      | PHP 8.2+, Laravel 12                 |
| Reaktivität  | Livewire 4, Alpine.js                |
| UI-Komponenten | Flux UI v2                         |
| Styling      | Tailwind CSS v4                      |
| Build        | Vite 7                               |
| Auth         | Laravel Fortify (2FA-fähig)          |
| Datenbank    | SQLite (Dev) / MySQL, PostgreSQL (Prod) |
| Tests        | Pest v4                              |

---

## Schnellstart

### Erstmalige Einrichtung

```bash
composer setup
```

Dieser Befehl führt aus: `composer install` → `.env` anlegen → `key:generate` → `migrate` → `npm install` → `npm run build`.

Danach Stammdaten einspielen:

```bash
php artisan db:seed --class=PartySeeder
php artisan db:seed --class=CandidateSeeder
```

### Entwicklungsserver starten

```bash
composer dev
```

Startet parallel: PHP-Dev-Server, Queue-Worker, Pail (Logs) und Vite. Die Anwendung ist unter `http://localhost:8000` erreichbar.

---

## Befehle

| Befehl | Beschreibung |
|---|---|
| `composer dev` | Vollständige Dev-Umgebung starten |
| `composer test` | Testsuite ausführen (inkl. Lint-Check) |
| `composer lint` | Code-Stil automatisch korrigieren (Pint) |
| `npm run build` | Frontend-Assets für Produktion bauen |
| `php artisan migrate` | Datenbankmigrationen ausführen |
| `php artisan db:seed --class=PartySeeder` | Parteien einspielen |
| `php artisan db:seed --class=CandidateSeeder` | Kandidaten einspielen |
| `php artisan test --filter=TestName` | Einzelnen Test ausführen |

---

## Routen

| URL | Route-Name | Sichtbarkeit |
|---|---|---|
| `/` | `home` | öffentlich |
| `/prognose` | `forecast` | öffentlich |
| `/dashboard` | `dashboard` | nur eingeloggt + verifiziert |
| `/settings/profile` | `profile.edit` | nur eingeloggt |
| `/settings/password` | `user-password.edit` | nur eingeloggt + verifiziert |

---

## Kandidaten (Bürgermeisterwahl)

| Kandidat | Partei |
|---|---|
| Dr. Dietmar Gruchmann | SPD |
| Thomas Lemke | CSU |
| Werner Landmann | GRÜNE |
| Christian Nolte | UG |
| Simone Schmidt | BfG |
| Bastian Dombret | FDP |

---

## Weiterführende Dokumentation

- [Datenmodell](docs/datenmodell.md) — ER-Diagramm, Tabellenbeschreibungen, Constraints
- [Livewire-Komponente](docs/komponenten.md) — Aufbau, Properties, Methoden, Alpine.js-Integration
- [Benutzerflüsse](docs/benutzerfluss.md) — Gast vs. registrierter Nutzer, Deadline-Logik, Validierungsregeln
- [Erweiterungsguide](docs/erweiterung.md) — Fotos hochladen, Ergebnisauswertung, neue Wahlen hinzufügen
