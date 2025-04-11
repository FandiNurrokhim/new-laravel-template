<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'uuid' => \Str::uuid(),
            'title' => 'Product 1',
            'title_folder' => 'product_1_folder',
            'slug' => 'product-1',
            'description' => 'Description for Product 1',
            'thumbnail' => 'https://via.placeholder.com/150',
            'order_number' => 1,
            'status' => 'PUBLISHED',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Product::create([
            'uuid' => \Str::uuid(),
            'title' => 'Product 2',
            'title_folder' => 'product_2_folder',
            'slug' => 'product-2',
            'description' => 'Description for Product 2',
            'thumbnail' => 'https://via.placeholder.com/150',
            'order_number' => 2,
            'status' => 'PUBLISHED',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
