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
                                    <option value="6" {{ ($filterDesil ?? '') == '6' ? 'selected' : '' }}>Desil 6
                                    </option>
                                    <option value="Tidak ada" {{ ($filterDesil ?? '') == 'Tidak ada' ? 'selected' : '' }}>
                                        Tidak ada</option>
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
                                <td class="px-3">{{ $keluarga->desil?->tingkat_desil ?? 'Tidak ada' }}</td>
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
                                            data-desil="{{ $keluarga->desil?->tingkat_desil ?? 'Tidak ada' }}"
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
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Data Warga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Data Keluarga</h5>
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
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered" style="font-size: 0.9em;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Status</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tgl Lahir</th>
                                    <th>L/P</th>
                                    <th>Agama</th>
                                    <th>Status Kawin</th>
                                    <th>Pendidikan</th>
                                    <th>Pekerjaan</th>
                                </tr>
                            </thead>
                            <tbody id="modal-anggota-list">
                            </tbody>
                        </table>
                    </div>
                    <h5>Berkas Pendukung</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th style="width:30%">Foto KTP</th>
                            <td>
                                <img id="modal-foto-ktp" src="{{ $keluarga->foto_ktp ? asset('storage/' . $keluarga->foto_ktp) : '' }}" 
                                    class="img-fluid rounded shadow-sm" 
                                    style="max-height:200px; display:none;">
                                <a id="modal-link-ktp" href="#" target="_blank" class="d-none">Lihat Berkas KTP</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Foto KK</th>
                            <td>
                                <img id="modal-foto-kk" src="" 
                                    class="img-fluid rounded shadow-sm" 
                                    style="max-height:200px; display:none;">
                                <a id="modal-link-kk" href="#" target="_blank" class="d-none">Lihat Berkas KK</a>
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                var no_kk = button.getAttribute('data-nokk');
                var kepala = button.getAttribute('data-kepala');
                var blok = button.getAttribute('data-blok');
                var desil = button.getAttribute('data-desil');
                var anggotaListJson = button.getAttribute('data-anggota');
                var anggotaList = JSON.parse(anggotaListJson);

                var modalNoKk = detailModal.querySelector('#modal-no-kk');
                var modalKepala = detailModal.querySelector('#modal-kepala-keluarga');
                var modalBlokDesil = detailModal.querySelector('#modal-blok-desil');
                var anggotaTableBody = detailModal.querySelector('#modal-anggota-list');

                var fotoKTP = button.getAttribute('data-foto-ktp');
                var fotoKK = button.getAttribute('data-foto-kk');
                var imgKTP = document.getElementById('modal-foto-ktp');
                var linkKTP = document.getElementById('modal-link-ktp');
                var imgKK = document.getElementById('modal-foto-kk');
                var linkKK = document.getElementById('modal-link-kk');
                
                // FOTO KTP
                if (fotoKTP) {
                    var urlKTP = '/storage/' + fotoKTP;
                    imgKTP.src = urlKTP;
                    imgKTP.style.display = 'block';
                    linkKTP.href = urlKTP;
                    linkKTP.classList.remove('d-none');
                } else {
                    imgKTP.style.display = 'none';
                    linkKTP.classList.add('d-none');
                }

                // FOTO KK
                if (fotoKK) {
                    var urlKK = '/storage/' + fotoKK;
                    imgKK.src = urlKK;
                    imgKK.style.display = 'block';
                    linkKK.href = urlKK;
                    linkKK.classList.remove('d-none');
                } else {
                    imgKK.style.display = 'none';
                    linkKK.classList.add('d-none');
                }

                modalNoKk.textContent = no_kk;
                modalKepala.textContent = kepala;
                modalBlokDesil.textContent = blok + ' / ' + desil;

                anggotaTableBody.innerHTML = '';

                if (anggotaList.length > 0) {
                    anggotaList.forEach(function(item, index) {
                        function formatDate(dateString) {
                            if (!dateString) return 'N/A';
                            try {
                                var date = new Date(dateString);
                                var d = date.getDate().toString().padStart(2, '0');
                                var m = (date.getMonth() + 1).toString().padStart(2,
                                    '0'); // Bulan mulai dari 0
                                var y = date.getFullYear();
                                return d + '-' + m + '-' + y;
                            } catch (e) {
                                return dateString; // Fallback jika format tidak valid
                            }
                        }

                        // Buat baris tabel dengan semua data
                        var row = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + (item.nik_anggota || 'N/A') + '</td>' +
                            '<td>' + (item.nama_lengkap || 'N/A') + '</td>' +
                            '<td>' + (item.status_dalam_keluarga || 'N/A') + '</td>' +
                            '<td>' + (item.tempat_lahir || 'N/A') + '</td>' +
                            '<td>' + formatDate(item.tanggal_lahir) + '</td>' +
                            '<td>' + (item.jenis_kelamin ? (item.jenis_kelamin.startsWith('L') ?
                                'L' : 'P') : 'N/A') + '</td>' +
                            '<td>' + (item.agama || 'N/A') + '</td>' +
                            '<td>' + (item.status_perkawinan || 'N/A') + '</td>' +
                            '<td>' + (item.pendidikan || 'N/A') + '</td>' +
                            '<td>' + (item.pekerjaan || 'N/A') + '</td>' +
                            '</tr>';

                        anggotaTableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    anggotaTableBody.innerHTML =
                        '<tr><td colspan="11" class="text-center">Tidak ada data anggota.</td></tr>';
                }
            });
        });
    </script>
@endpush
