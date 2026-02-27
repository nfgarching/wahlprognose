<?php

use App\Http\Controllers\ForecastExportController;
use App\Livewire\Dashboard;
use App\Livewire\ForecastForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::livewire('/prognose', ForecastForm::class)->name('prognose');

Route::view('/datenschutz', 'datenschutz')->name('privacy');
Route::view('/impressum', 'impressum')->name('impressum');

Route::livewire('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/export', ForecastExportController::class)
    ->middleware(['auth', 'verified'])
    ->name('forecast.export');

require __DIR__.'/settings.php';
