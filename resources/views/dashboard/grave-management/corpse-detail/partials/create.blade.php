<div class="modal fade" id="addCorpseDetailModal" tabindex="-1" aria-labelledby="addCorpseDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form id="addCorpseDetailForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCorpseDetailModalLabel">Add Corpse Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="group_id" class="form-label">Kelompok Makam <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="select2 form-select" id="group_id" name="group_id" required>
                            <option value="" disabled selected>Pilih kelompok</option>
                            @foreach ($graveGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grave_location_id" class="form-label">Lokasi Makam <span class="text-danger">(wajib Diisi)</span></label>
                        <div class="d-flex flex-wrap grave-box-container">
                            <!-- Grave boxes will be dynamically populated here -->
                        </div>
                        <input type="hidden" id="grave_location_id" name="grave_location_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date"  max="{{ date('Y-m-d') }}"> 
                    </div>
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place">
                    </div>
                    <div class="mb-3">
                        <label for="death_date" class="form-label">Tanggal Meninggal <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="date" class="form-control" id="death_date" name="death_date" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_javanese_weton" class="form-label">Hari Meniggal <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="form-select" id="javanese_day_death" name="javanese_day" required>
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
                        <label for="javanese_weton" class="form-label">Weton <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="form-select" id="javanese_weton" name="javanese_weton">
                            <option value="" disabled selected>Pilih Weton</option>
                            <option value="Legi">Legi</option>
                            <option value="Pahing">Pahing</option>
                            <option value="Pon">Pon</option>
                            <option value="Wage">Wage</option>
                            <option value="Kliwon">Kliwon</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
