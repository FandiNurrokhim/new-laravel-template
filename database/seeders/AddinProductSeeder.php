<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddinProduct;

class AddinProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AddinProduct::create([
            'type_product_id' => 1,
            'title' => 'Product 1',
            'folder_name' => 'product-1',
            'thumbnail' => null,
            'is_active' => true,
        ]);

        AddinProduct::create([
            'type_product_id' => 2,
            'title' => 'Product 2',
            'folder_name' => 'product-2',
            'thumbnail' => null,
            'is_active' => true,
        ]);
    }
}
