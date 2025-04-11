@extends('layouts.app')

@section('title', 'Sub-Categories Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Sub-Categories List</h4>
        <p class="mb-4">
            This page is used to manage sub-categories. You can add, edit, and delete sub-categories here.
        </p>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @foreach ($statusCounts as $status)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="fw-normal">Total <b>{{ $status['count'] }}</b> Status</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="role-heading">
                                    <h4 class="mb-1">{{ $status['name'] }}</h4>
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
                                <button data-bs-target="#offcanvasAddSubCategory" data-bs-toggle="offcanvas"
                                    class="btn btn-primary mb-3 text-nowrap add-new-role">
                                    Add Sub Category
                                </button>
                                <p class="mb-0">Add sub category, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="sub-categories-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>thumbnail</th>
                                    <th>Description</th>
                                    <th>Order Number</th>
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

    @include('dashboard.master-data.sub-categories.partials.add-modal')
    @include('dashboard.master-data.sub-categories.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#sub-categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('sub-categories.index') }}',
                order: [
                    [9, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'title_category',
                        name: 'title_category'
                    },
                    {
                        data: 'thumbnail',
                        name: 'thumbnail'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'order_number',
                        name: 'order_number'
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
                    }, {
                        data: 'updated_at',
                        name: 'updated_at',
                        visible: false
                    },
                ]
            });

            $('#addSubCategoryForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3,
                    },
                    category_id: {
                        required: true,
                    },
                    description: {
                        maxlength: 500,
                    },
                    order_number: {
                        number: true,
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: "Title is required",
                        minlength: "Title must be at least 3 characters",
                    },
                    category_id: {
                        required: "Category is required",
                    },
                    description: {
                        maxlength: "Description cannot exceed 500 characters",
                    },
                    order_number: {
                        number: "Order number must be a valid number",
                    },
                    status: {
                        required: "Status is required",
                    },
                },
                submitHandler: function(form) {
                    $('#description_add_hidden').val(quillAdd.root.innerHTML);
                    $.ajax({
                        url: '{{ route('sub-categories.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#sub-categories-table').DataTable().ajax.reload();

                            let offcanvasAdd = bootstrap.Offcanvas.getInstance(document
                                .getElementById('offcanvasAddSubCategory'));
                            if (offcanvasAdd) {
                                offcanvasAdd.hide();
                            }

                            form.reset();
                            quillAdd.root.innerHTML = '';
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message,
                            });
                        },
                    });
                    return false;
                },
            });

            $('#editSubCategoryForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3,
                    },
                    category_id: {
                        required: true,
                    },

                    order_number: {
                        number: true,
                    },
                    status: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: "Title is required",
                        minlength: "Title must be at least 3 characters",
                    },
                    category_id: {
                        required: "Category is required",
                    },
                    order_number: {
                        number: "Order number must be a valid number",
                    },
                    status: {
                        required: "Status is required",
                    },
                },
                submitHandler: function(form) {
                    $('#description_edit_hidden').val(quillEdit.root.innerHTML);
                    var id = $('#edit_id').val();

                    $.ajax({
                        url: '{{ url('dashboard/sub-categories') }}/' + id,
                        type: 'PUT',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#sub-categories-table').DataTable().ajax.reload();

                            let offcanvasEditEl = document.getElementById(
                                'offcanvasEditSubCategory');
                            let offcanvasEditObj = bootstrap.Offcanvas.getInstance(
                                offcanvasEditEl);
                            if (!offcanvasEditObj) {
                                offcanvasEditObj = new bootstrap.Offcanvas(offcanvasEditEl);
                            }

                            offcanvasEditObj.hide();

                            quillEdit.root.innerHTML = '';
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message,
                            });
                        },
                    });
                    return false;
                },
            });

            // Delete Sub-Category
            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('dashboard/sub-categories') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                $('#sub-categories-table').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.message,
                                });
                            },
                        });
                    }
                });
            });

            $(document).on('click', '.btn-edit', function() {
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                $btn.prop('disabled', true);

                var id = $btn.data('id');

                $.get('{{ url('dashboard/sub-categories') }}/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_title').val(data.title);
                    $('#edit_category_id').val(data.category_id).trigger('change');
                    $('#edit_thumbnail').val(data.thumbnail);
                    $('#edit_order_number').val(data.order_number);
                    $('#edit_status').val(data.status).trigger('change');
                    $('#editSubCategoryModal').modal('show');

                    quillEdit.root.innerHTML = data.description || '';

                    let offcanvasEl = document.getElementById('offcanvasEditSubCategory');
                    let offcanvasObj = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (!offcanvasObj) {
                        offcanvasObj = new bootstrap.Offcanvas(offcanvasEl);
                    }
                    offcanvasObj.show();
                }).always(function() {
                    $btn.prop('disabled', false);
                });
            });

            window.quillAdd = new Quill('#description_add_quill', {
                theme: 'snow',
                placeholder: 'Type something...',
                modules: {
                    syntax: true,
                    toolbar: [
                        [{
                            font: []
                        }, {
                            size: []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            script: 'super'
                        }, {
                            script: 'sub'
                        }],
                        [{
                            header: 1
                        }, {
                            header: 2
                        }, 'blockquote', 'code-block'],
                        [{
                            list: 'ordered'
                        }, {
                            indent: '-1'
                        }, {
                            indent: '+1'
                        }],
                        [{
                            direction: 'rtl'
                        }, {
                            align: []
                        }],
                        ['link', 'image', 'video', 'formula'],
                        ['clean']
                    ]
                }
            });

            window.quillEdit = new Quill('#description_edit_quill', {
                theme: 'snow',
                placeholder: 'Type something...',
                modules: {
                    syntax: true,
                    toolbar: [
                        [{
                            font: []
                        }, {
                            size: []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            color: []
                        }, {
                            background: []
                        }],
                        [{
                            script: 'super'
                        }, {
                            script: 'sub'
                        }],
                        [{
                            header: 1
                        }, {
                            header: 2
                        }, 'blockquote', 'code-block'],
                        [{
                            list: 'ordered'
                        }, {
                            indent: '-1'
                        }, {
                            indent: '+1'
                        }],
                        [{
                            direction: 'rtl'
                        }, {
                            align: []
                        }],
                        ['link', 'image', 'video', 'formula'],
                        ['clean']
                    ]
                }
            });
        });
    </script>
@endpush
