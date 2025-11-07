@extends('layouts.layout')

@section('title')
    Tambah Akun Admin
@endsection

@section('title_nav')
    Tambah Akun Admin
@endsection

@section('content')
    
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Akun Sub Admin</h4>
                    <a href="{{ route('akun-admin.formTambah') }}" class="btn btn-primary float-right">Tambah Akun Sub Admin</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Nomor Handphone</th>
                                    <th>Role</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mono</td>
                                        <td>0823728883</td>
                                        <td>Wibu</td>
                                        <td class="text-right">
                                            <a href="" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
