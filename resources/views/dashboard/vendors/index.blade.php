@extends('layouts.app')

@section('title', 'Manajemen Vendor')

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

        .dt-col-small {
            width: 70px !important;
            text-align: center;
            white-space: nowrap;
        }

        .dt-col-thumbnail {
            width: 80px !important;
            text-align: center;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Vendors List</h4>
        <p class="mb-4">
            This page is used to manage vendors. You can add, edit, and delete vendors here.
        </p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @include('dashboard.vendors.partials.widget')
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="vendors-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
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

    @include('dashboard.vendors.partials.img-preview')
    @include('dashboard.vendors.partials.create')
    @include('dashboard.vendors.partials.edit')
    @include('dashboard.vendors.partials.detail')

@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#vendors-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('vendors.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'dt-col-small',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'thumbnail',
                        className: 'dt-col-thumbnail',
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
                        data: 'status'
                    },
                    {
                        data: 'action',
                        className: 'dt-col-small',
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
                let vendorId = $(this).data('id');
                let title = $(this).data('title');
                let description = $(this).data('description') || '';
                let thumbnail = $(this).data('thumbnail');
                let status = $(this).data('status');
                $('#vendor_id').val(vendorId);
                $('#edit_title').val(title);
                $('#edit_thumbnail').val(thumbnail);
                $('#edit_status').val(status).trigger('change');
                quillEdit.root.innerHTML = description;
                let offcanvasEl = document.getElementById('offcanvasEditVendor');
                let offcanvasObj = bootstrap.Offcanvas.getInstance(offcanvasEl);
                if (!offcanvasObj) {
                    offcanvasObj = new bootstrap.Offcanvas(offcanvasEl);
                }
                offcanvasObj.show();
            });

            $(document).on('click', '.btn-detail', function() {
                let vendorId = $(this).data('id');
                setDetailSkeleton(true);
                let offcanvasDetailEl = document.getElementById('offcanvasDetailVendor');
                let offcanvasDetailObj = bootstrap.Offcanvas.getInstance(offcanvasDetailEl);
                if (!offcanvasDetailObj) {
                    offcanvasDetailObj = new bootstrap.Offcanvas(offcanvasDetailEl);
                }
                offcanvasDetailObj.show();

                $.ajax({
                    url: '/dashboard/vendors/' + vendorId,
                    type: 'GET',
                    success: function(response) {
                        setDetailSkeleton(false);
                        if (response.success) {
                            let vendor = response.vendor;
                            let fallbackThumbnail = '{{ asset('img/elements/dummy.png') }}';
                            let finalThumbnail = vendor.thumbnail ? vendor.thumbnail :
                                fallbackThumbnail;

                            $('#detail_vendor_id').text(vendor.id);
                            $('#detail_vendor_title').text(vendor.title);
                            $('#detail_vendor_description').html(vendor.description || '');
                            $('#detail_vendor_thumbnail').attr('src', finalThumbnail);

                            let statusColors = {
                                'ACTIVE': 'success',
                                'PENDING': 'warning',
                                'BLOCKED': 'danger',
                                'INACTIVE': 'secondary',
                                'SUSPENDED': 'info',
                                'DELETED': 'dark',
                                'BANNED': 'danger',
                                'EXPIRED': 'secondary'
                            };
                            let statusLabels = {
                                'ACTIVE': 'Active',
                                'PENDING': 'Pending',
                                'BLOCKED': 'Blocked',
                                'INACTIVE': 'Inactive',
                                'SUSPENDED': 'Suspended',
                                'DELETED': 'Deleted',
                                'BANNED': 'Banned',
                                'EXPIRED': 'Verification Expired'
                            };

                            let color = statusColors[vendor.status] || 'secondary';
                            let label = statusLabels[vendor.status] || 'Pending';

                            $('#detail_vendor_status')
                                .removeClass()
                                .addClass('badge bg-' + color)
                                .text(label);
                        } else {
                            $('#detail_vendor_id').text('Error');
                            $('#detail_vendor_title').text('Error');
                            $('#detail_vendor_description').text('');
                            $('#detail_vendor_thumbnail').attr('src',
                                '{{ asset('img/elements/dummy.png') }}');
                            $('#detail_vendor_status')
                                .removeClass()
                                .addClass('badge bg-secondary')
                                .text('Error');
                        }
                    },
                    error: function() {
                        setDetailSkeleton(false);
                        $('#detail_vendor_id').text('Error');
                        $('#detail_vendor_title').text('Error');
                        $('#detail_vendor_description').text('');
                        $('#detail_vendor_thumbnail').attr('src',
                            '{{ asset('img/elements/dummy.png') }}');
                        $('#detail_vendor_status')
                            .removeClass()
                            .addClass('badge bg-secondary')
                            .text('Error');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let vendorId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.showLoading();
                        $.ajax({
                            url: '/dashboard/vendors/' + vendorId,
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
                                Swal.close();
                                table.ajax.reload();
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

            $('#addNewVendorForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    thumbnail: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: 'Vendor title must be filled'
                    },
                    thumbnail: {
                        required: 'Thumbnail must be filled'
                    },
                    status: {
                        required: 'Status must be selected'
                    }
                },
                submitHandler: function(form) {
                    $('#description_add_hidden').val(quillAdd.root.innerHTML);
                    Swal.showLoading();
                    $.ajax({
                        url: '{{ route('vendors.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            table.ajax.reload();
                            let offcanvasAdd = bootstrap.Offcanvas.getInstance(document
                                .getElementById('offcanvasAddVendor'));
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

            $('#editVendorForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    thumbnail: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    title: {
                        required: 'Vendor title must be filled'
                    },
                    thumbnail: {
                        required: 'Thumbnail must be filled'
                    },
                    status: {
                        required: 'Status must be selected'
                    }
                },
                submitHandler: function(form) {
                    $('#description_edit_hidden').val(quillEdit.root.innerHTML);
                    let vendorId = $('#vendor_id').val();
                    Swal.showLoading();
                    $.ajax({
                        url: '/dashboard/vendors/' + vendorId,
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            let offcanvasEditEl = document.getElementById(
                                'offcanvasEditVendor');
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
                    '#detail_vendor_id',
                    '#detail_vendor_title',
                    '#detail_vendor_description',
                    '#detail_vendor_thumbnail',
                    '#detail_vendor_status'
                ];
                if (isLoading) {
                    fields.forEach(function(selector) {
                        if (selector === '#detail_vendor_thumbnail') {
                            $(selector).attr('src', '').addClass('placeholder-skeleton');
                        } else {
                            $(selector).empty().addClass('placeholder-skeleton');
                        }
                    });
                } else {
                    fields.forEach(function(selector) {
                        $(selector).removeClass('placeholder-skeleton');
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
        });
    </script>
@endpush
