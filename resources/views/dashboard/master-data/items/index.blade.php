@extends('layouts.app')

@section('title', 'Item Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Items List</h4>
        <p class="mb-4">
            This page is used to manage items. You can add, edit, and delete items here.
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
                                <button button data-bs-target="#offcanvasAddItem" data-bs-toggle="offcanvas"
                                    class="btn btn-primary mb-3 text-nowrap add-new-role">
                                    Add Items
                                </button>
                                <p class="mb-0">Add Items, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DataTables Items Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="items-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Products</th>
                                    <th>Sections</th>
                                    <th>Categories</th>
                                    <th>Sub Categories</th>
                                    <th>File Format</th>
                                    <th>Description</th>
                                    <th>Tag</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Order No</th>
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

    @include('dashboard.master-data.items.partials.add-modal')
    @include('dashboard.master-data.items.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var table = $('#items-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('items.index') }}',
                order: [
                    [10, 'desc']
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
                        data: 'products',
                        name: 'products',
                    }, {
                        data: 'sections',
                        name: 'sections',
                    },
                    {
                        data: 'categories',
                        name: 'categories'
                    },
                    {
                        data: 'sub_categories',
                        name: 'sub_categories',
                    }, {
                        data: 'file_format',
                        name: 'file_format'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'tag',
                        name: 'tag',
                    },
                    {
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'price',
                        name: 'price'
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
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        visible: false // untuk sorting
                    }
                ],
            });

            // Add Item Form Submission
            $('#addItemForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    file_format_id: {
                        required: true
                    },
                    price: {
                        number: true,
                        min: 0
                    },
                    status: {
                        required: true
                    },
                    products: {
                        select2Required: true
                    },
                    sections: {
                        select2Required: true
                    },
                    categories: {
                        select2Required: true
                    },
                    sub_categories: {
                        select2Required: true
                    },
                },
                messages: {
                    title: {
                        required: "Title is required",
                        minlength: "Title must be at least 3 characters"
                    },
                    file_format_id: {
                        required: "File format is required"
                    },
                    price: {
                        number: "Price must be a valid number",
                        min: "Price must be at least 0"
                    },
                    status: {
                        required: "Status is required"
                    },
                    products: {
                        select2Required: "Please select at least one product"
                    },
                    sections: {
                        select2Required: "Please select at least one section"
                    },
                    categories: {
                        select2Required: "Please select at least one category"
                    },
                    sub_categories: {
                        select2Required: "Please select at least one sub-category"
                    },
                },
                submitHandler: function(form) {
                    $('#description_add_hidden').val(quillAdd.root.innerHTML);

                    $.ajax({
                        url: '{{ route('items.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            table.ajax.reload();

                            let offcanvasAdd = bootstrap.Offcanvas.getInstance(document
                                .getElementById('offcanvasAddItem'));
                            if (offcanvasAdd) {
                                offcanvasAdd.hide();
                            }

                            form.reset();
                            quillAdd.root.innerHTML = '';

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
                        },
                    });
                    return false;
                }

            });

            // Edit Item Form Submission
            $('#editItemForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    file_format_id: {
                        required: true
                    },
                    price: {
                        number: true,
                        min: 0
                    },
                    status: {
                        required: true
                    },
                    products: {
                        select2Required: true
                    },
                    sections: {
                        select2Required: true
                    },
                    categories: {
                        select2Required: true
                    },
                    sub_categories: {
                        select2Required: true
                    },
                },
                messages: {
                    title: {
                        required: "Title is required",
                        minlength: "Title must be at least 3 characters"
                    },
                    file_format_id: {
                        required: "File format is required"
                    },
                    price: {
                        number: "Price must be a valid number",
                        min: "Price must be at least 0"
                    },
                    status: {
                        required: "Status is required"
                    },
                    products: {
                        select2Required: "Please select at least one product"
                    },
                    sections: {
                        select2Required: "Please select at least one section"
                    },
                    categories: {
                        select2Required: "Please select at least one category"
                    },
                    sub_categories: {
                        select2Required: "Please select at least one sub-category"
                    },
                },
                submitHandler: function(form) {
                    $('#description_edit_hidden').val(quillEdit.root.innerHTML);
                    var id = $('#edit_id').val();

                    $.ajax({
                        url: '{{ route('items.update', ':id') }}'.replace(':id', id),
                        type: 'PUT',
                        data: $(form).serialize(),
                        success: function(response) {
                            table.ajax.reload();

                            let offcanvasEditEl = document.getElementById(
                                'offcanvasEditItem');
                            let offcanvasEditObj = bootstrap.Offcanvas.getInstance(
                                offcanvasEditEl);
                            if (!offcanvasEditObj) {
                                offcanvasEditObj = new bootstrap.Offcanvas(offcanvasEditEl);
                            }

                            offcanvasEditObj.hide();

                            form.reset();
                            quillEdit.root.innerHTML = '';

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
                        },
                    });

                    return false;
                }
            });

            $(document).on('click', '.btn-edit', function() {
                var $btn = $(this);
                if ($btn.prop('disabled')) return;
                $btn.prop('disabled', true);

                var id = $btn.data('id');

                $.get('{{ url('dashboard/items') }}/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_title').val(data.title);
                    $('#edit_file_format_id').val(data.file_format_id).trigger('change');
                    $('#edit_price').val(data.price);
                    $('#edit_status').val(data.status).trigger('change');
                    $('#edit_file_name').val(data.file_name);
                    $('#edit_order_number').val(data.order_number);
                    $('#edit_type').val(data.type).trigger('change');

                    quillEdit.root.innerHTML = data.description || '';

                    try {
                        $('#edit_tag').val(data.tag).trigger('change');
                    } catch (e) {
                        console.error('Tag parsing error:', e);
                    }

                    fetchProducts().then(() => {
                        const productIds = data.products.map(p => p.id);
                        $('#edit_products').val(productIds).trigger('change');

                        fetchSections(productIds).then(() => {
                            const sectionIds = data.sections.map(s => s.id);
                            $('#edit_sections').val(sectionIds).trigger('change');

                            fetchCategories(sectionIds).then(() => {
                                const categoryIds = data.categories.map(c =>
                                    c.id);
                                $('#edit_categories').val(categoryIds)
                                    .trigger('change');

                                fetchSubCategories(categoryIds).then(() => {
                                    const subCategoryIds = data
                                        .sub_categories.map(sc => sc
                                            .id);
                                    $('#edit_sub_categories').val(
                                        subCategoryIds).trigger(
                                        'change');

                                    // ✅ Setelah semua selesai, panggil dependency check
                                    window.checkEditDependencies();
                                });
                            });
                        });
                    });

                    $('#offcanvasEditItem').offcanvas('show');
                }).always(function() {
                    $btn.prop('disabled', false);
                });
            });


            // Delete Item
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
                            url: '{{ route('items.destroy', ':id') }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.message
                                });
                            },
                        });
                    }
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
