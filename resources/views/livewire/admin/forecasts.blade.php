<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Admin &ndash; Prognosen</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ $this->forecasts->count() }} {{ $this->forecasts->count() === 1 ? 'Prognose' : 'Prognosen' }} gefunden</p>
        </div>
    </div>

    {{-- Filter-Leiste --}}
    <div class="flex flex-wrap items-end gap-3">
        <flux:field class="flex-1 min-w-48">
            <flux:label>Pseudonym suchen</flux:label>
            <flux:input wire:model.live.debounce.300ms="search" placeholder="z. B. MaxMuster…" icon="magnifying-glass" />
        </flux:field>

        <flux:field class="w-48">
            <flux:label>Status</flux:label>
            <flux:select wire:model.live="filterFake">
                <option value="">Alle</option>
                <option value="real">Nur echte</option>
                <option value="fake">Nur gefälschte</option>
            </flux:select>
        </flux:field>
    </div>

    {{-- Tabelle --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">

        @if ($this->forecasts->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-slate-400">Keine Prognosen gefunden.</div>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>#</flux:table.column>
                    <flux:table.column>Pseudonym</flux:table.column>
                    <flux:table.column>IP-Adresse</flux:table.column>
                    <flux:table.column>Nutzer</flux:table.column>
                    <flux:table.column>Eingereicht</flux:table.column>
                    <flux:table.column>Fake</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->forecasts as $forecast)
                        {{-- Zeile 1: Hauptdaten --}}
                        <flux:table.row :key="$forecast->id" class="{{ $forecast->is_fake ? 'opacity-50' : '' }}">

                            {{-- ID --}}
                            <flux:table.cell class="text-slate-400 tabular-nums text-xs">
                                {{ $forecast->id }}
                            </flux:table.cell>

                            {{-- Pseudonym --}}
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
                                    @if ($forecast->user->wants_newsletter)
                                        <flux:badge color="blue" size="sm" class="mt-0.5">Newsletter</flux:badge>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400">Gast</span>
                                @endif
                            </flux:table.cell>

                            {{-- Datum --}}
                            <flux:table.cell>
                                <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                    {{ $forecast->created_at->format('d.m.Y H:i') }}
                                </span>
                            </flux:table.cell>

                            {{-- Fake-Toggle --}}
                            <flux:table.cell>
                                <flux:button
                                    wire:click="toggleFake({{ $forecast->id }})"
                                    wire:loading.attr="disabled"
                                    size="sm"
                                    :variant="$forecast->is_fake ? 'danger' : 'ghost'"
                                    :icon="$forecast->is_fake ? 'x-circle' : 'check-circle'"
                                    inset="top bottom"
                                >
                                    {{ $forecast->is_fake ? 'Fake' : 'Echt' }}
                                </flux:button>
                            </flux:table.cell>

                        </flux:table.row>

                        {{-- Zeile 2: Kandidaten + Sitzverteilung --}}
                        <tr wire:key="{{ $forecast->id }}-detail" class="{{ $forecast->is_fake ? 'opacity-50' : '' }} border-b border-slate-100 dark:border-zinc-700 last:border-0">
                            <td colspan="6" class="px-4 pb-3 pt-0">
                                <div class="flex flex-wrap items-start gap-x-6 gap-y-1.5">

                                    {{-- Bürgermeister-Kandidaten --}}
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs text-slate-400 font-medium shrink-0">BM:</span>
                                        @if ($forecast->mayorCandidate1)
                                            <span class="inline-flex items-center gap-1 text-xs">
                                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ $forecast->mayorCandidate1->name }}</span>
                                                @if ($forecast->mayorCandidate1->party)
                                                    <span class="font-semibold" style="color: {{ $forecast->mayorCandidate1->party->color }}">({{ $forecast->mayorCandidate1->party->short_name }})</span>
                                                @endif
                                                @if ($forecast->mayorRunoffWinner?->id === $forecast->mayorCandidate1->id)
                                                    <span class="text-amber-500">★</span>
                                                @endif
                                            </span>
                                        @endif
                                        @if ($forecast->mayorCandidate2)
                                            <span class="text-slate-300 dark:text-zinc-600 text-xs">vs.</span>
                                            <span class="inline-flex items-center gap-1 text-xs">
                                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ $forecast->mayorCandidate2->name }}</span>
                                                @if ($forecast->mayorCandidate2->party)
                                                    <span class="font-semibold" style="color: {{ $forecast->mayorCandidate2->party->color }}">({{ $forecast->mayorCandidate2->party->short_name }})</span>
                                                @endif
                                                @if ($forecast->mayorRunoffWinner?->id === $forecast->mayorCandidate2->id)
                                                    <span class="text-amber-500">★</span>
                                                @endif
                                            </span>
                                        @endif
                                        @if (! $forecast->mayorCandidate1 && ! $forecast->mayorCandidate2)
                                            <span class="text-xs text-slate-400">–</span>
                                        @endif
                                    </div>

                                    {{-- Sitzverteilung --}}
                                    @if ($forecast->seats->isNotEmpty())
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="text-xs text-slate-400 font-medium shrink-0">Sitze:</span>
                                            @foreach ($forecast->seats->sortByDesc('seats') as $seat)
                                                @if ($seat->seats > 0)
                                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold px-1.5 py-0.5 rounded"
                                                          style="background-color: {{ $seat->party->color }}20; color: {{ $seat->party->color }}">
                                                        {{ $seat->party->short_name }}&nbsp;{{ $seat->seats }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif

    </div>

</div>
