<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasAddVendor"
     aria-labelledby="offcanvasAddVendorLabel"
     data-bs-scroll="true"
     style="max-height: 100vh;">
  <div class="offcanvas-header">
    <h5 id="offcanvasAddVendorLabel" class="offcanvas-title">Add Vendor</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body" style="overflow-y: auto;">
    <form id="addNewVendorForm" onsubmit="return false">
      @csrf
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <div id="description_add_quill" style="min-height: 150px;"></div>
        <input type="hidden" name="description" id="description_add_hidden" />
      </div>

      <div class="mb-3">
        <label for="thumbnail" class="form-label">Thumbnail (URL Link)</label>
        <input type="text" name="thumbnail" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select select2" required>
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
