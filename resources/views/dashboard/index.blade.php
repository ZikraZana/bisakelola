@extends('layouts.layout')

@section('title', 'Dashboard Utama')
@section('title_nav', 'Dashboard')

@section('content')
    @php
        // Cek Permission di sini agar kode di bawah lebih bersih
        // Jika RT/Blok, boleh lihat data bansos. Jika 'Ketua Bagian', tidak boleh.
        $canViewBansos = Auth::user()->role === 'Ketua RT' || Auth::user()->role === 'Ketua Blok';

        // Tentukan lebar kolom secara dinamis
        // Jika bisa lihat bansos: Bagi 3 (col-lg-4). Jika tidak: Bagi 2 (col-lg-6)
        $cardColClass = $canViewBansos ? 'col-lg-4' : 'col-lg-6';
    @endphp

    {{-- 1. KARTU STATISTIK UTAMA --}}
    <div class="row g-3 mb-4">
        {{-- Kartu Total KK --}}
        <div class="col-md-6 {{ $cardColClass }}">
            <div class="card border-0 shadow-sm h-100 rounded-3">
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
            <div class="card border-0 shadow-sm h-100 rounded-3">
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
                <div class="card border-0 shadow-sm h-100 rounded-3">
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

                        {{-- Kesimpulan Singkat --}}
                        <div class="alert alert-light border-start border-primary border-4 py-2 px-3 mb-3">
                            <small class="text-muted fst-italic">
                                <strong>Info:</strong> Desil 1 s/d 4 adalah kelompok yang paling berpeluang menerima semua
                                jenis bansos (PKH, BPNT, PBI-JK).
                            </small>
                        </div>

                        <div class="row g-3">
                            {{-- Kolom Kiri: Kelompok Miskin (1-3) --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-danger me-2 shadow-sm" style="min-width: 80px;">Desil 1</span>
                                    <div>
                                        <strong class="d-block text-danger">Sangat Miskin</strong>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    {{-- Warna Oranye Custom --}}
                                    <span class="badge me-2 shadow-sm"
                                        style="min-width: 80px; background-color: #fd7e14; color: white;">Desil 2</span>
                                    <div>
                                        <strong class="d-block" style="color: #fd7e14;">Miskin</strong>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning text-dark me-2 shadow-sm" style="min-width: 80px;">Desil
                                        3</span>
                                    <div>
                                        <strong class="d-block text-warning-emphasis">Hampir Miskin</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Kelompok Rentan s/d Mampu (4-10) --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    {{-- Hijau Muda/Cyan --}}
                                    <span class="badge bg-info text-dark me-2 shadow-sm" style="min-width: 80px;">Desil
                                        4</span>
                                    <div>
                                        <strong class="d-block text-info-emphasis">Rentan Miskin</strong>
                                        <small class="text-muted" style="font-size: 0.7rem;">(Batas Prioritas)</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2 shadow-sm" style="min-width: 80px;">Desil 5</span>
                                    <div>
                                        <strong class="d-block text-success">Pas-pasan</strong>
                                        <small class="text-muted" style="font-size: 0.7rem;">(Bantuan Terbatas)</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-dark me-2 shadow-sm" style="min-width: 80px;">Desil 6+</span>
                                    <div>
                                        <strong class="d-block text-dark">Menengah ke Atas</strong>
                                        <small class="text-muted" style="font-size: 0.7rem;">(Tidak Prioritas)</small>
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
                        <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-pie-chart-fill me-2 text-success"></i>Jenis
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

    @if (Auth::user()->role === 'Ketua RT')
        {{-- 3. TABEL AKTIVITAS TERBARU --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2"></i>Pengajuan Bansos Terbaru
                </h6>
                <a href="{{ route('kelola-bansos.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
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
@endpush
