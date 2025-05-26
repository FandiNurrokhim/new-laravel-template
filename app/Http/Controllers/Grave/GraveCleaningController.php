<?php

namespace App\Http\Controllers\Grave;

use Illuminate\Http\Request;
use App\Models\GraveLocation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\GraveCleaningRequest;

class GraveCleaningController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = GraveCleaningRequest::get();

                return DataTables::of($data)
                    ->addColumn('payment_status', fn($row) => $this->formatPaymentStatus($row))
                    ->addColumn('work_status', fn($row) => $this->formatWorkStatus($row))
                    ->addColumn('grave_detail', fn($row) => $this->formatGraveDetail($row))
                    ->addColumn('proof_photo', fn($row) => $this->seeProofPhoto($row))
                    ->addColumn('actions', fn($row) => $this->generateActions($row))
                    ->rawColumns(['payment_status', 'work_status', 'grave_detail', 'proof_photo', 'actions'])
                    ->make(true);
            }

            return view('dashboard.grave-management.grave-cleaning.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }


    private function formatPaymentStatus($row)
    {
        $map = [
            'unpaid' => ['label' => 'Belum Dibayar', 'class' => 'bg-danger'],
            'pending' => ['label' => 'Menunggu', 'class' => 'bg-warning'],
            'paid' => ['label' => 'Lunas', 'class' => 'bg-success'],
        ];

        $status = $map[$row->payment_status] ?? ['label' => ucfirst($row->payment_status), 'class' => 'bg-secondary'];
        return '<span class="badge ' . $status['class'] . '">' . $status['label'] . '</span>';
    }

    private function formatWorkStatus($row)
    {
        $map = [
            'pending' => ['label' => 'Belum Dikerjakan', 'class' => 'bg-warning'],
            'in_progress' => ['label' => 'Proses', 'class' => 'bg-primary'],
            'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-danger'],
        ];

        $status = $map[$row->work_status] ?? ['label' => ucfirst($row->work_status), 'class' => 'bg-secondary'];
        return '<span class="badge ' . $status['class'] . '">' . $status['label'] . '</span>';
    }

    private function formatGraveDetail($row)
    {
        $location = $row->location;
        $corpse = $location?->corpseDetail;
        $group = $location?->group;
    
        $photo = $corpse?->photo ? asset($corpse->photo) : asset('img/default-photo.png');
        $name = $corpse?->name ?? '-';
        $birthDate = $corpse?->birth_date ?? '-';
        $birthPlace = $corpse?->birth_place ?? '-';
        $age = $corpse?->age ?? '-';
        $deathDate = $corpse?->death_date ?? '-';
        $weton = $corpse?->javanese_weton ?? '-';
    
        return '
        <div class="w-full mb-3">
            <div class="row g-0 align-items-center">
                <div class="col-auto">
                    <img src="' . $photo . '" alt="Foto" class="img-fluid rounded" width="80" height="80">
                </div>
                <div class="col">
                    <div class="card-body py-2">
                        <h5 class="card-title mb-1">' . $name . '</h5>
                        <div class="small text-muted">TTL: ' . $birthPlace . ', ' . $birthDate . '</div>
                        <div class="small text-muted">Umur: ' . $age . ' | Weton: ' . $weton . '</div>
                        <div class="small text-muted">Tanggal Wafat: ' . $deathDate . '</div>
                    </div>
                </div>
            </div>
        </div>
        ';
    }

    private function seeProofPhoto($row)
    {
        if ($row->proof_photo) {
            $imgId = 'proofModal' . $row->id;

            return '
            <a href="#" data-bs-toggle="modal" data-bs-target="#' . $imgId . '">
                <img src="' . asset($row->proof_photo) . '" alt="Bukti Pembersihan" class="img-thumbnail" style="max-width: 100px;">
            </a>

            <div class="modal fade" id="' . $imgId . '" tabindex="-1" aria-labelledby="' . $imgId . 'Label" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="' . $imgId . 'Label">Bukti Pembersihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <img src="' . asset($row->proof_photo) . '" alt="Bukti Pembersihan" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
        ';
        }

        return '-';
    }

    private function generateActions($row)
    {
        $actions = 'Tidak ada aksi';

        // Jika sudah selesai dan lunas, tidak ada action
        if ($row->payment_status === 'paid' && $row->work_status === 'completed') {
            return '';
        }

        // Jika belum dibayar, atau payment_status pending dan work_status pending, tampilkan dropdown aksi
        if (
            $row->payment_status === 'unpaid' ||
            ($row->payment_status === 'pending' && $row->work_status === 'in_progress' || $row->work_status === 'pending')
        ) {
            $actions .= '
            <div class="dropdown">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih Aksi
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item btn-confirm-payment" href="#" data-id="' . $row->id . '">
                            Konfirmasi Pembayaran
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item btn-cancel-request" href="#" data-id="' . $row->id . '">
                            Batalkan Permintaan
                        </a>
                    </li>
                </ul>
            </div>
            ';
        }
        // Jika sudah dibayar, tampilkan upload bukti jika belum selesai
        elseif ($row->payment_status === 'paid' && in_array($row->work_status, ['pending', 'in_progress'])) {
            if ($row->work_status === 'pending') {
                $row->work_status = 'in_progress';
                $row->save();
            }
            $actions .= '
            <button class="btn btn-primary btn-sm btn-upload-photo" data-id="' . $row->id . '">
                Upload Bukti Selesai
            </button>
            ';
        }

        return $actions;
    }


    public function update(Request $request, $id)
    {
        try {
            $GraveCleaningRequest = GraveCleaningRequest::findOrFail($id);
            $GraveCleaningRequest->update(['payment_status' => 'paid', 'work_status' => 'in_progress']);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran Telah Dikonfirmasi',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function uploadProof(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'proof_photo' => 'required|image|max:2048',
            ]);

            $cleaning = GraveCleaningRequest::findOrFail($id);

            if ($cleaning->payment_status !== 'paid') {
                return response()->json(['success' => false, 'message' => 'Pembayaran belum dikonfirmasi.'], 400);
            }

            // Simpan foto
            $path = $this->uploadFile(
                'uploads/cleaning-proof',
                $request->file('proof_photo'),
                $request->file('proof_photo')->getClientOriginalName()
            );

            $cleaning->proof_photo = $path;
            $cleaning->work_status = 'completed';
            $cleaning->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Bukti selesai berhasil diupload.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah bukti: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $cleaning = GraveCleaningRequest::findOrFail($id);
            $cleaning->delete();

            return response()->json(['success' => true, 'message' => 'Permintaan pembersihan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
