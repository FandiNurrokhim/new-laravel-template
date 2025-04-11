<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileFormat>
 */
class FileFormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'thumbnail' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement([
                'DRAFT',
                'PENDING',
                'PUBLISHED',
                'INACTIVE',
                'ARCHIVED',
                'DELETED'
            ]),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
