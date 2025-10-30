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
                                    {{-- Loop data keluarga menggunakan @forelse --}}
                                    @forelse ($dataKeluarga as $key => $keluarga)
                                        {{-- 
                                          Cari anggota keluarga yang berstatus "Kepala Keluarga".
                                          'firstWhere' akan memfilter koleksi 'anggotaKeluarga' yang sudah di-load 
                                          (pastikan 'anggotaKeluarga' di-load di controller).
                                        --}}
                                        @php
                                            $kepalaKeluarga = $keluarga->anggotaKeluarga->firstWhere(
                                                'status_dalam_keluarga',
                                                'Kepala Keluarga',
                                            );
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $keluarga->no_kk }}</td>
                                            {{-- 
                                              Gunakan ?-> (nullsafe operator) untuk menghindari error 
                                              jika tidak ada Kepala Keluarga yang ditemukan.
                                            --}}
                                            <td>{{ $kepalaKeluarga?->nama_lengkap ?? 'N/A' }}</td>
                                            <td>{{ $kepalaKeluarga?->nik_anggota ?? 'N/A' }}</td>
                                            {{-- Tampilkan nama dari relasi 'blok' dan 'desil' --}}
                                            <td>{{ $keluarga->blok?->nama_blok ?? 'N/A' }}</td>
                                            <td>{{ $keluarga->desil?->tingkat_desil ?? 'N/A' }}</td>
                                            <td>
                                                {{-- Link Edit --}}
                                                <a href="{{ route('data_warga.edit', $keluarga->id_keluarga) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>

                                                {{-- Form Hapus --}}
                                                <form action="{{ route('data_warga.destroy', $keluarga->id_keluarga) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Tampilkan ini jika tidak ada data --}}
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
@endsection
