<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Admin &ndash; Stichwahl-Prognosen</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ $this->runoffForecasts->count() }} {{ $this->runoffForecasts->count() === 1 ? 'Prognose' : 'Prognosen' }} gefunden</p>
        </div>
    </div>

    {{-- Filter-Leiste --}}
    <div class="flex flex-wrap items-end gap-3">
        <flux:field class="flex-1 min-w-48">
            <flux:label>Pseudonym suchen</flux:label>
            <flux:input wire:model.live.debounce.300ms="search" placeholder="z. B. MaxMuster…" icon="magnifying-glass" />
        </flux:field>
    </div>

    {{-- Tabelle --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">

        @if ($this->runoffForecasts->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-slate-400">Keine Prognosen gefunden.</div>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>#</flux:table.column>
                    <flux:table.column>Pseudonym</flux:table.column>
                    <flux:table.column>IP-Adresse</flux:table.column>
                    <flux:table.column>Nutzer</flux:table.column>
                    <flux:table.column>Tipp</flux:table.column>
                    <flux:table.column>Gruchmann %</flux:table.column>
                    <flux:table.column>Eingereicht</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->runoffForecasts as $forecast)
                        <flux:table.row :key="$forecast->id">

                            <flux:table.cell class="text-slate-400 tabular-nums text-xs">
                                {{ $forecast->id }}
                            </flux:table.cell>

                            <flux:table.cell variant="strong">
                                {{ $forecast->pseudonym }}
                            </flux:table.cell>

                            {{-- IP + Duplikat-Badge --}}
                            <flux:table.cell>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="font-mono text-xs text-slate-600 dark:text-slate-300">
                                        {{ $forecast->ip_address ?? '–' }}
                                    </span>
                                    @if ($forecast->ip_address && in_array($forecast->ip_address, $this->duplicateIps))
                                        <flux:badge color="amber" size="sm" inset="top bottom">Duplikat</flux:badge>
                                    @endif
                                </div>
                            </flux:table.cell>

                            {{-- Nutzer --}}
                            <flux:table.cell>
                                @if ($forecast->user)
                                    <div class="text-sm text-slate-700 dark:text-slate-200">{{ $forecast->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $forecast->user->email }}</div>
                                @else
                                    <span class="text-xs text-slate-400">Gast</span>
                                @endif
                            </flux:table.cell>

                            {{-- Tipp: Gruchmann / Lemke --}}
                            <flux:table.cell>
                                @if ($forecast->predicted_winner === 'gruchmann')
                                    <flux:badge color="red" size="sm">Gruchmann (SPD)</flux:badge>
                                @else
                                    <flux:badge color="blue" size="sm">Lemke (CSU)</flux:badge>
                                @endif
                            </flux:table.cell>

                            {{-- Gruchmann-Prozent --}}
                            <flux:table.cell>
                                @if ($forecast->gruchmann_percent !== null)
                                    <span class="tabular-nums text-sm font-semibold text-slate-700 dark:text-slate-200">
                                        {{ $forecast->gruchmann_percent }}&thinsp;%
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">–</span>
                                @endif
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                    {{ $forecast->created_at->format('d.m.Y H:i') }}
                                </span>
                            </flux:table.cell>

                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif

    </div>

</div>
