@extends('layouts.app')

@section('title', 'File Formats Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">File Formats</h4>
        <p class="mb-4">
            This page is used to manage file formats. You can add, edit, and delete file formats here.
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
                                <button data-bs-target="#offcanvasAddFileFormat" data-bs-toggle="offcanvas"
                                    class="btn btn-primary mb-3 text-nowrap add-new">
                                    Add File Format
                                </button>
                                <p class="mb-0">Add file format, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTables File Formats Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables table border-top" id="file-formats-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Thumbnail</th>
                                    <th>Description</th>
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

    @include('dashboard.master-data.file-formats.partials.add-modal')
    @include('dashboard.master-data.file-formats.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#file-formats-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('file-formats.index') }}',
                order: [
                    [6, 'desc']
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
                        data: 'thumbnail',
                        name: 'thumbnail'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
                        visible: false
                    }
                ]
            });

            $('#addFileFormatForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    status: {
                        required: true
                    },
                },
                messages: {
                    title: {
                        required: "Title is required",
                        minlength: "Title must be at least 3 characters"
                    },
                    status: {
                        required: "Status is required"
                    },
                },
                submitHandler: function(form) {
                    $('#description_add_hidden').val(quillAdd.root.innerHTML);

                    $.ajax({
                        url: '{{ route('file-formats.store') }}',
                        type: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#file-formats-table').DataTable().ajax.reload();

                            let offcanvasAdd = bootstrap.Offcanvas.getInstance(document
                                .getElementById('offcanvasAddFileFormat'));
                            if (offcanvasAdd) {
                                offcanvasAdd.hide();
                            }

                            form.reset();
                            quillAdd.root.innerHTML = '';
                            Swal.fire('Success', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON.message, 'error');
                        }
                    });
                    return false;
                }
            });

            $(document).on('click', '.btn-edit', function() {
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                $btn.prop('disabled', true);

                var id = $btn.data('id');

                $.get('{{ url('dashboard/file-formats') }}/' + id, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_title').val(data.title);
                    $('#edit_thumbnail').val(data.thumbnail);
                    $('#edit_status').val(data.status).trigger('change');
                    quillEdit.root.innerHTML = data.description || '';

                    let offcanvasEl = document.getElementById('offcanvasEditFileFormat');
                    let offcanvasObj = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (!offcanvasObj) {
                        offcanvasObj = new bootstrap.Offcanvas(offcanvasEl);
                    }
                    offcanvasObj.show();
                }).always(function() {
                    $btn.prop('disabled', false);
                });
            });


            $('#editFileFormatForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    status: {
                        required: true
                    },
                },
                messages: {
                    title: {
                        required: "Title is required",
                        minlength: "Title must be at least 3 characters"
                    },
                    status: {
                        required: "Status is required"
                    },
                },
                submitHandler: function(form) {
                    $('#description_edit_hidden').val(quillEdit.root.innerHTML);

                    var id = $('#edit_id').val();

                    $.ajax({
                        url: '{{ route('file-formats.update', ':id') }}'.replace(':id', id),
                        type: 'PUT',
                        data: $(form).serialize(),
                        success: function(response) {
                            $('#file-formats-table').DataTable().ajax.reload();

                            let offcanvasEditEl = document.getElementById(
                                'offcanvasEditFileFormat');
                            let offcanvasEditObj = bootstrap.Offcanvas.getInstance(
                                offcanvasEditEl);
                            if (!offcanvasEditObj) {
                                offcanvasEditObj = new bootstrap.Offcanvas(offcanvasEditEl);
                            }

                            offcanvasEditObj.hide();
                            form.reset();
                            quillEdit.root.innerHTML = '';

                            Swal.fire('Success', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON.message, 'error');
                        }
                    });

                    return false;
                }
            });

            // Delete File Format
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
                            url: '{{ url('dashboard/file-formats') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#file-formats-table').DataTable().ajax.reload();
                                Swal.fire('Deleted!', response.message, 'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
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
