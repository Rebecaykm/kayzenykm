<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PartNumber>
 */
class PartNumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'number' => $this->faker->randomLetter . $this->faker->randomLetter  . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomLetter,
            'measurement_id' => $this->faker->numberBetween(1, 10),
            'type_id' => $this->faker->numberBetween(1, 10),
            'item_class_id' => $this->faker->numberBetween(1, 10),
            'standard_package_id' => $this->faker->numberBetween(1, 10),
            'workcenter_id' => $this->faker->numberBetween(1, 10),
            'planner_id' => $this->faker->numberBetween(1, 10),
            'project_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}
