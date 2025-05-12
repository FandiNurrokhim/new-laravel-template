@php
    $colors = ['bg-light', 'bg-secondary', 'bg-warning', 'bg-info', 'bg-danger', 'bg-success'];
@endphp

<div class="container py-4">
    <div class="row justify-content-center gap-4">
        @foreach ($graveLocations as $index => $group)
            <div class="col-3 p-2 justify-center border rounded bg-light">
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
                                    asset($location->corpseDetail->photo ?? 'img/default-photo.jpg') .
                                    "' alt='Photo' style='width: 100px; height: 100px; object-fit: cover;'>
                                "
                                : 'Kosong';
                        @endphp

                        <div
                            class="grave-box btn d-flex align-items-center justify-content-center p-3 {{ $isOccupied ? 'btn-warning text-white' : 'btn-outline-secondary' }}"
                            style="min-width: 60px;" data-group-id="{{ $group->id }}"
                            data-location-id="{{ $location->id }}" data-occupied="{{ $isOccupied ? '1' : '0' }}"
                            data-bs-toggle="tooltip" data-bs-html="true" title="{!! $tooltipText !!}">
                            {{-- <i class="fa-solid fa-car"></i> --}}
                            <img src="{{asset('img/icons/svg/tomb.svg')}}" width="20" height="20" alt="Tomb Icon">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

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

                if (isOccupied) {
                    Swal.fire('Error', 'This location is already occupied.', 'error');
                    return;
                }

                // Tandai yang diklik
                $(this).removeClass('bg-white').addClass('bg-success');

                // Ambil data
                var groupId = $(this).data('group-id');
                var locationId = $(this).data('location-id');

                // Isi hidden input
                $('#selected_group_id').val(groupId);
                $('#selected_location_id').val(locationId);
            });
        });
    </script>
@endpush
