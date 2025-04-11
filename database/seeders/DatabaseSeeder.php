<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            SectionSeeder::class,
            FileFormatSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            IconSeeder::class,
            MenuSeeder::class,
            AddinSeeder::class,
            AddinTypeProductSeeder::class,
            AddinProductSeeder::class,
            AddinItemSeeder::class,
        ]);
    }
}
