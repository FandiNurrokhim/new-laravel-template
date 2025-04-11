<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Section;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                if ($request->has('get_stats')) {
                    // Khusus untuk permintaan statistik
                    $stats = [
                        'totalCategoryPublished' => Category::where('status', 'PUBLISHED')->count(),
                        'totalCategoryDraft' => Category::where('status', 'DRAFT')->count(),
                    ];
                    
                    return response()->json(['success' => true, 'stats' => $stats]);
                }
                $categories = Category::select('*');
                return DataTables::of($categories)
                    ->addIndexColumn()
                    ->addColumn('action', function ($category) {
                        $btn = '<button class="btn btn-info btn-sm me-1 btn-detail"
                                     data-id="' . $category->id . '">
                                     <i class="bx bx-bullseye"></i>
                                 </button>';
                        $btn .= '<button class="btn btn-warning btn-sm me-1 btn-edit"
                                    data-id="' . $category->id . '"
                                    data-title="' . $category->title . '"
                                    data-slug="' . $category->slug . '"
                                    data-description=\'' . htmlspecialchars($category->description, ENT_QUOTES) . '\'
                                    data-thumbnail="' . $category->thumbnail . '"
                                    data-order-number="' . $category->order_number . '"
                                    data-section-id="' . $category->section_id . '"
                                    data-status="' . $category->status . '">
                                        <i class="bx bx-edit"></i>
                                    </button>';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete"
                                         data-id="' . $category->id . '">
                                            <i class="bx bx-trash"></i>
                                         </button>';
                        return $btn;
                    })
                    ->addColumn('section', function ($category) {
                        return $category->section->title ?? 'N/A';
                    })
                    ->editColumn('status', function ($row) {
                        $status = $row['status'];
                        $statusColors = [
                            'PUBLISHED' => 'success',
                            'DRAFT' => 'info',
                            'PENDING' => 'warning',
                            'INACTIVE' => 'secondary',
                            'ARCHIVED' => 'secondary',
                            'DELETED' => 'dark',
                        ];
                        $labels = [
                            'PUBLISHED' => 'Published',
                            'DRAFT' => 'Draft',
                            'PENDING' => 'Pending',
                            'INACTIVE' => 'Inactive',
                            'ARCHIVED' => 'Archived',
                            'DELETED' => 'Deleted',
                        ];
                        $color = $statusColors[$status] ?? 'secondary';
                        $label = $labels[$status] ?? 'Pending';
                        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            
            $categories = Category::all();
            $totalCategoryPublished = Category::where('status', 'PUBLISHED')->count();
            $totalCategoryDraft = Category::where('status', 'DRAFT')->count();
            $sections = Section::where('status', 'PUBLISHED')->get();
            
            return view('dashboard.categories.index', compact(
                'categories',
                'totalCategoryPublished',
                'totalCategoryDraft',
                'sections'
            ));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::where('status', 'PUBLISHED')->get();
        return view('dashboard.categories.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'section_id' => 'required|exists:sections,id',
                'title' => 'required|min:3|unique:categories,title',
                'slug' => 'required',
                'description' => 'nullable',
                'thumbnail' => 'nullable',
                'order_number' => 'required|numeric',
                'status' => 'required',
            ]);
            
            $category = Category::create([
                'section_id' => $validated['section_id'],
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'order_number' => $validated['order_number'],
                'status' => $validated['status'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Category added successfully.',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = Category::with('section')->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Category details fetched successfully.',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $sections = Section::where('status', 'PUBLISHED')->get();
        return view('dashboard.categories.edit', compact('category', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'section_id' => 'required|exists:sections,id',
                'title' => 'required|min:3|unique:categories,title,' . $category->id,
                'slug' => 'required|unique:categories,slug,' . $category->id,
                'description' => 'nullable',
                'thumbnail' => 'nullable',
                'order_number' => 'required|numeric',
                'status' => 'required',
            ]);

            // Cek apakah order_number yang baru sudah digunakan oleh category lain
            $newOrderNumber = $validated['order_number'];
            $oldOrderNumber = $category->order_number;
            $swappedWithCategory = null;
            
            // Jika order_number berubah dan sudah digunakan oleh category lain dalam section yang sama
            if ($newOrderNumber != $oldOrderNumber) {
                // Cari category lain dengan section_id yang sama dan order_number yang sama
                $existingCategory = Category::where('order_number', $newOrderNumber)
                                        ->where('section_id', $validated['section_id'])
                                        ->where('id', '!=', $category->id)
                                        ->first();
                
                if ($existingCategory) {
                    // Tukar order_number dengan category yang sudah menggunakan nomor tersebut
                    $existingCategory->update([
                        'order_number' => $oldOrderNumber
                    ]);
                    
                    // Simpan category yang diswap untuk ditampilkan dalam pesan
                    $swappedWithCategory = $existingCategory;
                }
            }

            $category->update([
                'section_id' => $validated['section_id'],
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'order_number' => $newOrderNumber,
                'status' => $validated['status'],
            ]);

            $message = 'Category updated successfully.';
            
            // Tambahkan informasi swap ke pesan jika terjadi
            if ($swappedWithCategory) {
                $message .= ' Order number has been swapped with "' . $swappedWithCategory->title . '".';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'category' => $category,
                'swapped_with' => $swappedWithCategory ? [
                    'id' => $swappedWithCategory->id,
                    'title' => $swappedWithCategory->title,
                    'old_order' => $newOrderNumber,
                    'new_order' => $oldOrderNumber
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the next available order number.
     */
    public function getNextOrderNumber(Request $request)
    {
        try {
            // Jika ada section_id, ambil max order_number berdasarkan section_id tersebut
            $sectionId = $request->query('section_id');
            
            if ($sectionId) {
                $maxOrderNumber = Category::where('section_id', $sectionId)->max('order_number');
            } else {
                $maxOrderNumber = Category::max('order_number');
            }
            
            return response()->json([
                'success' => true,
                'next_order_number' => $maxOrderNumber ? $maxOrderNumber + 1 : 1
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}