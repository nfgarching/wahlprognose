<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 flex-wrap">

        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Prognose-Ergebnisse</h1>
            <p class="text-sm text-slate-500 mt-0.5">Inoffizielle Bürgerschätzung zur Kommunalwahl Garching 2026</p>
        </div>

    </div>

    @if ($this->forecastCount === 0)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <p class="text-slate-500 text-sm">Noch keine Prognosen abgegeben.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-900">Gesamtübersicht aller Prognosen</h2>
                    <p class="text-xs text-slate-400">{{ $this->forecastCount }}
                        {{ $this->forecastCount === 1 ? 'Prognose' : 'Prognosen' }} abgegeben</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100">

                {{-- Bürgermeister-Übersicht --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">
                        Bürgermeisterwahl &mdash; Kandidatenbeliebtheit
                    </h3>
                    <div class="space-y-3">
                        @foreach ($this->mayorSummary as $c)
                            @if ($c->selections > 0)
                                <div class="flex items-center gap-3">
                                    <div class="w-32 sm:w-44 flex-shrink-0 flex items-center gap-2 min-w-0">
                                        <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0 border"
                                            style="border-color: {{ $c->party->color }}">
                                            @if ($c->photo_path)
                                                <img src="{{ Storage::url($c->photo_path) }}" alt="{{ $c->name }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs font-bold"
                                                    style="background-color: {{ $c->party->color }}18; color: {{ $c->party->color }}">
                                                    {{ mb_substr($c->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-semibold text-slate-700 truncate">
                                                {{ $c->name }}</div>
                                            <div class="text-xs" style="color: {{ $c->party->color }}">
                                                {{ $c->party->short_name }}</div>
                                        </div>
                                    </div>
                                    <div class="flex-1 h-5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-300"
                                            style="width: {{ round(($c->selections / $this->forecastCount) * 100) }}%; background-color: {{ $c->party->color }}">
                                        </div>
                                    </div>
                                    <div class="w-20 flex-shrink-0 text-right">
                                        <span class="text-sm font-bold tabular-nums"
                                            style="color: {{ $c->party->color }}">
                                            {{ $c->selections }}×
                                        </span>
                                        <span class="text-xs text-slate-400 ml-1">
                                            ({{ round(($c->selections / $this->forecastCount) * 100) }}%)
                                        </span>
                                    </div>
                                </div>
                                @if ($c->runoff_wins > 0)
                                    <div class="ml-32 sm:ml-44 pl-3 -mt-1 mb-1">
                                        <span class="text-xs text-amber-600">
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
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                        Stadtrat &mdash; Durchschnittliche Sitzverteilung
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">Mittelwert über alle {{ $this->forecastCount }} Prognosen
                    </p>

                    @php $maxAvg = $this->seatSummary->max('forecast_seats_avg_seats'); @endphp
                    <div class="space-y-2.5">
                        @foreach ($this->seatSummary as $party)
                            @php $avg = round($party->forecast_seats_avg_seats ?? 0, 1); @endphp
                            @if ($avg > 0)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 flex-shrink-0 text-right">
                                        <span class="text-xs font-bold"
                                            style="color: {{ $party->color }}">{{ $party->short_name }}</span>
                                    </div>
                                    <div class="flex-1 h-5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full"
                                            style="width: {{ $maxAvg > 0 ? round(($avg / $maxAvg) * 100) : 0 }}%; background-color: {{ $party->color }}">
                                        </div>
                                    </div>
                                    <div class="w-28 flex-shrink-0 text-xs text-slate-500 tabular-nums">
                                        Ø {{ $avg }} Sitze
                                        <span class="text-slate-300">({{ round(($avg / 24) * 100) }}%)</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wide">Visualisierung
                            Gesamtbild</p>
                        <div class="flex h-5 rounded-full overflow-hidden gap-px">
                            @foreach ($this->seatSummary as $party)
                                @php $avg = $party->forecast_seats_avg_seats ?? 0; @endphp
                                @if ($avg > 0)
                                    <div class="h-full"
                                        style="flex: {{ $avg }}; background-color: {{ $party->color }}"
                                        title="{{ $party->short_name }}: Ø {{ round($avg, 1) }} Sitze"></div>
                                @endif
                            @endforeach
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                            @foreach ($this->seatSummary as $party)
                                @php $avg = round($party->forecast_seats_avg_seats ?? 0, 1); @endphp
                                @if ($avg > 0)
                                    <span class="flex items-center gap-1 text-xs text-slate-600">
                                        <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0"
                                            style="background-color: {{ $party->color }}"></span>
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

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col gap-6">

        {{-- Links --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @if (Auth::check())
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-700 text-white font-semibold rounded-xl hover:bg-blue-800 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Zum Dashboard
                </a>
            @elseif ($this->deadlinePassed)
                <a href="https://wahlen-garching.de/" target="_blank" rel="noopener noreferrer"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-700 text-white font-semibold rounded-xl hover:bg-blue-800 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    Zu den Wahlergebnissen
                </a>
            @else
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-700 text-white font-semibold rounded-xl hover:bg-blue-800 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Zur Startseite
                </a>
            @endif
            <a href="https://www.buerger-fuer-garching.de" target="_blank" rel="noopener noreferrer"
                class="flex items-center justify-center gap-2 px-4 py-3 border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                Zur Homepage der BfG
            </a>
            <a href="https://www.buerger-fuer-garching.de/uns-treffen/" target="_blank" rel="noopener noreferrer"
                class="flex items-center justify-center gap-2 px-4 py-3 border border-emerald-300 text-emerald-700 font-semibold rounded-xl hover:bg-emerald-50 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                Beim Newsletter eintragen
            </a>
        </div>

        {{-- Social Media --}}
        <div class="border-t border-slate-100 pt-5">
            <p class="text-xs text-slate-400 mb-3 uppercase tracking-wide font-medium text-center">Folgt uns bei den sozialen Medien</p>
            <div class="flex justify-center gap-3">
                <a href="https://www.instagram.com/buerger4garching" target="_blank" rel="noopener noreferrer"
                    title="Instagram"
                    class="w-10 h-10 rounded-full bg-slate-100 hover:bg-pink-100 text-slate-500 hover:text-pink-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                </a>
                <a href="https://www.facebook.com/BuergerFuerGarching" target="_blank" rel="noopener noreferrer"
                    title="Facebook"
                    class="w-10 h-10 rounded-full bg-slate-100 hover:bg-blue-100 text-slate-500 hover:text-blue-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="https://twitter.com/garching_news" target="_blank" rel="noopener noreferrer"
                    title="X (Twitter)"
                    class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-900 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                <a href="https://www.youtube.com/@buergerfuergarching" target="_blank" rel="noopener noreferrer"
                    title="YouTube"
                    class="w-10 h-10 rounded-full bg-slate-100 hover:bg-red-100 text-slate-500 hover:text-red-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>
                </a>
            </div>
        </div>

    </div>

</div>
