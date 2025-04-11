@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Roles List</h4>
        <p class="mb-4">
            This page is used to manage users. You can add, edit, and delete roles here.
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
                                <button data-bs-target="#addUserModal" data-bs-toggle="modal"
                                    class="btn btn-primary mb-3 text-nowrap add-new-role">
                                    Add New User
                                </button>
                                <p class="mb-0">Add user, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTables Role Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
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

    @include('dashboard.users.partials.add-modal')
    @include('dashboard.users.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                    modalFullName: {
                        required: true,
                        minlength: 3
                    },
                    modalUsername: {
                        required: true,
                        minlength: 3
                    },
                    modalEmail: {
                        required: true,
                        email: true
                    },
                    modalPassword: {
                        required: true,
                        minlength: 6
                    },
                    modalRole: {
                        required: true
                    },
                    modalStatus: {
                        required: true
                    }
                },
                messages: {
                    modalFullName: {
                        required: "Full name is required",
                        minlength: "Full name must be at least 3 characters"
                    },
                    modalUsername: {
                        required: "Username is required",
                    },
                    modalEmail: {
                        required: "Email is required",
                        email: "Please enter a valid email address"
                    },
                    modalPassword: {
                        required: "Password is required",
                        minlength: "Password must be at least 6 characters"
                    },
                    modalRole: {
                        required: "Role is required"
                    },
                    modalStatus: {
                        required: "Status is required"
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: '{{ route('users.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#users-table').DataTable().ajax.reload();
                            $('#addUserModal').modal('hide');
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
                var userId = $(this).data('id');
                var fullName = $(this).data('name');
                var email = $(this).data('email');
                var username = $(this).data('username');
                var status = $(this).data('status');
                var roleId = $(this).data('role-id');
                $('#editUserId').val(userId);
                $('#editFullName').val(fullName);
                $('#editEmail').val(email);
                $('#editUsername').val(username);
                $('#editStatus').val(status).trigger('change');
                $('#editRole').val(roleId).trigger('change');
                $('#editUserModal').modal('show');
            });

            $('#editUserForm').validate({
                rules: {
                    editFullName: {
                        required: true,
                        minlength: 3
                    },
                    editEmail: {
                        required: true,
                        email: true
                    },
                    editUsername: {
                        required: true,
                        minlength: 3
                    },
                    editRole: {
                        required: true
                    },
                    editStatus: {
                        required: true
                    }
                },
                messages: {
                    editFullName: {
                        required: "Full name is required",
                        minlength: "Full name must be at least 3 characters"
                    },
                    editEmail: {
                        required: "Email is required",
                        email: "Please enter a valid email address"
                    },
                    editUsername: {
                        required: "Username is required",
                    },
                    editRole: {
                        required: "Role is required"
                    },
                    editStatus: {
                        required: "Status is required"
                    }
                },
                submitHandler: function(form) {
                    var roleId = $('#editUserId').val();
                    $.ajax({
                        url: '{{ url('dashboard/users') }}/' + roleId,
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#users-table').DataTable().ajax.reload();
                            $('#editUserModal').modal('hide');
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
                            url: '{{ url('dashboard/users') }}/' + roleId,
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
                                $('#users-table').DataTable().ajax.reload();
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
