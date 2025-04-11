<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddinItem;

class AddinItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AddinItem::create([
            'product_id' => 1,
            'section_id' => 1,
            'category_id' => 1,
            'subcategory_id' => 1,
            'title' => 'Sample Addin Item 1',
            'thumbnail' => null,
            'slug' => 'sample-addin-item-1',
            'tags' => 'sample, addin, item',
            'file_name' => 'sample-file-1.zip',
            'file_format' => 'zip',
            'price' => 9.99,
            'is_active' => true
        ]);

        AddinItem::create([
            'product_id' => 2,
            'section_id' => 2,
            'category_id' => 2,
            'subcategory_id' => 2,
            'title' => 'Sample Addin Item 2',
            'thumbnail' => null,
            'slug' => 'sample-addin-item-2',
            'tags' => 'sample, addin, item',
            'file_name' => 'sample-file-2.zip',
            'file_format' => 'zip',
            'price' => 19.99,
            'is_active' => true
        ]);
    }
}
