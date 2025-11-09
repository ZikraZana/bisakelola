@extends('layouts.layout')

@section('title')
    Formulir Pengajuan Bansos
@endsection

@section('title_nav')
    Pengajuan Bansos
@endsection

@section('content')
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4 p-md-5">

            {{-- DIUBAH: Action ke route 'store' --}}
            <form action="{{ route('data-penerima-bansos.store') }}" method="POST">
                @csrf

                {{-- Menampilkan ringkasan error (sesuai data-warga) --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                {{-- Pesan error sekarang lebih ramah (cth: "No. KK di baris 1 wajib diisi.") --}}
                                <li>{{ str_replace('pengajuan.', 'baris ', str_replace('_', ' ', $error)) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- DIUBAH: Menampilkan error 'session' --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- AKHIR BLOK ERROR --}}

                <h4 class="fw-bold mb-3">Data Pengajuan Warga</h4>
                <p class="text-body-secondary">
                    Anda bisa mengajukan lebih dari satu keluarga sekaligus.
                </p>

                {{-- Container untuk baris-baris pengajuan dinamis --}}
                <div id="pengajuan-container">
                    @php
                        // Menangani data 'old' jika validasi gagal
                        $pengajuan_list = old('pengajuan', [0 => []]);
                    @endphp

                    @foreach ($pengajuan_list as $index => $pengajuan)
                        <div class="card mb-3 pengajuan-item">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title pengajuan-title mb-0">Pengajuan {{ $index + 1 }}</h5>
                                @if ($index > 0)
                                    <button type="button" class="btn btn-danger btn-sm remove-pengajuan">Hapus</button>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Kolom No. KK --}}
                                    <div class="col-md-5">
                                        <label for="no_kk_{{ $index }}" class="form-label">Nomor Kartu Keluarga
                                            (KK)</label>
                                        <input type="text" {{-- DIUBAH: Menampilkan error 'is-invalid' --}}
                                            class="form-control @error("pengajuan.$index.no_kk") is-invalid @enderror"
                                            id="no_kk_{{ $index }}" name="pengajuan[{{ $index }}][no_kk]"
                                            placeholder="Masukkan No. KK..." value="{{ $pengajuan['no_kk'] ?? '' }}"
                                            required>
                                        {{-- DIUBAH: Menampilkan pesan error inline --}}
                                        @error("pengajuan.$index.no_kk")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Kolom Keterangan --}}
                                    <div class="col-md-7">
                                        <label for="keterangan_pengajuan_{{ $index }}" class="form-label">Alasan /
                                            Keterangan Pengajuan</label>
                                        <textarea class="form-control @error("pengajuan.$index.keterangan_pengajuan") is-invalid @enderror"
                                            id="keterangan_pengajuan_{{ $index }}" name="pengajuan[{{ $index }}][keterangan_pengajuan]"
                                            rows="3" placeholder="Contoh: Kehilangan pekerjaan, rumah tidak layak huni..." required>{{ $pengajuan['keterangan_pengajuan'] ?? '' }}</textarea>
                                        @error("pengajuan.$index.keterangan_pengajuan")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-success btn-sm mt-3" id="add-pengajuan">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Pengajuan
                </button>

                <div class="d-flex justify-content-end mt-4">
                    {{-- DIUBAH: Route ke 'index' --}}
                    <a href="{{ route('data-penerima-bansos.index') }}" class="btn btn-outline-secondary me-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send-fill me-1"></i> Kirim Semua Pengajuan
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- Script JS (tetap sama) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('pengajuan-container');
            const addButton = document.getElementById('add-pengajuan');

            // --- FUNGSI UNTUK MENAMBAH PENGAJUAN ---
            addButton.addEventListener('click', function() {
                const template = container.querySelector('.pengajuan-item');
                if (!template) return;
                const newForm = template.cloneNode(true);
                const newIndex = container.querySelectorAll('.pengajuan-item').length;

                newForm.querySelector('.pengajuan-title').textContent = 'Pengajuan ' + (newIndex + 1);

                // Reset semua nilai input
                newForm.querySelectorAll('input[type="text"], textarea').forEach(input => {
                    input.value = '';
                    input.classList.remove('is-invalid');
                });
                newForm.querySelectorAll('.text-danger.small').forEach(err => err.remove());

                // Tambahkan tombol hapus
                if (newIndex > 0 && !newForm.querySelector('.remove-pengajuan')) {
                    const header = newForm.querySelector('.card-header');
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'btn btn-danger btn-sm remove-pengajuan';
                    removeButton.textContent = 'Hapus';
                    header.appendChild(removeButton);
                }

                // Perbarui atribut 'name', 'id', 'for'
                newForm.querySelectorAll('[name], [id], [for]').forEach(el => {
                    ['name', 'id', 'for'].forEach(attr => {
                        const value = el.getAttribute(attr);
                        if (value) {
                            const newValue = value.replace(/\[\d+\]/g, '[' + newIndex + ']')
                                .replace(/_\d+$/, '_' + newIndex);
                            el.setAttribute(attr, newValue);
                        }
                    });
                });
                container.appendChild(newForm);
            });

            // --- FUNGSI UNTUK MENGHAPUS PENGAJUAN ---
            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-pengajuan')) {
                    const cardToRemove = e.target.closest('.pengajuan-item');
                    if (cardToRemove) {
                        cardToRemove.remove();
                        updateAllIndexes();
                    }
                }
            });

            // --- FUNGSI UNTUK MEMPERBARUI SEMUA INDEX SETELAH HAPUS ---
            function updateAllIndexes() {
                const allForms = container.querySelectorAll('.pengajuan-item');
                allForms.forEach((form, index) => {
                    form.querySelector('.pengajuan-title').textContent = 'Pengajuan ' + (index + 1);
                    const removeBtn = form.querySelector('.remove-pengajuan');
                    if (removeBtn) {
                        removeBtn.style.display = (index === 0) ? 'none' : 'block';
                    }
                    form.querySelectorAll('[name], [id], [for]').forEach(el => {
                        ['name', 'id', 'for'].forEach(attr => {
                            const value = el.getAttribute(attr);
                            if (value) {
                                const newValue = value.replace(/\[\d+\]/g, '[' + index +
                                        ']')
                                    .replace(/_\d+$/, '_' + index);
                                el.setAttribute(attr, newValue);
                            }
                        });
                    });
                });
            }
            updateAllIndexes();
        });
    </script>
@endpush
