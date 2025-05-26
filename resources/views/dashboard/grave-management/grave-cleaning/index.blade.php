@extends('layouts.app')

@section('title', 'Manajemen Permintaan Lokasi Makam')

@section('content')
    <style>
        #grave-cleaning-request-table td:nth-child(8),
        #grave-cleaning-request-table th:nth-child(8) {
            min-width: 400px;
            max-width: 700px;
            width: 700px;
            white-space: normal !important;
            word-break: break-word;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Permintaan Pembersihan Makam</h4>
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables table border-top" id="grave-cleaning-request-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemohon</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>RT</th>
                            <th>RW</th>
                            <th>Dusun</th>
                            <th>Nama Makam</th>
                            <th>Bukti Pembersihan Selesai</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pengerjaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('dashboard.grave-management.request-location.partials.location-detail')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#grave-cleaning-request-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('grave-cleaning-request.index') }}',
                order: [
                    [12, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id',
                        title: 'No'
                    },
                    {
                        data: 'requester_name',
                        name: 'requester_name',
                        title: 'Nama Pemohon'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        title: 'No. Telepon'
                    },
                    {
                        data: 'address',
                        name: 'address',
                        title: 'Alamat'
                    },
                    {
                        data: 'rt',
                        name: 'rt',
                        title: 'RT'
                    },
                    {
                        data: 'rw',
                        name: 'rw',
                        title: 'RW'
                    },
                    {
                        data: 'dusun',
                        name: 'dusun',
                        title: 'Dusun'
                    },
                    {
                        data: 'grave_detail',
                        name: 'grave_detail',
                        width: '700px',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'proof_photo',
                        name: 'proof_photo',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'work_status',
                        name: 'work_status',
                        orderable: false,
                        searchable: false
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

            $(document).on('click', '.btn-cancel-request', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Batalkan Permintaan',
                    text: "Apakah Anda yakin ingin membatalkan permintaan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('dashboard/grave-cleaning-request') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Success', response.message ||
                                    'Permintaan berhasil dibatalkan.', 'success');
                                $('#grave-cleaning-request-table').DataTable().ajax
                                    .reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message ||
                                    'Gagal membatalkan permintaan.', 'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btn-confirm-payment', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Pembayaran',
                    text: "Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus(id, 'paid');
                    }
                });
            });


            function updateStatus(id, status) {
                $.ajax({
                    url: '{{ route('grave-cleaning-request.update', ':id') }}'.replace(':id', id),
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        Swal.fire('Success', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            }

            // Handle dropdown actions
            $(document).on('click', '.btn-upload-photo', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Buat input file secara dinamis
                var $input = $('<input type="file" accept="image/*" style="display:none" />');
                $('body').append($input);

                $input.on('change', function() {
                    var file = this.files[0];
                    if (!file) return;

                    var formData = new FormData();
                    formData.append('proof_photo', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: '/dashboard/grave-cleaning-request/' + id +
                            '/upload-proof', // Ganti sesuai route upload kamu
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire('Success', response.message, 'success');
                            $('#grave-cleaning-request-table').DataTable().ajax
                                .reload();
                        },
                        error: function(xhr) {
                            let msg = 'Terjadi kesalahan.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                    $input.remove();
                });

                $input.click();
            });
        });
    </script>
@endpush
