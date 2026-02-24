<?php
// SICHERHEITSHINWEIS: Sofort nach Nutzung löschen!
echo '<h2>PHP Version: ' . phpversion() . '</h2>';
echo '<h3>Wichtige Extensions:</h3><ul>';
foreach (['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'] as $ext) {
    $ok = extension_loaded($ext);
    echo '<li style="color:' . ($ok ? 'green' : 'red') . '">' . $ext . ': ' . ($ok ? 'OK' : 'FEHLT') . '</li>';
}
echo '</ul>';
echo '<h3>Schreibrechte:</h3><ul>';
foreach ([__DIR__ . '/../storage', __DIR__ . '/../bootstrap/cache'] as $dir) {
    $ok = is_writable($dir);
    echo '<li style="color:' . ($ok ? 'green' : 'red') . '">' . basename($dir) . ': ' . ($ok ? 'schreibbar' : 'NICHT schreibbar') . '</li>';
}
echo '</ul>';
