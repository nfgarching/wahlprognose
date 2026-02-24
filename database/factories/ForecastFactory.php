<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Forecast;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Forecast>
 */
class ForecastFactory extends Factory
{
    protected $model = Forecast::class;

    public function definition(): array
    {
        $candidates = Candidate::inRandomOrder()->limit(2)->get();
        $hasRunoff = $this->faker->boolean(30);

        $candidate1Id = $candidates->get(0)?->id;
        $candidate2Id = $hasRunoff ? $candidates->get(1)?->id : null;
        $runoffWinnerId = ($hasRunoff && $this->faker->boolean(70))
            ? $this->faker->randomElement(array_filter([$candidate1Id, $candidate2Id]))
            : null;

        return [
            'user_id' => null,
            'ip_address' => $this->faker->ipv4(),
            'pseudonym' => $this->faker->userName(),
            'mayor_candidate_1_id' => $candidate1Id,
            'mayor_candidate_2_id' => $candidate2Id,
            'mayor_runoff_winner_id' => $runoffWinnerId,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Forecast $forecast) {
            $parties = Party::all();
            $seats = $this->randomSeatDistribution($parties->count());

            foreach ($parties->values() as $i => $party) {
                $forecast->seats()->create([
                    'party_id' => $party->id,
                    'seats' => $seats[$i],
                ]);
            }
        });
    }

    /**
     * Distribute $total seats uniformly at random among $k parties (sum = $total, each >= 0).
     *
     * @return array<int, int>
     */
    private function randomSeatDistribution(int $k, int $total = 24): array
    {
        $cuts = array_map(fn () => rand(0, $total), range(1, $k - 1));
        sort($cuts);
        $cuts = array_merge([0], $cuts, [$total]);

        $seats = [];
        for ($i = 0; $i < $k; $i++) {
            $seats[] = $cuts[$i + 1] - $cuts[$i];
        }

        return $seats;
    }
}
