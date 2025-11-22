@extends('layouts.layout')

@section('title', 'Kelola Bansos (Dinsos)')
@section('title_nav', 'Daftar Masuk Pengajuan')

@section('content')
    {{-- Card Group - Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Total Masuk</p>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Perlu Diproses</p>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['diajukan'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Disetujui</p>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['disetujui'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Ditolak</p>
                    <h3 class="fw-bold mb-0 text-danger">{{ $stats['ditolak'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTAINER UTAMA (Border) --}}
    <div class="border border-2 p-3 rounded min-vh-50">

        {{-- FORM WRAPPER (Membungkus Toolbar, Tabel, DAN Pagination) --}}
        <form action="{{ route('kelola-bansos.index') }}" method="GET">

            {{-- 1. Toolbar Aksi --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="mb-2 mb-md-0">
                    <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#searchCollapse" aria-expanded="{{ $searchQuery ? 'true' : 'false' }}">
                        <i class="bi bi-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="{{ $filterStatus ? 'true' : 'false' }}">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
                <div>
                    <span class="badge bg-primary p-2"><i class="bi bi-shield-lock me-1"></i> Area Dinsos</span>
                </div>
            </div>

            {{-- 2. Panels (Search & Filter) --}}
            <div id="toolbarPanels">
                {{-- Search --}}
                <div class="collapse mb-3 {{ $searchQuery ? 'show' : '' }}" id="searchCollapse"
                    data-bs-parent="#toolbarPanels">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search_query"
                            placeholder="Cari No KK / Nama Kepala Keluarga..." value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i> Cari</button>
                        @if ($searchQuery)
                            <a href="{{ route('kelola-bansos.index', request()->except('search_query')) }}"
                                class="btn btn-outline-danger"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </div>

                {{-- Filter --}}
                <div class="collapse mb-3 {{ $filterStatus ? 'show' : '' }}" id="filterCollapse"
                    data-bs-parent="#toolbarPanels">
                    <div class="card card-body bg-light border-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Filter Status</label>
                                <select name="filter_status" class="form-select">
                                    <option value="" {{ empty($filterStatus) ? 'selected' : '' }}>Semua Status
                                    </option>
                                    <option value="Diajukan" {{ $filterStatus == 'Diajukan' ? 'selected' : '' }}>Menunggu
                                        Konfirmasi</option>
                                    <option value="Disetujui" {{ $filterStatus == 'Disetujui' ? 'selected' : '' }}>
                                        Disetujui</option>
                                    <option value="Ditolak" {{ $filterStatus == 'Ditolak' ? 'selected' : '' }}>Ditolak
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i>
                                    Filter</button>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('kelola-bansos.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Tabel Data --}}
            <div class="card shadow-sm border-0 rounded-3 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle text-putih">
                        <thead class="aturlah disini warnanya">
                            <tr>
                                <th class="py-3 px-3">Tgl Masuk</th>
                                <th class="py-3 px-3">Calon Penerima (KK)</th>
                                <th class="py-3 px-3">Status Pengajuan</th>
                                <th class="py-3 px-3">Status Penyaluran</th>
                                <th class="py-3 px-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPengajuan as $item)
                                <tr>
                                    <td class="px-3">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="px-3">
                                        <span class="fw-bold">{{ $item->keluarga->no_kk }}</span><br>
                                        <small class="text-muted">
                                            {{ $item->keluarga->anggotaKeluarga->firstWhere('status_dalam_keluarga', 'Kepala Keluarga')->nama_lengkap ?? '-' }}
                                        </small>
                                    </td>

                                    {{-- Status Pengajuan --}}
                                    <td class="px-3">
                                        @if ($item->status_acc == 'Diajukan')
                                            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @elseif($item->status_acc == 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                            <div class="small text-success fw-bold mt-1">
                                                {{ $item->bansos->nama_bansos ?? '' }}</div>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>

                                    {{-- Status Penyaluran --}}
                                    <td class="px-3">
                                        @if ($item->status_acc == 'Disetujui')
                                            @if ($item->status_bansos_diterima == 'Sudah Diterima')
                                                <span class="badge bg-primary"><i class="bi bi-check-all me-1"></i>Sudah
                                                    Diterima</span>
                                                <div class="small text-muted mt-1">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_pengambilan_bansos)->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">Belum Diambil</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('kelola-bansos.edit', $item->id_penerima_bansos) }}"
                                                class="btn btn-outline-primary btn-sm" title="Proses Keputusan">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if ($item->status_acc == 'Disetujui')
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#modalPenyaluran"
                                                    data-id="{{ $item->id_penerima_bansos }}"
                                                    data-kk="{{ $item->keluarga->no_kk }}"
                                                    data-nama="{{ $item->keluarga->anggotaKeluarga->firstWhere('status_dalam_keluarga', 'Kepala Keluarga')->nama_lengkap ?? '-' }}"
                                                    data-status="{{ $item->status_bansos_diterima }}">
                                                    <i class="bi bi-box-seam"></i> Salur
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </form> {{-- Penutup Form --}}
    </div> {{-- Penutup Border Container --}}

    {{-- 4. Pagination (SEKARANG DI DALAM FORM & DI DALAM BORDER) --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
        <div class="mb-2 mb-md-0">
            <span class="ms-2 text-body-secondary">
                Menampilkan {{ $dataPengajuan->firstItem() ?? 0 }} - {{ $dataPengajuan->lastItem() ?? 0 }} dari
                {{ $dataPengajuan->total() }} hasil
            </span>
        </div>
        <div>{{ $dataPengajuan->links() }}</div>
    </div>

    {{-- MODAL UPDATE PENYALURAN --}}
    <div class="modal fade" id="modalPenyaluran" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPenyaluran" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Update Status Penyaluran</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <strong>Penerima:</strong> <span id="mdl-nama"></span><br>
                            <strong>No. KK:</strong> <span id="mdl-kk"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Saat Ini</label>
                            <select class="form-select form-select-lg" name="status_bansos_diterima"
                                id="mdl-select-status">
                                <option value="Belum">Belum Diambil</option>
                                <option value="Sudah Diterima">Sudah Diterima / Disalurkan</option>
                            </select>
                            <div class="form-text">Jika diubah ke "Sudah Diterima", tanggal pengambilan tercatat hari ini.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalPenyaluran = document.getElementById('modalPenyaluran');
            const formPenyaluran = document.getElementById('formPenyaluran');
            const spanNama = document.getElementById('mdl-nama');
            const spanKK = document.getElementById('mdl-kk');
            const selectStatus = document.getElementById('mdl-select-status');

            modalPenyaluran.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');

                const baseUrl = "{{ route('kelola-bansos.updatePenyaluran', ':id') }}";
                formPenyaluran.action = baseUrl.replace(':id', id);

                spanKK.textContent = button.getAttribute('data-kk');
                spanNama.textContent = button.getAttribute('data-nama');
                selectStatus.value = button.getAttribute('data-status');
            });
        });
    </script>
@endpush
