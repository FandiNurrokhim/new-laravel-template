<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ------------------------------------------------
        // 1) Super Admin
        // ------------------------------------------------
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'uuid' => (string) Str::uuid(),
                'status' => 'ACTIVE',
            ]
        );
        $superAdmin->assignRole('Super Admin');

        $superAdmin->profile()->updateOrCreate(
            [],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'ip_address' => '127.0.0.1',
                'country' => 'Indonesia',
                'city' => 'Malang',
                'latitude' => -7.9784,
                'longitude' => 112.6342,
                'timezone' => 'Asia/Jakarta',
                'language_preference' => 'id',
                'last_active' => now(),
            ]
        );
    }
}
