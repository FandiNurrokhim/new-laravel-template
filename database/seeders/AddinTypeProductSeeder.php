<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddinTypeProduct;

class AddinTypeProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AddinTypeProduct::create([
            'title' => 'Type Product 1',
            'is_active' => true,
        ]);

        AddinTypeProduct::create([
            'title' => 'Type Product 2',
            'is_active' => true,
        ]);
    }
}
