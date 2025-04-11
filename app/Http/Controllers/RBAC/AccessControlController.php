<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class AccessControlController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $roles = Role::orderBy('id', 'ASC');

                return DataTables::of($roles)
                    ->addIndexColumn()
                    ->addColumn('action', function ($role) {
                        $editUrl = route('access-control.edit', $role->id);

                        $btn = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm">
                                    Permissions
                                </a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('dashboard.access-control.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($roleId)
    {
        try {
            $role = Role::findById($roleId);
            $permissions = Permission::all();

            return view('dashboard.access-control.edit', compact('role', 'permissions'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $roleId)
    {
        try {
            $role = Role::findById($roleId);

            $permissionIds = $request->input('permissions', []);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
            $role->forgetCachedPermissions();

            if (auth()->check()) {
                auth()->user()->forgetCachedPermissions();
            }

            return redirect()
                ->route('access-control.index')
                ->with('success', 'Permissions berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
