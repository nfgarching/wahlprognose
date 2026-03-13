<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Admin &ndash; Stichwahl-Ranking</h1>
        <p class="text-xs text-slate-400 mt-0.5">{{ $this->stats['total'] }} {{ $this->stats['total'] === 1 ? 'Prognose' : 'Prognosen' }} insgesamt</p>
    </div>

    {{-- Offizielles Ergebnis / Ausstehend --}}
    @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_WINNER)
        <div class="bg-emerald-600 text-white rounded-2xl p-5 shadow">
            <p class="text-xs font-black tracking-widest uppercase text-emerald-200 mb-2">Offizielles Ergebnis</p>
            <p class="text-lg font-bold">
                Gewinner: {{ \App\Livewire\Admin\RunoffRanking::OFFICIAL_WINNER === 'gruchmann' ? 'Dr. Dietmar Gruchmann (SPD)' : 'Thomas Lemke (CSU)' }}
            </p>
            @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT !== null)
                <p class="text-sm text-emerald-100 mt-1">Gruchmann: {{ \App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT }}&thinsp;% &nbsp;|&nbsp; Lemke: {{ 100 - \App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT }}&thinsp;%</p>
            @endif
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <p class="text-sm font-semibold text-amber-700">Ergebnis noch ausstehend &mdash; Stichwahl am 22. März 2026</p>
            <p class="text-xs text-amber-600 mt-1">Sobald das Ergebnis feststeht, <code>OFFICIAL_WINNER</code> und <code>OFFICIAL_GRUCHMANN_PERCENT</code> in <code>RunoffRanking.php</code> setzen.</p>
        </div>
    @endif

    {{-- Stats-Box: Verteilung der Tipps --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm p-6">
        <h2 class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-4 uppercase tracking-widest">Verteilung der Tipps</h2>

        <div class="grid sm:grid-cols-2 gap-4 mb-5">
            {{-- Gruchmann --}}
            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/20 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-red-700">Gruchmann (SPD)</span>
                    <span class="text-xl font-black text-red-700 tabular-nums">{{ $this->stats['gruchmann_count'] }}</span>
                </div>
                <div class="w-full bg-red-100 rounded-full h-2.5">
                    <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $this->stats['gruchmann_share'] }}%"></div>
                </div>
                <p class="text-xs text-red-500 mt-1 text-right">{{ $this->stats['gruchmann_share'] }}&thinsp;%</p>
            </div>

            {{-- Lemke --}}
            <div class="rounded-xl border border-blue-200 bg-blue-50 dark:bg-blue-950/20 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-blue-700">Lemke (CSU)</span>
                    <span class="text-xl font-black text-blue-700 tabular-nums">{{ $this->stats['lemke_count'] }}</span>
                </div>
                <div class="w-full bg-blue-100 rounded-full h-2.5">
                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $this->stats['lemke_share'] }}%"></div>
                </div>
                <p class="text-xs text-blue-500 mt-1 text-right">{{ $this->stats['lemke_share'] }}&thinsp;%</p>
            </div>
        </div>

        @if ($this->stats['avg_gruchmann_percent'] !== null)
            <p class="text-sm text-slate-600 dark:text-slate-400">
                Ø vorhergesagter Gruchmann-Anteil: <strong class="text-slate-900 dark:text-white">{{ $this->stats['avg_gruchmann_percent'] }}&thinsp;%</strong>
                (Lemke: <strong class="text-slate-900 dark:text-white">{{ round(100 - $this->stats['avg_gruchmann_percent'], 1) }}&thinsp;%</strong>)
            </p>
        @endif
    </div>

    {{-- Einzelne Prognosen --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 dark:border-zinc-700 text-xs text-slate-400">
            @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_WINNER)
                Sortierung: Korrekte Sipper-Vorhersage zuerst, dann nach kleinster Prozent-Abweichung
            @else
                Sortierung: Gruchmann-Tipper zuerst, dann nach vorhergesagtem Stimmenanteil absteigend
            @endif
        </div>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Pseudonym</flux:table.column>
                <flux:table.column>Tipp</flux:table.column>
                <flux:table.column>Sieger&thinsp;%</flux:table.column>
                @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_WINNER)
                    <flux:table.column>Korrekt</flux:table.column>
                    @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT !== null)
                        <flux:table.column>Abweichung</flux:table.column>
                    @endif
                @endif
                <flux:table.column>Nutzer</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->stats['forecasts'] as $rank => $forecast)
                    <flux:table.row :key="$forecast->id">

                        <flux:table.cell class="text-slate-400 tabular-nums text-xs w-8">
                            {{ $rank + 1 }}
                        </flux:table.cell>

                        <flux:table.cell variant="strong">{{ $forecast->pseudonym }}</flux:table.cell>

                        <flux:table.cell>
                            @if ($forecast->predicted_winner === 'gruchmann')
                                <flux:badge color="red" size="sm">Gruchmann</flux:badge>
                            @else
                                <flux:badge color="blue" size="sm">Lemke</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            <span class="tabular-nums text-sm {{ $forecast->gruchmann_percent !== null ? 'text-slate-700 dark:text-slate-200 font-semibold' : 'text-slate-400' }}">
                                {{ $forecast->gruchmann_percent !== null ? $forecast->gruchmann_percent . ' %' : '–' }}
                            </span>
                        </flux:table.cell>

                        @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_WINNER)
                            <flux:table.cell>
                                @if ($forecast->predicted_winner === \App\Livewire\Admin\RunoffRanking::OFFICIAL_WINNER)
                                    <span class="text-emerald-600 font-bold">✓</span>
                                @else
                                    <span class="text-red-500">✗</span>
                                @endif
                            </flux:table.cell>

                            @if (\App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT !== null && $forecast->gruchmann_percent !== null)
                                <flux:table.cell>
                                    @php $diff = $forecast->gruchmann_percent - \App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT; @endphp
                                    <span class="tabular-nums text-sm {{ $diff === 0 ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
                                        {{ $diff > 0 ? '+' : '' }}{{ $diff }}&thinsp;%
                                    </span>
                                </flux:table.cell>
                            @elseif (\App\Livewire\Admin\RunoffRanking::OFFICIAL_GRUCHMANN_PERCENT !== null)
                                <flux:table.cell><span class="text-slate-400 text-xs">–</span></flux:table.cell>
                            @endif
                        @endif

                        <flux:table.cell>
                            @if ($forecast->user)
                                <div class="text-sm text-slate-700 dark:text-slate-200">{{ $forecast->user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $forecast->user->email }}</div>
                            @else
                                <span class="text-xs text-slate-400">Gast</span>
                            @endif
                        </flux:table.cell>

                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

</div>
