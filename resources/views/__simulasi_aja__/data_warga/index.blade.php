@extends('layouts.layout')

@section('title')
    Data Warga
@endsection

@section('title_nav')
    Data Warga
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Warga</h4>
                        <a href="{{ route('data_warga.tambah') }}" class="btn btn-primary btn-sm float-right">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                    </div>
                    <div class="card-body">

                        {{-- Alert untuk pesan sukses atau error --}}
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        {{-- Akhir Alert --}}

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nomor KK</th>
                                        <th>Nama Kepala Keluarga</th>
                                        <th>NIK</th>
                                        <th>Blok</th>
                                        <th>Desil</th>
                                        <th>Aksi</th>
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
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $keluarga->no_kk }}</td>
                                            <td>{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}</td>
                                            <td>{{ $kepalaKeluarga?->nik_anggota ?? 'N/A' }}</td>
                                            <td>{{ $keluarga->blok?->nama_blok ?? 'N/A' }}</td>
                                            <td>{{ $keluarga->desil?->tingkat_desil ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('data_warga.edit', $keluarga->id_keluarga) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>

                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal" data-nokk="{{ $keluarga->no_kk }}"
                                                    data-kepala="{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}"
                                                    data-blok="{{ $keluarga->blok?->nama_blok ?? 'N/A' }}"
                                                    data-desil="{{ $keluarga->desil?->tingkat_desil ?? 'N/A' }}"
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
            </div>
        </div>
    </div>


    {{-- MODAL UNTUK DETAIL DATA --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> {{-- <-- DIUBAH KE 'modal-xl' (Extra Large) agar muat --}}
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
                            {{-- <-- Ukuran font dikecilkan sedikit --}}

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

                        // --- PERUBAHAN DI SINI: Menambah semua data ke 'row' ---

                        // Fungsi helper kecil untuk format tanggal YYYY-MM-DD -> DD-MM-YYYY
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
                        // --- AKHIR PERUBAHAN ---

                        anggotaTableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    // --- PERUBAHAN DI SINI: Colspan disesuaikan menjadi 11 ---
                    anggotaTableBody.innerHTML =
                        '<tr><td colspan="11" class="text-center">Tidak ada data anggota.</td></tr>';
                }
            });
        });
    </script>
@endpush
