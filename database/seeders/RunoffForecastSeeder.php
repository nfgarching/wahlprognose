<?php

namespace Database\Seeders;

use App\Models\RunoffForecast;
use App\Models\User;
use Illuminate\Database\Seeder;

class RunoffForecastSeeder extends Seeder
{
    public function run(): void
    {
        // Registrierte User mit je einer Stichwahl-Prognose
        User::factory(5)->create()->each(function (User $user) {
            RunoffForecast::factory()->create(['user_id' => $user->id]);
        });

        // Gast-Prognosen (kein User-Account)
        RunoffForecast::factory(20)->create(['user_id' => null]);
    }
}
