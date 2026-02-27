<!DOCTYPE html>
<html lang="de">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 antialiased">

        {{-- Navigation --}}
        <header class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity">
                        <img src="https://ml6ymizwmc75.i.optimole.com/w:150/h:89/q:mauto/f:best/https://www.buerger-fuer-garching.de/wp-content/uploads/2025/08/Logo-small.png"
                             alt="Bürger für Garching"
                             class="h-10 w-auto">
                    </a>

                    <nav class="flex items-center gap-4 text-sm">
                        @auth
                            <span class="text-slate-500 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-blue-700 hover:underline font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900">Anmelden</a>
                            <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1.5 rounded-md hover:bg-blue-800 font-medium">Registrieren</a>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="mt-16 border-t border-slate-200 bg-white">
            <div class="max-w-5xl mx-auto px-4 py-6 text-center text-xs text-slate-400 space-y-1">
                <p>Kommunalwahl Garching bei München &mdash; 08. März 2026 &mdash; Inoffizielle Bürgerschätzung</p>
                <p class="flex items-center justify-center gap-3">
                    <a href="{{ route('privacy') }}" class="hover:text-slate-600 underline underline-offset-2">Datenschutzerklärung</a>
                    <span aria-hidden="true">&middot;</span>
                    <a href="{{ route('impressum') }}" class="hover:text-slate-600 underline underline-offset-2">Impressum</a>
                </p>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
