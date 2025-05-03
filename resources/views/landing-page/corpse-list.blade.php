@extends('layouts.home-layout')

@section('title', 'Manajemen Permintaan Lokasi Makam')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 pb-0 mb-2">Daftar Jenazah</h4>
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables table border-top" id="corpse-list-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Tanggal Meninggal</th>
                            <th>Umur saat Meninggal</th>
                            <th>Weton saat meninggal</th>
                            <th>Lokasi Kuburan</th>
                            <th>Grup Makam</th>
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
            var table = $('#corpse-list-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('corpse-list') }}',
                order: [
                    [8, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'birth_date',
                        name: 'birth_date'
                    },
                    {
                        data: 'death_date',
                        name: 'death_date'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'javanese_death_date',
                        name: 'javanese_death_date'
                    },
                    {
                        data: 'location_code',
                        name: 'location_code',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'group_name',
                        name: 'group_name',
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
        });
    </script>
@endpush
