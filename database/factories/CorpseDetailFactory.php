<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=CorpseDetail>
 */
class CorpseDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'grave_location_id' => null,
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date(),
            'birth_place' => $this->faker->city,
            'age' => $this->faker->numberBetween(1, 100),
            'death_date' => $this->faker->date(),
            'javanese_weton' => $this->faker->randomElement(['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon']),
        ];
    }
}
