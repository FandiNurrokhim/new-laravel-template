<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasDetailCategory" aria-labelledby="offcanvasDetailCategoryLabel"
    data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasDetailCategoryLabel" class="offcanvas-title">Category Detail</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <div class="mb-3">
            <label class="form-label">ID</label>
            <h5><b id="detail_category_id"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Section</label>
            <h5><b id="detail_category_section"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <h5><b id="detail_category_title"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Slug</label>
            <h5><b id="detail_category_slug"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <div id="detail_category_description"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <div>
                <img id="detail_category_thumbnail" src="" alt="Thumbnail" style="max-width: 100%; height: auto;"
                    onerror="this.onerror=null;this.src='{{ asset('img/elements/dummy.png') }}';" />
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Order Number</label>
            <h5><b id="detail_category_order_number"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <h5><b id="detail_category_status"></b></h5>
        </div>
    </div>
</div>