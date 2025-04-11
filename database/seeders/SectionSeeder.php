<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::create([
            'uuid' => \Str::uuid(),
            'product_id' => 1, // ID dari Product 1
            'title' => 'Section 1 for Product 1',
            'slug' => 'section-1-product-1',
            'description' => 'Description for Section 1 of Product 1',
            'thumbnail' => 'https://via.placeholder.com/150',
            'order_number' => 1,
            'status' => 'PUBLISHED',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Section::create([
            'uuid' => \Str::uuid(),
            'product_id' => 1, // ID dari Product 1
            'title' => 'Section 2 for Product 1',
            'slug' => 'section-2-product-1',
            'description' => 'Description for Section 2 of Product 1',
            'thumbnail' => 'https://via.placeholder.com/150',
            'order_number' => 2,
            'status' => 'PUBLISHED',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Section::create([
            'uuid' => \Str::uuid(),
            'product_id' => 2, // ID dari Product 2
            'title' => 'Section 1 for Product 2',
            'slug' => 'section-1-product-2',
            'description' => 'Description for Section 1 of Product 2',
            'thumbnail' => 'https://via.placeholder.com/150',
            'order_number' => 1,
            'status' => 'PUBLISHED',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}