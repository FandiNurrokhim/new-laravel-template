<!-- filepath: c:\joki program skripsi\iqsan\makam-management\resources\views\dashboard\grave-management\corpse-detail\partials\edit.blade.php -->
<div class="modal fade" id="editCorpseDetailModal" tabindex="-1" aria-labelledby="editCorpseDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCorpseDetailForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCorpseDetailModalLabel">Edit Corpse Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_group_id" class="form-label">Grave Group</label>
                        <select class="form-select" id="edit_group_id" name="group_id" required>
                            <option value="" disabled selected>Select a group</option>
                            @foreach ($graveGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grave_location_id" class="form-label">Grave Location</label>
                        <div class="d-flex flex-wrap grave-box-container">
                            <!-- Grave boxes will be dynamically populated here -->
                        </div>
                        <input type="hidden" id="grave_location_id" name="grave_location_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="edit_birth_date" name="birth_date" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_place" class="form-label">Birth Place</label>
                        <input type="text" class="form-control" id="edit_birth_place" name="birth_place">
                    </div>
                    <div class="mb-3">
                        <label for="edit_death_date" class="form-label">Death Date</label>
                        <input type="date" class="form-control" id="edit_death_date" name="death_date" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_javanese_day" class="form-label">Javanese Day</label>
                        <select class="form-select" id="edit_javanese_day" name="javanese_day">
                            <option value="" disabled selected>Select a Javanese Day</option>
                            <option value="Legi">Legi</option>
                            <option value="Pahing">Pahing</option>
                            <option value="Pon">Pon</option>
                            <option value="Wage">Wage</option>
                            <option value="Kliwon">Kliwon</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
