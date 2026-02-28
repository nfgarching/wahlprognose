<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if (! User::where('email', 'norbert.froehler@gmail.com')->first()) {
            User::factory()->create([
                'name' => 'Norbert Fröhler',
                'email' => 'norbert.froehler@gmail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('v1adimiR'),
                'is_admin' => True,
            ]);
        }

        $this->call([
            PartySeeder::class,
            CandidateSeeder::class,
            // ForecastSeeder::class,
        ]);
    }
}
