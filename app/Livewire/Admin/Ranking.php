<?php

namespace App\Livewire\Admin;

use App\Models\Candidate;
use App\Models\Forecast;
use App\Models\Party;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin – Ranking')]
#[Layout('layouts.app')]
class Ranking extends Component
{
    /** @var array<string, int> */
    public const OFFICIAL_SEATS = [
        'CSU' => 7,
        'SPD' => 5,
        'GRÜNE' => 5,
        'UG' => 3,
        'BfG' => 3,
        'FDP' => 1,
    ];

    public const OFFICIAL_RUNOFF_NAMES = [
        'Dr. Dietmar Gruchmann',
        'Thomas Lemke',
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Party> */
    #[Computed]
    public function parties(): \Illuminate\Database\Eloquent\Collection
    {
        return Party::whereIn('short_name', array_keys(self::OFFICIAL_SEATS))
            ->get()
            ->keyBy('short_name');
    }

    /** @return array<int, int> */
    #[Computed]
    public function runoffCandidateIds(): array
    {
        return Candidate::whereIn('name', self::OFFICIAL_RUNOFF_NAMES)
            ->pluck('id')
            ->all();
    }

    /** @return \Illuminate\Support\Collection<int, Forecast> */
    #[Computed]
    public function rankedForecasts(): \Illuminate\Support\Collection
    {
        $forecasts = Forecast::real()
            ->with(['mayorCandidate1.party', 'mayorCandidate2.party', 'mayorRunoffWinner', 'seats.party'])
            ->get();

        $runoffIds = $this->runoffCandidateIds;

        return $forecasts->map(function (Forecast $forecast) use ($runoffIds) {
            $seatsByParty = $forecast->seats->keyBy(fn ($s) => $s->party->short_name);

            $seatError = 0;
            $seatDiffs = [];

            foreach (self::OFFICIAL_SEATS as $shortName => $officialCount) {
                $predicted = $seatsByParty->get($shortName)?->seats ?? 0;
                $diff = $predicted - $officialCount;
                $seatError += abs($diff);
                $seatDiffs[$shortName] = $diff;
            }

            $predicted = array_filter([
                $forecast->mayor_candidate_1_id,
                $forecast->mayor_candidate_2_id,
            ]);
            $correctRunoff = count(array_intersect($runoffIds, $predicted));
            $mayorError = 2 - $correctRunoff;

            $forecast->seat_error = $seatError;
            $forecast->mayor_error = $mayorError;
            $forecast->seat_diffs = $seatDiffs;
            $forecast->total_score = $seatError + ($mayorError * 3);

            return $forecast;
        })->sortBy('total_score')->values();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.ranking');
    }
}
