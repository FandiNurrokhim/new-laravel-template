@extends('layouts.app')

@section('title', 'Grave Groups Management')

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
        <h4 class="fw-bold py-3 pb-0 mb-2">Grup Makam</h4>
        <p class="mb-4">
            This page is used to manage grave groups. You can add, edit, and delete grave groups here.
        </p>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @include('dashboard.grave-management.grave-group.partials.widget')
            <!-- DataTables Grave Groups Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="grave-group-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok</th>
                                    <th>Max Graves</th>
                                    <th>Makam Digunakan</th>
                                    <th>Makam Kosong</th>
                                    <th>Makam Penuh</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.grave-management.grave-group.partials.create')
    @include('dashboard.grave-management.grave-group.partials.edit')
    @include('dashboard.grave-management.grave-group.partials.detail')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#grave-group-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('grave-group.index') }}',
                order: [
                    [7, 'desc']
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
                        data: 'max_graves',
                        name: 'max_graves'
                    },
                    {
                        data: 'used_graves',
                        name: 'used_graves'
                    },
                    {
                        data: 'unused_graves',
                        name: 'unused_graves'
                    },
                    {
                        data: 'is_full',
                        name: 'is_full'
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

            $('#addGraveGroupForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('grave-group.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addGraveGroupModal').modal('hide');
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
                $.get('{{ url('dashboard/grave-group') }}/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_max_graves').val(data.max_graves);
                    $('#editGraveGroupModal').modal('show');
                });
            });

            $(document).on('click', '.btn-detail', function() {
                var id = $(this).data('id');
                $.get('{{ url('dashboard/grave-group') }}/' + id, function(data) {
                    // Clear the modal content
                    var graveContainer = $('.grave-box-container');
                    graveContainer.empty();

                    // Populate grave boxes
                    for (var i = 0; i < data.max_graves; i++) {
                        var location = data.locations[i]; // Check if location exists
                        var boxColor = location && location.corpseDetail ? 'bg-success' :
                        'bg-white'; 
                        var tooltipText = location && location.corpseDetail ? location.corpseDetail
                            .name : 'Kosong';

                        // Create the grave box
                        var graveBox = `
                        <div class="grave-box position-relative m-1 ${boxColor} text-white d-flex align-items-center justify-content-center"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="${tooltipText}"
                            style="width: 30px; height: 60px; border: 1px solid #000; border-radius: 4px;">
                        </div>
                    `;
                        graveContainer.append(graveBox);
                    }

                    // Initialize tooltips
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                        '[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                        new bootstrap.Tooltip(tooltipTriggerEl);
                    });

                    // Show the modal
                    $('#graveDetailModal').modal('show');
                });
            });
            $('#editGraveGroupForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();
                $.ajax({
                    url: '{{ route('grave-group.update', ':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editGraveGroupModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Success', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
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
                            url: '{{ url('dashboard/grave-group') }}/' + id,
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
