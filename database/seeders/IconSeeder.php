<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
{
    public function run()
    {
        $icons = [
            ['name' => 'Dashboard', 'class' => 'bx bx-home'],
            ['name' => 'User', 'class' => 'bx bx-user'],
            ['name' => 'Lock', 'class' => 'bx bx-lock'],
            ['name' => 'Key', 'class' => 'bx bx-key'],
            ['name' => 'Shield', 'class' => 'bx bx-check-shield'],
            ['name' => 'File', 'class' => 'bx bx-file'],
            ['name' => 'Receipt', 'class' => 'bx bx-receipt'],
            ['name' => 'Settings', 'class' => 'bx bx-cog'],
            ['name' => 'Graph', 'class' => 'bx bx-line-chart'],
            ['name' => 'Bell', 'class' => 'bx bx-bell'],
            ['name' => 'Message', 'class' => 'bx bx-message'],
            ['name' => 'Folder', 'class' => 'bx bx-folder'],
            ['name' => 'Edit', 'class' => 'bx bx-edit'],
            ['name' => 'Trash', 'class' => 'bx bx-trash'],
            ['name' => 'Calendar', 'class' => 'bx bx-calendar'],
            ['name' => 'Chart', 'class' => 'bx bx-chart'],
            ['name' => 'Menu', 'class' => 'bx bx-menu'],
            ['name' => 'Book', 'class' => 'bx bx-book'],
            ['name' => 'Shopping Cart', 'class' => 'bx bx-cart'],
            ['name' => 'Tag', 'class' => 'bx bx-tag'],
            ['name' => 'Credit Card', 'class' => 'bx bx-credit-card'],
            ['name' => 'Search', 'class' => 'bx bx-search'],
            ['name' => 'List', 'class' => 'bx bx-list-ul'],
            ['name' => 'Plus', 'class' => 'bx bx-plus'],
            ['name' => 'Minus', 'class' => 'bx bx-minus'],
            ['name' => 'Check', 'class' => 'bx bx-check'],
            ['name' => 'X', 'class' => 'bx bx-x'],
            ['name' => 'Up Arrow', 'class' => 'bx bx-up-arrow'],
            ['name' => 'Down Arrow', 'class' => 'bx bx-down-arrow'],
            ['name' => 'Left Arrow', 'class' => 'bx bx-left-arrow'],
            ['name' => 'Right Arrow', 'class' => 'bx bx-right-arrow'],
            ['name' => 'Up Arrow Circle', 'class' => 'bx bx-up-arrow-circle'],
            ['name' => 'Down Arrow Circle', 'class' => 'bx bx-down-arrow-circle'],
            ['name' => 'Left Arrow Circle', 'class' => 'bx bx-left-arrow-circle'],
            ['name' => 'Right Arrow Circle', 'class' => 'bx bx-right-arrow-circle']
        ];

        DB::table('icons')->insert($icons);
    }
}
