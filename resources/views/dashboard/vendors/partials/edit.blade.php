<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditVendor" aria-labelledby="offcanvasEditVendorLabel"
    data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasEditVendorLabel" class="offcanvas-title">Edit Vendor</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="editVendorForm" onsubmit="return false">
            @csrf
            @method('PUT')
            <input type="hidden" name="vendor_id" id="vendor_id" />

            <div class="mb-3">
                <label for="edit_title" class="form-label">Title</label>
                <input type="text" name="title" id="edit_title" class="form-control" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <div id="description_edit_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_edit_hidden" />
            </div>

            <div class="mb-3">
                <label for="edit_thumbnail" class="form-label">Thumbnail (URL Link)</label>
                <input type="text" name="thumbnail" id="edit_thumbnail" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="edit_status" class="form-label">Status</label>
                <select name="status" class="form-select select2" id="edit_status" required>
                    <option value="">-- Select Status --</option>
                    <option value="ACTIVE">ACTIVE</option>
                    <option value="PENDING">PENDING</option>
                    <option value="INACTIVE">INACTIVE</option>
                    <option value="BLOCKED">BLOCKED</option>
                    <option value="DELETED">DELETED</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
