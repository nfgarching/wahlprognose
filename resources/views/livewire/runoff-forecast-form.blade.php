<div class="space-y-8">

    {{-- Hero --}}
    <div class="text-center py-6">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Stichwahl-Prognose</h1>
        <p class="mt-2 text-slate-500">Bürgermeister-Stichwahl &mdash; 22. März 2026 &mdash; Wer macht das Rennen in Garching?</p>
    </div>

    {{-- Info-Banner --}}
    <div class="rounded-xl bg-blue-50 border border-blue-200 p-5">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <div>
                <p class="font-semibold text-blue-800">Stichwahl am 22. März 2026</p>
                <p class="text-sm text-blue-700 mt-1">
                    Da keiner der Kandidaten im ersten Wahlgang die absolute Mehrheit erreichte, findet am 22. März 2026 eine Stichwahl statt.
                    Wer wird Bürgermeister von Garching bei München?
                </p>
            </div>
        </div>
    </div>

    {{-- Erfolgsmeldung --}}
    @if ($saved)
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div>
                <p class="font-semibold text-emerald-800">Deine Stichwahl-Prognose wurde gespeichert!</p>
                @auth
                    <p class="text-sm text-emerald-700 mt-0.5">Du kannst sie bis zum 21.03.2026 noch ändern.</p>
                @else
                    <p class="text-sm text-emerald-700 mt-0.5">
                        <a href="{{ route('register') }}" class="underline font-medium">Registriere dich</a>, um deine Prognose noch anpassen zu können.
                    </p>
                @endauth
            </div>
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
                class="w-full rounded-lg border border-slate-300 bg-white text-slate-900 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition
                    @error('pseudonym') border-red-400 focus:border-red-400 focus:ring-red-100 @enderror"
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
    {{--  SCHRITT 2: Kandidaten-Auswahl                              --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-1 flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">2</span>
            Wer gewinnt die Stichwahl?
        </h2>
        <p class="text-sm text-slate-500 mb-5 ml-9">Wähle den Kandidaten, dem du den Sieg zutraust.</p>

        @error('predictedWinner')
            <div class="mb-4 ml-9 text-sm text-red-600">{{ $message }}</div>
        @enderror

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Gruchmann --}}
            <button
                type="button"
                wire:click="$set('predictedWinner', 'gruchmann')"
                class="relative flex flex-col items-center gap-3 p-6 rounded-xl border-2 text-center transition-all duration-150 cursor-pointer
                    {{ $predictedWinner === 'gruchmann'
                        ? 'border-red-600 shadow-md ring-2 ring-red-200 bg-red-50'
                        : 'border-slate-200 hover:border-slate-300 hover:shadow-sm bg-white' }}"
            >
                @if ($predictedWinner === 'gruchmann')
                    <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-red-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                @endif

                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center border-2 border-red-200 flex-shrink-0">
                    <span class="text-3xl font-bold text-red-700">G</span>
                </div>

                <div>
                    <div class="font-semibold text-slate-900 text-base">Dr. Dietmar Gruchmann</div>
                    <span class="inline-block mt-1 text-xs px-2.5 py-0.5 rounded-full text-white font-semibold bg-red-600">SPD</span>
                </div>
            </button>

            {{-- Lemke --}}
            <button
                type="button"
                wire:click="$set('predictedWinner', 'lemke')"
                class="relative flex flex-col items-center gap-3 p-6 rounded-xl border-2 text-center transition-all duration-150 cursor-pointer
                    {{ $predictedWinner === 'lemke'
                        ? 'border-sky-700 shadow-md ring-2 ring-sky-200 bg-sky-50'
                        : 'border-slate-200 hover:border-slate-300 hover:shadow-sm bg-white' }}"
            >
                @if ($predictedWinner === 'lemke')
                    <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-sky-700 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                @endif

                <div class="w-20 h-20 rounded-full bg-sky-100 flex items-center justify-center border-2 border-sky-200 flex-shrink-0">
                    <span class="text-3xl font-bold text-sky-700">L</span>
                </div>

                <div>
                    <div class="font-semibold text-slate-900 text-base">Thomas Lemke</div>
                    <span class="inline-block mt-1 text-xs px-2.5 py-0.5 rounded-full text-white font-semibold bg-sky-700">CSU</span>
                </div>
            </button>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{--  SCHRITT 3: Optionale Prozentangabe                         --}}
    {{-- ============================================================ --}}
    @if (true)
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6"
            x-data
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <h2 class="text-lg font-semibold text-slate-800 mb-1 flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">3</span>
                Wie hoch wird der Stimmenanteil? <span class="text-slate-400 font-normal text-sm">(optional)</span>
            </h2>
            <p class="text-sm text-slate-500 mb-5 ml-9">
                Wie viel Prozent erhält
                <strong>{{ $predictedWinner === 'gruchmann' ? 'Dr. Gruchmann' : 'Thomas Lemke' }}</strong>
                deiner Meinung nach?
            </p>

            <div class="ml-9 flex items-center gap-4 flex-wrap">
                @php $winner = $predictedWinner === 'gruchmann' ? 'Dr. Gruchmann (SPD)' : 'Thomas Lemke (CSU)'; @endphp
                @php $loser = $predictedWinner === 'gruchmann' ? 'Thomas Lemke (CSU)' : 'Dr. Gruchmann (SPD)'; @endphp
                @php $winnerColor = $predictedWinner === 'gruchmann' ? 'text-red-700' : 'text-sky-700'; @endphp

                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-slate-700">
                        <span class="{{ $winnerColor }} font-semibold">{{ $winner }}</span>
                    </label>
                    <input
                        type="number"
                        min="51"
                        max="100"
                        wire:model.live="gruchmannPercent"
                        placeholder="—"
                        class="w-20 text-center font-bold text-slate-900 tabular-nums text-base rounded-lg border border-slate-200 py-1 px-2
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition
                            [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    >
                    <span class="text-slate-500 font-medium">%</span>
                </div>

                @if ($gruchmannPercent !== null)
                    <div class="text-sm text-slate-500">
                        → <span class="font-medium">{{ $loser }}</span>:
                        <span class="font-bold text-slate-700">
                            {{ $predictedWinner === 'gruchmann'
                                ? (100 - $gruchmannPercent)
                                : $gruchmannPercent
                            }}%
                        </span>
                    </div>
                @endif
            </div>

            @error('gruchmannPercent')
                <p class="mt-2 ml-9 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    {{-- ============================================================ --}}
    {{--  Submit-Bereich                                             --}}
    {{-- ============================================================ --}}
    @if (! Auth::check() && $existingForecastId)
        <div class="rounded-xl bg-blue-50 border border-blue-200 p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" />
            </svg>
            <div>
                <p class="font-semibold text-blue-800">Prognose gespeichert!</p>
                <p class="text-sm text-blue-700 mt-1">
                    Um deine Prognose noch anpassen zu können,
                    <a href="{{ route('register') }}" class="underline font-medium">registriere dich</a>
                    oder <a href="{{ route('login') }}" class="underline font-medium">melde dich an</a>.
                </p>
            </div>
        </div>
    @else
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4">

            <ul class="text-sm space-y-1 text-slate-500">
                <li class="flex items-center gap-2">
                    <span class="{{ $pseudonym ? 'text-emerald-500' : 'text-slate-300' }}">
                        @if($pseudonym) ✓ @else ○ @endif
                    </span>
                    Pseudonym angegeben
                </li>
                <li class="flex items-center gap-2">
                    <span class="{{ $predictedWinner ? 'text-emerald-500' : 'text-slate-300' }}">
                        @if($predictedWinner) ✓ @else ○ @endif
                    </span>
                    Kandidat ausgewählt
                </li>
            </ul>

            <button
                type="button"
                wire:click="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-70 cursor-wait"
                wire:target="submit"
                @disabled(! $pseudonym || ! $predictedWinner)
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

    {{-- ============================================================ --}}
    {{--  Auswertung der abgegebenen Prognosen                       --}}
    {{-- ============================================================ --}}
    @if ($this->statistics['total'] > 1000)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-5">
            <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                Auswertung der Prognosen
                <span class="ml-auto text-sm font-normal text-slate-400">{{ $this->statistics['total'] }} {{ $this->statistics['total'] === 1 ? 'Stimme' : 'Stimmen' }}</span>
            </h2>

            {{-- Balken-Visualisierung --}}
            <div class="space-y-3">

                {{-- Gruchmann --}}
                <div class="flex items-center gap-3">
                    <div class="w-36 sm:w-48 flex-shrink-0">
                        <div class="font-medium text-sm text-slate-800">Dr. Gruchmann</div>
                        <div class="text-xs text-red-600 font-semibold">SPD</div>
                    </div>
                    <div class="flex-1 flex items-center gap-3">
                        <div class="flex-1 h-6 bg-slate-100 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-red-600 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                                style="width: {{ $this->statistics['gruchmann_pct'] }}%"
                            >
                                @if ($this->statistics['gruchmann_pct'] >= 15)
                                    <span class="text-xs font-bold text-white">{{ $this->statistics['gruchmann_pct'] }}%</span>
                                @endif
                            </div>
                        </div>
                        @if ($this->statistics['gruchmann_pct'] < 15)
                            <span class="text-sm font-bold text-slate-700 w-12">{{ $this->statistics['gruchmann_pct'] }}%</span>
                        @else
                            <span class="w-12"></span>
                        @endif
                    </div>
                    <div class="text-sm text-slate-500 w-16 text-right flex-shrink-0">
                        {{ $this->statistics['gruchmann'] }} {{ $this->statistics['gruchmann'] === 1 ? 'Stimme' : 'Stimmen' }}
                    </div>
                </div>

                {{-- Lemke --}}
                <div class="flex items-center gap-3">
                    <div class="w-36 sm:w-48 flex-shrink-0">
                        <div class="font-medium text-sm text-slate-800">Thomas Lemke</div>
                        <div class="text-xs text-sky-700 font-semibold">CSU</div>
                    </div>
                    <div class="flex-1 flex items-center gap-3">
                        <div class="flex-1 h-6 bg-slate-100 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-sky-700 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                                style="width: {{ $this->statistics['lemke_pct'] }}%"
                            >
                                @if ($this->statistics['lemke_pct'] >= 15)
                                    <span class="text-xs font-bold text-white">{{ $this->statistics['lemke_pct'] }}%</span>
                                @endif
                            </div>
                        </div>
                        @if ($this->statistics['lemke_pct'] < 15)
                            <span class="text-sm font-bold text-slate-700 w-12">{{ $this->statistics['lemke_pct'] }}%</span>
                        @else
                            <span class="w-12"></span>
                        @endif
                    </div>
                    <div class="text-sm text-slate-500 w-16 text-right flex-shrink-0">
                        {{ $this->statistics['lemke'] }} {{ $this->statistics['lemke'] === 1 ? 'Stimme' : 'Stimmen' }}
                    </div>
                </div>
            </div>

            {{-- Durchschnittliche Stimmenanteil-Prognose --}}
            @if ($this->statistics['avg_gruchmann_percent'] !== null)
                @php
                    $avgGruchmann = $this->statistics['avg_gruchmann_percent'];
                    $avgLemke = round(100 - $avgGruchmann, 1);
                @endphp
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Ø Stimmenanteil-Prognose</p>
                    <div class="flex h-8 rounded-xl overflow-hidden shadow-inner">
                        <div
                            class="flex items-center justify-center text-xs font-bold text-white transition-all duration-500"
                            style="flex: {{ $avgGruchmann }}; background-color: #dc2626"
                        >
                            @if ($avgGruchmann >= 20) {{ $avgGruchmann }}% @endif
                        </div>
                        <div
                            class="flex items-center justify-center text-xs font-bold text-white transition-all duration-500"
                            style="flex: {{ $avgLemke }}; background-color: #0369a1"
                        >
                            @if ($avgLemke >= 20) {{ $avgLemke }}% @endif
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 mt-1.5">
                        <span><span class="font-semibold text-red-600">Dr. Gruchmann</span> {{ $avgGruchmann }}%</span>
                        <span>{{ $avgLemke }}% <span class="font-semibold text-sky-700">Thomas Lemke</span></span>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Datenschutz-Hinweis --}}
    <p class="text-center text-xs text-slate-400 pb-2">
        Mit dem Absenden stimmst du unserer
        <a href="{{ route('privacy') }}" target="_blank" class="underline hover:text-slate-600">Datenschutzerklärung</a>
        zu. Deine IP-Adresse wird zur Missbrauchsprävention gespeichert.
    </p>

    {{-- ============================================================ --}}
    {{--  Success-Modal                                              --}}
    {{-- ============================================================ --}}
    <div
        x-show="$wire.saved"
        x-on:keydown.escape.window.prevent="$event.stopImmediatePropagation()"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        style="display: none"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    >
        <div
            class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-8 text-center space-y-6"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900">Danke für deine Prognose!</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Deine Stichwahl-Prognose wurde erfolgreich gespeichert.<br>
                    Wir sind gespannt auf das Ergebnis der Stichwahl am 22. März 2026!
                </p>
            </div>

            {{-- Auswertung im Modal --}}
            @if ($this->statistics['total'] > 10)
                <div class="text-left bg-slate-50 rounded-xl p-4 space-y-4">
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        So tippen die anderen
                        <span class="ml-auto font-normal text-slate-400">{{ $this->statistics['total'] }} {{ $this->statistics['total'] === 1 ? 'Stimme' : 'Stimmen' }}</span>
                    </h3>

                    {{-- Gruchmann-Balken --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-28 flex-shrink-0">
                                <span class="text-xs font-semibold text-red-600">Dr. Gruchmann <span class="font-normal text-slate-400">SPD</span></span>
                                <div class="text-xs text-slate-500 tabular-nums">{{ $this->statistics['gruchmann'] }}&thinsp;{{ $this->statistics['gruchmann'] === 1 ? 'Stimme' : 'Stimmen' }}</div>
                            </div>
                            <div class="flex-1 h-5 bg-white rounded-full overflow-hidden border border-slate-200">
                                <div class="h-full bg-red-600 rounded-full flex items-center justify-end pr-1.5 transition-all duration-500"
                                     style="width: {{ $this->statistics['gruchmann_pct'] }}%">
                                    @if ($this->statistics['gruchmann_pct'] >= 20)
                                        <span class="text-xs font-bold text-white tabular-nums">{{ $this->statistics['gruchmann'] }}&thinsp;×</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 w-8 text-right flex-shrink-0 tabular-nums">
                                @if ($this->statistics['gruchmann_pct'] < 20) {{ $this->statistics['gruchmann'] }}&thinsp;× @endif
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-28 flex-shrink-0">
                                <span class="text-xs font-semibold text-sky-700">Thomas Lemke <span class="font-normal text-slate-400">CSU</span></span>
                                <div class="text-xs text-slate-500 tabular-nums">{{ $this->statistics['lemke'] }}&thinsp;{{ $this->statistics['lemke'] === 1 ? 'Stimme' : 'Stimmen' }}</div>
                            </div>
                            <div class="flex-1 h-5 bg-white rounded-full overflow-hidden border border-slate-200">
                                <div class="h-full bg-sky-700 rounded-full flex items-center justify-end pr-1.5 transition-all duration-500"
                                     style="width: {{ $this->statistics['lemke_pct'] }}%">
                                    @if ($this->statistics['lemke_pct'] >= 20)
                                        <span class="text-xs font-bold text-white tabular-nums">{{ $this->statistics['lemke'] }}&thinsp;×</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 w-8 text-right flex-shrink-0 tabular-nums">
                                @if ($this->statistics['lemke_pct'] < 20) {{ $this->statistics['lemke'] }}&thinsp;× @endif
                            </span>
                        </div>
                    </div>

                    {{-- Ø Stimmenanteil --}}
                    @if ($this->statistics['avg_gruchmann_percent'] !== null)
                        @php
                            $avgG = $this->statistics['avg_gruchmann_percent'];
                            $avgL = round(100 - $avgG, 1);
                        @endphp
                        <div class="pt-3 border-t border-slate-200">
                            <p class="text-xs text-slate-400 uppercase tracking-wide font-medium mb-2">Ø Stimmenanteil-Prognose</p>
                            <div class="flex h-6 rounded-lg overflow-hidden">
                                <div class="flex items-center justify-center text-xs font-bold text-white"
                                     style="flex: {{ $avgG }}; background-color: #dc2626">
                                    @if ($avgG >= 20) {{ $avgG }}% @endif
                                </div>
                                <div class="flex items-center justify-center text-xs font-bold text-white"
                                     style="flex: {{ $avgL }}; background-color: #0369a1">
                                    @if ($avgL >= 20) {{ $avgL }}% @endif
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-500 mt-1">
                                <span class="text-red-600 font-semibold">{{ $avgG }}%</span>
                                <span class="text-sky-700 font-semibold">{{ $avgL }}%</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="space-y-3">
                <a href="{{ route('home') }}"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-700 text-white font-semibold rounded-xl hover:bg-blue-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Zur Startseite
                </a>
                <a href="https://www.buerger-fuer-garching.de"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    Zur Homepage der BfG
                </a>
            </div>

            <div>
                <p class="text-xs text-slate-400 mb-3 uppercase tracking-wide font-medium">Folgt uns bei den sozialen Medien</p>
                <div class="flex justify-center gap-3">
                    <a href="https://www.instagram.com/buerger4garching"
                       target="_blank" rel="noopener noreferrer" title="Instagram"
                       class="w-10 h-10 rounded-full bg-slate-100 hover:bg-pink-100 text-slate-500 hover:text-pink-600 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/BuergerFuerGarching"
                       target="_blank" rel="noopener noreferrer" title="Facebook"
                       class="w-10 h-10 rounded-full bg-slate-100 hover:bg-blue-100 text-slate-500 hover:text-blue-600 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
