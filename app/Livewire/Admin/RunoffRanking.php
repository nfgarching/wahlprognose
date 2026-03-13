<?php

namespace App\Livewire\Admin;

use App\Models\RunoffForecast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin – Stichwahl-Ranking')]
#[Layout('layouts.app')]
class RunoffRanking extends Component
{
    /**
     * Set after 22 March 2026 once the official result is known.
     * Allowed values: 'gruchmann', 'lemke', or null (result pending).
     */
    public const OFFICIAL_WINNER = null;

    public const OFFICIAL_GRUCHMANN_PERCENT = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    /** @return \Illuminate\Support\Collection<string, mixed> */
    #[Computed]
    public function stats(): \Illuminate\Support\Collection
    {
        $forecasts = RunoffForecast::query()->with('user')->get();
        $total = $forecasts->count();

        $gruchmannCount = $forecasts->where('predicted_winner', 'gruchmann')->count();
        $lemkeCount = $forecasts->where('predicted_winner', 'lemke')->count();

        $percents = $forecasts->whereNotNull('gruchmann_percent')->pluck('gruchmann_percent');
        $avgPercent = $percents->isNotEmpty() ? round($percents->avg(), 1) : null;

        $sorted = $forecasts->sortBy(function (RunoffForecast $f) {
            $winnerCorrect = self::OFFICIAL_WINNER !== null
                ? ($f->predicted_winner === self::OFFICIAL_WINNER ? 0 : 1)
                : ($f->predicted_winner === 'gruchmann' ? 0 : 1);

            $percentDeviation = self::OFFICIAL_GRUCHMANN_PERCENT !== null && $f->gruchmann_percent !== null
                ? abs($f->gruchmann_percent - self::OFFICIAL_GRUCHMANN_PERCENT)
                : ($f->gruchmann_percent !== null ? 0 : 999);

            $percentOrder = self::OFFICIAL_WINNER === null
                ? ($f->gruchmann_percent !== null ? (1000 - $f->gruchmann_percent) : 1001)
                : $percentDeviation;

            return [$winnerCorrect, $percentOrder];
        })->values();

        return collect([
            'total' => $total,
            'gruchmann_count' => $gruchmannCount,
            'lemke_count' => $lemkeCount,
            'gruchmann_share' => $total > 0 ? round($gruchmannCount / $total * 100) : 0,
            'lemke_share' => $total > 0 ? round($lemkeCount / $total * 100) : 0,
            'avg_gruchmann_percent' => $avgPercent,
            'forecasts' => $sorted,
        ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.runoff-ranking');
    }
}
