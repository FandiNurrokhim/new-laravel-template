<?php

namespace Database\Seeders;

use App\Models\GraveGroup;
use App\Models\CorpseDetail;
use App\Models\GraveLocation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GraveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GraveGroup::factory(5)->create()->each(function ($graveGroup) {
            $maxGraves = $graveGroup->max_graves;
            $graveLocations = GraveLocation::factory($maxGraves)->create([
                'grave_group_id' => $graveGroup->id,
            ]);

            $graveLocations->random(rand(1, $maxGraves))->each(function ($graveLocation) {
                CorpseDetail::factory()->create([
                    'grave_location_id' => $graveLocation->id,
                ]);

                $graveLocation->update(['is_confirmed' => true]);
            });

            $usedGraves = $graveGroup->locations()->where('is_confirmed', true)->count();
            $graveGroup->update([
                'used_graves' => $usedGraves,
                'unused_graves' => $maxGraves - $usedGraves,
                'is_full' => $usedGraves === $maxGraves,
            ]);
        });
    }
}
