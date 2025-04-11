<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = User::with('roles', 'profile')->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => optional($item->profile)->first_name . ' ' . optional($item->profile)->last_name,
                        'email' => $item->email,
                        'username' => $item->username,
                        'status' => $item->status,
                        'role_name' => $item->roles->pluck('name')->implode(', '),
                        'role_id' => $item->roles->pluck('id')->first(),
                    ];
                });

                return DataTables::of($data)
                    ->editColumn('role_name', function ($row) {
                        $roles = explode(', ', $row['role_name']);
                        $badges = '';
                        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];

                        foreach ($roles as $role) {
                            $index = crc32($role) % count($colors);
                            $color = $colors[$index];

                            $badges .= '<span class="badge bg-' . $color . '">' . $role . '</span> ';
                        }
                        return $badges;
                    })
                    ->editColumn('status', function ($row) {
                        $status = $row['status'];

                        $statusColors = [
                            'ACTIVE' => 'success',
                            'PENDING' => 'warning',
                            'BLOCK' => 'danger',
                            'INACTIVE' => 'secondary',
                            'SUSPENDED' => 'info',
                            'DELETED' => 'dark',
                            'BANNED' => 'danger',
                            'EXPIRED' => 'secondary',
                        ];

                        $labels = [
                            'ACTIVE' => 'Active',
                            'PENDING' => 'Pending',
                            'BLOCK' => 'Blocked',
                            'INACTIVE' => 'Inactive',
                            'SUSPENDED' => 'Suspended',
                            'DELETED' => 'Deleted',
                            'BANNED' => 'Banned',
                            'EXPIRED' => 'Verification Expired',
                        ];

                        $color = $statusColors[$status] ?? 'secondary';
                        $label = $labels[$status] ?? 'Verification Expired';

                        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
                    })

                    ->addColumn('actions', function ($row) {
                        $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-email="' . $row['email'] . '" data-role-id="' . $row['role_id'] . '"  data-username="' . $row['username'] . '"  data-status="' . $row['status'] . '">Edit</button> ';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $row['id'] . '">Delete</button>';
                        return $btn;
                    })
                    ->rawColumns(['actions', 'role_name', 'status'])
                    ->make(true);
            }

            $roles = Role::all();
            $users = User::with('roles', 'profile')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => optional($item->profile)->first_name . ' ' . optional($item->profile)->last_name,
                    'username' => $item->username,
                    'status' => $item->status,
                    'email' => $item->email,
                    'role_name' => $item->roles->pluck('name')->implode(', '),

                ];
            });

            return view('dashboard.users.index', compact('roles', 'users'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'modalUsername' => 'required|min:3|unique:users,username',
                'modalStatus' => 'required',
                'modalEmail' => 'required|email|unique:users,email',
                'modalPassword' => 'required|min:6',
                'modalRole' => 'required'
            ]);

            $user = User::create([
                'username' => $validated['modalUsername'],
                'status' => $validated['modalStatus'],
                'email' => $validated['modalEmail'],
                'password' => bcrypt($validated['modalPassword'])
            ]);

            $role = Role::findOrFail($validated['modalRole']);
            $user->assignRole($role->name);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'editEmail' => 'required|email|unique:users,email,' . $user->id,
                'editUsername' => 'required|min:3|unique:users,username,' . $user->id,
                'editStatus' => 'required',
                'editPassword' => 'nullable|min:6',
                'editRole' => 'required'
            ]);


            $user->update([
                'username' => $validated['editUsername'],
                'status' => $validated['editStatus'],
                'email' => $validated['editEmail']
            ]);

            if ($request->filled('editPassword')) {
                $user->update([
                    'password' => bcrypt($validated['editPassword'])
                ]);
            }

            $role = Role::findOrFail($validated['editRole']);
            $user->syncRoles([$role->name]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->syncRoles([]);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
