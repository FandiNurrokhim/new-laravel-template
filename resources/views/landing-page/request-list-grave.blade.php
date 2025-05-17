<div class="container mt-4 pt-5">
    <h3 class="text-center mb-4">Request Lokasi Makam</h3>

    <form id="requestLocationForm" enctype="multipart/form-data" method="POST" action="{{ route('create-request') }}">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="grave_location_id" class="form-label">Lokasi Makam <span class="text-danger">(wajib
                            Diisi)</span></label>
                    @foreach ($graveLocations as $index => $group)
                        <div class="col-12 p-2 justify-center border rounded bg-light">
                            <h5 class="text-center mb-3">{{ $group->name }}</h5>
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                @foreach ($group->locations as $location)
                                    @php
                                        $isOccupied = $location->corpseDetail !== null;
                                        $hasRequester = isset($location->request) && $location->request !== null;
                                        $boxClass = $isOccupied
                                            ? 'btn-warning text-white'
                                            : ($hasRequester
                                                ? 'btn-info text-white'
                                                : 'btn-outline-secondary');
                                        $tooltipText = $isOccupied
                                            ? 'Sudah terisi'
                                            : ($hasRequester
                                                ? 'Sudah ada permohonan oleh: ' . $location->request->requester_name
                                                : 'Tersedia');
                                    @endphp
                                    <div class="grave-box-request btn d-flex align-items-center justify-content-center p-3 {{ $boxClass }}"
                                        style="min-width: 60px;" data-group-id="{{ $group->id }}"
                                        data-location-id="{{ $location->id }}"
                                        data-occupied="{{ $isOccupied ? '1' : '0' }}"
                                        data-requested="{{ $hasRequester ? '1' : '0' }}" data-bs-toggle="tooltip"
                                        data-bs-html="true" title="{{ $tooltipText }}">
                                        <img src="{{ asset('img/icons/svg/tomb.svg') }}" width="20" height="20"
                                            alt="Tomb Icon">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="requester_name" class="form-label">Nama Pemohon <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <input type="text" class="form-control" id="requester_name" name="requester_name" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat Pemohon <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Nomor Telepon <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                </div>
                <div class="mb-3">
                    <label for="rt" class="form-label">RT <span class="text-danger">(wajib Diisi)</span></label>
                    <input type="number" class="form-control" id="rt" name="rt" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="rw" class="form-label">RW <span class="text-danger">(wajib Diisi)</span></label>
                    <input type="number" class="form-control" id="rw" name="rw" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="dusun" class="form-label">Dusun <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <input type="text" class="form-control" id="dusun" name="dusun" required>
                </div>
                <div class="mb-3">
                    <label for="corpse_name" class="form-label">Nama Mayit <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <input type="text" class="form-control" id="corpse_name" name="corpse_name" required>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date"
                        max="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label for="birth_place" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="birth_place" name="birth_place">
                </div>
                <div class="mb-3">
                    <label for="death_date" class="form-label">Tanggal Meninggal <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <input type="date" class="form-control" id="death_date" name="death_date"
                        max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label for="javanese_day" class="form-label">Hari Meninggal <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <select class="form-select" id="javanese_day" name="javanese_day" required>
                        <option value="" disabled selected>Pilih Hari</option>
                        <option value="Minggu">Minggu</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="javanese_weton" class="form-label">Weton Meninggal <span class="text-danger">(wajib
                            Diisi)</span></label>
                    <select class="form-select" id="javanese_weton" name="javanese_weton" required>
                        <option value="" disabled selected>Pilih Weton</option>
                        <option value="Legi">Legi</option>
                        <option value="Pahing">Pahing</option>
                        <option value="Pon">Pon</option>
                        <option value="Wage">Wage</option>
                        <option value="Kliwon">Kliwon</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        console.log('Document is ready');
        // Grave box click handler (pastikan ini sesuai dengan selector grave-box di komponen)
        $(document).on('click', '.grave-box-request', function() {
            var locationId = $(this).data('location-id');
            // Ganti URL berikut sesuai route yang mengembalikan data pemohon berdasarkan lokasi
            $.get('/grave-location/' + locationId + '/requester', function(data) {
                // Isi field form dan set readonly
                $('#requester_name').val(data.requester_name).prop('readonly', true);
                $('#address').val(data.address).prop('readonly', true);
                $('#phone_number').val(data.phone_number).prop('readonly', true);
                $('#rt').val(data.rt).prop('readonly', true);
                $('#rw').val(data.rw).prop('readonly', true);
                $('#dusun').val(data.dusun).prop('readonly', true);
                $('#corpse_name').val(data.corpse_name).prop('readonly', true);
                $('#birth_date').val(data.birth_date).prop('readonly', true);
                $('#birth_place').val(data.birth_place).prop('readonly', true);
                $('#death_date').val(data.death_date).prop('readonly', true);
                $('#javanese_day').val(data.javanese_day).prop('disabled', true);
                $('#javanese_weton').val(data.javanese_weton).prop('disabled', true);
                $('#notes').val(data.notes).prop('readonly', true);
            });
        });
    });
</script>
