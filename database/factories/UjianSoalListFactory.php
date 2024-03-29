<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UjianSoalList>
 */
class UjianSoalListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ujians_id' => $this->faker->randomNumber(1, 10),
            'soals_id' => $this->faker->randomNumber(1, 10),
            'kebenaran' => $this->faker->boolean(),
        ];
    }
}
