<section class="bsb-fact-5 py-3 py-md-5 bg-white">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                <h3 class="fs-5 mb-2 text-dark text-center text-uppercase">Statistik Sistem</h3>
                <h2 class="display-5 mb-5 mb-xl-9 text-center">Data Pemakaman & Permohonan</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid bg-light border shadow">
                    <div class="row">
                        <div class="col-12 col-md-3 p-0" data-aos="fade-up">
                            <div class="card border-0 bg-transparent">
                                <div class="card-body text-center p-4 p-xxl-5">
                                    <h3 class="display-4 fw-bold mb-2">{{$corpseCount }}</h3>
                                    <p class="fs-5 mb-0 text-dark">Total Jenazah Terdata</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 p-0 border-top border-bottom border-start border-end"
                            data-aos="fade-up">
                            <div class="card border-0 bg-transparent">
                                <div class="card-body text-center p-4 p-xxl-5">
                                    <h3 class="display-4 fw-bold mb-2">{{$graveUsedCount }}</h3>
                                    <p class="fs-5 mb-0 text-dark">Makam Terpakai</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 p-0 border-top border-bottom border-end" data-aos="fade-up">
                            <div class="card border-0 bg-transparent">
                                <div class="card-body text-center p-4 p-xxl-5">
                                    <h3 class="display-4 fw-bold mb-2">{{$formRequestCount }}</h3>
                                    <p class="fs-5 mb-0 text-dark">Total Permohonan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 p-0" data-aos="fade-up">
                            <div class="card border-0 bg-transparent">
                                <div class="card-body text-center p-4 p-xxl-5">
                                    <h3 class="display-4 fw-bold mb-2">{{$formRequestThisMonthCount }}
                                    </h3>
                                    <p class="fs-5 mb-0 text-dark">Permohonan Bulan Ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
