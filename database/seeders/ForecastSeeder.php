<?php

namespace Database\Seeders;

use App\Models\Forecast;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForecastSeeder extends Seeder
{
    public function run(): void
    {
        // 100 registrierte User mit je einer Prognose
        User::factory(100)->create()->each(function (User $user) {
            Forecast::factory()->create(['user_id' => $user->id]);
        });

        // 250 Gast-Prognosen (kein User-Account)
        Forecast::factory(250)->create(['user_id' => null]);
    }
}
