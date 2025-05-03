@extends('layouts.home-layout')

@section('title', 'Manajemen Permintaan Lokasi Makam')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Permintaan Lokasi Makam</h4>
        <div class="card">
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
