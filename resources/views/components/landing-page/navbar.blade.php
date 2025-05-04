<nav class="navbar navbar-expand-lg fixed-top bg-light navbar-light">
    <div class="container">
        <span class="navbar-brand">
            <img src="{{ asset('img/Logo purwosari.jpg') }}" alt="Logo" width="100">
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link mx-2 text-black" href="/request">Buat Permohonan</a></li>
                <li class="nav-item"><a class="nav-link mx-2 text-black" href="/request-list">Lihat Permohonan</a></li>
            </ul>
            <a href="/login" class="btn btn-dark btn-rounded">Login</a>
        </div>
    </div>
</nav>