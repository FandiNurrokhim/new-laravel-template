<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasDetailVendor" aria-labelledby="offcanvasDetailVendorLabel"
    data-bs-scroll="true" style="max-height: 100vh;">
    <div class="offcanvas-header">
        <h5 id="offcanvasDetailVendorLabel" class="offcanvas-title">Vendor Detail</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="overflow-y: auto;">
        <div class="mb-3">
            <label class="form-label">ID</label>
            <h5><b id="detail_vendor_id"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <h5><b id="detail_vendor_title"></b></h5>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <div id="detail_vendor_description"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Thumbnail</label>
            <div>
                <img id="detail_vendor_thumbnail" src="" alt="Thumbnail" style="max-width: 100%; height: auto;"
                    onerror="this.onerror=null;this.src='{{ asset('img/elements/dummy.png') }}';" />
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <h5><b id="detail_vendor_status"></b></h5>
        </div>
    </div>
</div>
