<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BISAKELOLA</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-utama1">

    <div class="container">
        <div class="row vh-100 d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <div class="card shadow-lg rounded-4">
                    <div class="card-body p-5">

                        <h3 class="fw-bold text-center mb-2">BISAKELOLA</h3>
                        <p class="text-center text-muted mb-4">Sistem Pengelolaan Data Warga</p>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" 
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" 
                                name="username"
                                placeholder="Masukkan Username" />
                                    @error('username')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                            </div>


                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" 
                                class="form-control @error('password') is-invalid
                                @enderror" 
                                name="password"
                                value="{{ old('password') }}"
                                placeholder="Masukkan Password" />
                                @error('password')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <a href="#" class="small">Lupa Password?</a>
                            </div>

                            <div class="d-grid mt-4">
                                <button class="btn bg-button-add-primary text-putih" type="submit">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000, // Beri waktu sedikit lebih lama untuk error
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
</body>

</html>
