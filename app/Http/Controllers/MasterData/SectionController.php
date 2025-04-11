<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
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
                        'totalSectionPublished' => Section::where('status', 'PUBLISHED')->count(),
                        'totalSectionDraft' => Section::where('status', 'DRAFT')->count(),
                    ];
                    
                    return response()->json(['success' => true, 'stats' => $stats]);
                }
                $sections = Section::select('*');
                return DataTables::of($sections)
                    ->addIndexColumn()
                    ->addColumn('action', function ($section) {
                        $btn = '<button class="btn btn-info btn-sm me-1 btn-detail"
                                     data-id="' . $section->id . '">
                                     <i class="bx bx-bullseye"></i>
                                 </button>';
                        $btn .= '<button class="btn btn-warning btn-sm me-1 btn-edit"
                                    data-id="' . $section->id . '"
                                    data-title="' . $section->title . '"
                                    data-slug="' . $section->slug . '"
                                    data-description=\'' . htmlspecialchars($section->description, ENT_QUOTES) . '\'
                                    data-thumbnail="' . $section->thumbnail . '"
                                    data-order-number="' . $section->order_number . '"
                                    data-product-id="' . $section->product_id . '"
                                    data-status="' . $section->status . '">
                                        <i class="bx bx-edit"></i>
                                    </button>';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete"
                                         data-id="' . $section->id . '">
                                            <i class="bx bx-trash"></i>
                                         </button>';
                        return $btn;
                    })
                    ->addColumn('product', function ($section) {
                        return $section->product->title ?? 'N/A';
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
            
            $sections = Section::all();
            $totalSectionPublished = Section::where('status', 'PUBLISHED')->count();
            $totalSectionDraft = Section::where('status', 'DRAFT')->count();
            $products = Product::where('status', 'PUBLISHED')->get();
            
            return view('dashboard.sections.index', compact(
                'sections',
                'totalSectionPublished',
                'totalSectionDraft',
                'products'
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
        $products = Product::where('status', 'PUBLISHED')->get();
        return view('dashboard.sections.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'title' => 'required|min:3|unique:sections,title',
                'slug' => 'required',
                'description' => 'nullable',
                'thumbnail' => 'nullable',
                'order_number' => 'required|numeric',
                'status' => 'required',
            ]);
            
            $section = Section::create([
                'product_id' => $validated['product_id'],
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'order_number' => $validated['order_number'],
                'status' => $validated['status'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Section added successfully.',
                'section' => $section
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
            $section = Section::with('product')->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Section details fetched successfully.',
                'section' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $products = Product::where('status', 'PUBLISHED')->get();
        return view('dashboard.sections.edit', compact('section', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'title' => 'required|min:3|unique:sections,title,' . $section->id,
                'slug' => 'required|unique:sections,slug,' . $section->id,
                'description' => 'nullable',
                'thumbnail' => 'nullable',
                'order_number' => 'required|numeric',
                'status' => 'required',
            ]);

            // Cek apakah order_number yang baru sudah digunakan oleh section lain
            $newOrderNumber = $validated['order_number'];
            $oldOrderNumber = $section->order_number;
            $swappedWithSection = null;
            
            // Jika order_number berubah dan sudah digunakan oleh section lain dalam product yang sama
            if ($newOrderNumber != $oldOrderNumber) {
                // Cari section lain dengan product_id yang sama dan order_number yang sama
                $existingSection = Section::where('order_number', $newOrderNumber)
                                        ->where('product_id', $validated['product_id'])
                                        ->where('id', '!=', $section->id)
                                        ->first();
                
                if ($existingSection) {
                    // Tukar order_number dengan section yang sudah menggunakan nomor tersebut
                    $existingSection->update([
                        'order_number' => $oldOrderNumber
                    ]);
                    
                    // Simpan section yang diswap untuk ditampilkan dalam pesan
                    $swappedWithSection = $existingSection;
                }
            }

            $section->update([
                'product_id' => $validated['product_id'],
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'order_number' => $newOrderNumber,
                'status' => $validated['status'],
            ]);

            $message = 'Section updated successfully.';
            
            // Tambahkan informasi swap ke pesan jika terjadi
            if ($swappedWithSection) {
                $message .= ' Order number has been swapped with "' . $swappedWithSection->title . '".';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'section' => $section,
                'swapped_with' => $swappedWithSection ? [
                    'id' => $swappedWithSection->id,
                    'title' => $swappedWithSection->title,
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
    public function destroy(Section $section)
    {
        try {
            // Cek apakah section digunakan dalam relasi dengan category
            $isUsed = $section->categories()->exists();

            if ($isUsed) {
                // Jika digunakan dalam relasi, update status menjadi DELETED
                $section->update([
                    'status' => 'DELETED'
                ]);
                return response()->json([
                    'success' => true, 
                    'message' => 'Section status changed to DELETED.'
                ]);
            } else {
                // Jika tidak digunakan dalam relasi, hapus permanen
                $section->delete();
                return response()->json([
                    'success' => true, 
                    'message' => 'Section permanently deleted.'
                ]);
            }
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
            // Jika ada product_id, ambil max order_number berdasarkan product_id tersebut
            $productId = $request->query('product_id');
            
            if ($productId) {
                $maxOrderNumber = Section::where('product_id', $productId)->max('order_number');
            } else {
                $maxOrderNumber = Section::max('order_number');
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