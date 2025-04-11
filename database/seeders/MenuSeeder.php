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
            'title' => 'Master Data'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'Vendors',
            'icon' => 'bx bx-file',
            'route' => 'vendors.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'Products',
            'icon' => 'bx bx-file',
            'route' => 'products.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'Sections',
            'icon' => 'bx bx-file',
            'route' => 'sections.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'Categories',
            'icon' => 'bx bx-file',
            'route' => 'categories.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'Sub Categories',
            'icon' => 'bx bx-file',
            'route' => 'sub-categories.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'File Formats',
            'icon' => 'bx bx-file',
            'route' => 'file-formats.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Master Data',
            'title' => 'Items',
            'icon' => 'bx bx-file',
            'route' => 'items.index'
        ]);

        Menu::create([
            'type' => 'HEADER',
            'title' => 'Transaction'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Transaction',
            'title' => 'Subscriptions',
            'icon' => 'bx bx-tag',
            'route' => 'subscriptions.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'Transaction',
            'title' => 'Licenses',
            'icon' => 'bx bx-receipt',
            'route' => 'licenses.index'
        ]);

        Menu::create([
            'type' => 'HEADER',
            'title' => 'System'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'System',
            'title' => 'System Settings',
            'icon' => 'bx bx-key',
            'route' => 'settings.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'System',
            'title' => 'Activity Log',
            'icon' => 'bx bx-list-ul',
            'route' => 'activity-log.index'
        ]);

        Menu::create([
            'type' => 'PARENT',
            'header' => 'System',
            'title' => 'Search Logs',
            'icon' => 'bx bx-search',
            'route' => 'search-logs.index'
        ]);
    }
}
