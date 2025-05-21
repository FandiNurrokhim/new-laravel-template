@extends('layouts.app')

@section('title', 'Corpse Details Management')

@push('styles')
    <style>
        .placeholder-skeleton {
            color: transparent !important;
            background: #eee;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }

        .placeholder-skeleton::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: translateX(-100%);
            background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            100% {
                transform: translateX(100%);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Data Mayit</h4>
        <p class="mb-4">
            Halaman ini digunakan untuk mengelola rincian jenazah. Anda dapat menambah, mengedit, dan menghapus detail
            jenazah di sini.
        </p>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @include('dashboard.grave-management.corpse-detail.partials.widget')
            <!-- DataTables Corpse Details Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="corpse-detail-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Tanggal Meninggal</th>
                                    <th>Umur saat Meninggal</th>
                                    <th>Weton saat meninggal</th>
                                    <th>Lokasi Kuburan</th>
                                    <th>Grup Makam</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.grave-management.corpse-detail.partials.create')
    @include('dashboard.grave-management.corpse-detail.partials.edit')
    @include('dashboard.grave-management.corpse-detail.partials.detail')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle Grave Group selection in Create Modal
            $('#group_id').on('change', function() {
                var groupId = $(this).val();
                fetchGraveLocations(groupId, '.grave-box-container');
            });

            // Handle Grave Group selection in Edit Modal
            $('#edit_group_id').on('change', function() {
                var groupId = $(this).val();
                fetchGraveLocations(groupId, '.grave-box-container');
            });
        });


        function fetchGraveLocations(groupId, locationContainerClass, selectedLocationId = null) {
            var graveContainer = $(locationContainerClass); 
            graveContainer.empty();

            if (groupId) {
                $.get('{{ url('/api/grave-locations') }}/' + groupId, function(response) {
                    if (response.success) {
                        // Render grave locations as boxes
                        response.data.forEach(function(location) {
                            var isOccupied = location.corpse_detail !== null;
                            var isSelected = location.id === selectedLocationId;
                            var boxColor = isOccupied ? 'bg-warning text-white' : isSelected ?
                                'bg-primary text-white' :
                                'bg-white text-dark';
                            var tooltipText = isOccupied ? location.corpse_detail.name : 'Kosong';
                            var isDisabled = isOccupied ? 'disabled' : '';

                            var graveBox = `
                                <div class="grave-box position-relative m-1 ${boxColor} d-flex flex-column align-items-center justify-content-center"
                                    data-id="${location.id}"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="${tooltipText}"
                                    style="width: 60px; height: 60px; border: 1px solid #000; border-radius: 4px; cursor: ${isOccupied ? 'not-allowed' : 'pointer'};"
                                    ${isDisabled}>
                                    <img src="{{ asset('img/icons/svg/tomb.svg') }}" width="20" height="20" alt="Tomb Icon">
                                    <span style="font-size: 0.9em; margin-top: 2px;">${location.code}</span>
                                </div>
                            `;
                            graveContainer.append(graveBox);
                        });

                        // Initialize tooltips
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                            new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                });
            }
        }

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
                        ${location.code}
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

        // Handle Grave Box Selection
        $('#addCorpseDetailModal, #editCorpseDetailModal').on('show.bs.modal', function() {
            $(document).on('click', '.grave-box', function() {
                var isOccupied = $(this).hasClass('bg-success');
                if (isOccupied) {
                    Swal.fire('Error', 'Lokasi sudah ada isi, tidak dapat dipilih.', 'error');
                    return;
                }

                $('.grave-box').not('.bg-success').removeClass('bg-primary').addClass('bg-white');
                $(this).removeClass('bg-white').addClass('bg-primary text-white');

                var locationId = $(this).data('id');
                $('#grave_location_id').val(locationId);
            });
        });
        $(document).ready(function() {
            var table = $('#corpse-detail-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('grave.index') }}',
                order: [
                    [9, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'birth_date',
                        name: 'birth_date'
                    },
                    {
                        data: 'death_date',
                        name: 'death_date'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'javanese_death_date',
                        name: 'javanese_death_date'
                    },
                    {
                        data: 'location_code',
                        name: 'location_code',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'group_name',
                        name: 'group_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
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

            $('#addCorpseDetailForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('grave.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#addCorpseDetailModal').modal('hide');
                        $('#addCorpseDetailForm')[0].reset();
                        table.ajax.reload();
                        Swal.fire('Success', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            });

            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.get('{{ url('dashboard/grave') }}/' + id, function(data) {
                    // Populate the form fields with the corpse detail data
                    $('#edit_id').val(data.data.id);
                    $('#edit_name').val(data.data.name);
                    $('#edit_birth_date').val(data.data.birth_date);
                    $('#edit_birth_place').val(data.data.birth_place);
                    $('#edit_death_date').val(data.data.death_date);
                    $('#edit_javanese_weton').val(data.data.javanese_weton);
                    $('#edit_javanese_day_death').val(data.data.javanese_day_death);
                    $('#edit_group_id').val(data.data.location.group.id).trigger('change');

                    // Fetch and render grave locations for the selected group
                    fetchGraveLocations(data.data.location.group.id, data.data.location.id);

                    // Show the modal
                    $('#editCorpseDetailModal').modal('show');
                });
            });

            // Fetch locations dynamically when the group is changed in the Edit Modal
            $('#edit_group_id').on('change', function() {
                var groupId = $(this).val();
                fetchGraveLocations(groupId);
            });

            $(document).on('click', '.btn-detail', function() {
                var id = $(this).data('id');
                $.get('{{ url('dashboard/grave') }}/' + id, function(data) {
                    if (data.success) {
                        var corpseDetail = data.data;

                        // Format the Javanese death date
                        var deathDate = corpseDetail.death_date ? new Date(corpseDetail
                            .death_date) : null;
                        var javaneseDeathDate = deathDate ?
                            corpseDetail.javanese_dey_death + ' ' + (corpseDetail.javanese_weton ??
                                'N/A') + ' ' +
                            deathDate.getFullYear() :
                            'N/A';

                        // Calculate time since death
                        var timeSinceDeath = deathDate ?
                            Math.floor((new Date() - deathDate) / (1000 * 60 * 60 * 24)) +
                            ' days ago' :
                            'N/A';

                        // Populate the modal with corpse details
                        $('#corpseName').text(corpseDetail.name);
                        $('#birthDate').text(corpseDetail.birth_date ?? 'N/A');
                        $('#deathDate').text(corpseDetail.death_date ?? 'N/A');
                        $('#ageAtDeath').text(corpseDetail.age ?? 'N/A');
                        $('#graveCode').text(corpseDetail.location.code ?? 'N/A');
                        $('#graveGroup').text(corpseDetail.location.group.name ?? 'N/A');
                        $('#javaneseDeathDate').text(javaneseDeathDate);
                        $('#timeSinceDeath').text(timeSinceDeath);

                        // Render grave locations using the renderGraveLocations method
                        if (corpseDetail.location.group) {
                            $.get('{{ url('/api/grave-locations') }}/' + corpseDetail
                                .location.group.id,
                                function(response) {
                                    if (response.success) {
                                        renderGraveLocations(response.data,
                                            '.grave-box-container', corpseDetail.location.id
                                        );
                                    } else {
                                        Swal.fire('Error', response.message, 'error');
                                    }
                                });
                        }

                        // Show the modal
                        $('#corpseDetailModal').modal('show');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                }).fail(function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                });
            });

            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('dashboard/grave') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload();
                                Swal.fire('Deleted!', response.message, 'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
