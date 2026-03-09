# Erweiterungsguide

## Kandidatenfotos hochladen

Fotos werden über das `photo_path`-Feld im `Candidate`-Modell referenziert.

**1. Storage-Link erstellen (einmalig):**
```bash
php artisan storage:link
```

**2. Foto per Tinker oder Seeder zuweisen:**
```php
// Datei nach storage/app/public/candidates/ legen, dann:
Candidate::where('name', 'Thomas Lemke')
    ->update(['photo_path' => 'candidates/lemke.jpg']);
```

Die View lädt das Foto automatisch via `Storage::url($candidate->photo_path)`. Solange `photo_path` null ist, wird stattdessen ein farbiger Initialen-Kreis angezeigt.

**3. Hochladen über Livewire (optional):**

Für ein Admin-Interface kann `WithFileUploads` in einem separaten Livewire-Component genutzt werden:
```php
use Livewire\WithFileUploads;

class CandidateEditor extends Component
{
    use WithFileUploads;

    public $photo;

    public function save(Candidate $candidate): void
    {
        $path = $this->photo->store('candidates', 'public');
        $candidate->update(['photo_path' => $path]);
    }
}
```

---

## Parteifarben oder -daten anpassen

Parteien werden idempotent über `firstOrCreate` in `PartySeeder` angelegt. Um eine Farbe zu ändern:

```php
Party::where('short_name', 'FDP')->update(['color' => '#FFD700']);
```

Oder den Seeder anpassen und mit `--force` erneut seeden. Da `firstOrCreate` nur erstellt wenn nicht vorhanden, müssen Änderungen explizit per `updateOrCreate` erfolgen:

```php
Party::updateOrCreate(
    ['short_name' => 'FDP'],
    ['color' => '#FFD700', 'name' => 'Freie Demokratische Partei']
);
```

---

## Ergebnisauswertung

Die öffentliche Ergebnisseite (`App\Livewire\Results`, Route `/ergebnisse`) bündelt die Aggregation bereits. Nach Ablauf der Deadline (`config('forecast.edit_deadline')`) leitet die Startseite automatisch dorthin weiter.

Für manuelle Abfragen per Tinker:

```php
// Durchschnittliche Sitzverteilung über alle echten Prognosen
$averageSeats = ForecastSeat::select('party_id', DB::raw('AVG(seats) as avg_seats'))
    ->whereHas('forecast', fn ($q) => $q->where('is_fake', false))
    ->groupBy('party_id')
    ->with('party')
    ->get();

// Häufigste Bürgermeister-Wahl
$mayorVotes = Forecast::scopeReal(Forecast::query())
    ->whereNotNull('mayor_candidate_1_id')
    ->select('mayor_candidate_1_id', DB::raw('COUNT(*) as votes'))
    ->groupBy('mayor_candidate_1_id')
    ->with('mayorCandidate1.party')
    ->orderByDesc('votes')
    ->get();
```

Test-/Demo-Einträge mit `is_fake = true` markieren, damit sie aus Auswertungen gefiltert werden:

```php
Forecast::where('pseudonym', 'Test')->update(['is_fake' => true]);
```

---

## Eine neue Wahl hinzufügen

Das Datenmodell ist auf die Kommunalwahl 2026 ausgerichtet. Für zukünftige Wahlen gibt es zwei Ansätze:

**Option A — Neue Tabellen:** Separate `elections`-Tabelle + FK in `forecasts`. Flexibel, erfordert Migration.

**Option B — Neuer Branch / Neustart:** Da die Seeder idempotent sind, können Parteien und Kandidaten für eine neue Wahl einfach per neuem Seeder eingespielt werden.

---

## Prognosen auf Nutzer beschränken

Aktuell können Gäste beliebig viele Prognosen abgeben. Um Spam einzudämmen:

**Option 1 — IP-basierte Ratenbegrenzung:**
```php
// In ForecastForm::submit():
if (! Auth::check()) {
    $recentCount = Forecast::where('ip_address', request()->ip())
        ->where('created_at', '>=', now()->subHour())
        ->count();
    if ($recentCount >= 3) {
        $this->addError('general', 'Zu viele Prognosen. Bitte später erneut versuchen.');
        return;
    }
}
```

**Option 2 — Login Pflicht:**

Route mit `->middleware('auth')` schützen und Gäste zur Registrierung weiterleiten.

---

## CSV-Export (Admin)

Der CSV-Export steht nur Nutzern mit `is_admin = true` zur Verfügung (Route `/dashboard/export`). Der Download-Button erscheint im Dashboard oben rechts.

Admin-Rechte vergeben:

```bash
php artisan tinker
>>> \App\Models\User::where('email', 'mail@example.com')->update(['is_admin' => true]);
```

Der Export enthält alle Prognosen inkl. Userdaten (Name, E-Mail bei registrierten Nutzern). Die Spalten für Partei-Sitze werden dynamisch aus der `parties`-Tabelle generiert — passen sich also automatisch an, falls Parteien hinzugefügt oder entfernt werden.

---

## Tests

Tests liegen in `tests/Feature/` und `tests/Unit/`. Das Pest-Framework ist konfiguriert mit `RefreshDatabase` für alle Feature-Tests.

Beispiel für einen ForecastForm-Test:

```php
// tests/Feature/ForecastFormTest.php

use App\Models\Party;
use App\Models\Candidate;
use Livewire\Livewire;
use App\Livewire\ForecastForm;

it('requires pseudonym before submit', function () {
    Party::factory()->count(6)->create();

    Livewire::test(ForecastForm::class)
        ->call('submit')
        ->assertHasErrors(['pseudonym']);
});

it('validates seat distribution must equal 24', function () {
    $parties = Party::factory()->count(2)->create();
    $candidate = Candidate::factory()->create();

    Livewire::test(ForecastForm::class)
        ->set('pseudonym', 'Tester')
        ->set('selectedMayorCandidates', [$candidate->id])
        ->set('seatDistribution', [$parties[0]->id => 10, $parties[1]->id => 10])
        ->call('submit')
        ->assertHasErrors(['seatDistribution']);
});
```

Zum Ausführen:

```bash
php artisan test --filter=ForecastForm
# oder
./vendor/bin/pest tests/Feature/ForecastFormTest.php
```
