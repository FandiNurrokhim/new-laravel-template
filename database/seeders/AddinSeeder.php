<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddinSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('addin_subcategories')->truncate();
        DB::table('addin_categories')->truncate();
        DB::table('addin_sections')->truncate();
        DB::table('addin_settings')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $icons = Icon::all();

        // Insert data into addin_settings
        $settings = [];
        for ($i = 1; $i <= 3; $i++) {
            $settings[] = [
                'title' => 'Setting ' . $i,
                'type_section' => 'section-' . $i,
                'pptx' => 'file' . $i . '.pptx',
                'glb' => 'file' . $i . '.glb',
                'png' => 'file' . $i . '.png',
                'svg' => 'file' . $i . '.svg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('addin_settings')->insert($settings);

        // Get inserted settings
        $settings = DB::table('addin_settings')->get();

        // Insert data into addin_sections
        $sections = [];
        foreach ($settings as $setting) {
            for ($i = 1; $i <= 2; $i++) {
                $sections[] = [
                    'setting_id' => $setting->id,
                    'title' => 'Section ' . $i . ' of ' . $setting->title,
                    'icon' => $icons->random()->class, // Use random icon
                    'is_active' => true,
                    'sort_number' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('addin_sections')->insert($sections);

        // Get inserted sections
        $sections = DB::table('addin_sections')->get();

        // Insert data into addin_categories
        $categories = [];
        foreach ($sections as $section) {
            for ($i = 1; $i <= 2; $i++) {
                $categories[] = [
                    'section_id' => $section->id,
                    'title' => 'Category ' . $i . ' of ' . $section->title,
                    'icon' => $icons->random()->class, 
                    'is_active' => true,
                    'sort_number' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('addin_categories')->insert($categories);

        // Get inserted categories
        $categories = DB::table('addin_categories')->get();

        // Insert data into addin_subcategories
        $subcategories = [];
        foreach ($categories as $category) {
            for ($i = 1; $i <= 2; $i++) {
                $subcategories[] = [
                    'section_id' => $category->section_id,
                    'category_id' => $category->id,
                    'title' => 'Subcategory ' . $i . ' of ' . $category->title,
                    'icon' => $icons->random()->class, // Use random icon
                    'is_active' => true,
                    'sort_number' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('addin_subcategories')->insert($subcategories);

        echo "Seeder AddinSeeder selesai dijalankan!\n";
    }
}