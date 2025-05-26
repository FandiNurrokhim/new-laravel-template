@props([
    'graveLocations' => [],
    'showSearch' => false,
    'isRequestCleaning' => false,
])

@if ($showSearch)
    <div class="mb-3">
        <input type="text" id="grave-search" class="form-control" placeholder="Cari nama jenazah...">
    </div>
@endif

<div class="row gap-4">
    @foreach ($graveLocations as $index => $group)
        <div class="col-12 p-2 justify-center border rounded bg-light">
            <h5 class="text-center mb-3">{{ $group->name }}</h5>
            <div class="d-flex flex-wrap justify-content-center gap-1">
                @foreach ($group->locations as $location)
                    @php
                        $isOccupied = $location->corpseDetail !== null;
                        $tooltipText = $isOccupied
                            ? "
                                <strong>Name:</strong> {$location->corpseDetail->name}<br>
                                <strong>Birth Date:</strong> {$location->corpseDetail->birth_date}<br>
                                <strong>Birth Place:</strong> {$location->corpseDetail->birth_place}<br>
                                <strong>Age:</strong> {$location->corpseDetail->age}<br>
                                <strong>Death Date:</strong> {$location->corpseDetail->death_date}<br>
                                <strong>Javanese Weton:</strong> {$location->corpseDetail->javanese_weton}<br>
                                <img src='" .
                                asset($location->corpseDetail->photo ?? 'img/default-photo.png') .
                                "' alt='Photo' class='img-fluid' width='100' height='100'>
                                "
                            : 'Kosong';
                    @endphp

                    <div class="grave-box btn d-flex align-items-center justify-content-center p-3 {{ $isOccupied ? 'btn-warning text-white' : 'btn-outline-secondary' }}"
                        style="min-width: 60px;" data-group-id="{{ $group->id }}"
                        data-location-id="{{ $location->id }}" data-occupied="{{ $isOccupied ? '1' : '0' }}"
                        data-name="{{ $location->corpseDetail->name ?? '' }}"
                        data-photo="{{ $location->corpseDetail->photo ?? asset('img/default-photo.png') }}"
                        data-birth-date="{{ $location->corpseDetail->birth_date ?? '' }}"
                        data-birth-place="{{ $location->corpseDetail->birth_place ?? '' }}"
                        data-age="{{ $location->corpseDetail->age ?? '' }}"
                        data-death-date="{{ $location->corpseDetail->death_date ?? '' }}"
                        data-javanese-weton="{{ $location->corpseDetail->javanese_weton ?? '' }}"
                        data-bs-toggle="tooltip" data-bs-html="true" title="{!! $tooltipText !!}">
                        <img src="{{ asset('img/icons/svg/tomb.svg') }}" width="20" height="20" alt="Tomb Icon">
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Hidden Inputs -->
    <input type="hidden" id="selected_group_id" name="grave_group_id">
    <input type="hidden" id="selected_location_id" name="grave_location_id">
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle klik pada grave-box
            $(document).on('click', '.grave-box', function() {
                var isOccupied = $(this).data('occupied') == 1;
                var isRequestCleaning = @json($isRequestCleaning);

                if (isRequestCleaning) {
                    // Pada mode request cleaning, hanya bisa pilih yang occupied
                    if (!isOccupied) {
                        Swal.fire('Info', 'Lokasi kosong tidak dapat dipilih untuk permintaan pembersihan.',
                            'info');
                        return;
                    }
                } else {
                    // Mode biasa, tidak bisa pilih yang occupied
                    if (isOccupied) {
                        Swal.fire('Error', 'This location is already occupied.', 'error');
                        return;
                    }
                }

                // Hapus bg-success dari semua grave-box yang bisa dipilih
                if (isRequestCleaning) {
                    $('.grave-box[data-occupied="1"]').removeClass('bg-success selected');
                } else {
                    $('.grave-box').not('[data-occupied="1"]').removeClass('bg-success selected');
                }

                // Tandai yang diklik
                $(this).addClass('bg-success selected');

                // Ambil data
                var groupId = $(this).data('group-id');
                var locationId = $(this).data('location-id');

                // Isi hidden input
                $('#selected_group_id').val(groupId);
                $('#selected_location_id').val(locationId);


                var name = $(this).data('name') || '-';
                var photo = $(this).data('photo') || '{{ asset('img/default-photo.png') }}';
                var birthDate = $(this).data('birth-date') || '-';
                var birthPlace = $(this).data('birth-place') || '-';
                var age = $(this).data('age') || '-';
                var deathDate = $(this).data('death-date') || '-';
                var weton = $(this).data('javanese-weton') || '-';

                // Render profil ke #grave-profile
                $('#grave-profile').html(`
        <div class="card mb-3">
            <div class="row g-0 align-items-center">
                <div class="col-auto">
                    <img src="${photo}" alt="Foto" class="img-fluid rounded" width="80" height="80">
                </div>
                <div class="col">
                    <div class="card-body py-2">
                        <h5 class="card-title mb-1">${name}</h5>
                        <div class="small text-muted">TTL: ${birthPlace}, ${birthDate}</div>
                        <div class="small text-muted">Umur: ${age} | Weton: ${weton}</div>
                        <div class="small text-muted">Tanggal Wafat: ${deathDate}</div>
                    </div>
                </div>
            </div>
        </div>
    `);
            });

            @if ($showSearch)
                let searchTimeout;
                $('#grave-search').on('keyup', function() {
                    clearTimeout(searchTimeout);
                    var input = $(this);
                    searchTimeout = setTimeout(function() {
                        var keyword = input.val().toLowerCase();

                        // Debug: cek apakah timeout berjalan
                        console.log('Debounce search triggered after 500ms with keyword:', keyword);

                        $('.grave-box').each(function() {
                            var name = ($(this).data('name') || '').toLowerCase();

                            if (keyword === '') {
                                // Jika kosong, hapus highlight
                                $(this).removeClass('bg-danger border border-success');
                            } else if (name.includes(keyword)) {
                                // Jika cocok, highlight
                                $(this).addClass('bg-danger border border-success');
                            } else {
                                // Jika tidak cocok, hapus highlight
                                $(this).removeClass('bg-danger border border-success');
                            }
                        });
                    }, 500);
                });
            @endif
        });
    </script>
@endpush
