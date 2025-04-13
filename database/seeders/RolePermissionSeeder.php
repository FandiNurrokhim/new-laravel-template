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
        $management = Role::firstOrCreate(['name' => 'User']);
        $operator = Role::firstOrCreate(['name' => 'Operator']);

        // For user addin
        Role::firstOrCreate(['name' => 'Subscriber']);
        Role::firstOrCreate(['name' => 'Guest']);

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

        $management->syncPermissions([
            'dashboard',
            'grave-request',
        ]);

        $operator->syncPermissions([
            'grave',
            'grave-group',
        ]);
    }
}
