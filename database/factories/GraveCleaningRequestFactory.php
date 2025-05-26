<?php

namespace Database\Factories;

use App\Models\GraveLocation;
use App\Models\GraveCleaningRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GraveCleaningRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = GraveCleaningRequest::class;

    public function definition()
    {
        return [
            'requester_name'   => $this->faker->name,
            'price'            => 15000,
            'address'          => $this->faker->address,
            'phone_number'     => $this->faker->phoneNumber,
            'rt'               => $this->faker->numberBetween(1, 10),
            'rw'               => $this->faker->numberBetween(1, 10),
            'dusun'            => $this->faker->word,
            'grave_location_id' => GraveLocation::inRandomOrder()->first()->id,
            'payment_status'   => $this->faker->randomElement(['unpaid', 'pending', 'paid']),
            'work_status'      => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
