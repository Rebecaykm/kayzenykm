<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->randomLetter . $this->faker->randomLetter . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit . $this->faker->randomDigit,
            'name' => $this->faker->company
        ];
    }
}
