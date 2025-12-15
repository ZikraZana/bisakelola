<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
</head>

<body class="bg-body-primary">

    <div class="d-flex">

        {{-- Sidebar --}}
        <div style="width: 280px; flex-shrink: 0;">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-utama1"
                style="width: 280px; position: fixed; left: 0; top: 0; bottom: 0; overflow: auto; z-index: 1030;">
                <a href="/"
                    class="d-flex justify-content-center align-items-center mb-3 mb-md-0 text-putih text-decoration-none">
                    <span style="font-family: 'Roboto+Slab'; font-weight: 600; font-size: 24px;">BISAKELOLA</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link text-putih {{ Request::routeIs('dashboard') ? 'bg-utama2' : '' }}"
                            aria-current="page">
                            <i class="bi bi-house-door-fill me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('data-warga.index') }}"
                            class="nav-link text-putih {{ Request::routeIs('data-warga.*') ? 'bg-utama2' : '' }}">
                            <i class="bi bi-people-fill me-2"></i>
                            Daftar Warga
                        </a>
                    </li>
                    @if (Auth::user()->role === 'Ketua RT' ||
                            (Auth::user()->role === 'Ketua Blok'))
                        <li>
                            <a href="{{ route('data-penerima-bansos.index') }}"
                                class="nav-link text-putih {{ Request::routeIs('data-penerima-bansos.*') ? 'bg-utama2' : '' }}">
                                <i class="bi bi-gift-fill me-2"></i>
                                Penerima Bansos
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('akun-admin.index') }}"
                            class="nav-link text-putih {{ Request::routeIs('akun-admin.*') ? 'bg-utama2' : '' }}">
                            <i class="bi bi-person-circle me-2"></i>
                            Akun Admin
                        </a>
                    </li>
                    @if (Auth::user()->role === 'Ketua RT')
                        <li>
                            <a href="{{ route('kelola-bansos.index') }}"
                                class="nav-link text-putih {{ Request::routeIs('kelola-bansos.*') ? 'bg-utama2' : '' }}">
                                <i class="bi bi-clipboard-check-fill me-2"></i>
                                Kelola Bansos
                            </a>
                        </li>
                    @endif
                </ul>
                <hr>
                <ul class="nav nav-pills flex-column">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="nav-link text-danger w-100 text-start">
                                <i class="bi bi-box-arrow-left me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex flex-column flex-grow-1">
            {{-- Navbar --}}
            <nav class="navbar bg-utama2 shadow-sm position-sticky top-0" style="z-index: 1020;">
                <div class="container-fluid">
                    <span class="navbar-brand text-putih fw-bold fs-4 mb-0 ms-2">@yield('title_nav')</span>
                </div>
            </nav>

            {{-- Content --}}
            <main class="flex-grow-1 bg-body-primary p-4">
                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                html: `
                    <ul style="text-align:left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `
            });
        @endif
    </script>
    @stack('scripts')
</body>

</html>
