<?php

namespace Database\Seeders;

use App\Models\GraveRequest;
use App\Models\GraveLocation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GraveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (GraveLocation::count() === 0) {
            $this->call(GraveSeeder::class); 
        }

        GraveRequest::factory(50)->create();
    }
}
