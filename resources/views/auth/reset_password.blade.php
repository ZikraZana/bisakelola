<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BISAKELOLA</title>

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

            {{-- BAGIAN KIRI: Branding Area (Konsisten) --}}
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

            {{-- BAGIAN KANAN: Form Reset Password --}}
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light">

                <div class="w-100 px-4" style="max-width: 500px;">

                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-biru">Buat Password Baru</h2>
                        <p class="text-secondary">
                            Silakan masukkan password baru untuk akun Anda.
                        </p>
                    </div>

                    {{-- Form Action ke route password.update --}}
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        {{-- PENTING: Token Reset Password (Hidden) --}}
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        {{-- Input Email (Readonly agar user yakin ini akun dia) --}}
                        <div class="mb-3 shadow-sm">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-white">
                                    <i class="bi bi-envelope-fill text-secondary"></i>
                                </span>
                                <input type="email" class="form-control p-2 border-0 bg-white" name="email"
                                    value="{{ $request->email }}" readonly>
                            </div>
                            {{-- Error email jarang terjadi di sini, tapi tetap kita handle --}}
                            @error('email')
                                <div class="invalid-feedback text-start ps-2 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Password Baru --}}
                        <div class="mb-3 shadow-sm">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-white">
                                    <i class="bi bi-lock-fill text-secondary"></i>
                                </span>
                                <input type="password"
                                    class="form-control p-2 border-0 @error('password') is-invalid @enderror"
                                    name="password" placeholder="Password Baru" required autofocus>
                            </div>
                            @error('password')
                                <div class="invalid-feedback text-start ps-2 d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Konfirmasi Password --}}
                        <div class="mb-4 shadow-sm">
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-white">
                                    <i class="bi bi-check-circle-fill text-secondary"></i>
                                </span>
                                <input type="password" class="form-control p-2 border-0" name="password_confirmation"
                                    placeholder="Ulangi Password Baru" required>
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn bg-button-add-primary btn-lg shadow fw-bold py-2"
                                style="font-size: 15px;">
                                UBAH PASSWORD <i class="bi bi-save-fill ms-2"></i>
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
        {{-- Menampilkan Error jika ada (Misal token kadaluarsa atau password tidak cocok) --}}
        @if ($errors->any())
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Gagal',
                text: 'Periksa kembali inputan Anda.',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
    </script>
</body>

</html>
