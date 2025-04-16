<div class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasEditFileFormat"
    aria-labelledby="offcanvasEditFileFormatLabel"
    data-bs-scroll="true"
    style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasEditFileFormatLabel" class="offcanvas-title">Edit File Format</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="editFileFormatForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
                <label for="edit_title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="edit_thumbnail" class="form-label">Thumbnail (URL LINK)</label>
                <textarea name="thumbnail" id="edit_thumbnail" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label for="edit-description-editor" class="form-label">Description</label>
                <div id="description_edit_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_edit_hidden" />
            </div>

            <div class="mb-3">
                <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="edit_status" class="select2 form-select" required>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="PUBLISHED">Published</option>
                    <option value="INACTIVE">Inactive</option>
                    <option value="ARCHIVED">Archived</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="offcanvas">Kembali</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
