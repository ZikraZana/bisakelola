<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-body-primary">

    <div class="d-flex">

        {{-- Sidebar --}}
        <div style="width: 280px; flex-shrink: 0;">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-utama1"
                style="width: 280px; position: fixed; left: 0; top: 0; bottom: 0; overflow: auto; z-index: 1030;">
                <a href="/"
                    class="d-flex justify-content-center align-items-center mb-3 mb-md-0 text-putih text-decoration-none">
                    <span class="fs-4 fw-bold">BISAKELOLA</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link bg-utama2 text-putih" aria-current="page">
                            <i class="bi bi-house-door-fill me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-putih">
                            <i class="bi bi-people-fill me-2"></i>
                            Daftar Warga
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-putih">
                            <i class="bi bi-gift-fill me-2"></i>
                            Penerima Bansos
                        </a>
                    </li>
                </ul>
                <hr>
                <ul class="nav nav-pills flex-column">
                    <li>
                        <form action="{{route('logout')}}" method="POST" class="w-100">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
