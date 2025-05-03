<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $permissions = [
            // GENERAL
            'dashboard',
            'roles',
            'menus',
            'access-control',
            'users',

            // MASTER DATA
            'grave',
            'grave-group',
            'grave-request',

            // SYSTEM
            'settings',
            'activity-log',
            'search-logs',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $superAdmin->syncPermissions($permissions);
    }
}
