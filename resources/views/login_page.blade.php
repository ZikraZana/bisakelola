<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BISAKELOLA</title>

    {{-- Bootstrap 5 CSS CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body>

    {{-- PERBAIKAN DI SINI: Tambahkan class 'p-0' --}}
    <div class="container-fluid vh-100 p-0">

        <div class="row h-100 g-0">

            {{-- BAGIAN KIRI: Branding Area --}}
            <div
                class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center bg-utama2 bg-gradient text-white position-relative overflow-hidden">

                {{-- Dekorasi Icon Besar --}}
                <i class="bi bi-buildings-fill position-absolute text-white opacity-25"
                    style="font-size: 20rem; right: -50px; bottom: -50px;"></i>

                {{-- Konten Branding --}}
                <div class="z-1 text-center px-5">
                    <div class="mb-4">
                        <i class="bi bi-shield-check fs-1 border border-3 border-white rounded-circle p-3"></i>
                    </div>
                    <h1 class="display-4 fw-bold">BISAKELOLA</h1>
                    <p class="lead fw-light mt-3">
                        Sistem Informasi Manajemen Warga.<br>
                        Transparan, Cepat, dan Akurat.
                    </p>
                </div>
            </div>

            {{-- BAGIAN KANAN: Form Login --}}
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light">

                <div class="w-100 px-4" style="max-width: 500px;">

                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-biru">Selamat Datang</h2>
                        <p class="text-secondary">Silakan login dengan akun Anda</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3 shadow-sm">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-white">
                                    <i class="bi bi-person-fill text-secondary"></i>
                                </span>
                                <input type="text"
                                    class="form-control p-2 border-0 @error('username') is-invalid @enderror"
                                    id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                            </div>
                            @error('username')
                                <div class="invalid-feedback text-start ps-2 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 shadow-sm">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-white">
                                    <i class="bi bi-lock-fill text-secondary"></i>
                                </span>
                                <input type="password"
                                    class="form-control p-2 border-0 @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback text-start ps-2 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                    style="accent-color: var(--bs-primary);">
                                <label class="form-check-label text-secondary small" for="remember">Ingat Saya</label>
                            </div>
                            <a href="#" class="text-decoration-none small fw-semibold text-biru" style="transition: all 0.3s ease;" onmouseover="this.style.textDecoration='underline'; this.style.opacity='0.7'" onmouseout="this.style.textDecoration='none'; this.style.opacity='1'">Lupa
                                Password?</a>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn bg-button-add-primary btn-lg shadow fw-bold py-2 fs-md-6"
                                style="font-size: 17px;">
                                LOGIN SEKARANG <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <div class="mt-5 text-center text-secondary small opacity-50">
                        &copy; {{ date('Y') }} Bisakelola. All rights reserved.
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
    </script>
</body>

</html>
