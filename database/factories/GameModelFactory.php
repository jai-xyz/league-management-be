<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameModel>
 */
class GameModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'home_team_id' => $this->faker->numberBetween(0, 10),
            'away_team_id' => $this->faker->numberBetween(0, 10),
            'game_date' => $this->faker->date(),
            'game_time' => $this->faker->time(),
            'location' => $this->faker->word,
            'status' => $this->faker->word,
            'home_team_final_score' => $this->faker->numberBetween(0, 100),
            'away_team_final_score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
