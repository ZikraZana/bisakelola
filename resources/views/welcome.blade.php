<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BisaKelola - Sistem Data Warga & Bansos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="bg-white d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <span class="text-primary">Bisa</span><span class="text-dark">Kelola</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link fw-bold text-dark">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-primary px-4 rounded-pill">
                                    Login Petugas
                                </a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5 mt-4">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-dark mb-3">
                        Kelola Data Warga & <span class="text-primary">Bansos Terpadu</span>
                    </h1>
                    <p class="lead text-secondary mb-4">
                        Sistem informasi manajemen desa untuk pendataan penduduk dan penyaluran bantuan sosial yang transparan, akurat, dan akuntabel.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2">Mulai Sekarang</a>
                        <a href="#fitur" class="btn btn-outline-primary btn-lg px-4">Pelajari Fitur</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 text-center">
                    <img src="https://img.freepik.com/free-vector/check-list-concept-illustration_114360-4535.jpg" 
                         alt="Ilustrasi Pendataan" 
                         class="img-fluid rounded-3" 
                         style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-5 bg-light mt-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Layanan Utama</h2>
                <p class="text-secondary">Solusi digital untuk administrasi desa yang lebih efisien.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="card-body text-center">
                            <div class="mb-3 text-primary">
                                <i class="bi bi-database-fill display-4"></i>
                            </div>
                            <h4 class="card-title fw-bold">Database Warga</h4>
                            <p class="card-text text-secondary">
                                Penyimpanan data kependudukan terpusat yang aman dan mudah diakses oleh perangkat desa.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="card-body text-center">
                            <div class="mb-3 text-primary">
                                <i class="bi bi-box2-heart-fill display-4"></i>
                            </div>
                            <h4 class="card-title fw-bold">Distribusi Bansos</h4>
                            <p class="card-text text-secondary">
                                Monitoring penyaluran bantuan sosial dengan riwayat penerimaan yang jelas dan transparan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="card-body text-center">
                            <div class="mb-3 text-primary">
                                <i class="bi bi-file-earmark-bar-graph-fill display-4"></i>
                            </div>
                            <h4 class="card-title fw-bold">Laporan & Statistik</h4>
                            <p class="card-text text-secondary">
                                Visualisasi data demografi dan laporan realisasi bantuan dalam bentuk grafik yang mudah dipahami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="fw-bold mb-1">BisaKelola</h5>
                    <small class="text-white-50">&copy; 2025 Pemerintah Desa Digital.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Bantuan</a>
                    <a href="#" class="text-white-50 text-decoration-none">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>