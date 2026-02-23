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
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-slate-900 hover:text-slate-700">
                        <div class="w-7 h-7 rounded bg-blue-700 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-sm tracking-tight">Wahlprognose Garching 2026</span>
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
            <div class="max-w-5xl mx-auto px-4 py-6 text-center text-xs text-slate-400">
                Kommunalwahl Garching bei München &mdash; 15. März 2026 &mdash; Inoffizielle Bürgerschätzung
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
