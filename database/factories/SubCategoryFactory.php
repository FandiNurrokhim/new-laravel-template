<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
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
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence(),
            'thumbnail' => 'default-subcategory.jpg',
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
