<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $roles = Role::withCount('users');
                return DataTables::of($roles)
                    ->addColumn('actions', function ($role) {
                        $editUrl = route('roles.edit', $role->id);
                        $deleteUrl = route('roles.destroy', $role->id);
                        $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $role->id . '" data-name="' . $role->name . '">Edit</button> ';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $role->id . '">Delete</button>';
                        return $btn;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }

            $roles = Role::all();
            return view('dashboard.roles.index', compact('roles'));
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

    public function create()
    {
        return view('dashboard.roles.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'modalRoleName' => 'required|min:3|unique:roles,name',
            ]);

            $role = Role::create([
                'name' => $validated['modalRoleName']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role added successfully.',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Role $role)
    {
        return view('dashboard.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        try {
            $validated = $request->validate([
                'modalRoleName' => 'required|min:3|unique:roles,name,' . $role->id,
            ]);

            $role->update([
                'name' => $validated['modalRoleName']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
