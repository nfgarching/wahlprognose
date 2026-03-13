<?php

namespace App\Livewire\Admin;

use App\Models\RunoffForecast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin – Stichwahl-Prognosen')]
#[Layout('layouts.app')]
class RunoffForecasts extends Component
{
    public string $search = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    /** @return array<int, string> */
    #[Computed]
    public function duplicateIps(): array
    {
        return RunoffForecast::query()
            ->select('ip_address')
            ->whereNotNull('ip_address')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('ip_address')
            ->all();
    }

    #[Computed]
    public function runoffForecasts(): \Illuminate\Database\Eloquent\Collection
    {
        return RunoffForecast::query()
            ->with('user')
            ->when($this->search, fn ($q) => $q->where('pseudonym', 'like', "%{$this->search}%"))
            ->latest()
            ->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.runoff-forecasts');
    }
}
