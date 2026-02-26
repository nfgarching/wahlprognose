<div class="flex flex-col gap-6 p-6">

    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Dashboard</h1>
        <a href="{{ route('prognose') }}"
           wire:navigate
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-blue-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>
            Zur Wahlprognose
        </a>
    </div>

    {{-- ==================== MEINE PROGNOSE ==================== --}}
    @if ($this->forecast)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-700 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">Meine Prognose</h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Als <strong class="text-slate-600 dark:text-slate-300">{{ $this->forecast->pseudonym }}</strong>
                        &mdash; zuletzt geändert {{ $this->forecast->updated_at->format('d.m.Y, H:i') }} Uhr
                    </p>
                </div>
                <a href="{{ route('prognose') }}" wire:navigate class="text-xs text-blue-700 hover:underline font-medium">
                    Bearbeiten →
                </a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-zinc-700">

                {{-- Bürgermeisterwahl --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Bürgermeisterwahl</h3>
                    @php
                        $c1 = $this->forecast->mayorCandidate1;
                        $c2 = $this->forecast->mayorCandidate2;
                        $winner = $this->forecast->mayorRunoffWinner;
                    @endphp
                    @if ($c1 || $c2)
                        <div class="flex flex-wrap gap-3">
                            @foreach (array_filter([$c1, $c2]) as $c)
                                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl border-2"
                                     style="border-color: {{ $c->party->color }}30; background-color: {{ $c->party->color }}08;">
                                    <div class="w-9 h-9 rounded-full overflow-hidden flex-shrink-0 border-2" style="border-color: {{ $c->party->color }}">
                                        @if ($c->photo_path)
                                            <img src="{{ Storage::url($c->photo_path) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-sm font-bold"
                                                 style="background-color: {{ $c->party->color }}18; color: {{ $c->party->color }}">
                                                {{ mb_substr($c->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-800 dark:text-white">{{ $c->name }}</div>
                                        <span class="text-xs px-1.5 py-0.5 rounded-full text-white font-semibold" style="background-color: {{ $c->party->color }}">
                                            {{ $c->party->short_name }}
                                        </span>
                                    </div>
                                    @if ($winner && $winner->id === $c->id)
                                        <div class="ml-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Stichwahl-Favorit</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if ($c2 && ! $winner)
                            <p class="mt-2 text-xs text-slate-400">Stichwahl erwartet &mdash; kein Favorit angegeben.</p>
                        @elseif ($c2 && $winner)
                            <p class="mt-2 text-xs text-slate-400">Stichwahl erwartet &mdash; Favorit: <strong>{{ $winner->name }}</strong>.</p>
                        @endif
                    @else
                        <p class="text-sm text-slate-400">Kein Kandidat gewählt.</p>
                    @endif
                </div>

                {{-- Sitzverteilung --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Stadtrat &mdash; Sitzverteilung</h3>
                    @php
                        $seatMap = $this->forecast->seats->keyBy('party_id');
                        $total = $this->forecast->seats->sum('seats');
                    @endphp
                    @if ($total > 0)
                        <div class="flex h-5 rounded-full overflow-hidden gap-px mb-3">
                            @foreach ($this->parties as $party)
                                @php $s = $seatMap[$party->id]->seats ?? 0; @endphp
                                @if ($s > 0)
                                    <div class="h-full" style="flex: {{ $s }}; background-color: {{ $party->color }}" title="{{ $party->short_name }}: {{ $s }} Sitze"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-y-2 gap-x-4">
                            @foreach ($this->parties as $party)
                                @php $s = $seatMap[$party->id]->seats ?? 0; @endphp
                                @if ($s > 0)
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="w-3 h-3 rounded-sm flex-shrink-0" style="background-color: {{ $party->color }}"></span>
                                        <span class="font-semibold" style="color: {{ $party->color }}">{{ $party->short_name }}</span>
                                        <span class="text-slate-500 dark:text-slate-400 tabular-nums">{{ $s }}</span>
                                        <span class="text-slate-400 text-xs">({{ round($s / 24 * 100) }}%)</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-400">Keine Sitzverteilung angegeben.</p>
                    @endif
                </div>

            </div>
        </div>

    @else
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm p-8 text-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-1">Noch keine Prognose abgegeben</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Gib jetzt deine Einschätzung zur Kommunalwahl Garching 2026 ab.</p>
            <a href="{{ route('prognose') }}" wire:navigate
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-blue-800 transition-colors">
                Jetzt Prognose abgeben
            </a>
        </div>
    @endif

    {{-- ==================== GESAMTÜBERSICHT ==================== --}}
    @if (! $this->forecast)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm p-5 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800 dark:text-white">Gesamtübersicht noch gesperrt</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Die Gesamtübersicht aller Prognosen wird sichtbar, sobald du deine eigene Prognose abgegeben hast.
                </p>
            </div>
        </div>
    @elseif ($this->forecastCount > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 dark:border-zinc-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">Gesamtübersicht aller Prognosen</h2>
                    <p class="text-xs text-slate-400">{{ $this->forecastCount }} {{ $this->forecastCount === 1 ? 'Prognose' : 'Prognosen' }} abgegeben</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-zinc-700">

                {{-- Bürgermeister-Übersicht --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                        Bürgermeisterwahl &mdash; Kandidatenbeliebtheit
                    </h3>
                    <div class="space-y-3">
                        @foreach ($this->mayorSummary as $c)
                            @if ($c->selections > 0)
                                <div class="flex items-center gap-3">
                                    {{-- Kandidat --}}
                                    <div class="w-32 sm:w-44 flex-shrink-0 flex items-center gap-2 min-w-0">
                                        <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0 border" style="border-color: {{ $c->party->color }}">
                                            @if ($c->photo_path)
                                                <img src="{{ Storage::url($c->photo_path) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs font-bold"
                                                     style="background-color: {{ $c->party->color }}18; color: {{ $c->party->color }}">
                                                    {{ mb_substr($c->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $c->name }}</div>
                                            <div class="text-xs" style="color: {{ $c->party->color }}">{{ $c->party->short_name }}</div>
                                        </div>
                                    </div>
                                    {{-- Balken --}}
                                    <div class="flex-1 h-5 bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-300"
                                             style="width: {{ round($c->selections / $this->forecastCount * 100) }}%; background-color: {{ $c->party->color }}">
                                        </div>
                                    </div>
                                    {{-- Zahl --}}
                                    <div class="w-20 flex-shrink-0 text-right">
                                        <span class="text-sm font-bold tabular-nums" style="color: {{ $c->party->color }}">
                                            {{ $c->selections }}×
                                        </span>
                                        <span class="text-xs text-slate-400 ml-1">
                                            ({{ round($c->selections / $this->forecastCount * 100) }}%)
                                        </span>
                                    </div>
                                </div>
                                @if ($c->runoff_wins > 0)
                                    <div class="ml-32 sm:ml-44 pl-3 -mt-1 mb-1">
                                        <span class="text-xs text-amber-600 dark:text-amber-400">
                                            ↳ {{ $c->runoff_wins }}× als Stichwahl-Favorit
                                        </span>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Sitzverteilung-Übersicht --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">
                        Stadtrat &mdash; Durchschnittliche Sitzverteilung
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">Mittelwert über alle {{ $this->forecastCount }} Prognosen</p>

                    {{-- Durchschnitts-Balken --}}
                    @php $maxAvg = $this->seatSummary->max('forecast_seats_avg_seats'); @endphp
                    <div class="space-y-2.5">
                        @foreach ($this->seatSummary as $party)
                            @php $avg = round($party->forecast_seats_avg_seats ?? 0, 1); @endphp
                            @if ($avg > 0)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 flex-shrink-0 text-right">
                                        <span class="text-xs font-bold" style="color: {{ $party->color }}">{{ $party->short_name }}</span>
                                    </div>
                                    <div class="flex-1 h-5 bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full"
                                             style="width: {{ $maxAvg > 0 ? round($avg / $maxAvg * 100) : 0 }}%; background-color: {{ $party->color }}">
                                        </div>
                                    </div>
                                    <div class="w-28 flex-shrink-0 text-xs text-slate-500 dark:text-slate-400 tabular-nums">
                                        Ø {{ $avg }} Sitze
                                        <span class="text-slate-300 dark:text-zinc-600">({{ round($avg / 24 * 100) }}%)</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Gesamt-Balken (Proportional) --}}
                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-zinc-700">
                        <p class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wide">Visualisierung Gesamtbild</p>
                        <div class="flex h-5 rounded-full overflow-hidden gap-px">
                            @foreach ($this->seatSummary as $party)
                                @php $avg = $party->forecast_seats_avg_seats ?? 0; @endphp
                                @if ($avg > 0)
                                    <div class="h-full" style="flex: {{ $avg }}; background-color: {{ $party->color }}" title="{{ $party->short_name }}: Ø {{ round($avg, 1) }} Sitze"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                            @foreach ($this->seatSummary as $party)
                                @php $avg = round($party->forecast_seats_avg_seats ?? 0, 1); @endphp
                                @if ($avg > 0)
                                    <span class="flex items-center gap-1 text-xs text-slate-600 dark:text-slate-400">
                                        <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background-color: {{ $party->color }}"></span>
                                        {{ $party->short_name }} Ø {{ $avg }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>
