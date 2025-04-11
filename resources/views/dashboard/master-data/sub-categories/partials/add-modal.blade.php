<div class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasAddSubCategory"
    aria-labelledby="offcanvasAddSubCategoryLabel"
    data-bs-scroll="true"
    style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasAddSubCategoryLabel" class="offcanvas-title">Add Sub-Category</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="addSubCategoryForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter sub-category title" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" class="select2 form-select" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <div id="description_add_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_add_hidden" />
            </div>
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail (URL LINK)</label>
                <textarea name="thumbnail" id="thumbnail" class="form-control" placeholder="Enter thumbnail URL"></textarea>
            </div>
            <div class="mb-3">
                <label for="order_number" class="form-label">Order Number</label>
                <input type="number" min=0 name="order_number" id="order_number" class="form-control" placeholder="Enter order number"></input>
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
