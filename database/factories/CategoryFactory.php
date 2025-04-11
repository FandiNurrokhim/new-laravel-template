<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(2, true);
        return [
            'uuid' => Str::uuid(),
            'section_id' => rand(1, 5),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence(),
            'thumbnail' => 'default-category.jpg',
            'order_number' => rand(1, 10),
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
