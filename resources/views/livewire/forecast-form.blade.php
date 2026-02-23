<div
    class="space-y-8"
    x-data="{
        get remaining() {
            const dist = $wire.seatDistribution;
            return {{ \App\Livewire\ForecastForm::TOTAL_SEATS }} - Object.values(dist).reduce((sum, n) => sum + Number(n), 0);
        }
    }"
>

    {{-- Hero --}}
    <div class="text-center py-6">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Wahlprognose Garching 2026</h1>
        <p class="mt-2 text-slate-500">Kommunalwahl &mdash; 15. März 2026 &mdash; Was glaubst du, wie Garching wählt?</p>
    </div>

    {{-- Erfolgsmeldung --}}
    @if ($saved)
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div>
                <p class="font-semibold text-emerald-800">Deine Prognose wurde gespeichert!</p>
                @auth
                    @if ($this->canEdit)
                        <p class="text-sm text-emerald-700 mt-0.5">Du kannst sie bis zum 07.03.2026 noch ändern.</p>
                    @endif
                @else
                    <p class="text-sm text-emerald-700 mt-0.5">
                        <a href="{{ route('register') }}" class="underline font-medium">Registriere dich</a>, um deine Prognose bis zum 07.03.2026 anpassen zu können.
                    </p>
                @endauth
            </div>
        </div>
    @endif

    {{-- Allgemeiner Fehler --}}
    @error('general')
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            {{ $message }}
        </div>
    @enderror

    {{-- Deadline-Hinweis für abgelaufene Prognosen --}}
    @if ($this->deadlinePassed)
        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">
            <strong>Hinweis:</strong> Die Frist zur Änderung deiner Prognose ist am 07.03.2026 abgelaufen. Deine gespeicherte Prognose ist unten zur Ansicht aufgeführt.
        </div>
    @endif

    {{-- ============================================================ --}}
    {{--  SCHRITT 1: Pseudonym                                        --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">1</span>
            Dein Name / Pseudonym
        </h2>

        <div class="max-w-sm">
            <label for="pseudonym" class="block text-sm font-medium text-slate-700 mb-1.5">
                Wie möchtest du heißen?
            </label>
            <input
                id="pseudonym"
                type="text"
                wire:model.live="pseudonym"
                placeholder="z. B. GarchingBürger42"
                maxlength="50"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition
                    @error('pseudonym') border-red-400 focus:border-red-400 focus:ring-red-100 @enderror"
                @if($this->deadlinePassed) readonly @endif
            >
            @error('pseudonym')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
            @auth
                <p class="mt-2 text-xs text-slate-400">Du bist eingeloggt als <strong>{{ auth()->user()->name }}</strong>. Deine Prognose wird deinem Konto zugeordnet.</p>
            @else
                <p class="mt-2 text-xs text-slate-400">
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Registriere dich</a>, um deine Prognose später bearbeiten zu können.
                </p>
            @endauth
        </div>
    </div>

    {{-- ============================================================ --}}
    {{--  SCHRITT 2: Bürgermeisterwahl                               --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-1 flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">2</span>
            Bürgermeisterwahl
        </h2>
        <p class="text-sm text-slate-500 mb-5 ml-9">
            Wähle <strong>einen</strong> Kandidaten (direkter Wahlsieg) oder <strong>zwei</strong> Kandidaten (du erwartest eine Stichwahl).
        </p>

        @error('selectedMayorCandidates')
            <div class="mb-4 ml-9 text-sm text-red-600">{{ $message }}</div>
        @enderror

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach ($candidates as $candidate)
                @php
                    $isSelected = in_array($candidate->id, $selectedMayorCandidates);
                    $isFirst = $selectedMayorCandidates[0] ?? null === $candidate->id;
                    $selectionOrder = array_search($candidate->id, $selectedMayorCandidates);
                    $canSelect = ! $isSelected && count($selectedMayorCandidates) >= 2;
                @endphp
                <button
                    type="button"
                    wire:click="toggleMayorCandidate({{ $candidate->id }})"
                    @if($this->deadlinePassed) disabled @endif
                    class="relative flex flex-col items-center gap-2 p-4 rounded-xl border-2 text-center transition-all duration-150 cursor-pointer
                        {{ $isSelected
                            ? 'shadow-md ring-2 ring-offset-1'
                            : ($canSelect ? 'border-slate-200 opacity-50 cursor-not-allowed' : 'border-slate-200 hover:border-slate-300 hover:shadow-sm') }}
                        {{ $this->deadlinePassed ? 'cursor-not-allowed opacity-70' : '' }}"
                    style="{{ $isSelected ? 'border-color: ' . $candidate->party->color . '; --tw-ring-color: ' . $candidate->party->color . '66;' : '' }}"
                >
                    {{-- Auswahl-Badge --}}
                    @if ($isSelected)
                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="background-color: {{ $candidate->party->color }}">
                            {{ $selectionOrder + 1 }}
                        </div>
                    @endif

                    {{-- Foto / Initialen-Placeholder --}}
                    <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0 border-2"
                         style="border-color: {{ $candidate->party->color }}20">
                        @if ($candidate->photo_path)
                            <img src="{{ Storage::url($candidate->photo_path) }}"
                                 alt="{{ $candidate->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xl font-bold"
                                 style="background-color: {{ $candidate->party->color }}18; color: {{ $candidate->party->color }}">
                                {{ mb_substr($candidate->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    {{-- Name --}}
                    <div class="font-medium text-xs text-slate-800 leading-tight">{{ $candidate->name }}</div>

                    {{-- Partei-Badge --}}
                    <span class="text-xs px-2 py-0.5 rounded-full text-white font-semibold"
                          style="background-color: {{ $candidate->party->color }}">
                        {{ $candidate->party->short_name }}
                    </span>
                </button>
            @endforeach
        </div>

        {{-- Stichwahl-Sektion (erscheint wenn 2 gewählt) --}}
        @if ($this->hasRunoff)
            <div class="mt-6 p-4 rounded-xl border border-amber-200 bg-amber-50">
                <h3 class="font-semibold text-amber-800 text-sm mb-1">
                    🗳️ Du erwartest eine Stichwahl
                </h3>
                <p class="text-xs text-amber-700 mb-3">
                    Optional: Wer gewinnt die Stichwahl?
                </p>
                <div class="flex gap-3">
                    @foreach ($selectedMayorCandidates as $candidateId)
                        @php $c = $candidates->firstWhere('id', $candidateId); @endphp
                        @if ($c)
                            <button
                                type="button"
                                wire:click="$set('mayorRunoffWinnerId', {{ $c->id === $mayorRunoffWinnerId ? 'null' : $c->id }})"
                                @if($this->deadlinePassed) disabled @endif
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 text-sm font-medium transition-all
                                    {{ $mayorRunoffWinnerId === $c->id ? 'text-white shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300' }}"
                                style="{{ $mayorRunoffWinnerId === $c->id ? 'background-color: ' . $c->party->color . '; border-color: ' . $c->party->color . ';' : '' }}"
                            >
                                {{ $c->name }}
                                <span class="text-xs px-1.5 py-0.5 rounded {{ $mayorRunoffWinnerId === $c->id ? 'bg-white/20' : 'bg-slate-100' }}"
                                      style="{{ $mayorRunoffWinnerId !== $c->id ? 'color: ' . $c->party->color : '' }}">
                                    {{ $c->party->short_name }}
                                </span>
                            </button>
                        @endif
                    @endforeach
                    @if ($mayorRunoffWinnerId)
                        <button
                            type="button"
                            wire:click="$set('mayorRunoffWinnerId', null)"
                            class="text-xs text-slate-400 hover:text-slate-600 px-2 self-center"
                        >
                            ✕ Zurücksetzen
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================================ --}}
    {{--  SCHRITT 3: Stadtratswahl                                    --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-1 flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">3</span>
            Stadtratswahl &mdash; Sitzverteilung
        </h2>
        <p class="text-sm text-slate-500 mb-2 ml-9">Verteile genau 24 Sitze auf die Parteien.</p>

        {{-- Counter --}}
        <div class="ml-9 mb-5">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border"
                :class="{
                    'bg-emerald-50 border-emerald-200 text-emerald-700': remaining === 0,
                    'bg-red-50 border-red-200 text-red-700': remaining < 0,
                    'bg-amber-50 border-amber-200 text-amber-700': remaining > 0
                }"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <span x-text="remaining === 0 ? 'Alle 24 Sitze verteilt ✓' : (remaining > 0 ? 'Noch ' + remaining + (remaining === 1 ? ' Sitz' : ' Sitze') + ' zu vergeben' : Math.abs(remaining) + (Math.abs(remaining) === 1 ? ' Sitz' : ' Sitze') + ' zu viel vergeben')"></span>
            </div>
        </div>

        @error('seatDistribution')
            <div class="mb-4 ml-9 text-sm text-red-600">{{ $message }}</div>
        @enderror

        {{-- Parteien-Zeilen --}}
        <div class="space-y-2">
            @foreach ($parties as $party)
                @php $seats = $seatDistribution[$party->id] ?? 0; @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">

                    {{-- Farb-Streifen --}}
                    <div class="w-1.5 h-10 rounded-full flex-shrink-0" style="background-color: {{ $party->color }}"></div>

                    {{-- Parteiname --}}
                    <div class="w-16 sm:w-24 flex-shrink-0">
                        <div class="font-bold text-sm" style="color: {{ $party->color }}">{{ $party->short_name }}</div>
                        <div class="text-xs text-slate-400 hidden sm:block truncate">{{ $party->name }}</div>
                    </div>

                    {{-- +/- Buttons --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button
                            type="button"
                            wire:click="decrementSeats({{ $party->id }})"
                            @if($this->deadlinePassed) disabled @endif
                            class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 font-bold text-lg leading-none
                                hover:bg-slate-100 hover:border-slate-400 active:scale-95 transition-all
                                disabled:opacity-30 disabled:cursor-not-allowed"
                            @disabled($seats === 0 || $this->deadlinePassed)
                        >−</button>

                        <span class="w-8 text-center font-bold text-slate-900 tabular-nums text-base">
                            {{ $seats }}
                        </span>

                        <button
                            type="button"
                            wire:click="incrementSeats({{ $party->id }})"
                            @if($this->deadlinePassed) disabled @endif
                            wire:loading.attr="disabled"
                            wire:target="incrementSeats({{ $party->id }}),decrementSeats({{ $party->id }})"
                            class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center text-slate-600 font-bold text-lg leading-none
                                hover:bg-slate-100 hover:border-slate-400 active:scale-95 transition-all
                                disabled:opacity-30 disabled:cursor-not-allowed"
                            x-bind:disabled="remaining <= 0"
                        >+</button>
                    </div>

                    {{-- Fortschrittsbalken --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div
                                    class="h-full rounded-full transition-all duration-200"
                                    style="width: {{ $seats > 0 ? round($seats / 24 * 100) : 0 }}%; background-color: {{ $party->color }}"
                                ></div>
                            </div>
                            <span class="text-xs text-slate-400 tabular-nums w-8 text-right flex-shrink-0">
                                {{ $seats > 0 ? round($seats / 24 * 100) . '%' : '' }}
                            </span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Gesamt-Visualisierung --}}
        @php $totalDistributed = array_sum($seatDistribution); @endphp
        @if ($totalDistributed > 0)
            <div class="mt-5 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wide">Sitzverteilung</p>
                <div class="flex h-5 rounded-full overflow-hidden gap-px">
                    @foreach ($parties as $party)
                        @php $s = $seatDistribution[$party->id] ?? 0; @endphp
                        @if ($s > 0)
                            <div
                                class="h-full transition-all duration-300 relative group"
                                style="flex: {{ $s }}; background-color: {{ $party->color }}"
                                title="{{ $party->short_name }}: {{ $s }} Sitze"
                            ></div>
                        @endif
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                    @foreach ($parties as $party)
                        @php $s = $seatDistribution[$party->id] ?? 0; @endphp
                        @if ($s > 0)
                            <span class="flex items-center gap-1 text-xs text-slate-600">
                                <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background-color: {{ $party->color }}"></span>
                                {{ $party->short_name }} {{ $s }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

    </div>{{-- Ende Alpine-Block --}}

    {{-- ============================================================ --}}
    {{--  Submit-Bereich                                             --}}
    {{-- ============================================================ --}}

    @if ($this->deadlinePassed)
        {{-- Deadline abgelaufen: bereits über dem Amber-Banner oben abgedeckt --}}
    @elseif (! Auth::check() && $existingForecastId)
        {{-- Gast hat bereits eine Prognose abgegeben – kann nicht aktualisieren --}}
        <div class="rounded-xl bg-blue-50 border border-blue-200 p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" />
            </svg>
            <div>
                <p class="font-semibold text-blue-800">Prognose gespeichert!</p>
                <p class="text-sm text-blue-700 mt-1">
                    Um deine Prognose bis zum <strong>07.03.2026</strong> noch anpassen zu können,
                    <a href="{{ route('register') }}" class="underline font-medium">registriere dich</a>
                    oder <a href="{{ route('login') }}" class="underline font-medium">melde dich an</a>.
                </p>
            </div>
        </div>
    @else
        {{-- Normaler Submit (Gast ohne bestehende Prognose ODER registrierter Nutzer vor Deadline) --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4">

            {{-- Checkliste --}}
            <ul class="text-sm space-y-1 text-slate-500">
                <li class="flex items-center gap-2">
                    <span class="{{ $pseudonym ? 'text-emerald-500' : 'text-slate-300' }}">
                        @if($pseudonym) ✓ @else ○ @endif
                    </span>
                    Pseudonym angegeben
                </li>
                <li class="flex items-center gap-2">
                    <span class="{{ count($selectedMayorCandidates) >= 1 ? 'text-emerald-500' : 'text-slate-300' }}">
                        @if(count($selectedMayorCandidates) >= 1) ✓ @else ○ @endif
                    </span>
                    Bürgermeisterkandidat(en) gewählt
                </li>
                <li class="flex items-center gap-2">
                    <span
                        :class="remaining === 0 ? 'text-emerald-500' : 'text-slate-300'"
                        x-text="remaining === 0 ? '✓' : '○'"
                    ></span>
                    Genau 24 Sitze vergeben
                </li>
            </ul>

            <button
                type="button"
                wire:click="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-70 cursor-wait"
                wire:target="submit"
                x-bind:disabled="remaining !== 0 || !$wire.pseudonym || $wire.selectedMayorCandidates.length < 1"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-700 text-white font-semibold rounded-xl shadow-sm
                    hover:bg-blue-800 active:scale-95 transition-all duration-150
                    disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-blue-700"
            >
                <span wire:loading.remove wire:target="submit">
                    @if ($existingForecastId) Prognose aktualisieren @else Prognose abgeben @endif
                </span>
                <span wire:loading wire:target="submit" class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Wird gespeichert…
                </span>
            </button>
        </div>
    @endif

</div>
