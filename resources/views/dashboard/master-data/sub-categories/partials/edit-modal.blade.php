<div class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasEditSubCategory"
    aria-labelledby="offcanvasEditSubCategoryLabel"
    data-bs-scroll="true"
    style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasEditSubCategoryLabel" class="offcanvas-title">Edit Sub-Category</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="editSubCategoryForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
                <label for="edit_title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="edit_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="edit_category_id" class="select2 form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="edit_description" class="form-label">Description</label>
                <div id="description_edit_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_edit_hidden" />
            </div>
            <div class="mb-3">
                <label for="edit_thumbnail" class="form-label">Thumbnail (URL LINK)</label>
                <textarea name="thumbnail" id="edit_thumbnail" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="edit_order_number" class="form-label">Order Number</label>
                <input type="number" min=0 name="order_number" id="edit_order_number" class="form-control"></input>
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
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="offcanvas">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>