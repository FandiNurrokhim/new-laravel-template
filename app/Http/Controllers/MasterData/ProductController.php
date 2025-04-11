<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
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
                        // Untuk Product
                        'totalProductPublished' => Product::where('status', 'PUBLISHED')->count(),
                        'totalProductPending' => Product::where('status', 'PENDING')->count(),
                        'totalProductInactive' => Product::where('status', 'INACTIVE')->count(),
                        'totalProductPublic' => Product::where('type', 'PUBLIC')->count(),
                        'totalProductPrivate' => Product::where('type', 'PRIVATE')->count(),
                    ];
                    
                    return response()->json(['success' => true, 'stats' => $stats]);
                }
                $products = Product::select('*')->orderBy('order_number', 'asc');
                return DataTables::of($products)
                    ->addIndexColumn()
                    ->addColumn('action', function ($product) {
                        $btn = '<button class="btn btn-info btn-sm me-1 btn-detail"
                                     data-id="' . $product->id . '">
                                     <i class="bx bx-bullseye"></i>
                                 </button>';
                        $btn .= '<button class="btn btn-warning btn-sm me-1 btn-edit"
                                    data-id="' . $product->id . '"
                                    data-title="' . $product->title . '"
                                    data-title-folder="' . $product->title_folder . '"
                                    data-slug="' . $product->slug . '"
                                    data-description=\'' . htmlspecialchars($product->description, ENT_QUOTES) . '\'
                                    data-thumbnail="' . $product->thumbnail . '"
                                    data-order-number="' . $product->order_number . '"
                                    data-type="' . $product->type . '"
                                    data-status="' . $product->status . '">
                                        <i class="bx bx-edit"></i>
                                    </button>';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete"
                                         data-id="' . $product->id . '">
                                            <i class="bx bx-trash"></i>
                                         </button>';
                        return $btn;
                    })->editColumn('type', function ($row) {
                        $type = $row['type'];
                        $typeColors = [
                            'PUBLIC' => 'success',
                            'PRIVATE' => 'warning'
                        ];
                        $labels = [
                            'PUBLIC' => 'Public',
                            'PRIVATE' => 'Private'
                        ];
                        $color = $typeColors[$type] ?? 'secondary';
                        $label = $labels[$type] ?? 'Pending';
                        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
                    })->editColumn('status', function ($row) {
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
                    })->rawColumns(['action','status','type'])->make(true);            
            }
            $products = Product::all();
            $totalProductPending = Product::where('status','PENDING')->count();
            $totalProductPublished = Product::where('status','PUBLISHED')->count();
            $totalProductInactive = Product::where('status','INACTIVE')->count();
            $totalProductPublic = Product::where('type','PUBLIC')->count();
            $totalProductPrivate = Product::where('type','PRIVATE')->count();
            return view('dashboard.products.index',compact('products','totalProductPending','totalProductPublished','totalProductInactive','totalProductPrivate','totalProductPublic'));            
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
        return view('dashboard.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|min:3|unique:products,title',
                'title_folder' => 'required|min:3|unique:products,title_folder',
                'slug' => 'required|unique:products,slug',
                'description' => 'nullable',
                'thumbnail' => 'nullable',
                'order_number' => 'required|numeric|unique:products,order_number',
                'type' => 'required',
                'status' => 'required',
            ]);
            
            $product = Product::create([
                'title' => $validated['title'],
                'title_folder' => $validated['title_folder'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'order_number' => $validated['order_number'],
                'type' => $validated['type'],
                'status' => $validated['status'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Product added successfully.',
                'product' => $product
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
            $product = Product::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Product details fetched successfully.',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|min:3|unique:products,title,' . $product->id,
                'title_folder' => 'required|min:3|unique:products,title_folder,' . $product->id,
                'slug' => 'required|unique:products,slug,' . $product->id,
                'description' => 'nullable',
                'thumbnail' => 'nullable',
                'order_number' => 'required|numeric',
                'type' => 'required',
                'status' => 'required',
            ]);

            // Cek apakah order_number yang baru sudah digunakan oleh product lain
            $newOrderNumber = $validated['order_number'];
            $oldOrderNumber = $product->order_number;
            $swappedWithProduct = null;
            
            // Jika order_number berubah dan sudah digunakan oleh product lain
            if ($newOrderNumber != $oldOrderNumber) {
                $existingProduct = Product::where('order_number', $newOrderNumber)
                                        ->where('id', '!=', $product->id)
                                        ->first();
                
                if ($existingProduct) {
                    // Tukar order_number dengan product yang sudah menggunakan nomor tersebut
                    $existingProduct->update([
                        'order_number' => $oldOrderNumber
                    ]);
                    
                    // Simpan product yang diswap untuk ditampilkan dalam pesan
                    $swappedWithProduct = $existingProduct;
                }
            }

            $product->update([
                'title' => $validated['title'],
                'title_folder' => $validated['title_folder'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'order_number' => $newOrderNumber,
                'type' => $validated['type'],
                'status' => $validated['status'],
            ]);

            $message = 'Product updated successfully.';
            
            // Tambahkan informasi swap ke pesan jika terjadi
            if ($swappedWithProduct) {
                $message .= ' Order number has been swapped with "' . $swappedWithProduct->title . '".';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'product' => $product,
                'swapped_with' => $swappedWithProduct ? [
                    'id' => $swappedWithProduct->id,
                    'title' => $swappedWithProduct->title,
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
    public function destroy(Product $product)
    {
        try {
            // Cek apakah product digunakan dalam relasi dengan section
            $isUsed = $product->sections()->exists();

            if ($isUsed) {
                // Jika digunakan dalam relasi, update status menjadi DELETED
                $product->update([
                    'status' => 'DELETED'
                ]);
                return response()->json([
                    'success' => true, 
                    'message' => 'Product status changed to DELETED.'
                ]);
            } else {
                // Jika tidak digunakan dalam relasi, hapus permanen
                $product->delete();
                return response()->json([
                    'success' => true, 
                    'message' => 'Product permanently deleted.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the next available order number.
     */
    public function getNextOrderNumber()
    {
        try {
            $maxOrderNumber = Product::max('order_number');
            return response()->json([
                'success' => true,
                'next_order_number' => $maxOrderNumber ? $maxOrderNumber + 1 : 1
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}