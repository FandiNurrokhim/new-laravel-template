<?php

namespace App\Http\Controllers\Grave;

use App\Models\GraveGroup;
use Illuminate\Http\Request;
use App\Models\GraveLocation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GraveGroupController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = GraveGroup::get();

                return DataTables::of($data)
                    ->editColumn('is_full', function ($row) {
                        $label = $row->is_full ? 'Full' : 'Tersedia';
                        $badgeClass = $row->is_full ? 'bg-danger' : 'bg-success';
                        return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
                    })
                    ->addColumn('actions', function ($row) {
                        $btn = '<button class="btn btn-info btn-sm me-1 btn-detail" data-id="' . $row->id . '">
                                <i class="bx bx-bullseye"></i>
                            </button>';
                        $btn .= '<button class="btn btn-warning btn-sm me-1 btn-edit" data-id="' . $row->id . '">
                                <i class="bx bx-edit"></i>
                            </button>';
                        $btn .= '<button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '">
                                <i class="bx bx-trash"></i>
                            </button>';
                        return $btn;
                    })
                    ->addColumn('used_graves', function ($row) {
                        return $row->used_graves . ' / ' . $row->max_graves;
                    })
                    ->addColumn('unused_graves', function ($row) {
                        return $row->unused_graves . ' / ' . $row->max_graves;
                    })
                    ->rawColumns(['actions', 'is_full', 'used_graves', 'unused_graves'])
                    ->make(true);
            }

            $usedGraves = GraveLocation::where('is_confirmed', true)->count();
            $unusedGraves = GraveLocation::where('is_confirmed', false)->count();

            return view('dashboard.grave-management.grave-group.index', compact('usedGraves', 'unusedGraves'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $graveGroup = GraveGroup::with('locations.corpseDetail')->findOrFail($id);
        return response()->json($graveGroup);
    }

    public function store(Request $request)
    {
        DB::beginTransaction(); 
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'max_graves' => 'required|integer|min:1|max:500', 
            ]);

            // Create the GraveGroup
            $graveGroup = GraveGroup::create([
                'name' => $validated['name'],
                'max_graves' => $validated['max_graves'],
                'unused_graves' => $validated['max_graves'],
                'used_graves' => 0,
                'is_full' => false,
            ]);

            for ($i = 1; $i <= $validated['max_graves']; $i++) {
                GraveLocation::create([
                    'grave_group_id' => $graveGroup->id,
                    'order' => $i,
                    'is_reserved' => false,
                    'is_confirmed' => false,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kelompok makam dan lokasi berhasil dibuat.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction(); 
        try {
            $graveGroup = GraveGroup::findOrFail($id);
    
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'max_graves' => 'sometimes|required|integer|min:1|max:500',
            ]);
    
            $usedGraves = $this->calculateUsedGraves();
    
            if (isset($validated['max_graves']) && $validated['max_graves'] < $usedGraves) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimum kuburan tidak boleh kurang dari jumlah kuburan yang sudah digunakan.',
                ], 400);
            }
    
            $graveGroup->update(array_merge($validated, [
                'unused_graves' => $this->calculateUnusedGraves($validated['max_graves'] ?? $graveGroup->max_graves),
                'used_graves' => $usedGraves,
                'is_full' => $this->isFull($validated['max_graves'] ?? $graveGroup->max_graves),
            ]));
    
            $currentLocations = $graveGroup->locations()->count();
            $newMaxGraves = $validated['max_graves'] ?? $graveGroup->max_graves;
    
            if ($newMaxGraves > $currentLocations) {
                for ($i = $currentLocations + 1; $i <= $newMaxGraves; $i++) {
                    GraveLocation::create([
                        'grave_group_id' => $graveGroup->id,
                        'order' => $i,
                        'is_reserved' => false,
                        'is_confirmed' => false,
                    ]);
                }
            } elseif ($newMaxGraves < $currentLocations) {
                $graveGroup->locations()
                    ->where('is_confirmed', false)
                    ->orderByDesc('order')
                    ->take($currentLocations - $newMaxGraves)
                    ->delete();
            }
    
            DB::commit(); // Commit the transaction
    
            return response()->json([
                'success' => true,
                'message' => 'Kelompok makam berhasil diperbarui.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $graveGroup = GraveGroup::findOrFail($id);

            $isReferenced = GraveLocation::where('grave_group_id', $graveGroup->id)->where('is_confirmed', true)->exists();

            if ($isReferenced) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat dihapus karena sudah digunakan oleh lokasi makam.',
                ], 400);
            }

            $graveGroup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelompok makam berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function calculateUnusedGraves($maxGraves)
    {
        $unusedGraves = GraveLocation::where('is_confirmed', false)->count();
        return $maxGraves - $unusedGraves >= 0 ? $maxGraves - $unusedGraves : 0;
    }

    private function calculateUsedGraves()
    {
        return GraveLocation::where('is_confirmed', true)->count();
    }

    private function isFull($maxGraves)
    {
        $usedGraves = $this->calculateUsedGraves();
        return $usedGraves >= $maxGraves;
    }
}
