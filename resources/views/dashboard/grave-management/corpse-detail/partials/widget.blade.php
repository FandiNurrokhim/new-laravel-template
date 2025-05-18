{{-- <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-normal">Total <b>{{ $usedGraves }}</b> Makam</h6>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div class="role-heading">
                    <h4 class="mb-1">Makam Digunakan</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <h6 class="fw-normal">Total <b>{{ $unusedGraves }}</b> Makam</h6>
            </div>
            <div class="d-flex justify-content-between align-items-end">
                <div class="role-heading">
                    <h4 class="mb-1">Makam Tersedia</h4>
                </div>
            </div>
        </div>
    </div>
</div> --}}

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
                    <button type="button" class="btn btn-primary mb-3 text-nowrap add-new" data-bs-toggle="modal"
                        data-bs-target="#addCorpseDetailModal">
                        Tambah Data Mayit
                    </button>
                    <p class="mb-0">Tambah Kelompok Makam jika tidak tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>
