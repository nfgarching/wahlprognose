<div class="flex flex-col gap-6 p-6">

    <div class="flex items-center justify-between gap-3 flex-wrap">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Dashboard</h1>
        <div class="flex items-center gap-2">
            @if (auth()->user()->is_admin)
                <a href="{{ route('forecast.export') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 dark:bg-zinc-700 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-lg shadow-sm hover:bg-slate-200 dark:hover:bg-zinc-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    CSV exportieren
                </a>
            @endif
            <a href="{{ route('stichwahl') }}"
               wire:navigate
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-blue-800 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                Zur Wahlprognose
            </a>
        </div>
    </div>

    {{-- ==================== STICHWAHL ==================== --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-700 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">Stichwahl — 22. März 2026</h2>
                    <p class="text-xs text-slate-400">{{ $this->runoffStats['total'] }} {{ $this->runoffStats['total'] === 1 ? 'Prognose' : 'Prognosen' }} abgegeben</p>
                </div>
            </div>
            @if ($this->runoffForecast)
                <a href="{{ route('stichwahl') }}" wire:navigate class="text-xs text-blue-700 hover:underline font-medium">Bearbeiten →</a>
            @else
                <a href="{{ route('stichwahl') }}" wire:navigate class="text-xs text-blue-700 hover:underline font-medium">Jetzt mitmachen →</a>
            @endif
        </div>

        <div class="divide-y divide-slate-100 dark:divide-zinc-700">

            {{-- Eigene Prognose --}}
            @if ($this->runoffForecast)
                <div class="px-6 py-4 bg-slate-50 dark:bg-zinc-800/50">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Meine Stichwahl-Prognose</p>
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($this->runoffForecast->predicted_winner === 'gruchmann')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-sm font-semibold text-red-700">
                                Dr. Dietmar Gruchmann <span class="font-normal text-red-500">SPD</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-50 border border-sky-200 text-sm font-semibold text-sky-700">
                                Thomas Lemke <span class="font-normal text-sky-500">CSU</span>
                            </span>
                        @endif
                        @if ($this->runoffForecast->gruchmann_percent !== null)
                            <span class="text-xs text-slate-500">
                                Stimmenanteil Sieger:
                                <strong class="text-slate-700 dark:text-slate-200 tabular-nums">{{ $this->runoffForecast->gruchmann_percent }}&thinsp;%</strong>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Verteilung aller Prognosen --}}
            @if ($this->runoffStats['total'] > 0)
                <div class="px-6 py-5 space-y-4">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">So tippen alle</p>

                    {{-- Gruchmann --}}
                    <div class="flex items-center gap-3">
                        <div class="w-36 sm:w-48 flex-shrink-0">
                            <div class="text-sm font-medium text-slate-800 dark:text-slate-100">Dr. Gruchmann</div>
                            <div class="text-xs font-semibold text-red-600">SPD</div>
                        </div>
                        <div class="flex-1 h-5 bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-red-600 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                                 style="width: {{ $this->runoffStats['gruchmann_share'] }}%">
                                @if ($this->runoffStats['gruchmann_share'] >= 15)
                                    <span class="text-xs font-bold text-white">{{ $this->runoffStats['gruchmann_share'] }}%</span>
                                @endif
                            </div>
                        </div>
                        @if ($this->runoffStats['gruchmann_share'] < 15)
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 w-10 tabular-nums">{{ $this->runoffStats['gruchmann_share'] }}%</span>
                        @else
                            <span class="w-10"></span>
                        @endif
                        <span class="text-xs text-slate-400 w-16 text-right flex-shrink-0">{{ $this->runoffStats['gruchmann'] }}×</span>
                    </div>

                    {{-- Lemke --}}
                    <div class="flex items-center gap-3">
                        <div class="w-36 sm:w-48 flex-shrink-0">
                            <div class="text-sm font-medium text-slate-800 dark:text-slate-100">Thomas Lemke</div>
                            <div class="text-xs font-semibold text-sky-700">CSU</div>
                        </div>
                        <div class="flex-1 h-5 bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-sky-700 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                                 style="width: {{ $this->runoffStats['lemke_share'] }}%">
                                @if ($this->runoffStats['lemke_share'] >= 15)
                                    <span class="text-xs font-bold text-white">{{ $this->runoffStats['lemke_share'] }}%</span>
                                @endif
                            </div>
                        </div>
                        @if ($this->runoffStats['lemke_share'] < 15)
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 w-10 tabular-nums">{{ $this->runoffStats['lemke_share'] }}%</span>
                        @else
                            <span class="w-10"></span>
                        @endif
                        <span class="text-xs text-slate-400 w-16 text-right flex-shrink-0">{{ $this->runoffStats['lemke'] }}×</span>
                    </div>

                    {{-- Ø Stimmenanteil --}}
                    @if ($this->runoffStats['avg_gruchmann_percent'] !== null)
                        @php
                            $avgG = $this->runoffStats['avg_gruchmann_percent'];
                            $avgL = round(100 - $avgG, 1);
                        @endphp
                        <div class="pt-3 border-t border-slate-100 dark:border-zinc-700">
                            <p class="text-xs text-slate-400 uppercase tracking-wide font-medium mb-2">Ø vorhergesagter Stimmenanteil</p>
                            <div class="flex h-6 rounded-lg overflow-hidden">
                                <div class="flex items-center justify-center text-xs font-bold text-white transition-all duration-500"
                                     style="flex: {{ $avgG }}; background-color: #dc2626">
                                    @if ($avgG >= 20) {{ $avgG }}% @endif
                                </div>
                                <div class="flex items-center justify-center text-xs font-bold text-white transition-all duration-500"
                                     style="flex: {{ $avgL }}; background-color: #0369a1">
                                    @if ($avgL >= 20) {{ $avgL }}% @endif
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-500 mt-1.5">
                                <span><span class="font-semibold text-red-600">Dr. Gruchmann</span> {{ $avgG }}%</span>
                                <span>{{ $avgL }}% <span class="font-semibold text-sky-700">Thomas Lemke</span></span>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="px-6 py-8 text-center text-sm text-slate-400">
                    Noch keine Stichwahl-Prognosen abgegeben.
                    <a href="{{ route('stichwahl') }}" wire:navigate class="text-blue-600 hover:underline ml-1">Sei der Erste!</a>
                </div>
            @endif

        </div>
    </div>

</div>
