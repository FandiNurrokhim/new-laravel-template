<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexAdmin()
    {
        return view('dashboard.home.admin');
    }

    public function indexManagement()
    {
        return view('dashboard.home.management');
    }
}
