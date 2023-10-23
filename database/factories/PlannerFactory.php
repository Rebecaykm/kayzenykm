<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Planner>
 */
class PlannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit,
            'type' => 'P',
            'facility' => 'YK1',
            'name' => $this->faker->name
        ];
    }
}
