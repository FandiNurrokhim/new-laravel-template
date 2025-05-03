<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <span class="app-brand-logo demo">
            <img src="{{ asset('img/Logo purwosari.jpg') }}" alt="Logo" width="100">
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/request">Buat Permohonan</a></li>
                <li class="nav-item"><a class="nav-link" href="/request-list">Lihat Permohonan</a></li>
            </ul>
            <a href="/login" class="btn btn-danger">Login</a>
        </div>
    </div>
</nav>
