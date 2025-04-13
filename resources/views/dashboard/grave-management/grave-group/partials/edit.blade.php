<div class="modal fade" id="editGraveGroupModal" tabindex="-1" aria-labelledby="editGraveGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editGraveGroupForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGraveGroupModalLabel">Edit Grave Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Kelompok</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_max_graves" class="form-label">Maksimal Jumlah Makam (Max 500)</label>
                        <input type="number" class="form-control" min="0" max="500" id="edit_max_graves" name="max_graves" required>
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