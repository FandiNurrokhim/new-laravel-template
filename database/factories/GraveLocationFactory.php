<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=GraveLocation>
 */
class GraveLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'grave_group_id' => null, 
            'order' => 0,
            'code' => $this->faker->unique()->word,
            'is_reserved' => false,
            'is_confirmed' => false,
        ];
    }
}
