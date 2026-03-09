<div class="flex flex-col gap-6 p-6">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Admin &ndash; Prognose-Ranking</h1>
        <p class="text-xs text-slate-400 mt-0.5">Rangliste aller echten Prognosen nach Genauigkeit</p>
    </div>

    {{-- Offizielles Ergebnis --}}
    <div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm">

        <div class="px-6 py-3 bg-emerald-600 flex items-center gap-2">
            <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h2 class="font-bold text-white text-sm">Offizielles Endergebnis &mdash; Kommunalwahl Garching 9. März 2026</h2>
        </div>

        <div class="bg-white divide-y divide-slate-100">

            {{-- Bürgermeister --}}
            <div class="px-6 py-4 flex flex-wrap items-center gap-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider w-28 shrink-0">Bürgermeister</span>
                <div class="flex flex-wrap items-center gap-3">
                    @foreach (\App\Livewire\Admin\Ranking::OFFICIAL_RUNOFF_NAMES as $name)
                        @php $candidate = \App\Models\Candidate::with('party')->where('name', $name)->first(); @endphp
                        @if ($candidate)
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $candidate->party->color }}"></span>
                                <span class="text-sm font-semibold text-slate-800">{{ $candidate->name }}</span>
                                <span class="text-xs font-semibold" style="color: {{ $candidate->party->color }}">({{ $candidate->party->short_name }})</span>
                            </div>
                        @endif
                    @endforeach
                    <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">Stichwahl &mdash; Ausgang offen</span>
                </div>
            </div>

            {{-- Stadtrat --}}
            <div class="px-6 py-4 flex flex-wrap items-start gap-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider w-28 shrink-0 pt-0.5">Stadtrat</span>
                <div class="flex flex-wrap gap-2">
                    @foreach (\App\Livewire\Admin\Ranking::OFFICIAL_SEATS as $shortName => $seats)
                        @php $party = $this->parties->get($shortName); @endphp
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg"
                              style="background-color: {{ $party?->color }}20; color: {{ $party?->color }}">
                            {{ $shortName }} {{ $seats }}
                        </span>
                    @endforeach
                    <span class="text-xs text-slate-400 self-center">= 24 Sitze gesamt</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Rangliste --}}
    @if ($this->rankedForecasts->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center text-sm text-slate-400">
            Keine echten Prognosen vorhanden.
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-700 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900 dark:text-white">Rangliste</h2>
                    <p class="text-xs text-slate-400">{{ $this->rankedForecasts->count() }} Prognosen &mdash; sortiert nach Gesamtfehler (niedriger = besser)</p>
                </div>
                <div class="text-xs text-slate-400 hidden sm:block">
                    Punkte = Sitzdifferenz + Bürgermeister-Fehler &times; 3
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-zinc-700">
                @foreach ($this->rankedForecasts as $rank => $forecast)
                    @php
                        $rankNum = $rank + 1;
                        $medal = match ($rankNum) {
                            1 => ['bg' => 'bg-amber-50', 'border' => 'border-l-4 border-amber-400', 'badge' => 'bg-amber-400 text-white', 'label' => '🥇'],
                            2 => ['bg' => 'bg-slate-50', 'border' => 'border-l-4 border-slate-400', 'badge' => 'bg-slate-400 text-white', 'label' => '🥈'],
                            3 => ['bg' => 'bg-orange-50', 'border' => 'border-l-4 border-orange-400', 'badge' => 'bg-orange-400 text-white', 'label' => '🥉'],
                            default => ['bg' => '', 'border' => '', 'badge' => 'bg-slate-100 text-slate-600', 'label' => (string) $rankNum],
                        };
                    @endphp

                    <div class="px-5 py-4 {{ $medal['bg'] }} {{ $medal['border'] }}">
                        <div class="flex flex-wrap items-start gap-3">

                            {{-- Rang --}}
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $medal['badge'] }}">
                                {{ $rankNum <= 3 ? $rankNum : $rankNum }}
                            </div>

                            {{-- Name + Score --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="font-semibold text-slate-900 dark:text-white text-sm">{{ $forecast->pseudonym }}</span>
                                    @if ($forecast->user)
                                        <span class="text-xs text-slate-400">{{ $forecast->user->email }}</span>
                                    @endif
                                    <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full
                                        {{ $forecast->total_score === 0 ? 'bg-emerald-100 text-emerald-700' : ($forecast->total_score <= 4 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $forecast->total_score }} Punkte
                                    </span>
                                </div>

                                {{-- Sitzvergleich --}}
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    @foreach (\App\Livewire\Admin\Ranking::OFFICIAL_SEATS as $shortName => $officialCount)
                                        @php
                                            $diff = $forecast->seat_diffs[$shortName] ?? -$officialCount;
                                            $party = $this->parties->get($shortName);
                                            $predicted = $officialCount + $diff;
                                        @endphp
                                        <span class="inline-flex items-center gap-0.5 text-xs px-2 py-0.5 rounded font-semibold"
                                              style="background-color: {{ $party?->color }}18; color: {{ $party?->color }}">
                                            {{ $shortName }}&nbsp;{{ $predicted }}
                                            @if ($diff !== 0)
                                                <span class="font-normal opacity-70">({{ $diff > 0 ? '+' : '' }}{{ $diff }})</span>
                                            @else
                                                <span class="font-normal opacity-50">✓</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Bürgermeister --}}
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                    <span class="font-medium">BM-Stichwahl:</span>
                                    @if ($forecast->mayorCandidate1)
                                        <span class="{{ in_array($forecast->mayor_candidate_1_id, $this->runoffCandidateIds) ? 'text-emerald-600 font-semibold' : 'text-rose-500' }}">
                                            {{ $forecast->mayorCandidate1->name }}
                                            {{ in_array($forecast->mayor_candidate_1_id, $this->runoffCandidateIds) ? '✓' : '✗' }}
                                        </span>
                                    @endif
                                    @if ($forecast->mayorCandidate2)
                                        <span class="text-slate-300">vs.</span>
                                        <span class="{{ in_array($forecast->mayor_candidate_2_id, $this->runoffCandidateIds) ? 'text-emerald-600 font-semibold' : 'text-rose-500' }}">
                                            {{ $forecast->mayorCandidate2->name }}
                                            {{ in_array($forecast->mayor_candidate_2_id, $this->runoffCandidateIds) ? '✓' : '✗' }}
                                        </span>
                                    @endif
                                    <span class="ml-2 text-slate-400">
                                        Bürgermeister-Fehler: {{ $forecast->mayor_error }}/2
                                        &bull; Sitz-Differenz: {{ $forecast->seat_error }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    @endif

</div>
