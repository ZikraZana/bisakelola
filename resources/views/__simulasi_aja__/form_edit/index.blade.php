@extends('layouts.layout')

@section('title')
    Form Edit Data Warga
@endsection

@section('title_nav')
    Form Edit Data Warga
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('data_warga.index')}}" method="GET" style="display: inline;">
                <button>Back</button>
            </form>
            <form action="{{route('data_warga.update', $data_warga->id)}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_kepala_keluarga" class="form-label">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control" id="nama_kepala_keluarga" name="nama_kepala_keluarga" value="{{$data_warga->nama_kepala_keluarga}}">
                </div>
                <div class="mb-3">
                    <label for="no_kk" class="form-label">No KK</label>
                    <input type="number" class="form-control" id="no_kk" name="no_kk" value="{{$data_warga->no_kk}}">
                </div>
                <div class="mb-3">
                    <label for="nik_kepala_keluarga" class="form-label">NIK Kepala Keluarga</label>
                    <input type="number" class="form-control" id="nik_kepala_keluarga" name="nik_kepala_keluarga" value="{{$data_warga->nik_kepala_keluarga}}">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection