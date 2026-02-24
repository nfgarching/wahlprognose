<!DOCTYPE html>
<html lang="de">
    <head>
        @include('partials.head', ['title' => 'Datenschutzerklärung – Wahlprognose Garching 2026'])
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
        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <div class="text-center py-6">
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Datenschutzerklärung</h1>
                <p class="mt-2 text-slate-500">Wahlprognose Garching 2026 &mdash; Inoffizielle Bürgerschätzung</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6 text-sm text-slate-700 leading-relaxed">

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">1. Verantwortliche Stelle</h2>
                    <p>
                        Verantwortlich für die Datenverarbeitung auf dieser Website ist die
                        <strong>Natürlichen Freunde Garching e.&thinsp;V. (NfG)</strong>.
                        Bei Fragen zum Datenschutz wende dich bitte per E-Mail an:
                        <a href="mailto:info@nfgarching.de" class="text-blue-700 hover:underline">info@nfgarching.de</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">2. Welche Daten werden erhoben?</h2>
                    <p class="mb-3">Bei der Nutzung dieser Website werden folgende Daten verarbeitet:</p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2">
                        <li><strong>Pseudonym:</strong> Der von dir frei gewählte Anzeigename.</li>
                        <li><strong>IP-Adresse:</strong> Wird zur Missbrauchsprävention (Verhinderung von Mehrfachabgaben) temporär gespeichert.</li>
                        <li><strong>Prognose-Daten:</strong> Deine gewählten Bürgermeisterkandidaten und die eingegebene Sitzverteilung.</li>
                        <li><strong>Zugangsdaten (nur registrierte Nutzer):</strong> Name und E-Mail-Adresse, die du bei der Registrierung angibst.</li>
                        <li><strong>Serverlogs:</strong> Technische Zugriffsdaten (IP-Adresse, Browser, Zeitstempel) zur Betriebssicherheit.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">3. Zweck der Datenverarbeitung</h2>
                    <p class="mb-2">Die erhobenen Daten werden ausschließlich für folgende Zwecke genutzt:</p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2">
                        <li>Darstellung und Auswertung der inoffiziellen Bürgerprognosen zur Kommunalwahl Garching 2026.</li>
                        <li>Ermöglichung der nachträglichen Bearbeitung deiner Prognose (nur für registrierte Nutzer).</li>
                        <li>Missbrauchsprävention (Verhinderung von Mehrfachabgaben über die IP-Adresse).</li>
                        <li>Betrieb und Sicherstellung der technischen Verfügbarkeit der Website.</li>
                    </ul>
                    <p class="mt-3">
                        <strong>Nur registrierte und eingeloggte Benutzer</strong> können die Ergebnisse und
                        Prognosen anderer Teilnehmer einsehen.
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">4. Rechtsgrundlage</h2>
                    <p>
                        Die Verarbeitung deiner Daten erfolgt auf Basis deiner
                        <strong>Einwilligung</strong> (Art.&thinsp;6 Abs.&thinsp;1 lit.&thinsp;a DSGVO),
                        die du durch das Absenden des Formulars erteilst. Die Verarbeitung der IP-Adresse
                        zur Missbrauchsprävention stützt sich auf unser berechtigtes Interesse
                        (Art.&thinsp;6 Abs.&thinsp;1 lit.&thinsp;f DSGVO).
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">5. Speicherdauer</h2>
                    <p>
                        Deine Prognose-Daten werden spätestens <strong>sechs Monate nach der Kommunalwahl</strong>
                        (15.&thinsp;März 2026) vollständig gelöscht. IP-Adressen werden nach spätestens
                        <strong>30 Tagen</strong> aus den Logs entfernt. Sofern du dein Benutzerkonto löschst,
                        werden alle damit verknüpften Daten unverzüglich entfernt.
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">6. Weitergabe an Dritte</h2>
                    <p>
                        Deine Daten werden <strong>nicht an Dritte weitergegeben</strong>. Die Website wird auf
                        einem Webserver in Deutschland betrieben. Es werden keine externen Tracking-Dienste,
                        Werbedienste oder Social-Media-Plugins eingesetzt.
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">7. Cookies</h2>
                    <p>
                        Diese Website verwendet technisch notwendige Cookies für die Session-Verwaltung
                        (Anmeldung) und zur Sicherstellung der Funktionalität (CSRF-Schutz). Es werden
                        keine Tracking- oder Werbe-Cookies eingesetzt.
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">8. Deine Rechte (DSGVO)</h2>
                    <p class="mb-2">Du hast nach der DSGVO folgende Rechte:</p>
                    <ul class="list-disc list-inside space-y-1.5 pl-2">
                        <li><strong>Auskunft</strong> (Art.&thinsp;15 DSGVO): Du kannst Auskunft über deine gespeicherten Daten verlangen.</li>
                        <li><strong>Berichtigung</strong> (Art.&thinsp;16 DSGVO): Du kannst die Korrektur unrichtiger Daten verlangen.</li>
                        <li><strong>Löschung</strong> (Art.&thinsp;17 DSGVO): Du kannst die Löschung deiner Daten verlangen.</li>
                        <li><strong>Einschränkung</strong> (Art.&thinsp;18 DSGVO): Du kannst die eingeschränkte Verarbeitung verlangen.</li>
                        <li><strong>Widerspruch</strong> (Art.&thinsp;21 DSGVO): Du kannst der Verarbeitung widersprechen.</li>
                        <li><strong>Widerruf der Einwilligung</strong> (Art.&thinsp;7 Abs.&thinsp;3 DSGVO): Du kannst deine Einwilligung jederzeit widerrufen.</li>
                    </ul>
                    <p class="mt-3">
                        Zur Ausübung deiner Rechte wende dich an:
                        <a href="mailto:info@nfgarching.de" class="text-blue-700 hover:underline">info@nfgarching.de</a>
                    </p>
                </section>

                <section>
                    <h2 class="text-base font-semibold text-slate-900 mb-2">9. Beschwerderecht</h2>
                    <p>
                        Du hast das Recht, dich bei einer Datenschutz-Aufsichtsbehörde zu beschweren.
                        Zuständig ist das
                        <a href="https://www.lda.bayern.de" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:underline">
                            Bayerische Landesamt für Datenschutzaufsicht (BayLDA)
                        </a>.
                    </p>
                </section>

            </div>

            <div class="text-center pb-4">
                <a href="{{ route('prognose') }}" class="text-sm text-blue-700 hover:underline">← Zurück zur Prognose</a>
            </div>

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
