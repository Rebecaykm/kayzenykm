<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workcenter>
 */
class WorkcenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'departament_id' => $this->faker->numberBetween(1, 5)
        ];
    }
}
