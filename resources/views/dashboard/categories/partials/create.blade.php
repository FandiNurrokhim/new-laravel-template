<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="offcanvasAddCategory"
     aria-labelledby="offcanvasAddCategoryLabel"
     data-bs-scroll="true"
     style="max-height: 100vh;">
  <div class="offcanvas-header">
    <h5 id="offcanvasAddCategoryLabel" class="offcanvas-title">Add Category</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body" style="overflow-y: auto;">
    <form id="addNewCategoryForm" onsubmit="return false">
      @csrf
      <div class="mb-3">
        <label for="section_id" class="form-label">Section</label>
        <select name="section_id" id="section_id" class="form-select select2" required>
          <option value="">-- Select Section --</option>
          @foreach($sections as $section)
          <option value="{{ $section->id }}">{{ $section->title }}</option>
          @endforeach
        </select>
      </div>
      
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" required />
      </div>
      
      <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" name="slug" id="slug" class="form-control" readonly required />
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <div id="description_add_quill" style="min-height: 150px;"></div>
        <input type="hidden" name="description" id="description_add_hidden" />
      </div>

      <div class="mb-3">
        <label for="thumbnail" class="form-label">Thumbnail (URL Link)</label>
        <input type="text" name="thumbnail" class="form-control" />
      </div>
      
      <div class="mb-3">
        <label for="order_number" class="form-label">Order Number</label>
        <input type="number" name="order_number" class="form-control" required id="order_number" />
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select select2" required>
          <option value="">-- Select Status --</option>
          <option value="PUBLISHED">Published</option>
          <option value="DRAFT">Draft</option>
          <option value="PENDING">Pending</option>
          <option value="INACTIVE">Inactive</option>
          <option value="ARCHIVED">Archived</option>
          <option value="DELETED">Deleted</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>