@extends('layouts.layout')

@section('title', 'Proses Persetujuan')
@section('title_nav', 'Proses Persetujuan Bansos')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        
        <form action="{{ route('kelola-bansos.update', $pengajuan->id_penerima_bansos) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <h4 class="fw-bold mb-4">Proses Pengajuan Bansos</h4>

                    {{-- 1. INFO PENGAJUAN (READ ONLY) --}}
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-info-circle me-2"></i>Detail Pengajuan</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">No. KK</label>
                                <p class="fw-bold mb-0">{{ $pengajuan->keluarga->no_kk }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Kepala Keluarga</label>
                                <p class="fw-bold mb-0">{{ $pengajuan->keluarga->anggotaKeluarga->firstWhere('status_dalam_keluarga', 'Kepala Keluarga')->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small text-uppercase fw-bold">Alasan Pengajuan</label>
                                <p class="mb-0 fst-italic">"{{ $pengajuan->keterangan_pengajuan }}"</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- 2. FORM KEPUTUSAN --}}
                    <h5 class="fw-bold mb-3">Keputusan Dinsos</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Status Pengajuan</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check card p-3 h-100 {{ old('status_acc', $pengajuan->status_acc) == 'Diajukan' ? 'bg-light-warning border-warning' : '' }}">
                                    <input class="form-check-input status-radio" type="radio" name="status_acc" id="st_diajukan" value="Diajukan"
                                        {{ old('status_acc', $pengajuan->status_acc) == 'Diajukan' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="st_diajukan">Tunda / Pending</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check card p-3 h-100 {{ old('status_acc', $pengajuan->status_acc) == 'Disetujui' ? 'bg-light-success border-success' : '' }}">
                                    <input class="form-check-input status-radio" type="radio" name="status_acc" id="st_setuju" value="Disetujui"
                                        {{ old('status_acc', $pengajuan->status_acc) == 'Disetujui' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-success" for="st_setuju">SETUJUI</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check card p-3 h-100 {{ old('status_acc', $pengajuan->status_acc) == 'Ditolak' ? 'bg-light-danger border-danger' : '' }}">
                                    <input class="form-check-input status-radio" type="radio" name="status_acc" id="st_tolak" value="Ditolak"
                                        {{ old('status_acc', $pengajuan->status_acc) == 'Ditolak' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-danger" for="st_tolak">TOLAK</label>
                                </div>
                            </div>
                        </div>
                        @error('status_acc') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- 3. DETAIL BANTUAN (Hanya muncul jika radio 'Disetujui' dipilih - Pakai JS) --}}
                    <div id="section-detail-bantuan" class="row g-3 mb-4 p-4 bg-light rounded-3 border">
                        <div class="col-12"><h6 class="fw-bold text-success mb-0"><i class="bi bi-check-lg me-2"></i>Detail Bantuan (Wajib Diisi)</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Bansos <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_bansos') is-invalid @enderror" name="id_bansos">
                                <option value="" selected>-- Pilih Jenis --</option>
                                @foreach($masterBansos as $bansos)
                                    <option value="{{ $bansos->id_bansos }}" {{ old('id_bansos', $pengajuan->id_bansos) == $bansos->id_bansos ? 'selected' : '' }}>
                                        {{ $bansos->nama_bansos }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_bansos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('periode') is-invalid @enderror" name="periode" 
                                placeholder="Cth: Triwulan 1 2025" value="{{ old('periode', $pengajuan->periode) }}">
                            @error('periode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- 4. CATATAN KEPUTUSAN (Selalu Muncul) --}}
                    <div class="mb-4">
                        <label class="form-label">Catatan Keputusan (Opsional)</label>
                        <textarea class="form-control" name="keterangan_acc" rows="2" 
                            placeholder="Alasan persetujuan atau penolakan...">{{ old('keterangan_acc', $pengajuan->keterangan_acc) }}</textarea>
                    </div>

                    {{-- 5. UPDATE PENYALURAN (HANYA MUNCUL JIKA DATABASE SUDAH DISETUJUI) --}}
                    {{-- Logika: Pakai Blade @if, bukan JS --}}
                    @if($pengajuan->status_acc == 'Disetujui')
                        <div class="card bg-primary-subtle border-primary mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-box-seam me-2"></i>Update Status Penyaluran</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <p class="small mb-2 mb-md-0">
                                            Apakah warga sudah datang ke kantor dan menerima bantuan (Uang/Barang)?
                                            <br>Status saat ini: <strong>{{ $pengajuan->status_bansos_diterima }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select border-primary" name="status_bansos_diterima">
                                            <option value="Belum" {{ $pengajuan->status_bansos_diterima == 'Belum' ? 'selected' : '' }}>Belum Diterima</option>
                                            <option value="Sudah Diterima" {{ $pengajuan->status_bansos_diterima == 'Sudah Diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Jika belum disetujui, kirim nilai default 'Belum' agar controller tidak error --}}
                        <input type="hidden" name="status_bansos_diterima" value="Belum">
                    @endif

                    {{-- TOMBOL AKSI --}}
                    <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                        <a href="{{ route('kelola-bansos.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Keputusan</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('.status-radio');
        const detailSection = document.getElementById('section-detail-bantuan');

        function toggleDetailSection() {
            let selectedValue = null;
            for (const radio of radioButtons) {
                if (radio.checked) { selectedValue = radio.value; break; }
            }
            
            // Hanya toggle form "Jenis & Periode". 
            // Form "Status Penyaluran" diatur oleh Blade (Server-side)
            if (selectedValue === 'Disetujui') {
                detailSection.classList.remove('d-none');
            } else {
                detailSection.classList.add('d-none');
            }
        }

        radioButtons.forEach(radio => radio.addEventListener('change', toggleDetailSection));
        
        // Jalankan saat load
        toggleDetailSection();
    });
</script>
@endpush