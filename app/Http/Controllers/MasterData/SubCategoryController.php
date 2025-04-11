<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use function PHPSTORM_META\type;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = SubCategory::with('category', 'createdBy', 'updatedBy')
                    ->orderByRaw('COALESCE(updated_at, created_at) DESC')
                    ->get()
                    ->map(fn($item) => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'slug' => $item->slug,
                        'category' => $item->category->title ?? '-',
                        'description' => $item->description,
                        'thumbnail' => $item->thumbnail,
                        'order_number' => $item->order_number,
                        'status' => $item->status,
                        'created_by' => $item->createdBy->name ?? '-',
                        'updated_by' => $item->updatedBy->name ?? '-',
                        'updated_at' => $item->updated_at,
                    ]);

                return DataTables::of($data)
                    ->editColumn('status', function ($row) {
                        $statusColors = [
                            'DRAFT' => 'warning',
                            'PENDING' => 'info',
                            'PUBLISHED' => 'success',
                            'INACTIVE' => 'secondary',
                            'ARCHIVED' => 'dark',
                            'DELETED' => 'danger',
                        ];

                        $labels = [
                            'DRAFT' => 'Draft',
                            'PENDING' => 'Pending',
                            'PUBLISHED' => 'Published',
                            'INACTIVE' => 'Inactive',
                            'ARCHIVED' => 'Archived',
                            'DELETED' => 'Deleted',
                        ];

                        $color = $statusColors[$row['status']] ?? 'secondary';
                        $label = $labels[$row['status']] ?? 'Unknown';

                        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
                    })
                    ->addColumn('title_category', function ($row) {
                        return '<span class="badge bg-primary">' . htmlspecialchars($row['title']) . '</span>';
                    })
                    ->addColumn('actions', function ($row) {
                        $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $row['id'] . '" data-title="' . $row['title'] . '" data-description="' . $row['description'] . '" data-thumbnail="' . $row['thumbnail'] . '" data-status="' . $row['status'] . '">Edit</button> ';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $row['id'] . '">Delete</button>';
                        return $btn;
                    })
                    ->rawColumns(['status', 'title_category', 'actions'])
                    ->make(true);
            }

            $statusCounts = SubCategory::select('status')
                ->get()
                ->groupBy('status')
                ->map(function ($items, $status) {
                    return [
                        'name' => $status,
                        'count' => $items->count(),
                    ];
                })
                ->values();

            $categories = Category::where('status', 'PUBLISHED')
                ->orderBy('order_number')
                ->get();

            return view('dashboard.master-data.sub-categories.index', [
                'statusCounts' => $statusCounts,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'order_number' => 'nullable|integer',
            'status' => 'required|in:DRAFT,PENDING,PUBLISHED,INACTIVE,ARCHIVED,DELETED',
        ]);

        $slug = str_replace(' ', '-', strtolower($request->title));

        if ($request->order_number) {
            $exists = SubCategory::where('order_number', $request->order_number)
                ->where('category_id', $request->category_id)
                ->exists();

            if ($exists) {
                return redirect()->back()->withErrors(['order_number' => 'Order number already exists. Please choose another.']);
            }
        } else {
            $lastOrderNumber = SubCategory::where('category_id', $request->category_id)
                ->max('order_number');
            $request->merge(['order_number' => $lastOrderNumber + 1]);
        }

        SubCategory::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'thumbnail' => $request->thumbnail,
            'order_number' => $request->order_number,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Subcategory created successfully.'], 201);
    }


    public function show(SubCategory $subCategory)
    {
        try {
            return response()->json(
                $subCategory,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'order_number' => 'nullable|integer',
            'status' => 'required|in:DRAFT,PENDING,PUBLISHED,INACTIVE,ARCHIVED,DELETED',
        ]);

        try {
            $subcategory = SubCategory::findOrFail($id);

            $oldOrderNumber = $subcategory->order_number;
            $isNewNumber = $request->order_number != $oldOrderNumber;

            if ($isNewNumber) {
                $exists = SubCategory::where('order_number', $request->order_number)
                    ->where('category_id', $request->category_id)
                    ->where('id', '!=', $subcategory->id)
                    ->exists();

                if ($exists) {
                    return response()->json(['error' => true, 'message' => 'Order number already exists. Please choose another'], 422);
                }
            }

            $subcategory->update([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $this->ensureUniqueSlug($request->title, $subcategory->id),
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'order_number' => $request->order_number,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);
            return response()->json(['success' => true, 'message' => 'Subcategory updated successfully.'], 200);
        
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Subcategory not found.'], 404);
        }
    }

    public function destroy(SubCategory $subCategory)
    {
        DB::beginTransaction();

        try {
            $isUsed = $subCategory->itemSubCategory()->exists();

            if ($isUsed) {
              $subCategory->status = 'DELETED';
                $subCategory->save();
                DB::commit();

                return response()->json(['success' => true, 'message' => 'Subcategory status updated to DELETED.']);
            }

            $subCategory->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Subcategory deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus subcategory: ' . $e->getMessage()
            ], 500);
        }
    }

    private function ensureUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (SubCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
