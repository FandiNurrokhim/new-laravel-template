@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Menus List</h4>
        <p class="mb-4">
            This page is used to manage menus. You can add, edit, and delete menus here.
        </p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="datatables table border-top" id="menus-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tipe</th>
                                <th>Judul</th>
                                <th>Ikon</th>
                                <th>Route</th>
                                <th>Header</th>
                                <th>Parent</th>
                                <th>Permission Title</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddMenu" aria-labelledby="offcanvasAddMenuLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddMenuLabel" class="offcanvas-title">Add Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"
                id="addCloseTrigger"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form id="addNewMenuForm" onsubmit="return false">
                @csrf
                <div class="mb-3">
                    <label for="add_type" class="form-label">Tipe Menu</label>
                    <select name="type" class="select2 form-select" id="add_type" required>
                        <option value="HEADER">HEADER</option>
                        <option value="PARENT">PARENT</option>
                        <option value="SUB PARENT">SUB PARENT</option>
                    </select>
                </div>

                <div class="mb-3 group-all">
                    <label for="title" class="form-label">Judul Menu</label>
                    <input type="text" name="title" class="form-control" required />
                </div>

                <div class="mb-3 group-parent group-sub-parent">
                    <label for="edit_icon" class="form-label">Ikon</label>
                    <select class="select2-icons form-select" name="icon" id="edit_icon">
                        @foreach ($icons as $icon)
                            <option value="{{ $icon->class }}" data-icon="{{ $icon->class }}">
                                {{ $icon->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 group-parent group-sub-parent">
                    <label for="route" class="form-label">Route</label>
                    <input type="text" name="route" class="form-control" />
                </div>

                <div class="mb-3 group-parent">
                    <label for="header" class="form-label">Header (Untuk tipe PARENT)</label>
                    <select name="header" class="form-select select2">
                        <option value="">-- Pilih Header --</option>
                        @foreach ($headers as $header)
                            <option value="{{ $header->title }}">{{ $header->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 group-sub-parent">
                    <label for="parent_id" class="form-label">Parent (Untuk tipe SUB PARENT)</label>
                    <select name="parent_id" class="form-select select2">
                        <option value="">-- Tidak Ada --</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 group-parent group-sub-parent">
                    <label for="permission_title" class="form-label">Permission Title</label>
                    <input type="text" name="permission_title" class="form-control" required />
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditMenu" aria-labelledby="offcanvasEditMenuLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasEditMenuLabel" class="offcanvas-title">Edit Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"
                id="editCloseTrigger"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form id="editMenuForm" onsubmit="return false">
                @csrf
                @method('PUT')
                <input type="hidden" name="menu_id" id="menu_id" />

                <div class="mb-3">
                    <label for="edit_type" class="form-label">Tipe Menu</label>
                    <select name="type" class="select2 form-select" id="edit_type" required>
                        <option value="HEADER">HEADER</option>
                        <option value="PARENT">PARENT</option>
                        <option value="SUB PARENT">SUB PARENT</option>
                    </select>
                </div>

                <div class="mb-3 group-all">
                    <label for="edit_title" class="form-label">Judul Menu</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required />
                </div>

                <div class="mb-3 group-parent group-sub-parent">
                    <label for="edit_sicon" class="form-label">Ikon</label>
                    <select class="select2-icons form-select" name="icon" id="edit_sicon">
                        @foreach ($icons as $icon)
                            <option value="{{ $icon->class }}" data-icon="{{ $icon->class }}">
                                {{ $icon->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 group-parent group-sub-parent">
                    <label for="edit_route" class="form-label">Route</label>
                    <input type="text" name="route" id="edit_route" class="form-control" />
                </div>

                <div class="mb-3 group-parent">
                    <label for="edit_header" class="form-label">Header (Untuk tipe PARENT)</label>
                    <select name="header" class="form-select select2" id="edit_header">
                        <option value="">-- Pilih Header --</option>
                        @foreach ($headers as $header)
                            <option value="{{ $header->title }}">{{ $header->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 group-sub-parent">
                    <label for="edit_parent_id" class="form-label">Parent (Untuk tipe SUB PARENT)</label>
                    <select name="parent_id" class="form-select select2" id="edit_parent_id">
                        <option value="">-- Tidak Ada --</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 group-parent group-sub-parent">
                    <label for="edit_permission_title" class="form-label">Permission Title</label>
                    <input type="text" name="permission_title" id="edit_permission_title" class="form-control"
                        required />
                </div>

                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#menus-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('menus.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'icon',
                        name: 'icon'
                    },
                    {
                        data: 'route',
                        name: 'route'
                    },
                    {
                        data: 'header',
                        name: 'header'
                    },
                    {
                        data: 'parent',
                        name: 'parent'
                    },
                    {
                        data: 'permission_title',
                        name: 'permission_title'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    sLengthMenu: '_MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                },
                dom: '<"row mx-1"' +
                    '<"col-sm-12 col-md-3" l>' +
                    '<"col-sm-12 col-md-9"' +
                    '<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex ' +
                    'align-items-center justify-content-md-end justify-content-center flex-wrap me-1"' +
                    '<"me-3" f>B' +
                    '>' +
                    '>' +
                    '>t' +
                    '<"row mx-2"' +
                    '<"col-sm-12 col-md-6" i>' +
                    '<"col-sm-12 col-md-6" p>' +
                    '>',
                buttons: [{
                    text: 'Add Menu',
                    className: 'add-new btn btn-primary mb-3 mb-md-0',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasAddMenu'
                    },
                    init: function(api, node) {
                        $(node).removeClass('btn-secondary');
                    }
                }]
            });

            function toggleAddFields(type) {
                $('.group-parent, .group-sub-parent').hide();
                if (type === 'PARENT') {
                    $('.group-parent').show();
                } else if (type === 'SUB PARENT') {
                    $('.group-sub-parent').show();
                }
            }

            function toggleEditFields(type) {
                $('.group-parent, .group-sub-parent').hide();
                if (type === 'PARENT') {
                    $('.group-parent').show();
                } else if (type === 'SUB PARENT') {
                    $('.group-sub-parent').show();
                }
            }

            toggleAddFields($('#add_type').val());
            $('#add_type').on('change', function() {
                toggleAddFields($(this).val());
            });

            $('#addNewMenuForm').validate({
                rules: {
                    type: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    permission_title: {
                        required: function() {
                            return $('#add_type').val() !== 'HEADER';
                        }
                    }
                },
                messages: {
                    type: {
                        required: 'Menu type must be selected'
                    },
                    title: {
                        required: 'Menu title must be filled'
                    },
                    permission_title: {
                        required: 'Permission title must be filled'
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: '{{ route('menus.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            table.ajax.reload();
                            $('#offcanvasAddMenu').modal('hide');
                            $('#addCloseTrigger').trigger('click');
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
                let menuId = $(this).data('id');
                let type = $(this).data('type');
                let title = $(this).data('title');
                let icon = $(this).data('icon');
                let routeVal = $(this).data('route');
                let headerVal = $(this).data('header');
                let parentId = $(this).data('parent-id');
                let permissionTitle = $(this).data('permission-title');

                $('#menu_id').val(menuId);
                $('#edit_type').val(type).trigger('change');
                $('#edit_title').val(title);
                $('#edit_sicon').val(icon).trigger('change');
                $('#edit_route').val(routeVal);
                $('#edit_header').val(headerVal).trigger('change');
                $('#edit_parent_id').val(parentId).trigger('change');
                $('#edit_permission_title').val(permissionTitle);

                updateEditIconPreview();

                toggleEditFields(type);

                $('#offcanvasEditMenu').modal('show');
            });

            $('#editMenuForm').validate({
                rules: {
                    type: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    permission_title: {
                        required: function() {
                            return $('#edit_type').val() !== 'HEADER';
                        }
                    }
                },
                messages: {
                    type: {
                        required: 'Menu type must be selected'
                    },
                    title: {
                        required: 'Menu title must be filled'
                    },
                    permission_title: {
                        required: 'Permission title must be filled'
                    }
                },
                submitHandler: function(form) {
                    let menuId = $('#menu_id').val();
                    $.ajax({
                        url: '/dashboard/menus/' + menuId,
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            table.ajax.reload();
                            $('#offcanvasEditMenu').modal('hide');
                            $('#editCloseTrigger').trigger('click');
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
                let menuId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/dashboard/menus/' + menuId,
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
                                table.ajax.reload();
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

        function updateIconPreview() {
            let select = document.getElementById("icon-select");
            let iconPreview = document.getElementById("icon-preview");
            let selectedOption = select.options[select.selectedIndex];
            iconPreview.className = selectedOption.getAttribute("data-icon");
        }

        function updateEditIconPreview() {
            let select = document.getElementById("edit_icon");
            let iconPreview = document.getElementById("edit_icon_preview");
            if (!select || !iconPreview) return;
            let selectedOption = select.options[select.selectedIndex];
            iconPreview.className = selectedOption.getAttribute("data-icon");
        }
    </script>
@endpush
