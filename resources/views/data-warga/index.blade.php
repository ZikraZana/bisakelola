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
                <div class="card-body text-center py-4">
                    <p class="text-body-secondary mb-1">Total Blok Lrg. Duren</p>
                    <h3 class="fw-bold mb-0">356</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4">
                    <p class="text-body-secondary mb-1">Total Blok Lrg. Duren</p>
                    <h3 class="fw-bold mb-0">356</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4">
                    <p class="text-body-secondary mb-1">Total Blok Lrg. Duren</p>
                    <h3 class="fw-bold mb-0">356</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-2 rounded-3">
                <div class="card-body text-center py-4">
                    <p class="text-body-secondary mb-1">Total Blok Lrg. Duren</p>
                    <h3 class="fw-bold mb-0">356</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Toolbar Aksi (Cari, Filter, Tambah Data) --}}
    <div class="border border-2 p-3 rounded min-vh-50">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            {{-- Grup Tombol Kiri (Ikon) --}}
            <div class="mb-2 mb-md-0">
                <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="tooltip" title="Cari">
                    <i class="bi bi-search"></i>
                </button>
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" title="Filter">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>

            {{-- button Tambah Data --}}
            <div>
                <a href="{{ route('data-warga.formTambah') }}" class="btn btn-primary">
                    Tambah Data
                </a>
            </div>
        </div>

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
                                <td class="px-3">{{ $keluarga->no_kk }}</td>
                                <td class="px-3">{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}</td>
                                <td class="px-3">{{ $kepalaKeluarga?->nik_anggota ?? 'N/A' }}</td>
                                <td class="px-3">{{ $keluarga->blok?->nama_blok ?? 'N/A' }}</td>
                                <td class="px-3">{{ $keluarga->desil?->tingkat_desil ?? 'Tidak ada' }}</td>
                                <td class="px-3">
                                    <a href="{{ route('data-warga.formEdit', $keluarga->id_keluarga) }}"
                                        class="btn btn-warning btn-sm">Edit</a>

                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#detailModal" data-nokk="{{ $keluarga->no_kk }}"
                                        data-kepala="{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}"
                                        data-blok="{{ $keluarga->blok?->nama_blok ?? 'N/A' }}"
                                        data-desil="{{ $keluarga->desil?->tingkat_desil ?? 'Tidak ada' }}"
                                        data-anggota="{{ $keluarga->anggotaKeluarga->toJson() }}">
                                        Detail
                                    </button>
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
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
        {{-- Kiri: Info Halaman --}}
        <div class="mb-2 mb-md-0">
            <span class="me-1 text-body-secondary">Hasil per halaman</span>
            <select class="form-select form-select-sm d-inline-block w-auto">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span class="ms-2 text-body-secondary">1 sampai 10 dari 356</span>
        </div>

        {{-- Kanan: Kontrol Paginasi --}}
        <div class="d-flex align-items-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="#">10</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <span class="ms-2 text-body-secondary">dari 36</span>
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
