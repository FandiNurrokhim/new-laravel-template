@extends('layouts.home-layout')

@section('content')
    <div class="container mt-4">
        <h3 class="text-center mb-4">Request Lokasi Makam</h3>

        <form id="requestLocationForm" enctype="multipart/form-data" method="POST" action="{{ route('create-request') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="grave_location_id" class="form-label">Lokasi Makam <span class="text-danger">(wajib Diisi)</span></label>
                        @include('components.landing-page.grave-locations', [
                            'graveLocations' => $graveLocations,
                        ])
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="requester_name" class="form-label">Nama Pemohon <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="requester_name" name="requester_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Pemohon <span class="text-danger">(wajib Diisi)</span></label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="rt" class="form-label">RT <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="number" class="form-control" id="rt" name="rt" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="rw" class="form-label">RW <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="number" class="form-control" id="rw" name="rw" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="dusun" class="form-label">Dusun <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="dusun" name="dusun"  required>
                    </div>
                    <div class="mb-3">
                        <label for="corpse_name" class="form-label">Nama Mayit <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="corpse_name" name="corpse_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date"
                            max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place">
                    </div>
                    <div class="mb-3">
                        <label for="death_date" class="form-label">Tanggal Meninggal <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="date" class="form-control" id="death_date" name="death_date"
                            max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="javanese_day" class="form-label">Hari Meninggal <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="form-select" id="javanese_day" name="javanese_day" required>
                            <option value="" disabled selected>Pilih Hari</option>
                            <option value="Minggu">Minggu</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="javanese_weton" class="form-label">Weton Meninggal <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="form-select" id="javanese_weton" name="javanese_weton" required>
                            <option value="" disabled selected>Pilih Weton</option>
                            <option value="Legi">Legi</option>
                            <option value="Pahing">Pahing</option>
                            <option value="Pon">Pon</option>
                            <option value="Wage">Wage</option>
                            <option value="Kliwon">Kliwon</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#requestLocationForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah form dikirim secara default

                // Ambil data form
                var formData = new FormData(this);

                // Kirim request AJAX
                $.ajax({
                    url: $(this).attr('action'), // URL dari form action
                    method: $(this).attr('method'), // Method dari form (POST)
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        // Tampilkan loading atau disable tombol submit
                        $('button[type="submit"]').prop('disabled', true).text('Submitting...');
                    },
                    success: function(response) {
                        // Jika berhasil
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Request berhasil dikirim!',
                        });

                        // Reset form
                        $('#requestLocationForm')[0].reset();
                        $('button[type="submit"]').prop('disabled', false).text(
                            'Submit Request');
                    },
                    error: function(xhr) {
                        // Jika gagal
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: errorMessage,
                        });

                        $('button[type="submit"]').prop('disabled', false).text(
                            'Submit Request');
                    },
                });
            });
        });
    </script>
@endpush
