@extends('layouts.home-layout')

@section('content')
    <div class="container mt-4 pt-5">
        <h3 class="text-center mb-4">Pesan Pembersihan Makam</h3>

        <form id="requestCleaningForm" enctype="multipart/form-data" method="POST"
            action="{{ route('create-cleaning-request') }}">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="grave_location_id" class="form-label">Lokasi Makam <span class="text-danger">(wajib
                                Diisi)</span></label>
                        @include('components.landing-page.grave-locations', [
                            'graveLocations' => $graveLocations,
                            'showSearch' => true,
                            'isRequestCleaning' => true,
                        ])
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="grave-profile" class="mb-3"></div>
                    <div class="mb-3">
                        <label for="requester_name" class="form-label">Nama Pemohon <span class="text-danger">(wajib
                                Diisi)</span></label>
                        <input type="text" class="form-control" id="requester_name" name="requester_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="text" class="form-control" id="price" name="price" value="15000" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Pemohon <span class="text-danger">(wajib
                                Diisi)</span></label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon <span class="text-danger">(wajib
                                Diisi)</span></label>
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
                        <label for="dusun" class="form-label">Dusun <span class="text-danger">(wajib
                                Diisi)</span></label>
                        <input type="text" class="form-control" id="dusun" name="dusun" required>
                    </div>
                    <!-- grave_location_id akan diisi otomatis lewat hidden input dari komponen grave-locations -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#requestCleaningForm').on('submit', function(e) {
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
                        $('#requestCleaningForm')[0].reset();
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
