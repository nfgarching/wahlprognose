<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kommunalwahl Garching 2026 | Bürger für Garching</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .lang-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-sans">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <img src="https://ml6ymizwmc75.i.optimole.com/w:150/h:89/q:mauto/f:best/https://www.buerger-fuer-garching.de/wp-content/uploads/2025/08/Logo-small.png"
                    alt="Bürger für Garching Logo" class="h-16 w-auto">
            </div>

            @auth
                <a href="/prognose"
                    class="bg-[#005293] hover:bg-blue-800 text-white px-5 py-2 rounded font-bold transition shadow-sm uppercase text-sm tracking-wider">
                    Wahlprognose abgeben
                </a>
            @else
                <button type="button" data-guest-modal-trigger
                    class="inline-block bg-white text-blue-900 px-10 py-4 rounded font-black text-xl hover:bg-yellow-400 hover:scale-105 transition-all duration-300 cursor-pointer">
                    Wahlprognose abgeben
                </button>
            @endauth


        </div>
    </nav>

    <header class="relative bg-[#005293] py-8 text-white overflow-hidden border-b-4 border-yellow-400">
        <div class="absolute inset-0 opacity-10 bg-slate-900"></div>
        <div class="relative max-w-5xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tight">Garching wählt die Zukunft.</h1>
            <p class="text-xl md:text-2xl mb-8 font-light italic">Kommunalwahl am 8. März 2026 – Jede Stimme zählt!</p>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-blue-900 mb-4 underline decoration-yellow-400 underline-offset-8">
                Wahlaufruf / Call to Vote</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">Demokratie lebt vom Mitmachen. Wir rufen alle Bürgerinnen und
                Bürger Garchings (auch EU-Bürger) dazu auf, ihr Wahlrecht zu nutzen.</p>
        </div>

        <div class="lang-grid">
            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Deutsch</span>
                <p class="mt-3 font-semibold text-lg leading-tight">"Gestalten Sie die Zukunft unserer Stadt aktiv mit –
                    gehen Sie wählen!"</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">English</span>
                <p class="mt-3 italic text-slate-700">"Actively help shape the future of our city – go vote!"</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Français</span>
                <p class="mt-3 italic text-slate-700">"Participez activement à l'avenir de notre ville – allez voter !"
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Italiano</span>
                <p class="mt-3 italic text-slate-700">"Partecipa attivamente al futuro della nostra città – vai a
                    votare!"</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Español</span>
                <p class="mt-3 italic text-slate-700">"Participa activamente en el futuro de nuestra ciudad – ¡vota!"
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Polski</span>
                <p class="mt-3 italic text-slate-700">"Aktywnie współkształtuj przyszłość naszego miasta – idź na
                    wybory!"</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Ελληνικά</span>
                <p class="mt-3 italic text-slate-700">"Διαμορφώστε ενεργά το μέλλον της πόλης μας – πηγαίνετε να
                    ψηφίσετε!"</p>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm hover:shadow-md transition">
                <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Română</span>
                <p class="mt-3 italic text-slate-700">"Contribuiți activ la viitorul orașului nostru – mergeți la vot!"
                </p>
            </div>
        </div>
    </section>

    <section class="bg-slate-900 py-16 text-white border-y-8 border-yellow-400">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold mb-6 italic text-yellow-400">Wie wählt Garching?</h3>
            <p class="text-lg mb-10 text-slate-300 uppercase tracking-widest">Geben Sie jetzt Ihre persönliche Prognose
                für die Stadtratswahl ab.</p>
            @auth
                <a href="{{ route('prognose') }}"
                    class="inline-block bg-white text-blue-900 px-10 py-4 rounded font-black text-xl hover:bg-yellow-400 hover:scale-105 transition-all duration-300">
                    JETZT ZUR WAHLPROGNOSE →
                </a>
            @else
                <button type="button" data-guest-modal-trigger
                    class="inline-block bg-white text-blue-900 px-10 py-4 rounded font-black text-xl hover:bg-yellow-400 hover:scale-105 transition-all duration-300 cursor-pointer">
                    JETZT ZUR WAHLPROGNOSE →
                </button>
            @endauth
        </div>
    </section>

    <footer class="bg-white py-12 border-t">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
            <img src="https://ml6ymizwmc75.i.optimole.com/w:150/h:89/q:mauto/f:best/https://www.buerger-fuer-garching.de/wp-content/uploads/2025/08/Logo-small.png"
                alt="Bürger für Garching" class="h-12 grayscale opacity-50">
            <div class="text-slate-400 text-sm">
                &copy; (c) 2026 – Überparteilicher Wahlaufruf für Garching b. München.
                &mdash; <a href="{{ route('privacy') }}" class="underline hover:text-slate-600">Datenschutzerklärung</a>
            </div>
        </div>
    </footer>

    @guest
        {{-- Gast-Modal (standardmäßig versteckt) --}}
        <div id="guest-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display:none; background: rgba(15,23,42,0.6); backdrop-filter: blur(2px);">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8" role="dialog" aria-modal="true"
                aria-labelledby="guest-modal-title">

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <h2 id="guest-modal-title" class="text-lg font-bold text-slate-900">Registriere dich, bevor du loslegst!
                    </h2>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed mb-2">
                    Registriere dich vorher, um Deine Prognose später bearbeiten zu können.
                </p>
                <p class="text-sm font-semibold text-slate-800 mb-6">
                    Nur registrierte Benutzer können die Prognosen einsehen.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}"
                        class="flex-1 text-center px-4 py-2.5 bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-blue-800 transition-colors">
                        Registrieren
                    </a>
                    <a href="{{ route('login') }}"
                        class="flex-1 text-center px-4 py-2.5 border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                        Anmelden
                    </a>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('prognose') }}" class="text-xs text-slate-400 hover:text-slate-600 hover:underline">
                        Fortfahren als Gastnutzer →
                    </a>
                </div>
            </div>
        </div>

        <script>
            (function () {
                var modal = document.getElementById('guest-modal');
                if (!modal) return;

                document.querySelectorAll('[data-guest-modal-trigger]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        modal.style.display = 'flex';
                    });
                });

                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            })();
        </script>
    @endguest

</body>

</html>
