<?php

namespace App\Http\Controllers;

use App\Models\GraveGroup;
use App\Models\CorpseDetail;
use App\Models\GraveLocation;
use App\Models\GraveRequest;
use COM;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    public function index()
    {
        $corpseCount = CorpseDetail::count();
        $graveUsedCount = GraveLocation::where('is_confirmed', true)->count();
        $formRequestCount = GraveRequest::count();
        $formRequestThisMonthCount = GraveRequest::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        return view(
            'landing-page.home',
            compact('corpseCount', 'graveUsedCount', 'formRequestCount', 'formRequestThisMonthCount')
        );
    }

    public function request()
    {
        $graveLocations = GraveGroup::with('locations.corpseDetail')->get();
        return view(
            'landing-page.request',
            compact('graveLocations')
        );
    }

    public function requestCleaning()
    {
        $graveLocations = GraveGroup::with('locations.corpseDetail')->get();
        return view(
            'landing-page.request-cleaning',
            compact('graveLocations')
        );
    }

    public function requestList(Request $request)
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
                    ->rawColumns(['status', 'location_view'])
                    ->make(true);
            }
            $graveLocations = GraveGroup::with([
                'locations.corpseDetail',
                'locations.request' 
            ])->get();

            return view('landing-page.request-list', compact('graveLocations'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function corpseList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = CorpseDetail::with(['location', 'group'])->get();

                return DataTables::of($data)
                    ->addColumn('location_code', function ($row) {
                        $code = $row->location->code ?? 'N/A';
                        return '<span class="badge bg-dark">' . $code . '</span>';
                    })
                    ->addColumn('group_name', function ($row) {
                        $name = $row->group->name ?? 'N/A';
                        return '<span class="badge bg-dark">' . $name . '</span>';
                    })
                    ->addColumn('birth_date', function ($row) {
                        return $row->birth_date ? \Carbon\Carbon::parse($row->birth_date)->format('d-m-Y') : 'N/A';
                    })
                    ->addColumn('death_date', function ($row) {
                        return $row->death_date ? \Carbon\Carbon::parse($row->death_date)->format('d-m-Y') : 'N/A';
                    })
                    ->addColumn('javanese_death_date', function ($row) {
                        if ($row->death_date) {
                            $deathDate = new \DateTime($row->death_date);
                            $day = $row->javanese_day_death;
                            $year = $deathDate->format('Y');
                            $javaneseDay = $row->javanese_weton ?? 'N/A';
                            return $day . ' ' . $javaneseDay . ' ' . $year;
                        }
                        return 'N/A';
                    })
                    ->rawColumns(['location_code', 'group_name', 'birth_date', 'death_date', 'javanese_death_date'])
                    ->make(true);
            }

            return view('landing-page.corpse-list');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function locationList()
    {
        $graveLocations = GraveGroup::with('locations.corpseDetail')->get();
        return view(
            'landing-page.location-list',
            compact('graveLocations')
        );
    }

    public function getRequesterByLocation($locationId)
    {
        $request = GraveRequest::where('grave_location_id', $locationId)->latest()->first();

        if (!$request) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'requester_name' => $request->requester_name,
            'address'        => $request->address,
            'phone_number'   => $request->phone_number,
            'rt'             => $request->rt,
            'rw'             => $request->rw,
            'dusun'          => $request->dusun,
            'corpse_name'    => $request->corpse_name,
            'birth_date'     => $request->birth_date,
            'birth_place'    => $request->birth_place,
            'death_date'     => $request->death_date,
            'javanese_day'   => $request->javanese_day,
            'javanese_weton' => $request->javanese_weton,
            'notes'          => $request->notes,
        ]);
    }
}
