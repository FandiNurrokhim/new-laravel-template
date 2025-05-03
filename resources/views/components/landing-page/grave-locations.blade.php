@php
    $colors = ['bg-light', 'bg-secondary', 'bg-warning', 'bg-info', 'bg-danger', 'bg-success'];
@endphp

<div class="container">
    <div class="row">
        @foreach ($graveLocations as $index => $group)
            @php
                $groupColor = $colors[$index % count($colors)];
            @endphp

            <div class="col-12 my-3 p-3 border rounded {{ $groupColor }}">
                <h5 class="text-center mb-3">{{ $group->name }}</h5>

                <div class="d-flex flex-wrap justify-content-center">
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
                                    <img src='" . asset($location->corpseDetail->photo ?? 'img/default-photo.jpg') . "' alt='Photo' style='width: 100px; height: 100px; object-fit: cover;'>
                                "
                                : 'Kosong';
                        @endphp

                        <div class="grave-box m-1 d-flex align-items-center justify-content-center text-white {{ $isOccupied ? 'bg-dark' : 'bg-white' }}"
                            style="width: 40px; height: 40px; border: 1px solid #000; border-radius: 4px; cursor: pointer;"
                            data-group-id="{{ $group->id }}" data-location-id="{{ $location->id }}"
                            data-occupied="{{ $isOccupied ? '1' : '0' }}" data-bs-toggle="tooltip" data-bs-html="true"
                            title="{!! $tooltipText !!}">
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

                // Reset semua kotak jadi putih (kecuali yang occupied / bg-dark)
                $('.grave-box').not('.bg-dark').removeClass('bg-primary').addClass('bg-white');

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