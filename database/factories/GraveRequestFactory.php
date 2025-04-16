<?php

namespace Database\Factories;

use App\Models\GraveLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GraveRequest>
 */
class GraveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'requester_name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'rt' => $this->faker->randomDigitNotNull(),
            'rw' => $this->faker->randomDigitNotNull(),
            'dusun' => $this->faker->word(),
            'corpse_name' => $this->faker->name(),
            'grave_location_id' => GraveLocation::inRandomOrder()->first()->id, // Relasi ke lokasi makam
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'notes' => $this->faker->sentence(),
        ];
    }
}
