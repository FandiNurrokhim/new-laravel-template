<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RBAC\MenuController;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Grave\GraveController;
use App\Http\Controllers\MasterData\ItemController;
use App\Http\Controllers\Grave\GraveGroupController;
use App\Http\Controllers\MasterData\VendorController;
use App\Http\Controllers\RBAC\AccessControlController;
use App\Http\Controllers\MasterData\FileFormatController;
use App\Http\Controllers\MasterData\SubCategoryController;
use App\Http\Controllers\Transaction\SubscriptionController;

// Redirect root
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes (prefix /dashboard, middleware auth)
Route::prefix('/dashboard')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'indexAdmin'])->name('dashboard')->middleware('check.permission:dashboard');
    Route::get('/management', [DashboardController::class, 'indexManagement'])
        ->name('dashboard.management')
        ->middleware('check.permission:dashboard.manjemen');

    // Role Management (Super Admin only)
    Route::resource('roles', RoleController::class)->middleware('check.permission:roles');

    // Access Control (Assign Permissions) (Super Admin only)
    Route::get('access-control', [AccessControlController::class, 'index'])->name('access-control.index')->middleware('check.permission:access-control');
    Route::get('access-control/{role}', [AccessControlController::class, 'edit'])->name('access-control.edit')->middleware('check.permission:access-control');
    Route::post('access-control/{role}', [AccessControlController::class, 'update'])->name('access-control.assign')->middleware('check.permission:access-control');


    // Menu Management (Super Admin only)
    Route::resource('menus', MenuController::class)->middleware('check.permission:menus');

    // User Management (Super Admin only)
    Route::resource('users', UserController::class)->middleware('check.permission:users');

    // Master Data (Super Admin & Operator)
    Route::resource('vendors', VendorController::class)->middleware('check.permission:vendors');

    Route::resource('grave', GraveController::class)->middleware('check.permission:grave');
    Route::resource('grave-group', GraveGroupController::class)->middleware('check.permission:grave-group');
    Route::resource('grave-request', GraveGroupController::class)->middleware('check.permission:grave-request');
    Route::get('api/grave-locations/{groupId}', [GraveController::class, 'fetchLocations'])->name('grave-locations.fetch');

    Route::get('licenses', fn() => 'PAGE LICENSES')
        ->name('licenses.index')
        ->middleware('check.permission:licenses');

    // System Settings (Super Admin only)
    Route::get('settings', fn() => 'PAGE SYSTEM SETTINGS')
        ->name('settings.index')
        ->middleware('check.permission:settings');

    Route::get('activity-log', fn() => 'PAGE ACTIVITY LOG')
        ->name('activity-log.index')
        ->middleware('check.permission:activity-log');

    Route::get('search-logs', fn() => 'PAGE SEARCH LOGS')
        ->name('search-logs.index')
        ->middleware('check.permission:search-logs');
});
