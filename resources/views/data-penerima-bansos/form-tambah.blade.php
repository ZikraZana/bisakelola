@extends('layouts.layout')

@section('title')
    Pilih Warga untuk Pengajuan Bansos
@endsection

@section('title_nav')
    Pengajuan Bansos
@endsection

@section('content')
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            
            {{-- Header & Pencarian --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Daftar Warga Potensial</h4>
                    <p class="text-muted mb-0">Cari dan pilih warga yang ingin diajukan menerima bantuan.</p>
                </div>
                
                {{-- Form Pencarian --}}
                <form action="{{ route('data-penerima-bansos.formTambah') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search_query" 
                            placeholder="Cari No. KK / Nama Kepala..." 
                            value="{{ $searchQuery ?? '' }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    @if($searchQuery)
                        <a href="{{ route('data-penerima-bansos.formTambah') }}" class="btn btn-outline-danger" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>

            {{-- Pesan Error/Sukses Session --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th class="py-3 px-3">No. KK</th>
                            <th class="py-3 px-3">Kepala Keluarga</th>
                            <th class="py-3 px-3">Blok / Lokasi</th>
                            <th class="py-3 px-3 text-center">Desil</th>
                            <th class="py-3 px-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataKeluarga as $keluarga)
                            @php
                                $kepala = $keluarga->anggotaKeluarga->firstWhere('status_dalam_keluarga', 'Kepala Keluarga');
                            @endphp
                            <tr>
                                <td class="px-3 fw-bold font-monospace">{{ $keluarga->no_kk }}</td>
                                <td class="px-3">
                                    <div class="fw-bold">{{ $kepala->nama_lengkap ?? 'Tidak Ada Data' }}</div>
                                    <small class="text-muted">NIK: {{ $kepala->nik_anggota ?? '-' }}</small>
                                </td>
                                <td class="px-3">{{ $keluarga->blok->nama_blok ?? '-' }}</td>
                                <td class="px-3 text-center">
                                    @if($keluarga->desil)
                                        <span class="badge bg-warning text-dark">Desil {{ $keluarga->desil->tingkat_desil }}</span>
                                    @else
                                        <span class="badge bg-light text-secondary border">Non-Desil</span>
                                    @endif
                                </td>
                                <td class="px-3 text-end">
                                    {{-- Tombol Trigger Modal --}}
                                    <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalAjukan"
                                        data-nokk="{{ $keluarga->no_kk }}"
                                        data-nama="{{ $kepala->nama_lengkap ?? 'Warga' }}">
                                        <i class="bi bi-plus-circle me-1"></i> Ajukan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-emoji-frown fs-1 d-block mb-2"></i>
                                    Data warga tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $dataKeluarga->links() }}
            </div>

            <div class="mt-3">
                <a href="{{ route('data-penerima-bansos.index') }}" class="btn btn-link text-secondary text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Kembali ke Data Penerima
                </a>
            </div>
        </div>
    </div>

    {{-- MODAL FORM PENGAJUAN --}}
    <div class="modal fade" id="modalAjukan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('data-penerima-bansos.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Formulir Pengajuan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Info Warga Terpilih --}}
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                            <div>
                                Mengajukan Keluarga: <br>
                                <strong id="modal-display-nama">Nama Kepala</strong> 
                                (<span id="modal-display-nokk" class="font-monospace">NO KK</span>)
                            </div>
                        </div>

                        {{-- Hidden Input untuk No KK --}}
                        {{-- Trik: Gunakan array index 0 agar cocok dengan validasi backend --}}
                        <input type="hidden" name="pengajuan[0][no_kk]" id="modal-input-nokk">

                        {{-- Input Keterangan --}}
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold">Alasan / Keterangan Pengajuan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="pengajuan[0][keterangan_pengajuan]" id="keterangan" rows="4" 
                                placeholder="Contoh: Kondisi ekonomi menurun, kehilangan pekerjaan, lansia sebatang kara..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send-fill me-1"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalAjukan = document.getElementById('modalAjukan');
        
        modalAjukan.addEventListener('show.bs.modal', function (event) {
            // Tombol yang memicu modal
            var button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            var nokk = button.getAttribute('data-nokk');
            var nama = button.getAttribute('data-nama');
            
            // Isi elemen di dalam modal
            modalAjukan.querySelector('#modal-display-nokk').textContent = nokk;
            modalAjukan.querySelector('#modal-display-nama').textContent = nama;
            modalAjukan.querySelector('#modal-input-nokk').value = nokk;
            
            // Reset textarea agar kosong setiap kali buka modal baru
            modalAjukan.querySelector('textarea').value = '';
        });
    });
</script>
@endpush