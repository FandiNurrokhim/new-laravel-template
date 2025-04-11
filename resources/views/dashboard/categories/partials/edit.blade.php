<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditCategory" aria-labelledby="offcanvasEditCategoryLabel"
    data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasEditCategoryLabel" class="offcanvas-title">Edit Category</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <form id="editCategoryForm" onsubmit="return false">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="category_id" />

            <div class="mb-3">
                <label for="edit_section_id" class="form-label">Section</label>
                <select name="section_id" id="edit_section_id" class="form-select select2" required>
                    <option value="">-- Select Section --</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label for="edit_title" class="form-label">Title</label>
                <input type="text" name="title" id="edit_title" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="edit_slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="edit_slug" class="form-control" readonly required />
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <div id="description_edit_quill" style="min-height: 150px;"></div>
                <input type="hidden" name="description" id="description_edit_hidden" />
            </div>

            <div class="mb-3">
                <label for="edit_thumbnail" class="form-label">Thumbnail (URL Link)</label>
                <input type="text" name="thumbnail" id="edit_thumbnail" class="form-control" />
            </div>
            
            <div class="mb-3">
                <label for="edit_order_number" class="form-label">Order Number</label>
                <input type="number" name="order_number" id="edit_order_number" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="edit_status" class="form-label">Status</label>
                <select name="status" class="form-select select2" id="edit_status" required>
                    <option value="">-- Select Status --</option>
                    <option value="PUBLISHED">Published</option>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="INACTIVE">Inactive</option>
                    <option value="ARCHIVED">Archived</option>
                    <option value="DELETED">Deleted</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>