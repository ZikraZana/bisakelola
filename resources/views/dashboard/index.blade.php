@extends('layouts.layout')

@section('title', 'Dashboard Utama')
@section('title_nav', 'Dashboard')

@push('styles')
    <style>
        .hover-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .hover-card:hover {
            box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endpush
@section('content')
    @php
        // Cek Permission di sini agar kode di bawah lebih bersih
        // Jika RT/Blok, boleh lihat data bansos. Jika 'Ketua Bagian', tidak boleh.
        $canViewBansos =
            Auth::user()->role === 'Ketua RT' ||
            Auth::user()->role === 'Wakil Ketua RT' ||
            Auth::user()->role === 'Sekretaris RT' ||
            Auth::user()->role === 'Bendahara RT' ||
            Auth::user()->role === 'Ketua Blok';

        // Tentukan lebar kolom secara dinamis
        // Jika bisa lihat bansos: Bagi 3 (col-lg-4). Jika tidak: Bagi 2 (col-lg-6)
        $cardColClass = $canViewBansos ? 'col-lg-4' : 'col-lg-6';
    @endphp

    {{-- 1. KARTU STATISTIK UTAMA --}}
    <div class="row g-3 mb-4">
        {{-- Kartu Total KK --}}
        <div class="col-md-6 {{ $cardColClass }}">
            <div class="card border-0 hover-card shadow-sm h-100 rounded-3">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Kepala Keluarga</p>
                        <h2 class="fw-bold mb-0 text-primary">{{ $totalKK }}</h2>
                    </div>
                    <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-house-door-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Total Warga --}}
        <div class="col-md-6 {{ $cardColClass }}">
            <div class="card border-0 hover-card shadow-sm h-100 rounded-3">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Penduduk</p>
                        <h2 class="fw-bold mb-0 text-info">{{ $totalWarga }}</h2>
                    </div>
                    <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        @if ($canViewBansos)
            {{-- Kartu Penerima Bansos --}}
            <div class="col-md-6 {{ $cardColClass }}">
                <div class="card border-0 hover-card shadow-sm h-100 rounded-3">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-bold small">Penerima Bansos</p>
                            <h2 class="fw-bold mb-0 text-success">{{ $totalPenerimaBansos }}</h2>
                        </div>
                        <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-gift-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- 2. AREA GRAFIK --}}
    <div class="row g-4 mb-4">

        {{-- Grafik Batang: Sebaran Desil --}}
        {{-- LOGIKA: Jika bansos disembunyikan, grafik ini jadi Full Width (col-12) --}}
        <div class="{{ $canViewBansos ? 'col-lg-8' : 'col-12' }}">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-secondary">
                        <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Statistik Ekonomi Warga (Desil)
                    </h6>
                    {{-- Tombol Info --}}
                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#infoDesil">
                        <i class="bi bi-info-circle text-muted"></i>
                    </button>
                </div>
                <div class="card-body">
                    {{-- Area Grafik --}}
                    <div style="height: 300px; width: 100%;">
                        <canvas id="desilChart"></canvas>
                    </div>

                    <hr class="my-4">

                    {{-- Area Keterangan (Legend) Sesuai Gambar Infografis --}}
                    <div id="infoDesil" class="collapse show">

                        {{-- Definisi Singkat --}}
                        <div class="alert alert-primary bg-primary-subtle border-0 d-flex align-items-center mb-4"
                            role="alert">
                            <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                            <div>
                                <span class="fw-bold text-primary">Apa itu Desil?</span>
                                <small class="d-block text-muted">
                                    Pengelompokan kesejahteraan rumah tangga menjadi 10 bagian (10%). Semakin rendah
                                    angkanya (1), semakin membutuhkan bantuan.
                                </small>
                            </div>
                        </div>

                        <div class="row g-4">
                            {{-- KOLOM KIRI: PRIORITAS BANSOS (1-3) --}}
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3 ls-1">
                                    <i class="bi bi-exclamation-circle-fill text-danger me-1"></i> Prioritas Tinggi
                                </h6>

                                {{-- Desil 1 --}}
                                <div
                                    class="p-3 mb-3 hover-card bg-white border border-light shadow-sm rounded-3 border-start border-5 border-danger position-relative overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-danger mb-1">Desil 1</span>
                                            <h6 class="fw-bold mb-0 text-dark">Sangat Miskin</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">Target utama PKH &
                                                Sembako</small>
                                        </div>
                                        <i
                                            class="bi bi-graph-down-arrow text-danger opacity-25 fs-1 position-absolute end-0 me-3"></i>
                                    </div>
                                </div>

                                {{-- Desil 2 --}}
                                <div class="p-3 mb-3 hover-card bg-white border border-light shadow-sm rounded-3 border-start border-5 position-relative overflow-hidden"
                                    style="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge mb-1 text-white" style="background-color: #fd7e14;">Desil
                                                2</span>
                                            <h6 class="fw-bold mb-0 text-dark">Miskin</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">Prioritas tinggi
                                                bantuan</small>
                                        </div>
                                        <i class="bi bi-house-dash text-warning opacity-25 fs-1 position-absolute end-0 me-3"
                                            style="color: #fd7e14 !important;"></i>
                                    </div>
                                </div>

                                {{-- Desil 3 --}}
                                <div
                                    class="p-3 hover-card bg-white border border-light shadow-sm rounded-3 border-start border-5 border-warning position-relative overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-warning text-dark mb-1">Desil 3</span>
                                            <h6 class="fw-bold mb-0 text-dark">Hampir Miskin</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">Rentan guncangan
                                                ekonomi</small>
                                        </div>
                                        <i
                                            class="bi bi-cone-striped text-warning opacity-25 fs-1 position-absolute end-0 me-3"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: MENENGAH - MAMPU (4-10) --}}
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3 ls-1">
                                    <i class="bi bi-shield-check text-success me-1"></i> Perbatasan & Mandiri
                                </h6>

                                {{-- Desil 4 --}}
                                <div
                                    class="p-3 mb-3 hover-card bg-white border border-light shadow-sm rounded-3 border-start border-5 border-info position-relative overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-info text-dark mb-1">Desil 4</span>
                                            <h6 class="fw-bold mb-0 text-dark">Rentan Miskin</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">Batas bawah penerima
                                                subsidi</small>
                                        </div>
                                        <i
                                            class="bi bi-activity text-info opacity-25 fs-1 position-absolute end-0 me-3"></i>
                                    </div>
                                </div>

                                {{-- Desil 5 --}}
                                <div
                                    class="p-3 mb-3 hover-card bg-white border border-light shadow-sm rounded-3 border-start border-5 border-success position-relative overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-success mb-1">Desil 5</span>
                                            <h6 class="fw-bold mb-0 text-dark">Menengah Bawah</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">Ekonomi mulai
                                                stabil</small>
                                        </div>
                                        <i
                                            class="bi bi-flower1 text-success opacity-25 fs-1 position-absolute end-0 me-3"></i>
                                    </div>
                                </div>

                                {{-- Desil 6+ --}}
                                <div
                                    class="p-3 bg-white hover-card border border-light shadow-sm rounded-3 border-start border-5 position-relative overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-dark mb-1">Desil 6 - 10</span>
                                            <h6 class="fw-bold mb-0 text-dark">Mampu / Kaya</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">Tidak diprioritaskan
                                                bansos</small>
                                        </div>
                                        <i
                                            class="bi bi-building text-dark opacity-25 fs-1 position-absolute end-0 me-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @if ($canViewBansos)
            {{-- Grafik Donut: Distribusi Jenis Bansos --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-secondary"><i
                                class="bi bi-pie-chart-fill me-2 text-success"></i>Jenis
                            Bantuan Tersalurkan</h6>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div style="width: 100%;">
                            <canvas id="bansosChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @if (Auth::user()->role === 'Ketua RT' ||
            Auth::user()->role === 'Wakil Ketua RT' ||
            Auth::user()->role === 'Sekretaris RT' ||
            Auth::user()->role === 'Bendahara RT')
        {{-- 3. TABEL AKTIVITAS TERBARU --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2"></i>Pengajuan Bansos Terbaru
                </h6>
                <a href="{{ route('data-penerima-bansos.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Warga (Kepala Keluarga)</th>
                            <th>Tgl Pengajuan</th>
                            <th>Diajukan Oleh</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $item)
                            @php
                                $kepala = $item->keluarga->anggotaKeluarga->firstWhere(
                                    'status_dalam_keluarga',
                                    'Kepala Keluarga',
                                );
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $kepala->nama_lengkap ?? 'N/A' }}</div>
                                    <small class="text-muted">KK: {{ $item->keluarga->no_kk }}</small>
                                </td>
                                <td>{{ $item->created_at->diffForHumans() }}</td>
                                <td>{{ $item->adminPengaju->nama_lengkap ?? 'Admin' }}</td>
                                <td>
                                    @if ($item->status_acc == 'Diajukan')
                                        <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                    @elseif($item->status_acc == 'Disetujui')
                                        <span class="badge bg-success rounded-pill">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    {{-- Load Chart.js dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Variabel PHP ke JS
            const canViewBansos = @json($canViewBansos);

            // --- 1. GRAFIK DONUT (BANSOS) ---
            // Hanya render jika user boleh lihat
            if (canViewBansos) {
                const bansosData = @json($bansosDistribution);
                const bLabels = Object.keys(bansosData).length ? Object.keys(bansosData) : ['Belum Ada Data'];
                const bValues = Object.keys(bansosData).length ? Object.values(bansosData) : [1];
                const bColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];

                const bansosCtx = document.getElementById('bansosChart');
                if (bansosCtx) {
                    new Chart(bansosCtx, {
                        type: 'doughnut',
                        data: {
                            labels: bLabels,
                            datasets: [{
                                data: bValues,
                                backgroundColor: bColors,
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 10
                                    }
                                }
                            },
                            cutout: '75%',
                        }
                    });
                }
            }


            // --- 2. GRAFIK BAR (DESIL) ---
            const desilData = @json($desilDistribution);
            const dLabels = Object.keys(desilData);
            const dValues = Object.values(desilData);

            const desilColors = dLabels.map(label => {
                if (label === 'Desil 1') return '#dc3545';
                if (label === 'Desil 2') return '#fd7e14';
                if (label === 'Desil 3') return '#ffc107';
                if (label === 'Desil 4') return '#0dcaf0';
                if (label === 'Desil 5') return '#198754';
                return '#212529';
            });

            new Chart(document.getElementById('desilChart'), {
                type: 'bar',
                data: {
                    labels: dLabels,
                    datasets: [{
                        label: 'Jumlah Keluarga',
                        data: dValues,
                        backgroundColor: desilColors,
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5]
                            },
                            ticks: {
                                precision: 0
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
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end', // Posisi di pojok kanan atas
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false, // Hilangkan tombol OK
                timer: 3000, // Tutup otomatis setelah 3 detik
                timerProgressBar: true, // Tampilkan progress bar waktu
                // Mencegah toast ditutup saat kursor mouse di atasnya
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        </script>
    @endif
@endpush
