<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Menu::create([
            'type' => 'PARENT',
            'title' => 'Dashboard',
            'icon' => 'bx bx-home',
            'route' => 'dashboard'
        ]);

        Menu::create([
            'type' => 'HEADER',
            'title' => 'User Management'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'User',
            'title' => 'Users',
            'icon' => 'bx bx-user',
            'route' => 'users.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'User Management',
            'title' => 'Roles',
            'icon' => 'bx bx-lock',
            'route' => 'roles.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'User Management',
            'title' => 'Menus',
            'icon' => 'bx bx-menu',
            'route' => 'menus.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'User Management',
            'title' => 'Access Control',
            'icon' => 'bx bx-check-shield',
            'route' => 'access-control.index'
        ]);

        Menu::create([
            'type' => 'HEADER',
            'title' => 'Manajemen Kuburan'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Manajemen Kuburan',
            'title' => 'Kelola Mayit',
            'icon' => 'bx bx-user-circle', 
            'route' => 'grave.index'
        ]);
        
        Menu::create([
            'type' => 'PARENT',
            'header' => 'Manajemen Kuburan',
            'title' => 'Kelompok Kuburan',
            'icon' => 'bx bx-group', // Updated icon
            'route' => 'grave-group.index'
        ]);
        
        Menu::create([
            'type' => 'PARENT',
            'header' => 'Manajemen Kuburan',
            'title' => 'Permintaan Lokasi',
            'icon' => 'bx bx-map', // Updated icon
            'route' => 'grave-request.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Manajemen Kuburan',
            'title' => 'Permintaan Pembersihan',
            'icon' => 'bx bx-brush', 
            'route' => 'grave-cleaning-request.index'
        ]);
    }
}
