<?php

use App\Http\Controllers\ForecastExportController;
use App\Livewire\Admin\Forecasts as AdminForecasts;
use App\Livewire\Dashboard;
use App\Livewire\ForecastForm;
use App\Livewire\Results;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (now()->gt(Carbon::parse(config('forecast.edit_deadline')))) {
        return redirect()->route('results');
    }

    return view('welcome');
})->name('home');

Route::livewire('/prognose', ForecastForm::class)->name('prognose');

Route::livewire('/ergebnisse', Results::class)->name('results');

Route::view('/datenschutz', 'datenschutz')->name('privacy');
Route::view('/impressum', 'impressum')->name('impressum');

Route::livewire('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/export', ForecastExportController::class)
    ->middleware(['auth', 'verified'])
    ->name('forecast.export');

Route::livewire('/admin/prognosen', AdminForecasts::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.forecasts');

require __DIR__.'/settings.php';
