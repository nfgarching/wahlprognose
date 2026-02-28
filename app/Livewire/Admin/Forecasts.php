<?php

namespace App\Livewire\Admin;

use App\Models\Forecast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin – Prognosen')]
#[Layout('layouts.app')]
class Forecasts extends Component
{
    public string $search = '';

    public string $filterFake = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    /** @return array<int, string> */
    #[Computed]
    public function duplicateIps(): array
    {
        return Forecast::query()
            ->select('ip_address')
            ->whereNotNull('ip_address')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('ip_address')
            ->all();
    }

    #[Computed]
    public function forecasts(): \Illuminate\Database\Eloquent\Collection
    {
        return Forecast::query()
            ->with(['user', 'mayorCandidate1.party', 'mayorCandidate2.party', 'mayorRunoffWinner', 'seats.party'])
            ->when($this->search, fn ($q) => $q->where('pseudonym', 'like', "%{$this->search}%"))
            ->when($this->filterFake === 'fake', fn ($q) => $q->where('is_fake', true))
            ->when($this->filterFake === 'real', fn ($q) => $q->where('is_fake', false))
            ->latest()
            ->get();
    }

    public function toggleFake(int $forecastId): void
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $forecast = Forecast::findOrFail($forecastId);
        $forecast->update(['is_fake' => ! $forecast->is_fake]);

        unset($this->forecasts);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.forecasts');
    }
}
