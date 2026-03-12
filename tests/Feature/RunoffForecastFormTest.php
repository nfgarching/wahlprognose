<?php

use App\Livewire\RunoffForecastForm;
use App\Models\RunoffForecast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the stichwahl page', function () {
    $response = $this->get('/stichwahl');
    $response->assertStatus(200);
    $response->assertSeeLivewire(RunoffForecastForm::class);
});

it('requires a pseudonym to submit', function () {
    Livewire::test(RunoffForecastForm::class)
        ->set('predictedWinner', 'gruchmann')
        ->call('submit')
        ->assertHasErrors(['pseudonym' => 'required']);
});

it('requires a winner selection to submit', function () {
    Livewire::test(RunoffForecastForm::class)
        ->set('pseudonym', 'TestUser')
        ->call('submit')
        ->assertHasErrors(['predictedWinner' => 'required']);
});

it('saves a runoff forecast as guest', function () {
    Livewire::test(RunoffForecastForm::class)
        ->set('pseudonym', 'GarchingFan')
        ->set('predictedWinner', 'gruchmann')
        ->set('gruchmannPercent', 55)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('saved', true);

    expect(RunoffForecast::where('pseudonym', 'GarchingFan')->exists())->toBeTrue();
});

it('saves a runoff forecast with lemke as winner', function () {
    Livewire::test(RunoffForecastForm::class)
        ->set('pseudonym', 'CSUFan')
        ->set('predictedWinner', 'lemke')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('saved', true);

    expect(RunoffForecast::where('predicted_winner', 'lemke')->exists())->toBeTrue();
});

it('shows statistics after forecasts are submitted', function () {
    RunoffForecast::factory()->create(['predicted_winner' => 'gruchmann', 'gruchmann_percent' => 55]);
    RunoffForecast::factory()->create(['predicted_winner' => 'gruchmann', 'gruchmann_percent' => 60]);
    RunoffForecast::factory()->create(['predicted_winner' => 'lemke', 'gruchmann_percent' => null]);

    $component = Livewire::test(RunoffForecastForm::class);

    expect($component->instance()->statistics['total'])->toBe(3)
        ->and($component->instance()->statistics['gruchmann'])->toBe(2)
        ->and($component->instance()->statistics['lemke'])->toBe(1)
        ->and($component->instance()->statistics['gruchmann_pct'])->toBe(66.7)
        ->and($component->instance()->statistics['avg_gruchmann_percent'])->toBe(57.5);
});
