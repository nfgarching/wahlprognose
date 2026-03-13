<?php

namespace App\Livewire;

use App\Models\RunoffForecast;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Stichwahl-Prognose Garching 2026')]
#[Layout('layouts.public')]
class RunoffForecastForm extends Component
{
    public string $pseudonym = '';

    /** 'gruchmann' oder 'lemke' */
    public string $predictedWinner = '';

    /** Prognostizierter Stimmenanteil für Gruchmann (0–100), null = keine Angabe */
    public ?int $gruchmannPercent = null;

    public bool $saved = false;

    public ?int $existingForecastId = null;

    public function mount(): void
    {
        if (Auth::check()) {
            $existing = RunoffForecast::where('user_id', Auth::id())->first();

            if ($existing) {
                $this->existingForecastId = $existing->id;
                $this->pseudonym = $existing->pseudonym;
                $this->predictedWinner = $existing->predicted_winner;
                $this->gruchmannPercent = $existing->gruchmann_percent;
            }
        }
    }

    public function submit(): void
    {
        $this->validate([
            'pseudonym' => 'required|string|max:50',
            'predictedWinner' => 'required|in:gruchmann,lemke',
            'gruchmannPercent' => 'nullable|integer|min:51|max:100',
        ], [
            'pseudonym.required' => 'Bitte gib ein Pseudonym an.',
            'pseudonym.max' => 'Das Pseudonym darf maximal 50 Zeichen haben.',
            'predictedWinner.required' => 'Bitte wähle einen Kandidaten aus.',
            'predictedWinner.in' => 'Ungültige Auswahl.',
            'gruchmannPercent.integer' => 'Bitte gib eine ganze Zahl ein.',
            'gruchmannPercent.min' => 'Der Gewinner muss mehr als 50 % erhalten.',
            'gruchmannPercent.max' => 'Der Wert darf maximal 100 sein.',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'pseudonym' => trim($this->pseudonym),
            'predicted_winner' => $this->predictedWinner,
            'gruchmann_percent' => $this->gruchmannPercent,
        ];

        if (Auth::check()) {
            $forecast = RunoffForecast::updateOrCreate(
                ['user_id' => Auth::id()],
                $data
            );
        } else {
            $forecast = RunoffForecast::create($data);
        }

        $this->existingForecastId = $forecast->id;
        $this->saved = true;
    }

    /**
     * @return array{total: int, gruchmann: int, lemke: int, gruchmann_pct: float, lemke_pct: float, avg_gruchmann_percent: float|null}
     */
    #[Computed]
    public function statistics(): array
    {
        $total = RunoffForecast::count();
        $gruchmann = RunoffForecast::where('predicted_winner', 'gruchmann')->count();
        $lemke = RunoffForecast::where('predicted_winner', 'lemke')->count();
        $avgGruchmannPercent = RunoffForecast::whereNotNull('gruchmann_percent')->avg('gruchmann_percent');

        return [
            'total' => $total,
            'gruchmann' => $gruchmann,
            'lemke' => $lemke,
            'gruchmann_pct' => $total > 0 ? round($gruchmann / $total * 100, 1) : 0,
            'lemke_pct' => $total > 0 ? round($lemke / $total * 100, 1) : 0,
            'avg_gruchmann_percent' => $avgGruchmannPercent !== null ? round($avgGruchmannPercent, 1) : null,
        ];
    }

    public function render(): View
    {
        return view('livewire.runoff-forecast-form');
    }
}
