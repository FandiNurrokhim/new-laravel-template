<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('') }}" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/highlight/highlight.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/quill/editor.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.9.3/tagify.css">
    @stack('styles')
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('components.templates.sidebar')
            <div class="layout-page">
                @include('components.templates.navbar')
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <footer class="content-footer footer bg-footer-theme">
                    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            © {{ date('Y') }}, made with ❤️ by
                            <a href="https://Makan Purwosari.com" target="_blank" class="footer-link fw-bolder">Makan
                                Purwosari</a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('vendor/libs/typeahead-js/typeahead.js') }}"></script>

    <script src="{{ asset('vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('vendor/libs/highlight/highlight.js') }}"></script>
    <script src="{{ asset('vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('vendor/js/forms-editors.js') }}"></script>

    <script src="{{ asset('vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('vendor/libs/highlight/highlight.js') }}"></script>
    <script src="{{ asset('vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('vendor/js/forms-editors.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.9.3/tagify.min.js"></script>
    <script src="{{ asset('vendor/js/menu.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('vendor/libs/cleavejs/addons/cleave-phone.id.js') }}"></script>
    <script src="{{ asset('vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('vendor/js/forms-selects.js') }}"></script>

    <script>
        function updateDateTime() {
            const timeNow = new Date();

            const optionsTime = {
                hour: 'numeric',
                minute: 'numeric',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            };

            const optionsDate = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            };

            const timeFormat = new Intl.DateTimeFormat('id-ID', optionsTime).format(timeNow);
            const dateFormat = new Intl.DateTimeFormat('id-ID', optionsDate).format(timeNow);

            document.getElementById('time-now').innerHTML = `${dateFormat} | ${timeFormat}`;
        }

        updateDateTime();

        setInterval(updateDateTime, 60000);
    </script>
    <script>
        function verificationForLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
