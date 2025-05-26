<?php

namespace App\Http\Controllers\Grave;

use App\Models\GraveCleaningRequest;
use App\Models\GraveRequest;
use Illuminate\Http\Request;
use App\Models\GraveLocation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GraveRequestController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = GraveRequest::get();

                return DataTables::of($data)
                    ->editColumn('status', function ($row) {
                        $statusMap = [
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        ];

                        $label = $statusMap[$row->status] ?? ucfirst($row->status);
                        $badgeClass = match ($row->status) {
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'rejected' => 'bg-danger',
                            default => 'bg-secondary',
                        };

                        return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
                    })
                    ->addColumn('location_view', function ($row) {
                        return '<button class="btn btn-info btn-sm btn-view-location" data-id="' . $row->location->group->id . '" data-location-id="' . $row->grave_location_id . '">Lihat Lokasi</button>';
                    })
                    ->addColumn('actions', function ($row) {
                        $dropdown = '
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                                    <li>
                                        <a class="dropdown-item btn-pending" href="#" data-id="' . $row->id . '">Pending</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item btn-approve" href="#" data-id="' . $row->id . '">Setujui</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item btn-reject" href="#" data-id="' . $row->id . '">Tolak</a>
                                    </li>
                                </ul>
                            </div>
                        ';
                        return $dropdown;
                    })
                    ->rawColumns(['actions', 'status', 'location_view'])
                    ->make(true);
            }

            return view('dashboard.grave-management.request-location.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function getGraveLocations($groupId)
    {
        try {
            $locations = GraveLocation::where('grave_group_id', $groupId)->with('corpseDetail')->get();

            return response()->json([
                'success' => true,
                'data' => $locations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat lokasi.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'requester_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone_number' => 'nullable',
                'rt' => 'nullable|string|max:10',
                'rw' => 'nullable|string|max:10',
                'dusun' => 'nullable|string|max:255',
                'corpse_name' => 'required|string|max:255',
                'grave_location_id' => 'required|exists:grave_locations,id',
                'notes' => 'nullable|string',
            ]);

            $validated['status'] = 'pending';

            GraveRequest::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan makam berhasil dibuat dengan status pending.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeCleaningRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'requester_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone_number' => 'nullable',
                'rt' => 'nullable|string|max:10',
                'rw' => 'nullable|string|max:10',
                'dusun' => 'nullable|string|max:255',
                'grave_location_id' => 'required|exists:grave_locations,id',
            ]);

            $exists = GraveCleaningRequest::where('grave_location_id', $validated['grave_location_id'])
                ->where('work_status', '!=', 'completed') // boleh disesuaikan
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan pembersihan untuk lokasi ini sudah terdaftar dan belum diselesaikan.',
                ], 409); 
            }

            GraveCleaningRequest::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan pembersihan berhasil, silahkan lanjutkan pembayaran ke WA.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,rejected',
            ]);

            $graveRequest = GraveRequest::findOrFail($id);
            $graveRequest->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui menjadi ' . ucfirst($validated['status']) . '.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
