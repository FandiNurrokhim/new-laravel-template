<?php

namespace Database\Seeders;

use App\Models\GraveCleaningRequest;
use App\Models\GraveLocation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CleaningRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (GraveLocation::count() === 0) {
            $this->call(GraveSeeder::class);
        }

        GraveCleaningRequest::factory(10)->create();
    }
}
