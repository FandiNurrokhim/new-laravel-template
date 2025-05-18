<div class="modal fade" id="addGraveGroupModal" tabindex="-1" aria-labelledby="addGraveGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addGraveGroupForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGraveGroupModalLabel">Tambah Kelompok Makam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kelompok <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_graves" class="form-label">Maksimal Jumlah Makam (Max 500) <span class="text-danger">(wajib Diisi)</span></label>
                        <input type="number" class="form-control" id="max_graves" name="max_graves" min="0" max="500" required>
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