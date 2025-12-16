@extends('layouts.layout')

@section('title', 'Pilih Warga (Realtime)')
@section('title_nav', 'Pengajuan Bansos')

@section('content')
    {{-- Inisialisasi Alpine Data --}}
    <div x-data="bansosApp({{ Js::from($dataWarga) }})" class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-4">

            {{-- Header & Search Bar --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Daftar Warga Potensial</h4>
                    <p class="text-muted mb-0">Cari warga secara realtime untuk diajukan.</p>
                </div>

                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                        {{-- INPUT PENCARIAN ALPINE --}}
                        <input type="text" class="form-control" placeholder="Ketik No. KK atau Nama..."
                            x-model="searchQuery">
                    </div>
                </div>
            </div>

            {{-- Pesan Error/Sukses Session (Blade) --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th class="py-3 px-3">No. KK</th>
                            <th class="py-3 px-3">Kepala Keluarga</th>
                            <th class="py-3 px-3">Blok / Lokasi</th>
                            <th class="py-3 px-3 text-center">Desil</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOPING ALPINE JS --}}
                        <template x-for="warga in filteredWarga" :key="warga.no_kk">
                            <tr>
                                <td class="px-3 fw-bold font-monospace" x-text="warga.no_kk"></td>
                                <td class="px-3">
                                    <div class="fw-bold" x-text="warga.nama_kepala"></div>
                                    <small class="text-muted">NIK: <span x-text="warga.nik_kepala"></span></small>
                                </td>
                                <td class="px-3" x-text="warga.blok"></td>
                                <td class="px-3 text-center">
                                    <template x-if="warga.desil">
                                        <span class="badge bg-warning text-dark" x-text="'Desil ' + warga.desil"></span>
                                    </template>
                                    <template x-if="!warga.desil">
                                        <span class="badge bg-light text-secondary border">Non-Desil</span>
                                    </template>
                                </td>
                                <td class="px-3 text-center">
                                    {{-- Tombol Aksi Alpine --}}
                                    <button type="button" class="btn btn-sm btn-primary" @click="openModal(warga)">
                                        <i class="bi bi-plus-circle me-1"></i> Ajukan
                                    </button>
                                </td>
                            </tr>
                        </template>

                        {{-- Jika Hasil Pencarian Kosong --}}
                        <tr x-show="filteredWarga.length === 0">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 d-block mb-2 opacity-50"></i>
                                Tidak ada data yang cocok dengan pencarian "<span x-text="searchQuery"></span>".
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <small class="text-muted">Menampilkan <span x-text="filteredWarga.length"></span> dari <span
                        x-text="allWarga.length"></span> data.</small>
            </div>
        </div>
    </div>

    {{-- MODAL FORM PENGAJUAN (Di-trigger via Alpine) --}}
    <div class="modal fade" id="modalAjukan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('data-penerima-bansos.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Formulir Pengajuan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Info Warga Terpilih (Diisi Alpine) --}}
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                            <div>
                                Mengajukan Keluarga:<br>
                                <strong id="modal-nama-display"></strong>
                                (<span id="modal-nokk-display" class="font-monospace"></span>)
                            </div>
                        </div>

                        {{-- Input Hidden No KK --}}
                        <input type="hidden" name="pengajuan[0][no_kk]" id="modal-nokk-input">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan / Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="pengajuan[0][keterangan_pengajuan]" rows="4" required
                                placeholder="Contoh: Kondisi ekonomi menurun..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bansosApp', (initialData) => ({
                searchQuery: '',
                allWarga: initialData,

                // Logika Filter (Getter)
                get filteredWarga() {
                    if (this.searchQuery === '') {
                        return this.allWarga;
                    }
                    const lowerSearch = this.searchQuery.toLowerCase();
                    return this.allWarga.filter(item => {
                        return item.searchable.includes(lowerSearch);
                    });
                },

                // Fungsi Buka Modal
                openModal(warga) {
                    // Isi tampilan modal menggunakan Vanilla JS DOM manipulation (agar aman dengan form submit)
                    document.getElementById('modal-nama-display').textContent = warga.nama_kepala;
                    document.getElementById('modal-nokk-display').textContent = warga.no_kk;
                    document.getElementById('modal-nokk-input').value = warga.no_kk;

                    // Tampilkan Modal Bootstrap
                    var myModal = new bootstrap.Modal(document.getElementById('modalAjukan'));
                    myModal.show();
                }
            }));
        });
    </script>
@endpush
