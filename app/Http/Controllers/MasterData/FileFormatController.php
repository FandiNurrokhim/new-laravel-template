<?php

namespace App\Http\Controllers\MasterData;

use App\Models\FileFormat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FileFormatController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = FileFormat::with('createdBy', 'updatedBy')
                    ->orderByRaw('COALESCE(updated_at, created_at) DESC')
                    ->get()
                    ->map(fn($item) => [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'thumbnail' => $item->thumbnail,
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
                    ->addColumn('actions', function ($row) {
                        $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $row['id'] . '" data-title="' . $row['title'] . '" data-description="' . $row['description'] . '" data-thumbnail="' . $row['thumbnail'] . '" data-status="' . $row['status'] . '">Edit</button> ';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $row['id'] . '">Delete</button>';
                        return $btn;
                    })
                    ->rawColumns(['actions', 'status'])
                    ->make(true);
            }

            $statusCounts = FileFormat::select('status')
                ->get()
                ->groupBy('status')
                ->map(function ($items, $status) {
                    return [
                        'name' => $status,
                        'count' => $items->count(),
                    ];
                })
                ->values();

            return view('dashboard.master-data.file-formats.index', [
                'statusCounts' => $statusCounts
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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|string',
                'status' => 'required|in:DRAFT,PENDING,PUBLISHED,INACTIVE,ARCHIVED,DELETED',
            ]);

            FileFormat::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'status' => $validated['status'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File format created successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(FileFormat $fileFormat)
    {
        try {
            return response()->json(
                $fileFormat,
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, FileFormat $fileFormat)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|string',
                'status' => 'required|in:DRAFT,PENDING,PUBLISHED,INACTIVE,ARCHIVED,DELETED',
            ]);

            $fileFormat->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'status' => $validated['status'],
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File format updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(FileFormat $fileFormat)
    {
        DB::beginTransaction();
        try {
            $isUsed = $fileFormat->items()->exists();

            if ($isUsed) {
                $fileFormat->status = 'DELETED';
                $fileFormat->save();
                DB::commit();

                return response()->json(['success' => true, 'message' => 'File Format status updated to DELETED.']);
            }

            $fileFormat->delete();

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
}
