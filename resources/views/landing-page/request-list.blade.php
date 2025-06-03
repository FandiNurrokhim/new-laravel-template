@extends('layouts.home-layout')

@section('title', 'Manajemen Permintaan Lokasi Makam')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="container mt-4 pt-5">
            <h3 class="text-center mb-4">Daftar Permintaan lokasi</h3>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="grave_location_id" class="form-label">Lokasi Makam</label>

                        <div class="mb-2">
                            <span class="badge bg-warning text-dark px-3 py-2 me-2" style="font-size: 1em;">
                                <i class="fas fa-user-shield me-1"></i> Ditambahkan oleh Admin
                            </span>
                            <span class="badge bg-info text-white px-3 py-2 me-2" style="font-size: 1em;">
                                <i class="fas fa-hourglass-half me-1"></i> Permohonan Diproses
                            </span>
                            <span class="badge bg-success text-white px-3 py-2" style="font-size: 1em;">
                                <i class="fas fa-check-circle me-1"></i> Request Disetujui
                            </span>
                        </div>
                        @foreach ($graveLocations as $index => $group)
                            <div class="col-12 p-2 justify-center border rounded bg-light">
                                <h5 class="text-center mb-3">{{ $group->name }}</h5>
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    @foreach ($group->locations as $location)
                                        @php
                                            $isOccupied = $location->corpseDetail !== null;
                                            $hasRequester = isset($location->request) && $location->request !== null;

                                            if ($isOccupied && $hasRequester) {
                                                $boxClass = 'btn-success text-white';
                                                $tooltipText =
                                                    'Sudah terisi, diajukan oleh: ' .
                                                    $location->request->requester_name;
                                            } elseif ($isOccupied && !$hasRequester) {
                                                $boxClass = 'btn-warning text-white';
                                                $tooltipText = 'Sudah terisi, ditambahkan oleh admin';
                                            } elseif (!$isOccupied && $hasRequester) {
                                                $boxClass = 'btn-info text-white';
                                                $tooltipText =
                                                    'Sudah ada permohonan oleh: ' . $location->request->requester_name;
                                            } else {
                                                $boxClass = 'btn-outline-secondary';
                                                $tooltipText = 'Tersedia';
                                            }
                                        @endphp
                                        <div class="grave-box-request btn d-flex align-items-center justify-content-center p-3 {{ $boxClass }}"
                                            style="min-width: 60px;" data-group-id="{{ $group->id }}"
                                            data-location-id="{{ $location->id }}"
                                            data-occupied="{{ $isOccupied ? '1' : '0' }}"
                                            data-requested="{{ $hasRequester ? '1' : '0' }}" data-bs-toggle="tooltip"
                                            data-bs-html="true" title="{{ $tooltipText }}">
                                            <span class="me-1 fw-bold">{{ $loop->iteration }}</span>
                                            
                                            <img src="{{ asset('img/icons/svg/tomb.svg') }}" width="20" height="20"
                                                alt="Tomb Icon">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="requester_name" class="form-label">Nama Pemohon</label>
                        <input type="text" class="form-control" id="requester_name" name="requester_name" required
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Pemohon</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="rt" class="form-label">RT</label>
                        <input type="number" class="form-control" id="rt" name="rt" min="1" required
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="rw" class="form-label">RW</label>
                        <input type="number" class="form-control" id="rw" name="rw" min="1" required
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="dusun" class="form-label">Dusun</label>
                        <input type="text" class="form-control" id="dusun" name="dusun" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="corpse_name" class="form-label">Nama Mayit</label>
                        <input type="text" class="form-control" id="corpse_name" name="corpse_name" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <div id="photo-preview" class="mt-2 text-muted">Tidak ada foto</div>
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date"
                            max="{{ date('Y-m-d') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="death_date" class="form-label">Tanggal Meninggal</label>
                        <input type="date" class="form-control" id="death_date" name="death_date"
                            max="{{ date('Y-m-d') }}" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="javanese_day" class="form-label">Hari Meninggal</label>
                        <select class="form-select" id="javanese_day" name="javanese_day" required disabled>
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
                        <label for="javanese_weton" class="form-label">Weton Meninggal</label>
                        <select class="form-select" id="javanese_weton" name="javanese_weton" required disabled>
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
                        <textarea class="form-control" id="notes" name="notes" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>


        <div class="card mt-5">
            <div class="card-datatable table-responsive">
                <table class="datatables table border-top" id="grave-request-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemohon</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th>Dusun</th>
                            <th>Nama Mayit</th>
                            <th>Lokasi Makam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('dashboard.grave-management.request-location.partials.location-detail')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Grave box click handler (pastikan ini sesuai dengan selector grave-box di komponen)
            $(document).on('click', '.grave-box-request', function() {
                var locationId = $(this).data('location-id');
                $.get('/grave-location/' + locationId + '/requester', function(data) {
                    // Isi field form dan set readonly
                    $('#requester_name').val(data.requester_name).prop('readonly', true);
                    $('#address').val(data.address).prop('readonly', true);
                    $('#phone_number').val(data.phone_number).prop('readonly', true);
                    $('#rt').val(data.rt).prop('readonly', true);
                    $('#rw').val(data.rw).prop('readonly', true);
                    $('#dusun').val(data.dusun).prop('readonly', true);
                    $('#corpse_name').val(data.corpse_name).prop('readonly', true);
                    $('#birth_date').val(data.birth_date).prop('readonly', true);
                    $('#birth_place').val(data.birth_place).prop('readonly', true);
                    $('#death_date').val(data.death_date).prop('readonly', true);
                    $('#javanese_day').val(data.javanese_day).prop('disabled', true);
                    $('#javanese_weton').val(data.javanese_weton).prop('disabled', true);
                    $('#notes').val(data.notes).prop('readonly', true);

                    // Tampilkan foto jika ada, jika tidak ada tampilkan keterangan
                    if (data.photo && data.photo !== '') {
                        $('#photo-preview').html('<img src="' + data.photo +
                            '" alt="Foto Mayit" class="img-fluid" style="max-width:150px;">');
                    } else {
                        $('#photo-preview').html('<div class="text-muted">Tidak ada foto</div>');
                    }
                });
            });
        });
    </script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#grave-request-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('request-list') }}',
                order: [
                    [10, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id',
                        title: 'No'
                    },
                    {
                        data: 'requester_name',
                        name: 'requester_name',
                        title: 'Nama Pemohon'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        title: 'No. Telepon'
                    },
                    {
                        data: 'address',
                        name: 'address',
                        title: 'Alamat'
                    },
                    {
                        data: 'rt',
                        name: 'rt',
                        title: 'RT'
                    },
                    {
                        data: 'rw',
                        name: 'rw',
                        title: 'RW'
                    },
                    {
                        data: 'dusun',
                        name: 'dusun',
                        title: 'Dusun'
                    },
                    {
                        data: 'corpse_name',
                        name: 'corpse_name',
                        title: 'Nama Mayit'
                    },
                    {
                        data: 'location_view',
                        name: 'location_view',
                        title: 'Lokasi Makam'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        title: 'Status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        visible: false
                    }
                ]
            });

            $(document).on('click', '.btn-view-location', function() {
                var groupId = $(this).data('id');
                var locationId = $(this).data('location-id');
                if (groupId) {
                    $.get('{{ url('/api/grave-locations') }}/' + groupId, function(response) {
                        if (response.success) {
                            renderGraveLocations(response.data, '.grave-box-container', locationId);
                            $('#locationViewModal').modal('show');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }).fail(function() {
                        Swal.fire('Error', 'Gagal memuat lokasi.', 'error');
                    });
                } else {
                    Swal.fire('Error', 'ID lokasi tidak ditemukan.', 'error');
                }
            });

            function renderGraveLocations(locations, locationContainerClass, selectedLocationId = null) {
                var graveContainer = $(locationContainerClass);
                graveContainer.empty(); // Clear previous locations

                locations.forEach(function(location) {
                    var isOccupied = location.corpse_detail !== null;
                    var isSelected = location.id === selectedLocationId;
                    var boxColor = isSelected ? 'bg-success' : isOccupied ? 'bg-dark' : 'bg-white';
                    var tooltipText = isOccupied ?
                        `
                        <strong>Name:</strong> ${location.corpse_detail.name}<br>
                        <strong>Birth Date:</strong> ${location.corpse_detail.birth_date ?? 'N/A'}<br>
                        <strong>Death Date:</strong> ${location.corpse_detail.death_date ?? 'N/A'}<br>
                        <strong>Age:</strong> ${location.corpse_detail.age ?? 'N/A'}
                      ` :
                        'Kosong';

                    var graveBox = `
                    <div class="grave-box position-relative m-1 ${boxColor} text-white d-flex align-items-center justify-content-center"
                        data-id="${location.id}"
                        data-bs-toggle="tooltip"
                        data-bs-html="true"
                        data-bs-placement="top"
                        title="${tooltipText}"
                        style="width: 40px; height: 40px; border: 1px solid #000; border-radius: 4px; cursor: pointer;">
                    </div>
                `;
                    graveContainer.append(graveBox);
                });

                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    </script>
@endpush
