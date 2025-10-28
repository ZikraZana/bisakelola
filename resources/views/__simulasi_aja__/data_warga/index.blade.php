@extends('layouts.layout')

@section('title')
    Data Warga
@endsection

@section('title_nav')
    Data Warga
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <button>
                    <a href="{{route('form_tambah.index')}}">Tambah Data Warga</a>
                </button>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>No KK</th>
                            <th>NIK Kepala Keluarga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_warga as $warga)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $warga->nama_kepala_keluarga }}</td>
                            <td>{{ $warga->no_kk }}</td>
                            <td>{{ $warga->nik_kepala_keluarga }}</td>
                            <td>
                                <form action="{{route('data_warga.edit', $warga->id)}}" method="POST" style="display: inline;">
                                    @csrf
                                    <button>Edit</button>
                                </form>
                                <button>Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection