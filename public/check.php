<?php
// SICHERHEITSHINWEIS: Sofort nach Nutzung löschen!

echo '<h2>Vendor-Dateien</h2><ul>';
$files = [
    'vendor/livewire/livewire/dist/livewire.js',
    'vendor/livewire/livewire/dist/livewire.esm.js',
    'vendor/livewire/flux/dist/flux.js',
    'vendor/livewire/flux/dist/flux.min.js',
];
foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    $exists = file_exists($path);
    $size = $exists ? filesize($path) . ' Bytes' : '-';
    echo '<li style="color:' . ($exists ? 'green' : 'red') . '">'
        . $file . ': ' . ($exists ? "OK ($size)" : 'FEHLT') . '</li>';
}
echo '</ul>';

echo '<h2>Deaktivierte PHP-Funktionen</h2>';
$disabled = ini_get('disable_functions');
echo '<pre>' . ($disabled ?: '(keine)') . '</pre>';

echo '<h2>open_basedir</h2>';
$basedir = ini_get('open_basedir');
echo '<pre>' . ($basedir ?: '(nicht gesetzt)') . '</pre>';
