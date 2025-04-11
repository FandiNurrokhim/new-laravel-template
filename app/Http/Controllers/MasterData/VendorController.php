<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $vendors = Vendor::select('*');
                return DataTables::of($vendors)
                    ->addIndexColumn()
                    ->addColumn('action', function ($vendor) {
                        $btn = '<button class="btn btn-info btn-sm me-1 btn-detail"
                                     data-id="' . $vendor->id . '">
                                     <i class="bx bx-bullseye"></i>
                                 </button>';
                        $btn .= '<button class="btn btn-warning btn-sm me-1 btn-edit"
                                        data-id="' . $vendor->id . '"
                                        data-title="' . $vendor->title . '"
                                        data-description="' . $vendor->description . '"
                                        data-thumbnail="' . $vendor->thumbnail . '"
                                        data-status="' . $vendor->status . '">
                                            <i class="bx bx-edit"></i>
                                        </button>';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete"
                                         data-id="' . $vendor->id . '">
                                            <i class="bx bx-trash"></i>
                                         </button>';
                        return $btn;
                    })
                    ->editColumn('status', function ($row) {
                        $status = $row['status'];

                        $statusColors = [
                            'ACTIVE' => 'success',
                            'PENDING' => 'warning',
                            'BLOCKED' => 'danger',
                            'INACTIVE' => 'secondary',
                            'SUSPENDED' => 'info',
                            'DELETED' => 'dark',
                            'BANNED' => 'danger',
                            'EXPIRED' => 'secondary',
                        ];


                        $labels = [
                            'ACTIVE' => 'Active',
                            'PENDING' => 'Pending',
                            'BLOCKED' => 'Blocked',
                            'INACTIVE' => 'Inactive',
                            'SUSPENDED' => 'Suspended',
                            'DELETED' => 'Deleted',
                            'BANNED' => 'Banned',
                            'EXPIRED' => 'Verification Expired',
                        ];

                        $color = $statusColors[$status] ?? 'secondary';
                        $label = $labels[$status] ?? 'Pending';

                        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }

            $vendors = Vendor::all();
            $totalVendorActive = Vendor::where('status', 'active')->count();
            $totalVendorInactive = Vendor::where('status', 'inactive')->count();
            return view('dashboard.vendors.index', compact('vendors', 'totalVendorActive', 'totalVendorInactive'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('dashboard.vendors.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|min:3|unique:vendors,title',
                'description' => 'nullable',
                'thumbnail' => 'required',
                'status' => 'required',
            ]);

            $vendor = Vendor::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vendor added successfully.',
                'vendor' => $vendor
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit(Vendor $vendor)
    {
        return view('dashboard.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|min:3|unique:vendors,title,' . $vendor->id,
                'description' => 'nullable',
                'thumbnail' => 'required',
                'status' => 'required',
            ]);

            $vendor->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'thumbnail' => $validated['thumbnail'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vendor updated successfully.',
                'vendor' => $vendor
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Vendor $vendor)
    {
        try {
            $vendor->delete();
            return response()->json(['success' => true, 'message' => 'Vendor deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Vendor details fetched successfully.',
                'vendor' => $vendor
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
