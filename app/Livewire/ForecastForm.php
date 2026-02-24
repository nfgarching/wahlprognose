<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Models\Forecast;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Wahlprognose Garching 2026')]
#[Layout('layouts.public')]
class ForecastForm extends Component
{
    public const TOTAL_SEATS = 24;
    public const EDIT_DEADLINE = '2026-03-07 23:59:59';

    public string $pseudonym = '';

    /** @var array<int> IDs der gewählten Bürgermeisterkandidaten (max. 2) */
    public array $selectedMayorCandidates = [];

    /** Optional: Wer gewinnt die Stichwahl? */
    public ?int $mayorRunoffWinnerId = null;

    /** @var array<int, int> party_id => Anzahl Sitze */
    public array $seatDistribution = [];

    public bool $saved = false;

    /** Vorhandene Prognose-ID für registrierte User */
    public ?int $existingForecastId = null;

    public function mount(): void
    {
        // Sitzverteilung mit 0 initialisieren
        foreach (Party::all() as $party) {
            $this->seatDistribution[$party->id] = 0;
        }

        // Für eingeloggte User: bestehende Prognose laden
        if (Auth::check()) {
            $existing = Forecast::where('user_id', Auth::id())
                ->with('seats')
                ->first();

            if ($existing) {
                $this->existingForecastId = $existing->id;
                $this->pseudonym = $existing->pseudonym;

                if ($existing->mayor_candidate_1_id) {
                    $this->selectedMayorCandidates[] = $existing->mayor_candidate_1_id;
                }
                if ($existing->mayor_candidate_2_id) {
                    $this->selectedMayorCandidates[] = $existing->mayor_candidate_2_id;
                }
                $this->mayorRunoffWinnerId = $existing->mayor_runoff_winner_id;

                foreach ($existing->seats as $seat) {
                    $this->seatDistribution[$seat->party_id] = $seat->seats;
                }
            }
        }
    }

    #[Computed]
    public function remainingSeats(): int
    {
        return self::TOTAL_SEATS - array_sum($this->seatDistribution);
    }

    #[Computed]
    public function hasRunoff(): bool
    {
        return count($this->selectedMayorCandidates) === 2;
    }

    #[Computed]
    public function canEdit(): bool
    {
        // Neue Prognose: immer möglich (Gast oder registrierter Nutzer, noch keine Prognose)
        if ($this->existingForecastId === null) {
            return true;
        }
        // Bestehende Prognose: nur registrierte Nutzer vor der Deadline
        if (! Auth::check()) {
            return false;
        }

        return now()->lte(Carbon::parse(self::EDIT_DEADLINE));
    }

    #[Computed]
    public function deadlinePassed(): bool
    {
        return $this->existingForecastId !== null && now()->gt(Carbon::parse(self::EDIT_DEADLINE));
    }

    public function toggleMayorCandidate(int $candidateId): void
    {
        if (in_array($candidateId, $this->selectedMayorCandidates)) {
            $this->selectedMayorCandidates = array_values(
                array_filter($this->selectedMayorCandidates, fn ($id) => $id !== $candidateId)
            );
            // Stichwahl-Gewinner zurücksetzen wenn einer abgewählt wird
            if ($this->mayorRunoffWinnerId === $candidateId) {
                $this->mayorRunoffWinnerId = null;
            }
        } elseif (count($this->selectedMayorCandidates) < 2) {
            $this->selectedMayorCandidates[] = $candidateId;
        }
    }

    public function incrementSeats(int $partyId): void
    {
        if ($this->remainingSeats > 0) {
            $this->seatDistribution[$partyId] = ($this->seatDistribution[$partyId] ?? 0) + 1;
        }
    }

    public function decrementSeats(int $partyId): void
    {
        if (($this->seatDistribution[$partyId] ?? 0) > 0) {
            $this->seatDistribution[$partyId]--;
        }
    }

    public function updatedSeatDistribution(mixed $value, string $key): void
    {
        $partyId = (int) $key;
        $others = array_sum(array_filter(
            $this->seatDistribution,
            fn ($k) => $k !== $partyId,
            ARRAY_FILTER_USE_KEY
        ));
        $this->seatDistribution[$partyId] = max(0, min((int) $value, self::TOTAL_SEATS - $others));
    }

    public function submit(): void
    {
        $this->validate([
            'pseudonym' => 'required|string|max:50',
            'selectedMayorCandidates' => 'required|array|min:1|max:2',
            'seatDistribution' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (array_sum($value) !== self::TOTAL_SEATS) {
                        $fail('Die Sitzverteilung muss genau '.self::TOTAL_SEATS.' Sitze ergeben.');
                    }
                },
            ],
        ], [
            'pseudonym.required' => 'Bitte gib ein Pseudonym an.',
            'pseudonym.max' => 'Das Pseudonym darf maximal 50 Zeichen haben.',
            'selectedMayorCandidates.required' => 'Bitte wähle mindestens einen Bürgermeisterkandidaten.',
            'selectedMayorCandidates.min' => 'Bitte wähle mindestens einen Bürgermeisterkandidaten.',
            'selectedMayorCandidates.max' => 'Du kannst maximal zwei Kandidaten für die Stichwahl wählen.',
        ]);

        if (! $this->canEdit) {
            if (! Auth::check()) {
                $this->addError('general', 'Bitte melde dich an, um deine Prognose zu ändern.');
            } else {
                $this->addError('general', 'Die Frist zur Änderung der Prognose ist am '.Carbon::parse(self::EDIT_DEADLINE)->format('d.m.Y').' abgelaufen.');
            }

            return;
        }

        $forecastData = [
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'pseudonym' => trim($this->pseudonym),
            'mayor_candidate_1_id' => $this->selectedMayorCandidates[0] ?? null,
            'mayor_candidate_2_id' => $this->selectedMayorCandidates[1] ?? null,
            'mayor_runoff_winner_id' => $this->hasRunoff ? $this->mayorRunoffWinnerId : null,
        ];

        if (Auth::check()) {
            $forecast = Forecast::updateOrCreate(
                ['user_id' => Auth::id()],
                $forecastData
            );
        } else {
            $forecast = Forecast::create($forecastData);
        }

        $this->existingForecastId = $forecast->id;

        // Sitzverteilung atomar speichern
        DB::transaction(function () use ($forecast) {
            $forecast->seats()->delete();
            foreach ($this->seatDistribution as $partyId => $seats) {
                $forecast->seats()->create([
                    'party_id' => $partyId,
                    'seats' => $seats,
                ]);
            }
        });

        $this->saved = true;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.forecast-form', [
            'parties' => Party::all(),
            'candidates' => Candidate::with('party')->get(),
        ]);
    }
}
