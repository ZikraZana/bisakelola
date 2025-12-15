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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body { font-family: 'Roboto', sans-serif; }
        /* Sedikit style tambahan agar grafik rapi */
        .stat-card {
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-white d-flex flex-column min-vh-100">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <span class="text-biru">Bisa</span><span class="text-dark">Kelola</span>
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
                                <a href="{{ route('login') }}" class="btn bg-button-add-primary px-4 rounded-pill">
                                    Login Petugas
                                </a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="py-5 mt-4">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h1 class="display-4 fw-bold text-dark mb-3">
                        Kelola Data Warga & <span class="text-biru">Bansos Terpadu</span>
                    </h1>
                    <p class="lead text-secondary mb-4">
                        Sistem informasi manajemen desa untuk pendataan penduduk dan penyaluran bantuan sosial yang transparan, akurat, dan akuntabel.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('login') }}" class="btn bg-button-add-primary btn-lg px-4 me-md-2">Mulai Sekarang</a>
                        <a href="#statistik" class="btn btn-outline-biru btn-lg px-4">Lihat Data</a>
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

    {{-- SECTION STATISTIK (BARU) --}}
    <section id="statistik" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center g-5">
                {{-- Kolom Kiri: Angka Statistik --}}
                <div class="col-lg-5">
                    <h2 class="fw-bold text-dark mb-4">Update Terkini Desa Digital</h2>
                    <p class="text-secondary mb-4">
                        Berikut adalah rekapitulasi jumlah penduduk terdaftar dan realisasi penyaluran bantuan sosial yang telah disetujui.
                    </p>

                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-4 border rounded-4 bg-light h-100 stat-card shadow-sm">
                                <i class="bi bi-people-fill fs-1 text-biru mb-2 d-block"></i>
                                <h2 class="display-5 fw-bold text-dark mb-0">{{ $totalWarga }}</h2>
                                <small class="text-secondary fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Penduduk</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 border rounded-4 bg-light h-100 stat-card shadow-sm">
                                <i class="bi bi-box2-heart-fill fs-1 text-success mb-2 d-block"></i>
                                <h2 class="display-5 fw-bold text-dark mb-0">{{ $totalPenerima }}</h2>
                                <small class="text-secondary fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Penerima Bansos</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Grafik Chart.js --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold mb-0 text-center text-secondary">
                                <i class="bi bi-pie-chart-fill me-2 text-warning"></i> Sebaran Penyaluran Bantuan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 350px; width: 100%;">
                                <canvas id="publicBansosChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION LAYANAN UTAMA --}}
    <section id="fitur" class="py-5 mt-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Layanan Utama</h2>
                <p class="text-secondary">Solusi digital untuk administrasi desa yang lebih efisien.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4 stat-card">
                    <div class="card h-100 border-0 shadow-sm p-3 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3 text-biru">
                                <i class="bi bi-database-fill display-4"></i>
                            </div>
                            <h4 class="card-title fw-bold">Database Warga</h4>
                            <p class="card-text text-secondary">
                                Penyimpanan data kependudukan terpusat yang aman dan mudah diakses oleh perangkat desa.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stat-card">
                    <div class="card h-100 border-0 shadow-sm p-3 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3 text-biru">
                                <i class="bi bi-box2-heart-fill display-4"></i>
                            </div>
                            <h4 class="card-title fw-bold">Distribusi Bansos</h4>
                            <p class="card-text text-secondary">
                                Monitoring penyaluran bantuan sosial dengan riwayat penerimaan yang jelas dan transparan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stat-card">
                    <div class="card h-100 border-0 shadow-sm p-3 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3 text-biru">
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
                    <small class="text-white-50">&copy; 2025 RT 14 BISA.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Bantuan</a>
                    <a href="#" class="text-white-50 text-decoration-none">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('publicBansosChart');
            
            // Ambil data dari Controller (dikirim via Blade)
            const dataBansos = @json($bansosDist);
            
            // Siapkan label dan data, handle jika kosong
            const labels = Object.keys(dataBansos).length ? Object.keys(dataBansos) : ['Belum Ada Data'];
            const values = Object.keys(dataBansos).length ? Object.values(dataBansos) : [0];
            
            // Warna-warni untuk Bar Chart
            const bgColors = [
                '#1C88D3', // Biru Utama
                '#198754', // Hijau
                '#ffc107', // Kuning
                '#0dcaf0', // Cyan
                '#dc3545', // Merah
                '#6c757d', // Abu
                '#6610f2'  // Ungu
            ];

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar', // Gunakan Bar Chart agar mudah dibaca perbandingannya
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Penerima (KK)',
                            data: values,
                            backgroundColor: bgColors,
                            borderRadius: 6, // Sudut tumpul pada bar
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: false // Legend tidak perlu untuk bar chart satu dataset
                            },
                            tooltip: {
                                backgroundColor: '#2C3137',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' Keluarga';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [5, 5],
                                    color: '#e9ecef'
                                },
                                ticks: {
                                    stepSize: 1 // Pastikan angka bulat (tidak ada 1.5 orang)
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>