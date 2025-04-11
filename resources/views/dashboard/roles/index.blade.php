@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Roles List</h4>
        <p class="mb-4">
            This page is used to manage roles. You can add, edit, and delete roles here.
        </p>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @foreach ($roles as $role)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="fw-normal">Total <b>{{ $role->users->count() ?? 0 }}</b> Users</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="role-heading">
                                    <h4 class="mb-1">{{ $role->name }}</h4>
                                    <small>
                                        This role has <b>{{ $role->permissions->count() ?? 0 }}</b> permissions
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Add New Role Card -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="row h-100">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                                <img src="{{ asset('img/illustrations/sitting-girl-with-laptop-light.png') }}"
                                    class="img-fluid" alt="Image" width="120" />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                                    class="btn btn-primary mb-3 text-nowrap add-new-role">
                                    Add New Role
                                </button>
                                <p class="mb-0">Add role, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTables Role Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="roles-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role</th>
                                    <th>Total Users</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.roles.partials.add-modal')
    @include('dashboard.roles.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('roles.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'users_count',
                        name: 'users_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#addRoleForm').validate({
                rules: {
                    modalRoleName: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    modalRoleName: {
                        required: "Role name is required",
                        minlength: "Role name must be at least 3 characters"
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: '{{ route('roles.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#roles-table').DataTable().ajax.reload();
                            $('#addRoleModal').modal('hide');
                            form.reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message
                            });
                        }
                    });
                    return false;
                }
            });

            $(document).on('click', '.btn-edit', function() {
                var roleId = $(this).data('id');
                var roleName = $(this).data('name');
                $('#editRoleName').val(roleName);
                $('#editRoleModal').data('role-id', roleId).modal('show');
            });

            $('#editRoleForm').validate({
                rules: {
                    modalRoleName: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    modalRoleName: {
                        required: "Role name is required",
                        minlength: "Role name must be at least 3 characters"
                    }
                },
                submitHandler: function(form) {
                    var roleId = $('#editRoleModal').data('role-id');
                    $.ajax({
                        url: '/dashboard/roles/' + roleId,
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#roles-table').DataTable().ajax.reload();
                            $('#editRoleModal').modal('hide');
                            form.reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message
                            });
                        }
                    });
                    return false;
                }
            });

            $(document).on('click', '.btn-delete', function() {
                var roleId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/dashboard/roles/' + roleId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message
                                });
                                $('#roles-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.message
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
