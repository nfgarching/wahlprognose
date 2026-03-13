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

            <a href="/stichwahl"
                class="bg-[#005293] hover:bg-blue-800 text-white px-5 py-2 rounded font-bold transition shadow-sm uppercase text-sm tracking-wider">
                Wahlprognose abgeben
            </a>

        </div>
    </nav>

    <header class="relative bg-[#005293] py-8 text-white overflow-hidden border-b-4 border-yellow-400">
        <div class="absolute inset-0 opacity-10 bg-slate-900"></div>
        <div class="relative max-w-5xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-black mb-6 uppercase tracking-tight">Garching wählt die Zukunft.</h1>
            <p class="text-xl md:text-2xl mb-8 font-light italic">Stichwahl am 22. März 2026 zwischen Dr. Dietmar
                Gruchmann (SPD) und Thomas Lemke (CSU) – Jede Stimme zählt!</p>

            <div id="countdown" class="flex justify-center gap-4 sm:gap-8 mt-2 mb-2">
                <div class="flex flex-col items-center">
                    <span id="cd-days" class="text-4xl sm:text-5xl font-black tabular-nums">--</span>
                    <span class="text-xs uppercase tracking-widest text-blue-200 mt-1">Tage</span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-blue-300 leading-none pt-0.5">:</div>
                <div class="flex flex-col items-center">
                    <span id="cd-hours" class="text-4xl sm:text-5xl font-black tabular-nums">--</span>
                    <span class="text-xs uppercase tracking-widest text-blue-200 mt-1">Stunden</span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-blue-300 leading-none pt-0.5">:</div>
                <div class="flex flex-col items-center">
                    <span id="cd-minutes" class="text-4xl sm:text-5xl font-black tabular-nums">--</span>
                    <span class="text-xs uppercase tracking-widest text-blue-200 mt-1">Minuten</span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-blue-300 leading-none pt-0.5">:</div>
                <div class="flex flex-col items-center">
                    <span id="cd-seconds" class="text-4xl sm:text-5xl font-black tabular-nums">--</span>
                    <span class="text-xs uppercase tracking-widest text-blue-200 mt-1">Sekunden</span>
                </div>
            </div>
            <p id="countdown-label" class="text-sm text-blue-200 mt-3">bis zum Ende der Prognose-Abgabe</p>
        </div>
    </header>

    <script>
        (function() {
            var deadline = new Date('{{ config('forecast.edit_deadline') }}').getTime();

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function tick() {
                var now = Date.now();
                var diff = deadline - now;

                if (diff <= 0) {
                    document.getElementById('countdown').style.display = 'none';
                    document.getElementById('countdown-label').textContent = 'Die Prognosephase ist abgelaufen.';
                    return;
                }

                var days = Math.floor(diff / 86400000);
                var hours = Math.floor((diff % 86400000) / 3600000);
                var minutes = Math.floor((diff % 3600000) / 60000);
                var seconds = Math.floor((diff % 60000) / 1000);

                document.getElementById('cd-days').textContent = pad(days);
                document.getElementById('cd-hours').textContent = pad(hours);
                document.getElementById('cd-minutes').textContent = pad(minutes);
                document.getElementById('cd-seconds').textContent = pad(seconds);

                setTimeout(tick, 1000 - (Date.now() % 1000));
            }

            tick();
        })();
    </script>

    <section class="max-w-5xl mx-auto px-4 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-blue-900 mb-4 underline decoration-yellow-400 underline-offset-8">
                Alles zur Wahl am 22. März</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">Infos zu Briefwahl und Wahllokal –
                <a href="https://www.garching.de/pressemitteilungen/kommunalwahl-2026---stichwahlen-am-22_-m%C3%A4rz"
                    target="_blank" rel="noopener"
                    class="text-blue-700 underline hover:text-blue-900">Offizielle Pressemitteilung der Stadt
                    Garching →</a>
            </p>
        </div>

        {{-- Wahllokal-Box --}}
        <div class="bg-[#005293] text-white rounded-xl p-6 mb-10 flex gap-5 items-start shadow-md">
            <div class="text-4xl flex-shrink-0">🗳️</div>
            <div>
                <h3 class="text-xl font-black mb-2 uppercase tracking-wide">Wählen im Wahllokal</h3>
                <p class="text-blue-100 text-sm mb-3">Die Wahllokale sind am <strong class="text-white">Sonntag, 22.
                        März von 8:00 bis 18:00 Uhr</strong> geöffnet. Sie nutzen dasselbe Wahllokal wie beim ersten
                    Wahlgang am 8. März.</p>
                <p class="text-blue-100 text-sm">Bitte bringen Sie Ihre <strong class="text-white">Wahlbenachrichtigung
                        und einen Lichtbildausweis</strong> mit. Ohne Benachrichtigung reicht
                    <strong class="text-white">Personalausweis oder Reisepass</strong> allein.
                </p>
            </div>
        </div>

        {{-- Schritt 1 --}}
        <div class="flex gap-6 mb-10">
            <div
                class="flex-shrink-0 w-12 h-12 rounded-full bg-[#005293] text-white flex items-center justify-center font-black text-xl shadow">
                1</div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Briefwahl: Erhalte ich die Unterlagen automatisch?
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-5">
                        <span class="text-xs font-black text-green-700 tracking-widest uppercase">✓ Ja,
                            automatisch</span>
                        <p class="mt-2 text-slate-700 text-sm">Wenn Sie beim ersten Wahlgang das Feld
                            <strong>„Unterlagen auch für eine mögliche Stichwahl"</strong> angekreuzt haben. Versand
                            erfolgt in <strong>KW 11</strong> (ab ca. 10. März).
                        </p>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
                        <span class="text-xs font-black text-amber-700 tracking-widest uppercase">⚠ Nein, neu
                            beantragen</span>
                        <p class="mt-2 text-slate-700 text-sm">Wenn Sie beim ersten Mal im Wahllokal waren oder das
                            Kreuz vergessen haben, müssen Sie die Unterlagen jetzt <strong>neu beantragen</strong>.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Schritt 2 --}}
        <div class="flex gap-6 mb-10">
            <div
                class="flex-shrink-0 w-12 h-12 rounded-full bg-[#005293] text-white flex items-center justify-center font-black text-xl shadow">
                2</div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-blue-900 mb-4">So beantragen Sie die Briefwahl neu</h3>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                        <div class="text-2xl mb-2">🌐</div>
                        <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Online</span>
                        <p class="mt-2 text-slate-700 text-sm"><a
                                href="https://www.buergerservice-portal.de/bayern/garching/bsp_ewo_briefwahl/#/"
                                target="_blank" rel="noopener"
                                class="text-blue-700 underline hover:text-blue-900 font-semibold">Direkt zum
                                Online-Antrag →</a> Möglich bis <strong>Mittwoch, 18. März, 12:00 Uhr</strong>. Den
                            QR-Code der alten Wahlbenachrichtigung <em>nicht</em> verwenden.</p>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                        <div class="text-2xl mb-2">🏛️</div>
                        <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Persönlich</span>
                        <p class="mt-2 text-slate-700 text-sm">Briefwahlbüro im Rathaus, <strong>Zimmer R 1.08</strong>
                            (Nebengebäude, Aufgang „Ratssaal"), Rathausplatz 1. Ab <strong>Do. 12. März,
                                14:00 Uhr</strong>. Ausweis erforderlich.</p>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                        <div class="text-2xl mb-2">✉️</div>
                        <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Post / E-Mail</span>
                        <p class="mt-2 text-slate-700 text-sm">Formlos per E-Mail an
                            <strong>wahl@garching.de</strong> (Name, Geburtsdatum, Anschrift) oder Rückseite der alten
                            Wahlbenachrichtigung nutzen.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Schritt 3: Fristen --}}
        <div class="flex gap-6 mb-10">
            <div
                class="flex-shrink-0 w-12 h-12 rounded-full bg-[#005293] text-white flex items-center justify-center font-black text-xl shadow">
                3</div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Wichtige Fristen</h3>
                <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <tbody>
                            <tr class="border-b border-slate-100">
                                <td class="px-5 py-3 text-slate-500 w-2/5">Versandbeginn</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">Ab ca. 10. März 2026 (KW 11)</td>
                            </tr>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <td class="px-5 py-3 text-slate-500">Letzte Online-Beantragung</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">Mittwoch, 18. März, <strong>12:00
                                        Uhr</strong></td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="px-5 py-3 text-slate-500">Ersatzunterlagen im Rathaus</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">
                                    Fr. 20. März: 8–12 &amp; 13–15 Uhr<br>
                                    Sa. 21. März: 10–12 Uhr
                                </td>
                            </tr>
                            <tr class="bg-red-50">
                                <td class="px-5 py-3 text-red-700 font-bold">Abgabe des Wahlbriefs</td>
                                <td class="px-5 py-3 font-bold text-red-700">Sonntag, 22. März, bis 18:00 Uhr</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Schritt 4: Rückgabe --}}
        <div class="flex gap-6 mb-4">
            <div
                class="flex-shrink-0 w-12 h-12 rounded-full bg-[#005293] text-white flex items-center justify-center font-black text-xl shadow">
                4</div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Den Wahlbrief zurücksenden</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                        <span class="text-xs font-black text-blue-600 tracking-widest uppercase">Per Post</span>
                        <p class="mt-2 text-slate-700 text-sm">Den Wahlbrief spätestens am <strong>Mittwoch, 18.
                                März</strong> aufgeben, damit er rechtzeitig eingeht.</p>
                    </div>
                    <div class="bg-[#005293] text-white rounded-lg p-5 shadow-sm">
                        <span class="text-xs font-black text-blue-200 tracking-widest uppercase">✓ Sicherste
                            Variante</span>
                        <p class="mt-2 text-sm">Brief direkt in den <strong>Hausbriefkasten des Rathauses</strong>
                            (Rathausplatz 1) einwerfen. Wird am Wahlsonntag um 18:00 Uhr geleert.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hinweis-Box --}}
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg p-5 text-sm text-slate-700">
            <strong class="text-yellow-700">Hinweis:</strong> Eine neue Wahlbenachrichtigung wird für die Stichwahl
            nicht verschickt. Nutzen Sie die alte Benachrichtigung weiter – oder wählen Sie mit
            <strong>Personalausweis oder Reisepass</strong> direkt im Wahllokal. Bei Fragen: Rathaus Garching,
            Tel. <strong>089/32089-0</strong>.
        </div>
    </section>

    <section class="bg-slate-900 py-16 text-white border-y-8 border-yellow-400">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold mb-6 italic text-yellow-400">Wie wählt Garching?</h3>
            <p class="text-lg mb-10 text-slate-300 uppercase tracking-widest">Geben Sie jetzt Ihre persönliche Prognose
                für die Stadtratswahl ab.</p>

            <a href="{{ route('stichwahl') }}"
                class="inline-block bg-white text-blue-900 px-10 py-4 rounded font-black text-xl hover:bg-yellow-400 hover:scale-105 transition-all duration-300">
                JETZT ZUR WAHLPROGNOSE →
            </a>

        </div>
    </section>

    <footer class="bg-white py-12 border-t">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
            <img src="https://ml6ymizwmc75.i.optimole.com/w:150/h:89/q:mauto/f:best/https://www.buerger-fuer-garching.de/wp-content/uploads/2025/08/Logo-small.png"
                alt="Bürger für Garching" class="h-12 grayscale opacity-50">
            <div class="text-slate-400 text-sm">
                &copy; (c) 2026 – Überparteilicher Wahlaufruf für Garching b. München.
                &mdash; <a href="{{ route('privacy') }}" class="underline hover:text-slate-600">Datenschutzerklärung</a>
                &mdash; <a href="{{ route('impressum') }}" class="underline hover:text-slate-600">Impressum</a>
            </div>
        </div>
    </footer>

</body>

</html>
