@extends('layouts.app')

@section('title', 'Hak Akses')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Access Control List</h4>
        <p class="mb-4">
            This page is used to manage user roles and permissions. You can create, edit, and delete roles and permissions.
        </p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="table border-top" id="access-control-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#access-control-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('access-control.index') }}', 
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                language: {
                    sLengthMenu: '_MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                dom: '<"row mx-1"' +
                    '<"col-sm-12 col-md-6" l>' +
                    '<"col-sm-12 col-md-6" f>' +
                    '>t' +
                    '<"row mx-2"' +
                    '<"col-sm-12 col-md-5" i>' +
                    '<"col-sm-12 col-md-7" p>' +
                    '>'
            });
        });
    </script>
@endpush
