<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class MenuHelper
{
    public static function getSidebarMenus()
    {
        return Menu::orderBy('id')->with('children')->get();
    }

    public static function canViewMenu(Menu $menu)
    {
        if (empty($menu->route)) {
            return true;
        }
        $prefix = explode('.', $menu->route)[0];

        $perm = Permission::where('name', $prefix)->first();

        if ($perm) {
            return auth()->user()->can($perm->name);
        }
        return true;
    }

    public static function isActiveMenu($menu)
    {
        if (request()->routeIs($menu->route)) {
            return true;
        }

        if (request()->routeIs($menu->route . '.*')) {
            return true;
        }

        return false;
    }
}
