<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Models\Forecast;
use App\Models\Party;
use App\Models\RunoffForecast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
#[Layout('layouts.app')]
class Dashboard extends Component
{
    #[Computed]
    public function forecast(): ?Forecast
    {
        return Forecast::where('user_id', Auth::id())
            ->with([
                'mayorCandidate1.party',
                'mayorCandidate2.party',
                'mayorRunoffWinner.party',
                'seats.party',
            ])
            ->first();
    }

    #[Computed]
    public function parties(): \Illuminate\Database\Eloquent\Collection
    {
        return Party::all();
    }

    #[Computed]
    public function forecastCount(): int
    {
        return Forecast::real()->count();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Party> */
    #[Computed]
    public function seatSummary(): \Illuminate\Database\Eloquent\Collection
    {
        return Party::withSum(
            ['forecastSeats' => fn ($q) => $q->whereHas('forecast', fn ($q) => $q->where('is_fake', false))],
            'seats'
        )
            ->withAvg(
                ['forecastSeats' => fn ($q) => $q->whereHas('forecast', fn ($q) => $q->where('is_fake', false))],
                'seats'
            )
            ->get()
            ->sortByDesc('forecast_seats_avg_seats')
            ->values();
    }

    /** @return \Illuminate\Support\Collection<int, Candidate> */
    #[Computed]
    public function mayorSummary(): \Illuminate\Support\Collection
    {
        $candidates = Candidate::with('party')->get();
        $forecasts = Forecast::real()->select([
            'mayor_candidate_1_id',
            'mayor_candidate_2_id',
            'mayor_runoff_winner_id',
        ])->get();

        return $candidates->map(function (Candidate $c) use ($forecasts) {
            $c->selections = $forecasts->filter(
                fn ($f) => $f->mayor_candidate_1_id === $c->id || $f->mayor_candidate_2_id === $c->id
            )->count();
            $c->runoff_wins = $forecasts->filter(fn ($f) => $f->mayor_runoff_winner_id === $c->id)->count();

            return $c;
        })->sortByDesc('selections')->values();
    }

    #[Computed]
    public function runoffForecast(): ?RunoffForecast
    {
        return RunoffForecast::where('user_id', Auth::id())->first();
    }

    /**
     * @return array{total: int, gruchmann: int, lemke: int, gruchmann_share: int, lemke_share: int, avg_gruchmann_percent: float|null}
     */
    #[Computed]
    public function runoffStats(): array
    {
        $total = RunoffForecast::count();
        $gruchmann = RunoffForecast::where('predicted_winner', 'gruchmann')->count();
        $lemke = RunoffForecast::where('predicted_winner', 'lemke')->count();
        $avg = RunoffForecast::whereNotNull('gruchmann_percent')->avg('gruchmann_percent');

        return [
            'total' => $total,
            'gruchmann' => $gruchmann,
            'lemke' => $lemke,
            'gruchmann_share' => $total > 0 ? round($gruchmann / $total * 100) : 0,
            'lemke_share' => $total > 0 ? round($lemke / $total * 100) : 0,
            'avg_gruchmann_percent' => $avg !== null ? round($avg, 1) : null,
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.dashboard');
    }
}
