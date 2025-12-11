<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - BISAKELOLA</title>

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

    <div class="container-fluid vh-100 p-0">

        <div class="row h-100 g-0">

            {{-- BAGIAN KIRI: Branding Area (Sama Persis dengan Login) --}}
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

            {{-- BAGIAN KANAN: Form Lupa Password --}}
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light">

                <div class="w-100 px-4" style="max-width: 500px;">

                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-biru">Lupa Password?</h2>
                        <p class="text-secondary">
                            Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan petunjuk reset password.
                        </p>
                    </div>

                    {{-- Form Action mengarah ke route password.email (Default Laravel) --}}
                    <form action="" method="POST" class="mb-4">
                        @csrf

                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-white">
                                    {{-- Icon Envelope untuk Email --}}
                                    <i class="bi bi-envelope-fill text-secondary"></i>
                                </span>
                                <input type="email"
                                    class="form-control p-2 border-0 @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Masukkan Email Terdaftar"
                                    value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback text-start ps-2 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn bg-button-add-primary btn-lg shadow fw-bold py-2"
                                style="font-size: 15px;">
                                KIRIM LINK RESET <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Tombol Kembali ke Login --}}
                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}"
                            class="text-decoration-none small fw-bold text-biru d-inline-flex align-items-center icon-link-hover"
                            style="transition: all 0.3s ease;">
                            <i class="bi bi-arrow-left me-2" style="margin-bottom: -3px"></i> Kembali ke Login
                        </a>
                    </div>

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
        // Notifikasi Sukses (Link terkirim)
        @if (session('status'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('status') }}',
                showConfirmButton: true,
                confirmButtonColor: '#0d6efd' // Sesuaikan warna tombol OK
            });
        @endif

        // Notifikasi Error (Email tidak ditemukan dll)
        @if ($errors->any())
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Silakan periksa kembali email Anda.',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
    </script>
</body>

</html>
