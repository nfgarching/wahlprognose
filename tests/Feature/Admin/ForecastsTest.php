<?php

use App\Livewire\Admin\Forecasts;
use App\Models\Forecast;
use App\Models\User;
use Database\Seeders\CandidateSeeder;
use Database\Seeders\PartySeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed([PartySeeder::class, CandidateSeeder::class]);
});

test('guests are redirected to the login page', function () {
    $this->get(route('admin.forecasts'))
        ->assertRedirect(route('login'));
});

test('non-admin users get a 403', function () {
    $user = User::factory()->create(['is_admin' => false]);

    Livewire::actingAs($user)
        ->test(Forecasts::class)
        ->assertForbidden();
});

test('admin users can visit the admin forecasts page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.forecasts'))
        ->assertOk();
});

test('admin sees all forecasts including fake ones', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Forecast::factory()->create(['pseudonym' => 'EchterNutzer', 'is_fake' => false]);
    Forecast::factory()->fake()->create(['pseudonym' => 'TestFake']);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->assertSee('EchterNutzer')
        ->assertSee('TestFake');
});

test('admin can mark a real forecast as fake', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $forecast = Forecast::factory()->create(['is_fake' => false]);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->call('toggleFake', $forecast->id);

    expect($forecast->fresh()->is_fake)->toBeTrue();
});

test('admin can restore a fake forecast back to real', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $forecast = Forecast::factory()->fake()->create();

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->call('toggleFake', $forecast->id);

    expect($forecast->fresh()->is_fake)->toBeFalse();
});

test('duplicate IPs are flagged with a Duplikat badge', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Forecast::factory()->count(2)->create(['ip_address' => '10.0.0.1']);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->assertSee('Duplikat');
});

test('unique IPs are not flagged as duplicates', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Forecast::factory()->create(['ip_address' => '10.0.0.1']);
    Forecast::factory()->create(['ip_address' => '10.0.0.2']);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->assertDontSee('Duplikat');
});

test('fake filter shows only fake forecasts', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Forecast::factory()->create(['pseudonym' => 'EchterNutzer', 'is_fake' => false]);
    Forecast::factory()->fake()->create(['pseudonym' => 'GefaelschterNutzer']);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->set('filterFake', 'fake')
        ->assertSee('GefaelschterNutzer')
        ->assertDontSee('EchterNutzer');
});

test('real filter shows only real forecasts', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Forecast::factory()->create(['pseudonym' => 'EchterNutzer', 'is_fake' => false]);
    Forecast::factory()->fake()->create(['pseudonym' => 'GefaelschterNutzer']);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->set('filterFake', 'real')
        ->assertSee('EchterNutzer')
        ->assertDontSee('GefaelschterNutzer');
});

test('search filters forecasts by pseudonym', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    Forecast::factory()->create(['pseudonym' => 'SuchTreffer']);
    Forecast::factory()->create(['pseudonym' => 'AndererNutzer']);

    Livewire::actingAs($admin)
        ->test(Forecasts::class)
        ->set('search', 'SuchTreffer')
        ->assertSee('SuchTreffer')
        ->assertDontSee('AndererNutzer');
});
