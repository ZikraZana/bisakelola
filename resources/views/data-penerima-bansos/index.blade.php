@extends('layouts.layout')

@section('title')
    Data Pengajuan Bansos
@endsection

@section('title_nav')
    Pengajuan Bansos
@endsection

@section('content')
    {{-- Card Group - Statistik --}}
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
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['diajukan'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4 text-utama1">
                    <p class="text-body-secondary mb-1">Sudah Disetujui</p>
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

    {{-- Toolbar Aksi --}}
    <div class="border border-2 p-3 rounded min-vh-50">
        <form action="{{ route('data-penerima-bansos.index') }}" method="GET">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="mb-2 mb-md-0">
                    <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#searchCollapse" aria-expanded="{{ $searchQuery ? 'true' : 'false' }}">
                        <i class="bi bi-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse"
                        aria-expanded="{{ $filterStatusAcc || $filterStatusTerima ? 'true' : 'false' }}">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
                <div>
                    @if (Auth::user()->role === 'Ketua RT')
                        <a href="{{ route('data-penerima-bansos.formTambah') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Warga
                        </a>
                    @endif
                </div>
            </div>

            {{-- Toolbar Panels (Search & Filter) --}}
            <div id="toolbarPanels">
                <div class="collapse mb-3 {{ $searchQuery ? 'show' : '' }}" id="searchCollapse"
                    data-bs-parent="#toolbarPanels">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search_query"
                            placeholder="Cari No. KK / Nama Kepala Keluarga..." value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i> Cari</button>
                    </div>
                </div>
                <div class="collapse mb-3 {{ $filterStatusAcc || $filterStatusTerima ? 'show' : '' }}" id="filterCollapse"
                    data-bs-parent="#toolbarPanels">
                    <div class="card card-body bg-light border-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Filter Status Pengajuan</label>
                                <select name="filter_status_acc" class="form-select">
                                    <option value="" selected>Semua Status</option>
                                    <option value="Diajukan"
                                        {{ ($filterStatusAcc ?? '') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                                    <option value="Disetujui"
                                        {{ ($filterStatusAcc ?? '') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ ($filterStatusAcc ?? '') == 'Ditolak' ? 'selected' : '' }}>
                                        Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Filter Status Penerimaan</label>
                                <select name="filter_status_terima" class="form-select">
                                    <option value="" selected>Semua Status</option>
                                    <option value="Belum" {{ ($filterStatusTerima ?? '') == 'Belum' ? 'selected' : '' }}>
                                        Belum Diterima</option>
                                    <option value="Sudah Diterima"
                                        {{ ($filterStatusTerima ?? '') == 'Sudah Diterima' ? 'selected' : '' }}>Sudah
                                        Diterima</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('data-penerima-bansos.index') }}"
                                class="btn btn-outline-secondary me-2">Reset</a>
                            <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="card shadow-sm border-0 rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle text-putih">
                        <thead class="aturlah disini warnanya">
                            <tr>
                                <th class="py-3 px-3">No</th>
                                <th class="py-3 px-3">No. KK</th>
                                <th class="py-3 px-3">Kepala Keluarga</th>
                                <th class="py-3 px-3">Keterangan (RT)</th>
                                <th class="py-3 px-3">Status & Catatan (Dinsos)</th> {{-- DIGABUNG --}}
                                <th class="py-3 px-3">Jenis Bansos</th>
                                <th class="py-3 px-3">Penyaluran</th>
                                <th class="py-3 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataPenerima as $key => $pengajuan)
                                @php
                                    $kepala = $pengajuan->keluarga->anggotaKeluarga->firstWhere(
                                        'status_dalam_keluarga',
                                        'Kepala Keluarga',
                                    );
                                @endphp
                                <tr>
                                    <td class="px-3">{{ $dataPenerima->firstItem() + $key }}</td>
                                    <td class="px-3">{{ $pengajuan->keluarga->no_kk }}</td>
                                    <td class="px-3">{{ $kepala->nama_lengkap ?? '-' }}</td>
                                    <td class="px-3">
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;"
                                            title="{{ $pengajuan->keterangan_pengajuan }}">
                                            {{ $pengajuan->keterangan_pengajuan }}
                                        </span>
                                    </td>
                                    <td class="px-3">
                                        @if ($pengajuan->status_acc == 'Disetujui')
                                            <span class="badge bg-success mb-1">Disetujui</span>
                                        @elseif ($pengajuan->status_acc == 'Ditolak')
                                            <span class="badge bg-danger mb-1">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark mb-1">Diajukan</span>
                                        @endif

                                        {{-- MENAMPILKAN CATATAN DINSOS DI SINI --}}
                                        @if ($pengajuan->keterangan_acc)
                                            <br><small class="text-muted fst-italic" style="font-size: 0.8em;">
                                                "{{ Str::limit($pengajuan->keterangan_acc, 30) }}"
                                            </small>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        {{ $pengajuan->bansos->nama_bansos ?? '-' }}
                                        @if ($pengajuan->periode)
                                            <br><small class="text-muted">{{ $pengajuan->periode }}</small>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        @if ($pengajuan->status_bansos_diterima == 'Sudah Diterima')
                                            <span class="badge bg-primary">Sudah</span>
                                        @else
                                            <span class="badge bg-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-pengajuan="{{ $pengajuan->toJson() }}"
                                                data-keluarga="{{ $pengajuan->keluarga->toJson() }}"
                                                data-kepala="{{ $kepala ? $kepala->nama_lengkap : 'N/A' }}">
                                                Detail
                                            </button>
                                            {{-- Edit/Hapus logic here if needed --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    {{-- Footer Paginasi --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
        <div class="mb-2 mb-md-0">
            <span class="ms-2 text-body-secondary">
                Menampilkan {{ $dataPenerima->firstItem() ?? 0 }} - {{ $dataPenerima->lastItem() ?? 0 }} dari
                {{ $dataPenerima->total() }} hasil
            </span>
        </div>
        <div>{{ $dataPenerima->links() }}</div>
    </div>

    {{-- MODAL DETAIL --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Data Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Status & Keputusan (Dinsos)</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="35%">Status Pengajuan</td>
                            <td width="5%">:</td>
                            <td id="m-status" class="fw-bold"></td>
                        </tr>
                        {{-- INI YANG ANDA CARI: CATATAN DINSOS --}}
                        <tr class="table-warning">
                            <td>Catatan Dinsos</td>
                            <td>:</td>
                            <td id="m-ket-acc" class="fst-italic"></td>
                        </tr>
                        <tr>
                            <td>Jenis Bansos</td>
                            <td>:</td>
                            <td id="m-jenis"></td>
                        </tr>
                        <tr>
                            <td>Periode</td>
                            <td>:</td>
                            <td id="m-periode"></td>
                        </tr>
                        <tr>
                            <td>Status Penyaluran</td>
                            <td>:</td>
                            <td id="m-terima"></td>
                        </tr>
                    </table>

                    <h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">Data Pengajuan (RT)</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="35%">Tanggal Pengajuan</td>
                            <td width="5%">:</td>
                            <td id="m-tgl"></td>
                        </tr>
                        <tr>
                            <td>No. KK</td>
                            <td>:</td>
                            <td id="m-kk"></td>
                        </tr>
                        <tr>
                            <td>Kepala Keluarga</td>
                            <td>:</td>
                            <td id="m-kepala"></td>
                        </tr>
                        <tr>
                            <td>Keterangan RT</td>
                            <td>:</td>
                            <td id="m-ket-rt"></td>
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
                var btn = event.relatedTarget;
                var p = JSON.parse(btn.getAttribute('data-pengajuan'));
                var k = JSON.parse(btn.getAttribute('data-keluarga'));
                var kepala = btn.getAttribute('data-kepala');

                // Helper
                const txt = (id, val) => document.getElementById(id).textContent = val || '-';

                // Isi Data
                txt('m-status', p.status_acc);
                txt('m-ket-acc', p.keterangan_acc ? p.keterangan_acc : 'Belum catatan');
                txt('m-jenis', p.bansos ? p.bansos.nama_bansos : 'Belum Ditentukan');

                // Atur jika status_acc adalah 'Ditolak'
                if (p.status_acc === 'Ditolak') {
                    txt('m-jenis', '-');
                    txt('m-periode', '-');
                    txt('m-terima', '-');
                } else {
                    txt('m-periode', p.periode);
                    txt('m-terima', p.status_bansos_diterima);
                }
                txt('m-tgl', new Date(p.created_at).toLocaleDateString('id-ID'));
                txt('m-kk', k.no_kk);
                txt('m-kepala', kepala);
                txt('m-ket-rt', p.keterangan_pengajuan);
            });
        });
    </script>
@endpush
