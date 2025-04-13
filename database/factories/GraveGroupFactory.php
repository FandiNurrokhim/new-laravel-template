<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=GraveGroup>
 */
class GraveGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'max_graves' => $this->faker->numberBetween(10, 50), // Random number of graves
            'unused_graves' => 0,
            'used_graves' => 0,
            'is_full' => false,
        ];
    }
}
