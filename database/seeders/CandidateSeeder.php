<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Party;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        $candidates = [
            ['name' => 'Dr. Dietmar Gruchmann', 'party_short' => 'SPD'],
            ['name' => 'Thomas Lemke', 'party_short' => 'CSU'],
            ['name' => 'Werner Landmann', 'party_short' => 'GRÜNE'],
            ['name' => 'Christian Nolte', 'party_short' => 'UG'],
            ['name' => 'Simone Schmidt', 'party_short' => 'BfG'],
            ['name' => 'Bastian Dombret', 'party_short' => 'FDP'],
        ];

        foreach ($candidates as $data) {
            $party = Party::where('short_name', $data['party_short'])->first();

            Candidate::firstOrCreate(
                ['name' => $data['name']],
                ['party_id' => $party?->id, 'photo_path' => null]
            );
        }
    }
}
