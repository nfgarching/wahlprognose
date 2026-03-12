<?php

namespace Database\Factories;

use App\Models\RunoffForecast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RunoffForecast>
 */
class RunoffForecastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'ip_address' => $this->faker->ipv4(),
            'pseudonym' => $this->faker->userName(),
            'predicted_winner' => $this->faker->randomElement(['gruchmann', 'lemke']),
            'gruchmann_percent' => $this->faker->optional()->numberBetween(40, 65),
        ];
    }
}
