<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddFileFormat"
    aria-labelledby="offcanvasAddFileFormatLabel" data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasAddFileFormatLabel" class="offcanvas-title">Add File Format</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="addFileFormatForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail (URL LINK)</label>
                <textarea name="thumbnail" id="thumbnail" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label for="description-editor" class="form-label">Description</label>
                <div id="description_add_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_add_hidden" />
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="select2 form-select" required>
                    <option value="" disabled selected>Select status</option>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="PUBLISHED">Published</option>
                    <option value="INACTIVE">Inactive</option>
                    <option value="ARCHIVED">Archived</option>
                    <option value="DELETED">Deleted</option>
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
