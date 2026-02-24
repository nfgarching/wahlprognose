<?php

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    public function run(): void
    {
        $parties = [
            ['name' => 'Christlich-Soziale Union', 'short_name' => 'CSU', 'color' => '#0066B3'],
            ['name' => 'Sozialdemokratische Partei Deutschlands', 'short_name' => 'SPD', 'color' => '#E3000F'],
            ['name' => 'Bündnis 90/Die Grünen', 'short_name' => 'GRÜNE', 'color' => '#46962B'],
            ['name' => 'Unabhängige Garchinger', 'short_name' => 'UG', 'color' => '#7C3AED'],
            ['name' => 'Bürger für Garching', 'short_name' => 'BfG', 'color' => '#0891B2'],
            ['name' => 'Freie Demokratische Partei', 'short_name' => 'FDP', 'color' => '#F9A825'],
        ];

        foreach ($parties as $party) {
            Party::firstOrCreate(['short_name' => $party['short_name']], $party);
        }
    }
}
