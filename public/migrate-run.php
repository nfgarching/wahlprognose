<?php
// SICHERHEITSHINWEIS: Diese Datei sofort nach Nutzung vom Server löschen!
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('migrate', ['--force' => true]);
echo '<pre>'.htmlspecialchars($kernel->output()).'</pre>';
