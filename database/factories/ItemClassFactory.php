<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemClass>
 */
class ItemClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'abbreviation' => $this->faker->randomDigit . $this->faker->randomLetter . $this->faker->randomDigit . $this->faker->randomLetter,
            'name' => $this->faker->unique()->word
        ];
    }
}