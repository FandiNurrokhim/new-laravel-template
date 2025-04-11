@extends('layouts.app')

@section('title', 'Manajemen Kategori')

{{-- push styles --}}
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
        <h4 class="fw-bold py-3 pb-0 mb-2">Categories List</h4>
        <p class="mb-4">
            This page is used to manage categories. You can add, edit, and delete categories here.
        </p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @include('dashboard.categories.partials.widget')
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="categories-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.categories.partials.img-preview')
    @include('dashboard.categories.partials.create')
    @include('dashboard.categories.partials.edit')
    @include('dashboard.categories.partials.detail')

@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('categories.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'thumbnail',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            let fallbackUrl = '{{ asset('img/elements/dummy.png') }}';
                            let finalUrl = data ? data : fallbackUrl;
                            return '<img src="' + finalUrl +
                                '" width="50" height="50" class="clickable-thumbnail" data-full-url="' +
                                finalUrl + '" onerror="this.onerror=null;this.src=\'' +
                                fallbackUrl + '\'" style="cursor:pointer;" />';
                        }
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'section'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    sLengthMenu: '_MENU_',
                    search: 'Search',
                    searchPlaceholder: 'Search..'
                }
            });

            $(document).on('click', '.clickable-thumbnail', function() {
                let fullUrl = $(this).data('full-url');
                $('#fullImagePreview').attr('src', fullUrl);
                $('#imagePreviewModal').modal('show');
            });

            $(document).on('click', '.btn-edit', function() {
                let categoryId = $(this).data('id');
                let title = $(this).data('title');
                let slug = $(this).data('slug');
                let description = $(this).data('description') || '';
                let thumbnail = $(this).data('thumbnail');
                let order_number = $(this).data('order-number'); 
                let section_id = $(this).data('section-id');
                let status = $(this).data('status');
                
                $('#category_id').val(categoryId);
                $('#edit_title').val(title);
                $('#edit_slug').val(slug);
                $('#edit_thumbnail').val(thumbnail);
                $('#edit_order_number').val(order_number);
                $('#edit_section_id').val(section_id).trigger('change');
                $('#edit_status').val(status).trigger('change');
                quillEdit.root.innerHTML = description;
                
                let offcanvasEl = document.getElementById('offcanvasEditCategory');
                let offcanvasObj = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if (!offcanvasObj) {
                    offcanvasObj = new bootstrap.Offcanvas(offcanvasEl);
                }
                offcanvasObj.show();
            });

            $(document).on('click', '.btn-detail', function() {
                let categoryId = $(this).data('id');
                setDetailSkeleton(true);
                let offcanvasDetailEl = document.getElementById('offcanvasDetailCategory');
                let offcanvasDetailObj = bootstrap.Offcanvas.getInstance(offcanvasDetailEl);
                if (!offcanvasDetailObj) {
                    offcanvasDetailObj = new bootstrap.Offcanvas(offcanvasDetailEl);
                }
                offcanvasDetailObj.show();

                $.ajax({
                    url: '/dashboard/categories/' + categoryId,
                    type: 'GET',
                    success: function(response) {
                        setDetailSkeleton(false);
                        if (response.success) {
                            let category = response.category;
                            let fallbackThumbnail = '{{ asset('img/elements/dummy.png') }}';
                            let finalThumbnail = category.thumbnail ? category.thumbnail :
                                fallbackThumbnail;

                            $('#detail_category_id').text(category.id);
                            $('#detail_category_title').text(category.title);
                            $('#detail_category_slug').text(category.slug);
                            $('#detail_category_description').html(category.description || '');
                            $('#detail_category_thumbnail').attr('src', finalThumbnail);
                            $('#detail_category_order_number').text(category.order_number);
                            $('#detail_category_section').text(category.section ? category.section.title : 'N/A');

                            let statusColors = {
                                'PUBLISHED': 'success',
                                'DRAFT': 'info',
                                'PENDING': 'warning',
                                'INACTIVE': 'secondary',
                                'ARCHIVED': 'secondary',
                                'DELETED': 'dark'
                            };
                            let statusLabels = {
                                'PUBLISHED': 'Published',
                                'DRAFT': 'Draft',
                                'PENDING': 'Pending',
                                'INACTIVE': 'Inactive',
                                'ARCHIVED': 'Archived',
                                'DELETED': 'Deleted'
                            };

                            let statusColor = statusColors[category.status] || 'secondary';
                            let statusLabel = statusLabels[category.status] || 'Unknown';

                            $('#detail_category_status')
                                .removeClass()
                                .addClass('badge bg-' + statusColor)
                                .text(statusLabel);
                        } else {
                            $('#detail_category_id').text('Error');
                            $('#detail_category_title').text('Error');
                            $('#detail_category_slug').text('Error');
                            $('#detail_category_description').text('');
                            $('#detail_category_thumbnail').attr('src',
                                '{{ asset('img/elements/dummy.png') }}');
                            $('#detail_category_order_number').text('Error');
                            $('#detail_category_section').text('Error');
                            $('#detail_category_status')
                                .removeClass()
                                .addClass('badge bg-secondary')
                                .text('Error');
                        }
                    },
                    error: function() {
                        setDetailSkeleton(false);
                        $('#detail_category_id').text('Error');
                        $('#detail_category_title').text('Error');
                        $('#detail_category_slug').text('Error');
                        $('#detail_category_description').text('');
                        $('#detail_category_thumbnail').attr('src',
                            '{{ asset('img/elements/dummy.png') }}');
                        $('#detail_category_order_number').text('Error');
                        $('#detail_category_section').text('Error');
                        $('#detail_category_status')
                            .removeClass()
                            .addClass('badge bg-secondary')
                            .text('Error');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let categoryId = $(this).data('id');
                Swal.showLoading();
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/dashboard/categories/' + categoryId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.close();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message
                                });
                                table.ajax.reload();
                                updateWidgetStats();
                            },
                            error: function(xhr) {
                                Swal.close();
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

            $('#addNewCategoryForm').validate({
                rules: {
                    section_id: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    slug: {
                        required: true
                    },
                    order_number: {
                        required: true,
                        number: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    section_id: {
                        required: 'Section must be selected'
                    },
                    title: {
                        required: 'Category title must be filled'
                    },
                    slug: {
                        required: 'Slug must be filled'
                    },
                    order_number: {
                        required: 'Order number must be filled',
                        number: 'Please enter a valid number'
                    },
                    status: {
                        required: 'Status must be selected'
                    }
                },
                submitHandler: function(form) {
                    $('#description_add_hidden').val(quillAdd.root.innerHTML);
                    Swal.showLoading();
                    $.ajax({
                        url: '{{ route('categories.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            table.ajax.reload();
                            let offcanvasAdd = bootstrap.Offcanvas.getInstance(document
                                .getElementById('offcanvasAddCategory'));
                            if (offcanvasAdd) {
                                offcanvasAdd.hide();
                            }
                            form.reset();
                            quillAdd.root.innerHTML = '';
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });
                            updateWidgetStats();
                        },
                        error: function(xhr) {
                            Swal.close();
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

            $('#editCategoryForm').validate({
                rules: {
                    section_id: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    slug: {
                        required: true
                    },
                    order_number: {
                        required: true,
                        number: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    section_id: {
                        required: 'Section must be selected'
                    },
                    title: {
                        required: 'Category title must be filled'
                    },
                    slug: {
                        required: 'Slug must be filled'
                    },
                    order_number: {
                        required: 'Order number must be filled',
                        number: 'Please enter a valid number'
                    },
                    status: {
                        required: 'Status must be selected'
                    }
                },
                submitHandler: function(form) {
                    $('#description_edit_hidden').val(quillEdit.root.innerHTML);
                    let categoryId = $('#category_id').val();
                    Swal.showLoading();
                    $.ajax({
                        url: '/dashboard/categories/' + categoryId,
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            let offcanvasEditEl = document.getElementById(
                                'offcanvasEditCategory');
                            let offcanvasEditObj = bootstrap.Offcanvas.getInstance(
                                offcanvasEditEl);
                            if (!offcanvasEditObj) {
                                offcanvasEditObj = new bootstrap.Offcanvas(offcanvasEditEl);
                            }
                            offcanvasEditObj.hide();
                            table.ajax.reload();
                            form.reset();
                            quillEdit.root.innerHTML = '';
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });
                            updateWidgetStats();
                        },
                        error: function(xhr) {
                            Swal.close();
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

            function setDetailSkeleton(isLoading) {
                let fields = [
                    '#detail_category_id',
                    '#detail_category_title',
                    '#detail_category_slug',
                    '#detail_category_description',
                    '#detail_category_thumbnail',
                    '#detail_category_order_number',
                    '#detail_category_section',
                    '#detail_category_status'
                ];
                if (isLoading) {
                    fields.forEach(function(selector) {
                        if (selector === '#detail_category_thumbnail') {
                            $(selector).attr('src', '').addClass('placeholder-skeleton');
                        } else {
                            $(selector).empty().addClass('placeholder-skeleton');
                        }
                    });
                } else {
                    fields.forEach(function(selector) {
                        if (selector === '#detail_category_thumbnail') {
                            $(selector).removeClass('placeholder-skeleton');
                        } else {
                            $(selector).removeClass('placeholder-skeleton');
                        }
                    });
                }
            }

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
            
            // Auto generate slug from title
            $('#title, #edit_title').on('keyup', function() {
                let title = $(this).val();
                let slug = title.toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                
                if ($(this).attr('id') === 'title') {
                    $('#slug').val(slug);
                } else {
                    $('#edit_slug').val(slug);
                }
            });
        });

        $(document).on('click', '[data-bs-target="#offcanvasAddCategory"]', function() {
            $('#addNewCategoryForm')[0].reset();
            quillAdd.root.innerHTML = '';
            
            $.ajax({
                url: '{{ route("categories.get-next-order-number") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#order_number').val(response.next_order_number);
                    }
                },
                error: function() {
                    $('#order_number').val(0);
                }
            });
        });


        // Fungsi untuk memperbarui widget statistik
        function updateWidgetStats() {
            $.ajax({
                url: window.location.pathname + '?get_stats=true',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Update nilai statistik pada widget
                    if (response.stats) {
                        if (response.stats.totalCategoryPublished !== undefined) {
                            $('.category-published-count').text(response.stats.totalCategoryPublished);
                        }
                        if (response.stats.totalCategoryDraft !== undefined) {
                            $('.category-draft-count').text(response.stats.totalCategoryDraft);
                        }
                    }
                }
            });
        }
    </script>
@endpush