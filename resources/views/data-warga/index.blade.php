@extends('layouts.layout')

@section('title')
    Data Warga
@endsection

@section('title_nav')
    Data Warga
@endsection

@section('content')
    {{-- Card Group --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Total Blok Lrg. Duren</p>
                    <h3 class="fw-bold mb-0">{{ $totalBlok['Lrg. Duren'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Total Blok Makakau</p>
                    <h3 class="fw-bold mb-0">{{ $totalBlok['Makakau'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Total Blok Matahari</p>
                    <h3 class="fw-bold mb-0">{{ $totalBlok['Matahari'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Total Blok Lrg. Gardu</p>
                    <h3 class="fw-bold mb-0">{{ $totalBlok['Lrg. Gardu'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Toolbar Aksi (Cari, Filter, Tambah Data) --}}
    <div class="border border-2 p-3 rounded min-vh-50">
        <form action="{{ route('data-warga.index') }}" method="GET">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                {{-- Grup Tombol Kiri (Ikon) --}}
                <div class="mb-2 mb-md-0">
                    {{-- Tombol ini sekarang mengontrol collapse --}}
                    <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#searchCollapse" aria-expanded="{{ $searchQuery ?? null ? 'true' : 'false' }}"
                        aria-controls="searchCollapse" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse"
                        aria-expanded="{{ ($filterBlok ?? null) || ($filterDesil ?? null) ? 'true' : 'false' }}"
                        aria-controls="filterCollapse" title="Filter">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>

                {{-- button Tambah Data --}}
                @if (Auth::user()->role === 'Ketua RT' || Auth::user()->role === 'Ketua Blok')
                    <div>
                        <a href="{{ route('data-warga.formTambah') }}" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                @endif
            </div>

            {{-- Wrapper untuk Efek Akordeon --}}
            <div id="toolbarPanels">

                {{-- Panel Input Search Bar --}}
                {{-- BARU: Tambah class 'show' jika $searchQuery ada --}}
                <div class="collapse mb-3 {{ $searchQuery ?? null ? 'show' : '' }}" id="searchCollapse"
                    data-bs-parent="#toolbarPanels">

                    {{-- BARU: Modifikasi Input Group --}}
                    <div class="input-group">
                        {{-- Input --}}
                        <input type="text" class="form-control" name="search_query"
                            placeholder="Cari berdasarkan NIK, Nama, atau No. KK..." aria-label="Cari data warga"
                            value="{{ $searchQuery ?? '' }}">

                        {{-- Tombol Submit Cari --}}
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>

                        {{-- ====================================== --}}
                        {{-- ====== TOMBOL RESET (BARU) ======= --}}
                        {{-- ====================================== --}}

                        {{-- Tampilkan tombol ini HANYA JIKA $searchQuery ada isinya --}}
                        @if ($searchQuery ?? null)
                            {{-- 
                      - Tombol ini me-load ulang halaman ke route 'data-warga.index'
                      - request()->except('search_query') = Ambil SEMUA parameter
                        URL yang ada SEKARANG (cth: filter_blok, filter_desil) 
                        KECUALI 'search_query'.
                    --}}
                            <a href="{{ route('data-warga.index', request()->except('search_query')) }}"
                                class="btn btn-outline-danger" title="Reset Pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                        {{-- ====================================== --}}
                        {{-- ============ AKHIR BARU ============== --}}
                        {{-- ====================================== --}}
                    </div>
                </div>

                {{-- Panel Filter --}}
                {{-- FIX 2: Menambahkan logika pengecekan status !== null di class show --}}
                <div class="collapse mb-3 {{ ($filterBlok ?? null) || ($filterDesil ?? null) || ($filterStatus ?? null) !== null ? 'show' : '' }}"
                    id="filterCollapse" data-bs-parent="#toolbarPanels">
                    <div class="card card-body bg-light border-0">
                        <div class="row g-3">
                            {{-- Filter Blok --}}
                            <div class="col-md-4">
                                <label for="filterBlok" class="form-label">Filter Blok</label>
                                <select id="filterBlok" name="filter_blok" class="form-select">
                                    <option value="" selected>Semua Blok</option>
                                    <option value="Lrg. Duren" {{ ($filterBlok ?? '') == 'Lrg. Duren' ? 'selected' : '' }}>
                                        Lrg. Duren</option>
                                    <option value="Makakau" {{ ($filterBlok ?? '') == 'Makakau' ? 'selected' : '' }}>
                                        Makakau</option>
                                    <option value="Matahari" {{ ($filterBlok ?? '') == 'Matahari' ? 'selected' : '' }}>
                                        Matahari</option>
                                    <option value="Lrg. Gardu" {{ ($filterBlok ?? '') == 'Lrg. Gardu' ? 'selected' : '' }}>
                                        Lrg. Gardu</option>
                                </select>
                            </div>
                            {{-- Filter Desil --}}
                            <div class="col-md-4">
                                <label for="filterDesil" class="form-label">Filter Desil</label>
                                <select id="filterDesil" name="filter_desil" class="form-select">
                                    <option value="" selected>Semua Desil</option>
                                    <option value="1" {{ ($filterDesil ?? '') == '1' ? 'selected' : '' }}>Desil 1
                                    </option>
                                    <option value="2" {{ ($filterDesil ?? '') == '2' ? 'selected' : '' }}>Desil 2
                                    </option>
                                    <option value="3" {{ ($filterDesil ?? '') == '3' ? 'selected' : '' }}>Desil 3
                                    </option>
                                    <option value="4" {{ ($filterDesil ?? '') == '4' ? 'selected' : '' }}>Desil 4
                                    </option>
                                    <option value="5" {{ ($filterDesil ?? '') == '5' ? 'selected' : '' }}>Desil 5
                                    </option>
                                    {{-- Kita gunakan value="null" (string) untuk dikirim ke controller --}}
                                    <option value="null" {{ ($filterDesil ?? '') == 'null' ? 'selected' : '' }}>
                                        Desil 6+
                                    </option>
                                </select>
                            </div>
                            {{-- Filter Status --}}
                            <div class="col-md-4">
                                <label for="filterStatus" class="form-label">Filter Status</label>
                                <select id="filterStatus" name="filter_status" class="form-select">
                                    <option value="" selected>Semua Status</option>
                                    <option value="1" {{ ($filterStatus ?? '') === '1' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ ($filterStatus ?? '') === '0' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('data-warga.index') }}" class="btn btn-outline-secondary me-2">Reset</a>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-funnel me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div> {{-- Penutup #toolbarPanels --}}
        </form>

        {{-- Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle text-putih">
                    <thead class="aturlah disini warnanya">
                        <tr>
                            <th scope="col" class="py-3 px-3">No</th>
                            <th scope="col" class="py-3 px-3">Nomor Kartu Keluarga</th>
                            <th scope="col" class="py-3 px-3">Nama Kepala Keluarga</th>
                            <th scope="col" class="py-3 px-3">NIK Kepala Keluarga</th>
                            <th scope="col" class="py-3 px-3">Blok</th>
                            <th scope="col" class="py-3 px-3">Desil</th>
                            <th scope="col" class="py-3 px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataKeluarga as $key => $keluarga)
                            @php
                                $kepalaKeluarga = $keluarga->anggotaKeluarga->firstWhere(
                                    'status_dalam_keluarga',
                                    'Kepala Keluarga',
                                );
                            @endphp
                            <tr>
                                <td class="px-3">{{ $key + 1 }}</td>
                                <td class="px-3">{{ $keluarga->no_kk }}
                                    @if ($keluarga->status == 0)
                                        <span class="badge bg-danger ms-1" style="font-size: 0.6em;">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-3">{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}</td>
                                <td class="px-3">{{ $kepalaKeluarga?->nik_anggota ?? 'N/A' }}</td>
                                <td class="px-3">{{ $keluarga->blok?->nama_blok ?? 'N/A' }}</td>
                                <td class="px-3">{{ $keluarga->desil?->tingkat_desil ?? '6+' }}</td>
                                <td class="px-3">
                                    <div class="d-flex flex-nowrap gap-2">
                                        @if (Auth::user()->role === 'Ketua RT' ||
                                                (Auth::user()->role === 'Ketua Blok' && Auth::user()->id_blok === $keluarga->blok->id_blok))
                                            <a href="{{ route('data-warga.formEdit', $keluarga->id_keluarga) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        @endif
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" data-nokk="{{ $keluarga->no_kk }}"
                                            data-kepala="{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}"
                                            data-blok="{{ $keluarga->blok?->nama_blok ?? 'N/A' }}"
                                            data-desil="{{ $keluarga->desil?->tingkat_desil ?? '6+' }}"
                                            data-anggota="{{ $keluarga->anggotaKeluarga->toJson() }}"
                                            data-foto-ktp="{{ $keluarga->foto_ktp }}"
                                            data-foto-kk="{{ $keluarga->foto_kk }}">
                                            Detail
                                        </button>

                                        @if (Auth::user()->role === 'Ketua RT' ||
                                                (Auth::user()->role === 'Ketua Blok' && Auth::user()->id_blok === $keluarga->blok->id_blok))
                                            {{-- Tombol Aksi Status --}}
                                            {{-- Cek kondisi: Jika Status 1 (Aktif), tombol Merah (Nonaktifkan). Jika 0, tombol Hijau (Aktifkan) --}}
                                            @if ($keluarga->status == 1)
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal-{{ $keluarga->id_keluarga }}">
                                                    Nonaktif
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal-{{ $keluarga->id_keluarga }}">
                                                    Aktifkan
                                                </button>
                                            @endif
                                        @endif

                                        {{-- Modal Status (ID dibuat UNIK menggunakan id_keluarga) --}}
                                        <div class="modal fade" id="statusModal-{{ $keluarga->id_keluarga }}"
                                            tabindex="-1"
                                            aria-labelledby="statusModalLabel-{{ $keluarga->id_keluarga }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content text-dark"> {{-- Tambah text-dark agar tulisan terbaca --}}
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="statusModalLabel-{{ $keluarga->id_keluarga }}">
                                                            Konfirmasi Status
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin mengubah status keluarga
                                                        <strong>{{ $keluarga->no_kk }}</strong> menjadi
                                                        <strong>{{ $keluarga->status == 1 ? 'Nonaktif' : 'Aktif' }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>

                                                        {{-- Form Update Status --}}
                                                        <form
                                                            action="{{ route('data-warga.status', $keluarga->id_keluarga) }}"
                                                            method="POST">
                                                            @csrf {{-- WAJIB ADA --}}
                                                            @method('PUT') {{-- Disarankan pakai PUT/PATCH untuk update --}}

                                                            {{-- Kirim status kebalikan. Jika skrg 1 kirim 0, jika skrg 0 kirim 1 --}}
                                                            <input type="hidden" name="status"
                                                                value="{{ $keluarga->status == 1 ? 0 : 1 }}">

                                                            <button type="submit"
                                                                class="btn {{ $keluarga->status == 1 ? 'btn-danger' : 'btn-success' }}">
                                                                Ya,
                                                                {{ $keluarga->status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Footer Paginasi --}}
    {{-- BARU: Wrapper ini akan ada DI LUAR <form> --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">

        {{-- Kiri: Info Halaman & Hasil per Halaman --}}
        <div class="mb-2 mb-md-0">
            {{-- Kita perlu form baru di sini HANYA untuk "per_page" --}}
            <form action="{{ route('data-warga.index') }}" method="GET" class="d-inline">
                {{-- Bawa semua filter & search yang sedang aktif --}}
                <input type="hidden" name="search_query" value="{{ $searchQuery ?? '' }}">
                <input type="hidden" name="filter_blok" value="{{ $filterBlok ?? '' }}">
                <input type="hidden" name="filter_desil" value="{{ $filterDesil ?? '' }}">
                {{-- BARU: Tambahkan ini --}}
                <input type="hidden" name="filter_status" value="{{ $filterStatus ?? '' }}">

                <span class="me-1 text-body-secondary">Hasil per halaman</span>
                <select name="per_page" class="form-select form-select-sm d-inline-block w-auto"
                    onchange="this.form.submit()">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>

            <span class="ms-2 text-body-secondary">
                Menampilkan {{ $dataKeluarga->firstItem() ?? 0 }}
                sampai {{ $dataKeluarga->lastItem() ?? 0 }}
                dari {{ $dataKeluarga->total() }} hasil
            </span>
        </div>

        {{-- Kanan: Kontrol Paginasi --}}
        <div class="d-flex align-items-center">
            {{-- Ini akan otomatis rapi setelah fix di AppServiceProvider --}}
            {{ $dataKeluarga->links() }}
        </div>

    </div>

    {{-- MODAL UNTUK DETAIL DATA --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"> {{-- Tambah scrollable agar enak dilihat di layar kecil --}}
            <div class="modal-content border-0 shadow-lg">

                {{-- Header Modal --}}
                <div class="modal-header bg-utama2 text-white">
                    <h5 class="modal-title fw-bold" id="detailModalLabel">
                        <i class="bi bi-person-vcard me-2"></i>Detail Data Keluarga
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light">

                    {{-- BAGIAN 1: INFO KARTU KELUARGA (Card Style) --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row g-4 align-items-center">
                                {{-- Info Utama: No KK --}}
                                <div class="col-md-4 border-end border-light-subtle">
                                    <small class="text-uppercase text-muted fw-bold ls-1">Nomor Kartu Keluarga</small>
                                    <h2 class="text-biru fw-bold mb-0 mt-1" id="modal-no-kk">Loading...</h2>
                                </div>

                                {{-- Info Kepala Keluarga --}}
                                <div class="col-md-4 border-end border-light-subtle">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Kepala Keluarga</small>
                                            <h5 class="fw-bold mb-0 text-dark" id="modal-kepala-keluarga">Loading...</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- Info Lokasi & Ekonomi --}}
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Lokasi & Ekonomi</small>
                                            <h5 class="fw-bold mb-0 text-dark" id="modal-blok-desil">Loading...</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN 2: DAFTAR ANGGOTA KELUARGA --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h6 class="mb-0 fw-bold text-secondary">
                                <i class="bi bi-people-fill me-2 text-primary"></i>Daftar Anggota Keluarga
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 1rem;">
                                    <thead class="bg-light text-secondary">
                                        <tr>
                                            <th class="py-3 ps-4">No</th>
                                            <th class="py-3">NIK</th>
                                            <th class="py-3">Nama Lengkap</th>
                                            <th class="py-3">Status Dalam Keluarga</th>
                                            <th class="py-3">L/P</th>
                                            <th class="py-3">TTL</th>
                                            <th class="py-3">Agama</th>
                                            <th class="py-3">Pendidikan</th>
                                            <th class="py-3 pe-4">Pekerjaan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal-anggota-list" class="bg-white">
                                        {{-- Data diinject via JS --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN 3: BERKAS PENDUKUNG (Grid Layout) --}}
                    <h6 class="fw-bold text-secondary mb-3 ps-1">
                        <i class="bi bi-folder-fill me-2 text-warning"></i>Berkas Pendukung
                    </h6>
                    <div class="row g-4">
                        {{-- Card Foto KTP --}}
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <h6 class="card-title fw-bold text-muted mb-3">Foto KTP Kepala Keluarga</h6>

                                    {{-- Area Gambar --}}
                                    <div id="ktp-container"
                                        class="rounded bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative"
                                        style="height: 250px; border: 2px dashed #dee2e6;">
                                        {{-- Placeholder jika kosong --}}
                                        <div id="ktp-placeholder" class="text-muted">
                                            <i class="bi bi-image fs-1 d-block mb-2 opacity-25"></i>
                                            <small>Tidak ada lampiran KTP</small>
                                        </div>

                                        {{-- Gambar Asli --}}
                                        <img id="modal-foto-ktp" src=""
                                            class="img-fluid w-100 h-100 object-fit-contain" style="display:none;">
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="mt-3">
                                        <a id="modal-link-ktp" href="#" target="_blank"
                                            class="btn btn-sm btn-outline-primary d-none">
                                            <i class="bi bi-zoom-in me-1"></i> Lihat Ukuran Penuh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Foto KK --}}
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <h6 class="card-title fw-bold text-muted mb-3">Foto Kartu Keluarga</h6>

                                    {{-- Area Gambar --}}
                                    <div id="kk-container"
                                        class="rounded bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative"
                                        style="height: 250px; border: 2px dashed #dee2e6;">
                                        {{-- Placeholder --}}
                                        <div id="kk-placeholder" class="text-muted">
                                            <i class="bi bi-file-earmark-image fs-1 d-block mb-2 opacity-25"></i>
                                            <small>Tidak ada lampiran KK</small>
                                        </div>

                                        {{-- Gambar Asli --}}
                                        <img id="modal-foto-kk" src=""
                                            class="img-fluid w-100 h-100 object-fit-contain" style="display:none;">
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="mt-3">
                                        <a id="modal-link-kk" href="#" target="_blank"
                                            class="btn btn-sm btn-outline-primary d-none">
                                            <i class="bi bi-zoom-in me-1"></i> Lihat Ukuran Penuh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var detailModal = document.getElementById('detailModal');

            detailModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;

                // Ambil Data
                var no_kk = button.getAttribute('data-nokk');
                var kepala = button.getAttribute('data-kepala');
                var blok = button.getAttribute('data-blok');
                var desil = button.getAttribute('data-desil');
                var anggotaListJson = button.getAttribute('data-anggota');
                var anggotaList = JSON.parse(anggotaListJson);
                var fotoKTP = button.getAttribute('data-foto-ktp');
                var fotoKK = button.getAttribute('data-foto-kk');

                // Elemen DOM
                var modalNoKk = detailModal.querySelector('#modal-no-kk');
                var modalKepala = detailModal.querySelector('#modal-kepala-keluarga');
                var modalBlokDesil = detailModal.querySelector('#modal-blok-desil');
                var anggotaTableBody = detailModal.querySelector('#modal-anggota-list');

                // --- ISI DATA TEKS ---
                modalNoKk.textContent = no_kk;
                modalKepala.textContent = kepala;

                // Format Badge untuk Desil/Blok (Opsional, biar lebih cantik)
                modalBlokDesil.innerHTML =
                    `<span class="text-dark">${blok}</span> <span class="text-muted mx-1">|</span> <span class="badge bg-info text-dark">Desil ${desil}</span>`;


                // --- LOGIKA GAMBAR KTP ---
                var imgKTP = document.getElementById('modal-foto-ktp');
                var linkKTP = document.getElementById('modal-link-ktp');
                var placeholderKTP = document.getElementById('ktp-placeholder');

                if (fotoKTP) {
                    var urlKTP = '/storage/' + fotoKTP;
                    imgKTP.src = urlKTP;
                    imgKTP.style.display = 'block';
                    placeholderKTP.style.display = 'none'; // Sembunyikan placeholder

                    linkKTP.href = urlKTP;
                    linkKTP.classList.remove('d-none');
                } else {
                    imgKTP.style.display = 'none';
                    placeholderKTP.style.display = 'block'; // Tampilkan placeholder
                    linkKTP.classList.add('d-none');
                }

                // --- LOGIKA GAMBAR KK ---
                var imgKK = document.getElementById('modal-foto-kk');
                var linkKK = document.getElementById('modal-link-kk');
                var placeholderKK = document.getElementById('kk-placeholder');

                if (fotoKK) {
                    var urlKK = '/storage/' + fotoKK;
                    imgKK.src = urlKK;
                    imgKK.style.display = 'block';
                    placeholderKK.style.display = 'none';

                    linkKK.href = urlKK;
                    linkKK.classList.remove('d-none');
                } else {
                    imgKK.style.display = 'none';
                    placeholderKK.style.display = 'block';
                    linkKK.classList.add('d-none');
                }

                // --- TABEL ANGGOTA KELUARGA ---
                anggotaTableBody.innerHTML = '';

                if (anggotaList.length > 0) {
                    anggotaList.forEach(function(item, index) {
                        // Helper Format Tanggal
                        function formatDate(dateString) {
                            if (!dateString) return '-';
                            try {
                                var date = new Date(dateString);
                                var d = date.getDate().toString().padStart(2, '0');
                                var m = (date.getMonth() + 1).toString().padStart(2, '0');
                                var y = date.getFullYear();
                                return d + '/' + m + '/' + y;
                            } catch (e) {
                                return dateString;
                            }
                        }

                        // Helper Label Status
                        let statusBadge = item.status_dalam_keluarga;
                        if (item.status_dalam_keluarga === 'Kepala Keluarga') {
                            statusBadge =
                                `<span class="badge bg-primary-subtle text-dark border border-dark">Kepala Keluarga</span>`;
                        }

                        var row = `
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">${index + 1}</td>
                            <td class="fw-medium font-monospace">${item.nik_anggota || '-'}</td>
                            <td class="fw-bold text-dark">${item.nama_lengkap || '-'}</td>
                            <td>${statusBadge}</td>
                            <td>${item.jenis_kelamin ? (item.jenis_kelamin.startsWith('L') ? '<span class="badge bg-info text-dark">L</span>' : '<span class="badge bg-danger-subtle text-danger">P</span>') : '-'}</td>
                            <td>
                                <div class="d-flex flex-column" style="line-height:1.2">
                                    <small class="text-muted" style="font-size:0.75rem">${item.tempat_lahir || '-'}</small>
                                    <span>${formatDate(item.tanggal_lahir)}</span>
                                </div>
                            </td>
                            <td>${item.agama || '-'}</td>
                            <td>${item.pendidikan || '-'}</td>
                            <td class="pe-4">${item.pekerjaan || '-'}</td>
                        </tr>
                    `;

                        anggotaTableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    anggotaTableBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data anggota keluarga.
                        </td>
                    </tr>
                `;
                }
            });
        });
    </script>
@endpush
