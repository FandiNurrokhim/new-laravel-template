<!-- filepath: c:\joki program skripsi\iqsan\makam-management\resources\views\dashboard\grave-management\corpse-detail\partials\edit.blade.php -->
<div class="modal fade" id="editCorpseDetailModal" tabindex="-1" aria-labelledby="editCorpseDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCorpseDetailForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCorpseDetailModalLabel">Edit Detail Mayit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_group_id" class="form-label">Kelompok Makam <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="select2 form-select" id="edit_group_id" name="group_id" required>
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
                        <label for="edit_name" class="form-label">Nama <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_date" class="form-label">Tanggal Lahir <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="date" class="form-control" id="edit_birth_date" name="birth_date" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_place" class="form-label">Tempat Lahir <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="edit_birth_place" name="birth_place">
                    </div>
                    <div class="mb-3">
                        <label for="edit_death_date" class="form-label">Tanggal Meninggal <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="date" class="form-control" id="edit_death_date" name="death_date" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_javanese_weton" class="form-label">Hari Meniggal <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="form-select" id="edit_javanese_day_death" name="javanese_day" required>
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
                        <label for="edit_javanese_weton" class="form-label">Weton <span class="text-danger">(wajib Diisi)</span></label>
                        <select class="form-select" id="edit_javanese_weton" name="javanese_weton" required>
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
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
