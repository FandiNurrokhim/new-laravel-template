<?php

namespace App\Http\Controllers\RBAC;

use App\Models\Icon;
use App\Models\Menu;
use App\Models\AddinSection;
use App\Models\AddinSetting;
use Illuminate\Http\Request;
use App\Models\AddinCategory;
use App\Models\AddinSubcategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class AddinController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                // $settings = AddinSetting::select('id', 'title', 'icon', 'is_active', 'updated_at', DB::raw('"setting" as type'));
                // $sections = AddinSection::select('id', 'title', 'icon', 'is_active', 'sort_number', 'updated_at', DB::raw('"section" as type'));
                // $categories = AddinCategory::select('id', 'title', 'icon', 'is_active', 'sort_number', 'updated_at', DB::raw('"category" as type'));
                // $subcategories = AddinSubCategory::select('id', 'title', 'icon', 'is_active', 'sort_number', 'updated_at', DB::raw('"subcategory" as type'));

                // // Gabungkan semua model menggunakan union
                // $addins = $settings->unionAll($sections)->unionAll($categories)->unionAll($subcategories)->orderBy('updated_at', 'desc');

                $sections = AddinSection::select('id', 'title', 'icon', 'is_active', 'sort_number', 'updated_at', DB::raw('"section" as type'));
                $categories = AddinCategory::select('id', 'title', 'icon', 'is_active', 'sort_number', 'updated_at', DB::raw('"category" as type'));
                $subcategories = AddinSubCategory::select('id', 'title', 'icon', 'is_active', 'sort_number', 'updated_at', DB::raw('"subcategory" as type'));

                // Gabungkan semua model menggunakan union
                $addins = $sections->unionAll($categories)->unionAll($subcategories)->orderBy('updated_at', 'desc');

                return DataTables::of($addins)
                    ->addIndexColumn()
                    ->editColumn('type', function ($addin) {
                        return match ($addin->type) {
                            'section' => '<span class="badge bg-primary">Section</span>',
                            'category' => '<span class="badge bg-success">Category</span>',
                            'subcategory' => '<span class="badge bg-info">Subcategory</span>',
                            'setting' => '<span class="badge bg-dark">Setting</span>',
                            default => '<span class="badge bg-secondary">Unknown</span>',
                        };
                    })
                    ->addColumn('action', function ($addin) {
                        $editUrl = route('addin.show', ['type' => $addin->type, 'id' => $addin->id]);

                        $btn = '<button class="btn btn-warning btn-sm btn-edit_addin"
                            data-id="' . $addin->id . '"
                            data-type="' . $addin->type . '"
                            data-title="' . $addin->title . '"
                            data-icon="' . $addin->icon . '"
                            data-is_active="' . $addin->is_active . '"
                            data-sort_number="' . $addin->sort_number . '"
                            data-url="' . $editUrl . '"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasEditAddin"
                            aria-controls="offcanvasEditAddin"
                        >Edit</button> ';

                        $btn .= '<button class="btn btn-danger btn-sm btn-delete_addin"
                        data-id="' . $addin->id . '"
                        data-type="' . $addin->type . '">
                        Delete
                    </button>';


                        return $btn;
                    })
                    ->rawColumns(['action', 'type'])
                    ->make(true);
            }

            $sections = AddinSection::with('categories', 'subcategories')->orderBy('id')->get();
            $settings = AddinSetting::get();
            $categories = AddinCategory::get();
            $subcategories = AddinSubcategory::get();
            $icons = Icon::all();

            return view('dashboard.addin.index', compact('sections', 'settings', 'categories', 'subcategories', 'icons'));
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
            $request->validate([
                'type' => 'required|in:SETTING,SECTION,CATEGORY,SUB CATEGORY',
                'title' => 'required|string|max:255',
                'is_active' => 'required|boolean',
                'sort_number' => 'required_if:type,SECTION,CATEGORY,SUBCATEGORY|integer',
                'setting_id' => 'nullable|required_if:type,SECTION|integer',
                'section_id' => 'nullable|required_if:type,CATEGORY,SUB CATEGORY|integer',
                'category_id' => 'nullable|required_if:type,SUB CATEGORY|integer',
                'icon' => 'nullable|string|max:255'
            ]);

            $data = $request->all();

            switch ($request->type) {
                case 'SETTING':
                    $addin = AddinSetting::create($data);
                    break;
                case 'SECTION':
                    $addin = AddinSection::create($data);
                    break;
                case 'CATEGORY':
                    $addin = AddinCategory::create($data);
                    break;
                case 'SUB CATEGORY':
                    $addin = AddinSubcategory::create($data);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Addin berhasil dibuat.',
                'data' => $addin
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($type, $id)
    {
        switch (strtolower($type)) {
            case 'setting':
                $addin = AddinSetting::findOrFail($id);
                $typeFormatted = 'SETTING';
                break;
            case 'section':
                $addin = AddinSection::with('setting')->findOrFail($id);
                $typeFormatted = 'SECTION';
                break;
            case 'category':
                $addin = AddinCategory::with(['section.setting'])->findOrFail($id);
                $typeFormatted = 'CATEGORY';
                break;
            case 'subcategory':
                $addin = AddinSubcategory::with(['category.section.setting'])->findOrFail($id);
                $typeFormatted = 'SUB CATEGORY';
                break;
            default:
                return response()->json(['message' => 'Invalid type'], 400);
        }
    
        return response()->json([
            'addin' => $addin,
            'type' => $typeFormatted
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'is_active' => 'required|boolean',
                'sort_number' => 'required_if:type,SECTION,CATEGORY,SUBCATEGORY|integer',
                'setting_id' => 'nullable|required_if:type,SECTION|integer',
                'section_id' => 'nullable|required_if:type,CATEGORY,SUB CATEGORY|integer',
                'category_id' => 'nullable|required_if:type,SUB CATEGORY|integer',
                'icon' => 'nullable|string|max:255'
            ]);


            $data = $request->all();
            $type = $request->type;

            switch ($type) {
                case 'SETTING':
                    $addin = AddinSetting::findOrFail($id);
                    break;
                case 'SECTION':
                    $addin = AddinSection::findOrFail($id);
                    break;
                case 'CATEGORY':
                    $addin = AddinCategory::findOrFail($id);
                    break;
                case 'SUB CATEGORY':
                    $addin = AddinSubcategory::findOrFail($id);
                    break;
                default:
                    return response()->json(['message' => 'Invalid type'], 400);
            }

            $addin->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Addin berhasil diperbarui.',
                'data' => $addin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($type, $id)
    {
        try {
            switch ($type) {
                case 'setting':
                    $addin = AddinSetting::findOrFail($id);
                    break;
                case 'section':
                    $addin = AddinSection::findOrFail($id);
                    break;
                case 'category':
                    $addin = AddinCategory::findOrFail($id);
                    break;
                case 'subcategory':
                    $addin = AddinSubcategory::findOrFail($id);
                    break;
                default:
                    return response()->json(['message' => 'Invalid type'], 400);
            }

            $addin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Addin berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
