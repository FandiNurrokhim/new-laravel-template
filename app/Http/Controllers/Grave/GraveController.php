<?php

namespace App\Http\Controllers\Grave;

use App\Models\CorpseDetail;
use Illuminate\Http\Request;
use App\Models\GraveLocation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\GraveGroup;

class GraveController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = CorpseDetail::with(['location', 'group'])->get();

                return DataTables::of($data)
                    ->addColumn('location_code', function ($row) {
                        return $row->location->code ?? 'N/A';
                    })
                    ->addColumn('group_name', function ($row) {
                        return $row->location->group->name ?? 'N/A';
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
                    ->rawColumns(['actions', 'location_code', 'group_name'])
                    ->make(true);
            }

            $graveGroups = GraveGroup::where('is_full', false)->get();

            return view('dashboard.grave-management.corpse-detail.index', compact('graveGroups'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'grave_location_id' => 'required|exists:grave_locations,id',
                'name' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'birth_place' => 'nullable|string|max:255',
                'death_date' => 'nullable|date',
                'javanese_day' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk gambar
            ]);

            // Simpan gambar jika ada
            $validated['photo'] = $this->storeImage($request->file('photo'));

            // Check if the location is already confirmed
            $graveLocation = GraveLocation::findOrFail($validated['grave_location_id']);
            if ($graveLocation->is_confirmed === true) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected location is already occupied.',
                ], 400);
            }

            $age = $validated['birth_date'] ? now()->diffInYears($validated['birth_date']) : null;
            $validated['age'] = $age;

            // Create the CorpseDetail
            $corpseDetail = CorpseDetail::create($validated);

            // Update the GraveLocation
            $graveLocation->update(['is_confirmed' => true]);

            // Update the GraveGroup's used_graves
            $graveGroup = $graveLocation->group;
            $graveGroup->increment('used_graves');
            $graveGroup->update(['is_full' => $graveGroup->used_graves === $graveGroup->max_graves]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Corpse detail successfully created.',
                'data' => $corpseDetail,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $corpseDetail = CorpseDetail::with(['location.group'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $corpseDetail,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $corpseDetail = CorpseDetail::findOrFail($id);

            $validated = $request->validate([
                'grave_location_id' => 'required|exists:grave_locations,id',
                'name' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'birth_place' => 'nullable|string|max:255',
                'death_date' => 'nullable|date',
                'javanese_day' => 'nullable|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk gambar
            ]);

            // Perbarui gambar jika ada
            $validated['photo'] = $this->updateImage($request->file('photo'), $corpseDetail->photo);

            // Check if the grave location is changing
            if ($corpseDetail->grave_location_id !== $validated['grave_location_id']) {
                $newLocation = GraveLocation::findOrFail($validated['grave_location_id']);
                if ($newLocation->is_confirmed) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected new location is already occupied.',
                    ], 400);
                }

                // Update the old location
                $oldLocation = $corpseDetail->location;
                $oldLocation->update(['is_confirmed' => false]);

                // Update the new location
                $newLocation->update(['is_confirmed' => true]);

                // Update the GraveGroup's used_graves
                $oldGroup = $oldLocation->group;
                $oldGroup->decrement('used_graves');
                $oldGroup->update(['is_full' => $oldGroup->used_graves === $oldGroup->max_graves]);

                $newGroup = $newLocation->group;
                $newGroup->increment('used_graves');
                $newGroup->update(['is_full' => $newGroup->used_graves === $newGroup->max_graves]);
            }

            $age = $validated['birth_date'] ? now()->diffInYears($validated['birth_date']) : null;
            $validated['age'] = $age;

            // Update the CorpseDetail
            $corpseDetail->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Corpse detail successfully updated.',
                'data' => $corpseDetail,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $corpseDetail = CorpseDetail::findOrFail($id);

            // Update the GraveLocation
            $graveLocation = $corpseDetail->location;
            $graveLocation->update(['is_confirmed' => false]);

            // Update the GraveGroup's used_graves
            $graveGroup = $graveLocation->group;
            $graveGroup->decrement('used_graves');
            $graveGroup->update(['is_full' => $graveGroup->used_graves === $graveGroup->max_graves]);

            // Delete the CorpseDetail
            $corpseDetail->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Corpse detail successfully deleted.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function fetchLocations($groupId)
    {
        try {
            $locations = GraveLocation::where('grave_group_id', $groupId)
                ->with('corpseDetail') // Include corpse details to check if the location is occupied
                ->get(['id', 'code', 'grave_group_id']);

            return response()->json(['success' => true, 'data' => $locations]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function storeImage($image, $path = 'uploads/corpse-details')
    {
        if ($image) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path($path);

            // Pastikan folder tujuan ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $filename);

            return $path . '/' . $filename;
        }

        return null;
    }

    private function updateImage($newImage, $oldImagePath, $path = 'uploads/corpse-details')
    {
        if ($newImage) {
            // Hapus file lama
            if ($oldImagePath && file_exists(public_path($oldImagePath))) {
                unlink(public_path($oldImagePath));
            }

            // Simpan file baru
            $filename = uniqid() . '.' . $newImage->getClientOriginalExtension();
            $destinationPath = public_path($path);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $newImage->move($destinationPath, $filename);

            return $path . '/' . $filename;
        }

        return $oldImagePath;
    }
}
