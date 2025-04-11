<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Icon;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $menus = Menu::with(['parent', 'children'])->orderBy('id');

                return DataTables::of($menus)
                    ->addIndexColumn()
                    ->editColumn('type', function ($menu) {
                        if ($menu->type === 'HEADER') {
                            return '<span class="badge bg-primary">' . $menu->type . '</span>';
                        } elseif ($menu->type === 'PARENT') {
                            return '<span class="badge bg-success">' . $menu->type . '</span>';
                        } elseif ($menu->type === 'SUB PARENT') {
                            return '<span class="badge bg-info">' . $menu->type . '</span>';
                        }
                        return $menu->type;
                    })
                    ->editColumn('parent', function ($menu) {
                        return optional($menu->parent)->title;
                    })
                    ->editColumn('permission_title', function ($menu) {
                        return optional($menu->permission_like)->name ?: ($menu->permission_title ?? '');
                    })
                    ->addColumn('action', function ($menu) {
                        $editUrl = route('menus.edit', $menu->id);
                        $permissionName = optional($menu->permission_like)->name ?: ($menu->permission_title ?? '');

                        $btn = '<a href="' . $editUrl . '" class="btn btn-warning btn-sm btn-edit"
                            data-id="' . $menu->id . '"
                            data-type="' . $menu->type . '"
                            data-title="' . $menu->title . '"
                            data-icon="' . $menu->icon . '"
                            data-route="' . $menu->route . '"
                            data-header="' . $menu->header . '"
                            data-parent-id="' . optional($menu->parent)->id . '"
                            data-permission-title="' . $permissionName . '"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasEditMenu"
                            aria-controls="offcanvasEditMenu"
                        >Edit</a> ';

                        $btn .= '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $menu->id . '">
                            Delete
                        </button>';

                        return $btn;
                    })
                    ->rawColumns(['action', 'type'])
                    ->make(true);
            }

            $menus = Menu::with('children')->orderBy('id')->get();
            $icons = Icon::all();
            $headers = Menu::where('type', 'HEADER')->get();
            $parents = Menu::where('type', 'PARENT')->get();

            return view('dashboard.menus.index', compact('menus', 'icons', 'headers', 'parents'));

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $icons = Icon::all();
            $parents = Menu::where('type', 'PARENT')->get();
            $headers = Menu::where('type', 'HEADER')->get();
            return view('dashboard.menus.create', compact('icons', 'parents', 'headers'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:HEADER,PARENT,SUB PARENT',
                'title' => 'required',
                'permission_title' => 'required_unless:type,HEADER'
            ]);

            $data = $request->all();

            if ($request->type !== 'HEADER') {
                $permissionTitle = strtolower(str_replace(' ', '_', trim($request->permission_title)));
            } else {
                $permissionTitle = null;
            }

            $data['permission_title'] = $permissionTitle;

            Menu::create($data);

            if (!is_null($permissionTitle) && !Permission::where('name', $permissionTitle)->exists()) {
                Permission::create(['name' => $permissionTitle]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Menu $menu)
    {
        try {
            $icons = Icon::all();
            $parents = Menu::where('type', 'PARENT')->get();
            $headers = Menu::where('type', 'HEADER')->get();
            return view('dashboard.menus.edit', compact('menu', 'icons', 'parents', 'headers'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Menu $menu)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:HEADER,PARENT,SUB PARENT',
                'title' => 'required',
                'permission_title' => 'required_unless:type,HEADER'
            ]);

            $data = $request->all();

            if ($request->type !== 'HEADER') {
                $newPermissionTitle = strtolower(str_replace(' ', '_', trim($request->permission_title)));
            } else {
                $newPermissionTitle = null;
            }

            $oldPermissionTitle = $menu->permission_title;
            $data['permission_title'] = $newPermissionTitle;

            $menu->update($data);

            if ($oldPermissionTitle !== $newPermissionTitle && !is_null($newPermissionTitle)) {
                Permission::where('name', $oldPermissionTitle)->update(['name' => $newPermissionTitle]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
