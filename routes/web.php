<?php

use App\Livewire\Dashboard;
use App\Livewire\ForecastForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::livewire('/prognose', ForecastForm::class)->name('prognose');

Route::view('/datenschutz', 'datenschutz')->name('privacy');

Route::livewire('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
