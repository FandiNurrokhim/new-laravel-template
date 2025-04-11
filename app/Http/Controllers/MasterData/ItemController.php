<?php

namespace App\Http\Controllers\MasterData;

use App\Models\Item;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\FileFormat;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ItemSubCategory;
use App\Models\Models\ItemProduct;
use App\Models\Models\ItemSection;
use Illuminate\Support\Facades\DB;
use App\Models\Models\ItemCategory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::with('fileFormat', 'products', 'sections', 'categories', 'subCategories', 'createdBy', 'updatedBy')
                ->orderBy('created_at', 'desc')
                ->get();

            return datatables()->of($data)
                ->addColumn('actions', function ($row) {
                    return '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $row->id . '">Edit</button>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '">Delete</button>';
                })
                ->addColumn('tag', function ($row) {
                    if (!$row->tag) return '-';
                    $tags = json_decode($row->tag, true);

                    if (!is_array($tags)) return '-';
                    $html = '';
                    foreach ($tags as $tag) {
                        $html .= '<span class="badge bg-secondary me-1">' . htmlspecialchars($tag) . '</span>';
                    }
                    return $html;
                })
                ->editColumn('file_format', function ($row) {
                    return $row->fileFormat ? $row->fileFormat->title : '-';
                })
                ->editColumn('type', function ($row) {
                    $data = $row->type;
                    if ($data === 'PREMIUM') {
                        return '<span class="badge bg-warning text-dark">Premium</span>';
                    }
                    return '<span class="badge bg-success">Free</span>';
                })
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
                ->addColumn('products', function ($row) {
                    return $row->products->pluck('title')->map(function ($title) {
                        return '<span class="badge bg-info text-dark me-1">' . htmlspecialchars($title) . '</span>';
                    })->implode(' ');
                })
                ->addColumn('sections', function ($row) {
                    return $row->sections->pluck('title')->map(function ($title) {
                        return '<span class="badge bg-primary me-1">' . htmlspecialchars($title) . '</span>';
                    })->implode(' ');
                })
                ->addColumn('categories', function ($row) {
                    return $row->categories->pluck('title')->map(function ($title) {
                        return '<span class="badge bg-dark me-1">' . htmlspecialchars($title) . '</span>';
                    })->implode(' ');
                })
                ->addColumn('sub_categories', function ($row) {
                    return $row->subCategories->pluck('title')->map(function ($title) {
                        return '<span class="badge bg-secondary me-1">' . htmlspecialchars($title) . '</span>';
                    })->implode(' ');
                })

                ->rawColumns(['actions', 'tag', 'type', 'status', 'products', 'sections', 'categories', 'sub_categories'])
                ->make(true);
        }

        $statusCounts = Item::select('status')
            ->get()
            ->groupBy('status')
            ->map(function ($items, $status) {
                return [
                    'name' => $status,
                    'count' => $items->count(),
                ];
            })
            ->values();

        $fileFormats = FileFormat::where('status', 'PUBLISHED')->get();

        return view('dashboard.master-data.items.index', compact('statusCounts', 'fileFormats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_format_id' => 'required|exists:file_formats,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'tag' => 'nullable',
            'file_name' => 'required|string',
            'type' => 'required|in:FREE,PREMIUM',
            'price' => 'nullable|numeric|min:0',
            'order_number' => 'nullable|integer',
            'status' => 'required|in:DRAFT,PENDING,PUBLISHED,INACTIVE,ARCHIVED,DELETED',
            'products' => 'array',
            'products.*' => 'exists:products,id',
            'sections' => 'array',
            'sections.*' => 'exists:sections,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'sub_categories' => 'array',
            'sub_categories.*' => 'exists:sub_categories,id',
        ]);

        DB::beginTransaction();

        try {
            $tag = collect(json_decode($request->tag, true))->pluck('value')->toArray();
            $slug = $this->ensureUniqueSlug($request->title);

            if ($request->filled('order_number')) {
                $exists = Item::where('order_number', $request->order_number)
                    ->where('file_format_id', $request->file_format_id)
                    ->exists();

                if ($exists) {
                    return back()->withErrors(['order_number' => 'Order number already exists for this file format.']);
                }
            } else {
                $lastOrderNumber = Item::where('file_format_id', $request->file_format_id)
                    ->max('order_number');

                $request->merge(['order_number' => ($lastOrderNumber ?? 0) + 1]);
            }

            $item = Item::create([
                'file_format_id' => $request->file_format_id,
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'slug' => $slug,
                'tag' => json_encode($tag),
                'file_name' => $request->file_name,
                'type' => $request->type,
                'price' => $request->type === 'FREE' ? 0 : $request->price,
                'order_number' => $request->order_number,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $item->products()->sync($request->products ?? []);
            $item->sections()->sync($request->sections ?? []);
            $item->categories()->sync($request->categories ?? []);
            $item->subCategories()->sync($request->sub_categories ?? []);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item created successfully.',
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store item: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Item $item)
    {
        $item->load(['products', 'sections', 'categories', 'subCategories', 'fileFormat']);

        return response()->json([
            'id' => $item->id,
            'title' => $item->title,
            'file_format_id' => $item->file_format_id,
            'file_format' => $item->fileFormat ? $item->fileFormat->title : null,
            'description' => $item->description,
            'thumbnail' => $item->thumbnail,
            'tag' => $item->tag ? json_decode($item->tag, true) : [],
            'file_name' => $item->file_name,
            'type' => $item->type,
            'price' => $item->price,
            'order_number' => $item->order_number,
            'status' => $item->status,
            'products' => $item->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                ];
            }),
            'sections' => $item->sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                ];
            }),
            'categories' => $item->categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'title' => $category->title,
                ];
            }),
            'sub_categories' => $item->subCategories->map(function ($subCategory) {
                return [
                    'id' => $subCategory->id,
                    'title' => $subCategory->title,
                ];
            }),
        ]);
    }
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'file_format_id' => 'required|exists:file_formats,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'tag' => 'nullable',
            'file_name' => 'required|string',
            'type' => 'required|in:FREE,PREMIUM',
            'price' => 'nullable|numeric|min:0',
            'order_number' => 'nullable|integer',
            'status' => 'required|in:DRAFT,PENDING,PUBLISHED,INACTIVE,ARCHIVED,DELETED',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'sections' => 'nullable|array',
            'sections.*' => 'exists:sections,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'sub_categories' => 'nullable|array',
            'sub_categories.*' => 'exists:sub_categories,id',
        ]);

        DB::beginTransaction();

        try {
            $tag = collect(json_decode($request->tag, true))->pluck('value')->toArray();
            $slug = $this->ensureUniqueSlug($request->title, $item->id);

            if ($request->filled('order_number')) {
                $exists = Item::where('order_number', $request->order_number)
                    ->where('file_format_id', $request->file_format_id)
                    ->where('id', '!=', $item->id)
                    ->exists();

                if ($exists) {
                    return back()->withErrors(['order_number' => 'Order number already exists for this file format.']);
                }
            } else {
                $lastOrderNumber = Item::where('file_format_id', $request->file_format_id)
                    ->where('id', '!=', $item->id)
                    ->max('order_number');

                $request->merge(['order_number' => ($lastOrderNumber ?? 0) + 1]);
            }

            $item->update([
                'file_format_id' => $request->file_format_id,
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $request->thumbnail,
                'slug' => $slug,
                'tag' => json_encode($tag),
                'file_name' => $request->file_name,
                'type' => $request->type,
                'price' => $request->type === 'FREE' ? 0 : $request->price,
                'order_number' => $request->order_number,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);

            // Sync relationships
            $item->products()->sync($request->products ?? []);
            $item->sections()->sync($request->sections ?? []);
            $item->categories()->sync($request->categories ?? []);
            $item->subCategories()->sync($request->sub_categories ?? []);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully.',
                'data' => $item
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update item: ' . $e->getMessage(), [
                'exception' => $e,
                'item_id' => $item->id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Item $item)
    {
        DB::beginTransaction();
        try {
            if ($item->isUsed()) {
                $item->status = 'DELETED';
                $item->save();
                DB::commit();

                return response()->json(['success' => true, 'message' => 'File Format status updated to DELETED.']);
            }

            $item->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'File format deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function ensureUniqueSlug($title, $itemId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Item::where('slug', $slug)->where('id', '!=', $itemId)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getProducts()
    {
        $products = Product::where('status', 'PUBLISHED')->get(['id', 'title']);
        return response()->json($products);
    }

    public function getSections(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        $sections = Section::whereIn('product_id', $productIds)
            ->where('status', 'PUBLISHED')
            ->get(['id', 'title']);
        return response()->json($sections);
    }

    public function getCategories(Request $request)
    {
        $sectionIds = $request->input('section_ids', []);
        $categories = Category::whereIn('section_id', $sectionIds)
            ->where('status', 'PUBLISHED')
            ->get(['id', 'title']);
        return response()->json($categories);
    }

    public function getSubCategories(Request $request)
    {
        $categoryIds = $request->input('category_ids', []);
        $subCategories = SubCategory::whereIn('category_id', $categoryIds)
            ->where('status', 'PUBLISHED')
            ->get(['id', 'title']);
        return response()->json($subCategories);
    }
}
