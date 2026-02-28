<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="chart-bar" :href="route('prognose')" :current="request()->routeIs('prognose')"
                    wire:navigate>
                    Wahlprognose
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        @if(auth()->user()?->is_admin)
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Admin')" class="grid">
                    <flux:sidebar.item icon="shield-check" :href="route('admin.forecasts')" :current="request()->routeIs('admin.forecasts')" wire:navigate>
                        Prognosen
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
        @endif

        <flux:spacer />

        <flux:sidebar.nav>

            <flux:sidebar.group :heading="__('Links zu den Garchinger Parteien')" class="grid">

                <flux:sidebar.item icon="folder-git-2" href="https://www.buerger-fuer-garching.de/"
                    target="_blank">
                    {{ __('Bürger für Garching (BfG)') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="folder-git-2" href="https://www.csu.de/verbaende/ov/garching/"
                    target="_blank">
                    {{ __('CSU') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="folder-git-2" href="https://www.fdp-garching.de/"
                    target="_blank">
                    {{ __('FDP') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="folder-git-2" href="https://www.unabhaengige-garchinger.de/"
                    target="_blank">
                    {{ __('Freie Wähler (Unabhängige)') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="folder-git-2" href="https://gruene-garching.de/"
                    target="_blank">
                    {{ __('Grüne') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="folder-git-2" href="https://www.spd-garching.de/"
                    target="_blank">
                    {{ __('SPD') }}
                </flux:sidebar.item>

            </flux:sidebar.group>

        </flux:sidebar.nav>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    {{-- <footer
        class="border-t border-zinc-200 dark:border-zinc-700 py-4 px-6 text-center text-xs text-zinc-400 dark:text-zinc-500">
        <span class="flex items-center justify-center gap-3">
            <a href="{{ route('privacy') }}"
                class="hover:text-zinc-600 dark:hover:text-zinc-300 underline underline-offset-2">Datenschutzerklärung</a>
            <span aria-hidden="true">&middot;</span>
            <a href="{{ route('impressum') }}"
                class="hover:text-zinc-600 dark:hover:text-zinc-300 underline underline-offset-2">Impressum</a>
        </span>
    </footer> --}}

    @fluxScripts
</body>

</html>
