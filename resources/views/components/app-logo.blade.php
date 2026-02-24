@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }}>
        <x-slot name="logo">
            <img src="https://ml6ymizwmc75.i.optimole.com/w:150/h:89/q:mauto/f:best/https://www.buerger-fuer-garching.de/wp-content/uploads/2025/08/Logo-small.png"
                 alt="Bürger für Garching"
                 class="h-8 w-auto">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }}>
        <x-slot name="logo">
            <img src="https://ml6ymizwmc75.i.optimole.com/w:150/h:89/q:mauto/f:best/https://www.buerger-fuer-garching.de/wp-content/uploads/2025/08/Logo-small.png"
                 alt="Bürger für Garching"
                 class="h-8 w-auto">
        </x-slot>
    </flux:brand>
@endif
