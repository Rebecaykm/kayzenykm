<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StandardPackage>
 */
class StandardPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomLetter .  $this->faker->randomLetter,
            'capacity' => $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit
        ];
    }
}
