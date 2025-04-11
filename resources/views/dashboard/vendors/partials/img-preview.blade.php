<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <img src="" alt="Full Image" class="img-fluid" id="fullImagePreview" style="max-height: 80vh;"
                    onerror="this.onerror=null;this.src='{{ asset('img/elements/dummy.png') }}';" />
            </div>
        </div>
    </div>
</div>
