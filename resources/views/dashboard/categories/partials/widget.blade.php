<div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-normal">Total <b class="category-published-count">{{ $totalCategoryPublished }}</b> Categories</h6>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div class="role-heading">
                    <h4 class="mb-1">Published</h4>
                    <small>
                        This category has <b class="category-published-count">{{ $totalCategoryPublished }}</b> published
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-normal">Total <b class="category-draft-count">{{ $totalCategoryDraft }}</b> Categories</h6>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div class="role-heading">
                    <h4 class="mb-1">Draft</h4>
                    <small>
                        This category has <b class="category-draft-count">{{ $totalCategoryDraft }}</b> draft
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card h-100">
        <div class="row h-100">
            <div class="col-sm-5">
                <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                    <img src="{{ asset('img/illustrations/sitting-girl-with-laptop-light.png') }}" class="img-fluid"
                        alt="Image" width="120" />
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card-body text-sm-end text-center ps-sm-0">
                    <button data-bs-target="#offcanvasAddCategory" data-bs-toggle="offcanvas"
                        class="btn btn-primary mb-3 text-nowrap add-new">
                        Add New Category
                    </button>
                    <p class="mb-0">Add category, if it does not exist</p>
                </div>
            </div>
        </div>
    </div>
</div>