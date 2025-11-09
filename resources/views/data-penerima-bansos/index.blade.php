@extends('layouts.layout')

@section('title')
    Data Pengajuan Bansos
@endsection

@section('title_nav')
    Pengajuan Bansos
@endsection

@section('content')
    {{-- Card Group - Statistik Bansos (Sudah dinamis) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Total Pengajuan Saya</p>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Masih Diajukan (Pending)</p>
                    <h3 class="fw-bold mb-0">{{ $stats['diajukan'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Sudah Disetujui</p>
                    <h3 class="fw-bold mb-0">{{ $stats['disetujui'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Ditolak</p>
                    <h3 class="fw-bold mb-0">{{ $stats['ditolak'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar Aksi (Cari, Filter, Tambah Data) --}}
    <div class="border border-2 p-3 rounded min-vh-50">
        {{-- DIUBAH: Form action sekarang ke route 'index' --}}
        <form action="{{ route('data-penerima-bansos.index') }}" method="GET">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                {{-- Grup Tombol Kiri (Ikon) --}}
                <div class="mb-2 mb-md-0">
                    <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#searchCollapse" aria-expanded="{{ $searchQuery ? 'true' : 'false' }}"
                        aria-controls="searchCollapse" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse"
                        aria-expanded="{{ $filterStatusAcc || $filterStatusTerima ? 'true' : 'false' }}"
                        aria-controls="filterCollapse" title="Filter">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>

                {{-- button Tambah Data --}}
                <div>
                    {{-- DIUBAH: Route ke 'formTambah' --}}
                    <a href="{{ route('data-penerima-bansos.formTambah') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Ajukan Warga
                    </a>
                </div>
            </div>

            {{-- Wrapper untuk Efek Akordeon --}}
            <div id="toolbarPanels">
                {{-- Panel Input Search Bar --}}
                <div class="collapse mb-3 {{ $searchQuery ? 'show' : '' }}" id="searchCollapse"
                    data-bs-parent="#toolbarPanels">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search_query"
                            placeholder="Cari berdasarkan NIK, Nama Kepala Keluarga, atau No. KK..."
                            aria-label="Cari data penerima bansos" value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                        @if ($searchQuery ?? null)
                            <a href="{{ route('data-penerima-bansos.index', request()->except('search_query')) }}"
                                class="btn btn-outline-danger" title="Reset Pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Panel Filter --}}
                <div class="collapse mb-3 {{ $filterStatusAcc || $filterStatusTerima ? 'show' : '' }}" id="filterCollapse"
                    data-bs-parent="#toolbarPanels">
                    <div class="card card-body bg-light border-0">
                        <div class="row g-3">
                            {{-- Filter berdasarkan Status Pengajuan --}}
                            <div class="col-md-6">
                                <label for="filter_status_acc" class="form-label">Filter Status Pengajuan</label>
                                <select id="filter_status_acc" name="filter_status_acc" class="form-select">
                                    <option value="" selected>Semua Status Pengajuan</option>
                                    <option value="Diajukan"
                                        {{ ($filterStatusAcc ?? '') == 'Diajukan' ? 'selected' : '' }}>Diajukan (Pending)
                                    </option>
                                    <option value="Disetujui"
                                        {{ ($filterStatusAcc ?? '') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ ($filterStatusAcc ?? '') == 'Ditolak' ? 'selected' : '' }}>
                                        Ditolak</option>
                                </select>
                            </div>
                            {{-- Filter berdasarkan Status Penerimaan --}}
                            <div class="col-md-6">
                                <label for="filter_status_terima" class="form-label">Filter Status Penerimaan</label>
                                <select id="filter_status_terima" name="filter_status_terima" class="form-select">
                                    <option value="" selected>Semua Status Penerimaan</option>
                                    <option value="Belum" {{ ($filterStatusTerima ?? '') == 'Belum' ? 'selected' : '' }}>
                                        Belum Diterima</option>
                                    <option value="Sudah Diterima"
                                        {{ ($filterStatusTerima ?? '') == 'Sudah Diterima' ? 'selected' : '' }}>Sudah
                                        Diterima</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            {{-- DIUBAH: Route ke 'index' --}}
                            <a href="{{ route('data-penerima-bansos.index') }}"
                                class="btn btn-outline-secondary me-2">Reset</a>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div> {{-- Penutup #toolbarPanels --}}

            {{-- Table --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle text-putih">
                        <thead class="aturlah disini warnanya">
                            <tr>
                                <th scope="col" class="py-3 px-3">No</th>
                                <th scope="col" class="py-3 px-3">No Kartu Keluarga</th>
                                <th scope="col" class="py-3 px-3">Kepala Keluarga</th>
                                <th scope="col" class="py-3 px-3">Keterangan Pengajuan</th>
                                <th scope="col" class="py-3 px-3">Status Pengajuan</th>
                                <th scope="col" class="py-3 px-3">Jenis Bansos (Keputusan)</th>
                                <th scope="col" class="py-3 px-3">Status Diterima</th>
                                <th scope="col" class="py-3 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DIUBAH: Menggunakan loop @forelse --}}
                            @forelse ($dataPenerima as $key => $pengajuan)
                                @php
                                    // Ambil kepala keluarga
                                    $kepalaKeluarga = $pengajuan->keluarga->anggotaKeluarga->firstWhere(
                                        'status_dalam_keluarga',
                                        'Kepala Keluarga',
                                    );
                                @endphp
                                <tr>
                                    <td class="px-3">{{ $dataPenerima->firstItem() + $key }}</td>
                                    <td class="px-3">{{ $pengajuan->keluarga->no_kk }}</td>
                                    <td class="px-3">{{ $kepalaKeluarga->nama_lengkap ?? $pengajuan->keluarga->no_kk }}
                                    </td>
                                    <td class="px-3">{{ $pengajuan->keterangan_pengajuan }}</td>
                                    <td class="px-3">
                                        @if ($pengajuan->status_acc == 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif ($pengajuan->status_acc == 'Ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Diajukan</span>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        {{-- Cek jika bansos sudah ditentukan --}}
                                        @if ($pengajuan->bansos)
                                            {{ $pengajuan->bansos->nama_bansos }}
                                        @else
                                            <span class="text-muted fst-italic">Belum Ditentukan</span>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        @if ($pengajuan->status_bansos_diterima == 'Sudah Diterima')
                                            <span class="badge bg-primary">Sudah Diterima</span>
                                        @else
                                            <span class="badge bg-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" {{-- DIUBAH: Menambahkan data-atribute untuk modal --}}
                                                data-pengajuan="{{ $pengajuan->toJson() }}"
                                                data-keluarga="{{ $pengajuan->keluarga->toJson() }}"
                                                data-kepala-keluarga="{{ $kepalaKeluarga ? $kepalaKeluarga->nama_lengkap : 'N/A' }}">
                                                Detail
                                            </button>

                                            {{-- Tampilkan Edit/Hapus hanya jika masih 'Diajukan' --}}
                                            @if ($pengajuan->status_acc == 'Diajukan')
                                                <a href="{{-- route('penerima-bansos.formEdit', $pengajuan->id_penerima_bansos) --}}" class="btn btn-warning btn-sm">Edit</a>
                                                {{-- Tambahkan form Hapus jika perlu --}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                </div>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        @if ($searchQuery || $filterStatusAcc || $filterStatusTerima)
                            Data tidak ditemukan.
                        @else
                            Anda belum mengajukan data warga.
                        @endif
                    </td>
                </tr>
                @endforelse

                </tbody>
                </table>
            </div>
    </div>
    </form>
    </div>

    {{-- Footer Paginasi (Sudah dinamis) --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
        <div class="mb-2 mb-md-0">
            <span class="ms-2 text-body-secondary">
                Menampilkan {{ $dataPenerima->firstItem() ?? 0 }}
                sampai {{ $dataPenerima->lastItem() ?? 0 }}
                dari {{ $dataPenerima->total() }} hasil
            </span>
        </div>
        <div class="d-flex align-items-center">
            {{ $dataPenerima->links() }}
        </div>
    </div>

    {{-- MODAL UNTUK DETAIL DATA --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Data Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <h5>Data Pengajuan & Keputusan</h5>
                    <table class="table table-sm table-bordered mb-4">
                        <tr>
                            <th style="width: 30%;">Tanggal Pengajuan</th>
                            <td id="modal-tgl-pengajuan"></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Diajukan Oleh</th>
                            <td id="modal-admin-pengaju"></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Keterangan dari Pengaju</th>
                            <td id="modal-keterangan-rt"></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Status Pengajuan</th>
                            <td id="modal-status-acc"></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Diproses Oleh ("Dinsos")</th>
                            <td id="modal-admin-acc"></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Jenis Bansos Ditetapkan</th>
                            <td id="modal-jenis-bansos"></td>
                        </tr>
                        <tr>
                            <th>Periode Ditetapkan</th>
                            <td id="modal-periode"></td>
                        </tr>
                        <tr>
                            <th>Keterangan dari "Dinsos"</th>
                            <td id="modal-keterangan-acc"></td>
                        </tr>
                        <tr>
                            <th>Status Penerimaan Warga</th>
                            <td id="modal-status-terima"></td>
                        </tr>
                    </table>

                    <h5>Data Keluarga yang Diajukan</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th style="width: 30%;">Nomor KK</th>
                            <td id="modal-no-kk"></td>
                        </tr>
                        <tr>
                            <th>Kepala Keluarga</th>
                            <td id="modal-kepala-keluarga"></td>
                        </tr>
                        <tr>
                            <th>Blok / Desil</th>
                            <td id="modal-blok-desil"></td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Anggota Keluarga</h5>
                    <div class="table-responsive" id="modal-anggota-list-wrapper">
                        {{-- Data anggota keluarga akan di-load oleh JS --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- DIUBAH: Script modal sekarang dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var detailModal = document.getElementById('detailModal');

            detailModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                // Ambil data dari tombol
                var pengajuan = JSON.parse(button.getAttribute('data-pengajuan'));
                var keluarga = JSON.parse(button.getAttribute('data-keluarga'));
                var kepalaKeluarga = button.getAttribute('data-kepala-keluarga');

                // --- 1. Isi Data Pengajuan & Keputusan ---
                document.getElementById('modal-tgl-pengajuan').textContent = new Date(pengajuan.created_at)
                    .toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                document.getElementById('modal-admin-pengaju').textContent = pengajuan.admin_pengaju ?
                    pengajuan.admin_pengaju.nama_lengkap : 'N/A';
                document.getElementById('modal-keterangan-rt').textContent = pengajuan
                    .keterangan_pengajuan || 'N/A';
                document.getElementById('modal-status-acc').textContent = pengajuan.status_acc;
                document.getElementById('modal-admin-acc').textContent = pengajuan.admin_penyetuju ?
                    pengajuan.admin_penyetuju.nama_lengkap : 'Menunggu Proses';
                document.getElementById('modal-jenis-bansos').textContent = pengajuan.bansos ? pengajuan
                    .bansos.nama_bansos : 'Belum Ditentukan';
                document.getElementById('modal-periode').textContent = pengajuan.periode ||
                    'Belum Ditentukan';
                document.getElementById('modal-keterangan-acc').textContent = pengajuan.keterangan_acc ||
                    'N/A';
                document.getElementById('modal-status-terima').textContent = pengajuan
                    .status_bansos_diterima;

                // --- 2. Isi Data Keluarga ---
                document.getElementById('modal-no-kk').textContent = keluarga.no_kk;
                document.getElementById('modal-kepala-keluarga').textContent = kepalaKeluarga;
                document.getElementById('modal-blok-desil').textContent = (keluarga.blok ? keluarga.blok
                    .nama_blok : 'N/A') + ' / ' + (keluarga.desil ? 'Desil ' + keluarga.desil
                    .tingkat_desil : 'N/A');

                // --- 3. Isi Tabel Anggota Keluarga ---
                var anggotaWrapper = document.getElementById('modal-anggota-list-wrapper');
                var anggotaList = keluarga.anggota_keluarga;

                // Buat tabel
                var tableHtml =
                    '<table class="table table-sm table-striped table-bordered" style="font-size: 0.9em;"><thead><tr><th>No.</th><th>NIK</th><th>Nama</th><th>Status</th><th>L/P</th><th>Pekerjaan</th></tr></thead><tbody>';

                if (anggotaList && anggotaList.length > 0) {
                    anggotaList.forEach(function(item, index) {
                        tableHtml += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + (item.nik_anggota || 'N/A') + '</td>' +
                            '<td>' + (item.nama_lengkap || 'N/A') + '</td>' +
                            '<td>' + (item.status_dalam_keluarga || 'N/A') + '</td>' +
                            '<td>' + (item.jenis_kelamin ? (item.jenis_kelamin.startsWith('L') ?
                                'L' : 'P') : 'N/A') + '</td>' +
                            '<td>' + (item.pekerjaan || 'N/A') + '</td>' +
                            '</tr>';
                    });
                } else {
                    tableHtml +=
                        '<tr><td colspan="6" class="text-center">Tidak ada data anggota.</td></tr>';
                }

                tableHtml += '</tbody></table>';
                anggotaWrapper.innerHTML = tableHtml;
            });
        });
    </script>
@endpush
