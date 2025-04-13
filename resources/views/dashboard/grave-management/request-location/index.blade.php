@extends('layouts.app')

@section('title', 'Grave Locations Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Lokasi Makam</h4>
        <p class="mb-4">
            This page is used to manage grave locations. You can view, add, edit, and delete grave locations here.
        </p>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @include('dashboard.grave-management.grave-location.partials.widget')
            <!-- DataTables Grave Locations Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="grave-location-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lokasi</th>
                                    <th>Kelompok</th>
                                    <th>Nama Makam</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.grave-management.grave-location.partials.create')
    @include('dashboard.grave-management.grave-location.partials.edit')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#grave-location-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('grave-location.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'location_name',
                        name: 'location_name',
                        title: 'Nama Lokasi'
                    },
                    {
                        data: 'group_name',
                        name: 'group_name',
                        title: 'Grave Group'
                    },
                    {
                        data: 'corpse_name',
                        name: 'corpse_name',
                        title: 'Occupant'
                    },
                    {
                        data: 'is_confirmed',
                        name: 'is_confirmed',
                        render: function(data) {
                            return data ? '<span class="badge bg-success">Confirmed</span>' :
                                '<span class="badge bg-danger">Unconfirmed</span>';
                        },
                        title: 'Status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#addGraveLocationForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('grave-location.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addGraveLocationModal').modal('hide');
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
                $.get('{{ url('dashboard/grave-location') }}/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_grave_group_id').val(data.grave_group_id);
                    $('#edit_is_confirmed').prop('checked', data.is_confirmed);
                    $('#editGraveLocationModal').modal('show');
                });
            });

            $('#editGraveLocationForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();
                $.ajax({
                    url: '{{ route('grave-location.update', ':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editGraveLocationModal').modal('hide');
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
                            url: '{{ url('dashboard/grave-location') }}/' + id,
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
