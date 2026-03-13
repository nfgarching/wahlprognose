<?php

use App\Livewire\Admin\RunoffForecasts;
use App\Livewire\Admin\RunoffRanking;
use App\Models\RunoffForecast;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to the login page', function () {
    $this->get(route('admin.runoff-forecasts'))
        ->assertRedirect(route('login'));
});

test('non-admin users get a 403 on runoff forecasts', function () {
    $user = User::factory()->create(['is_admin' => false]);

    Livewire::actingAs($user)
        ->test(RunoffForecasts::class)
        ->assertForbidden();
});

test('admin users can visit the runoff forecasts page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.runoff-forecasts'))
        ->assertOk();
});

test('admin sees all runoff forecasts', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    RunoffForecast::factory()->create(['pseudonym' => 'GruchmannFan', 'predicted_winner' => 'gruchmann']);
    RunoffForecast::factory()->create(['pseudonym' => 'LemkeFan', 'predicted_winner' => 'lemke']);

    Livewire::actingAs($admin)
        ->test(RunoffForecasts::class)
        ->assertSee('GruchmannFan')
        ->assertSee('LemkeFan');
});

test('search filters runoff forecasts by pseudonym', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    RunoffForecast::factory()->create(['pseudonym' => 'SuchTreffer']);
    RunoffForecast::factory()->create(['pseudonym' => 'AndererNutzer']);

    Livewire::actingAs($admin)
        ->test(RunoffForecasts::class)
        ->set('search', 'SuchTreffer')
        ->assertSee('SuchTreffer')
        ->assertDontSee('AndererNutzer');
});

test('duplicate IPs are flagged with a Duplikat badge on runoff forecasts', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    RunoffForecast::factory()->count(2)->create(['ip_address' => '10.0.0.1']);

    Livewire::actingAs($admin)
        ->test(RunoffForecasts::class)
        ->assertSee('Duplikat');
});

test('admin users can visit the runoff ranking page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.runoff-ranking'))
        ->assertOk();
});

test('runoff ranking shows winner distribution', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    RunoffForecast::factory()->count(3)->create(['predicted_winner' => 'gruchmann']);
    RunoffForecast::factory()->count(2)->create(['predicted_winner' => 'lemke']);

    Livewire::actingAs($admin)
        ->test(RunoffRanking::class)
        ->assertSee('Gruchmann')
        ->assertSee('Lemke');
});
