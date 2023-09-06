<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->numberBetween(1, 10),
            'model' => $this->faker->word,
            'prefixe' => $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit,
            'client_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}
