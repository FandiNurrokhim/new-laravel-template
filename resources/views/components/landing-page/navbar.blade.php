<!-- Tambahkan ini di <body> tag -->

<body data-bs-spy="scroll" data-bs-target="#navbarNav" data-bs-offset="80" tabindex="0">

    <nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm" style="background-color: #990000 !important;">
        <div class="container">
            <a href="/home" class="navbar-brand">
                <img src="{{ asset('img/Logo purwosari.jpg') }}" alt="Logo" width="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="#faq">Faq</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="#informasi">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="/request">Buat Permohonan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="/request-list">Lihat Permohonan</a>
                    </li>
                </ul>
                <a href="/login" class="btn btn-dark btn-rounded ms-3">Login</a>
            </div>
        </div>
    </nav>

    <style>
        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #fff;
        }
    </style>
