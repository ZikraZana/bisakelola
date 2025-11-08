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
                        @if (Auth::user()->role === 'Ketua RT')
                            <a href="{{ route('akun-admin.formTambah') }}" class="btn btn-primary float-right">Tambah Akun Sub
                                Admin</a>
                        @endif
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
                                        <th>Blok</th>
                                        <th>Bagian</th>
                                        <th class="text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $admin->username }}</td>
                                            <td>{{ $admin->nama_lengkap }}</td>
                                            <td>{{ $admin->no_handphone }}</td>
                                            <td>{{ $admin->role }}</td>
                                            <td>{{ $admin->blok ? $admin->blok->nama_blok : '-' }}</td>
                                            <td>{{ $admin->bagian == null ? '-' : $admin->bagian }}</td>
                                            <td class="text-right">
                                                @if (Auth::user()->role === 'Ketua RT' || Auth::user()->id_admin == $admin->id_admin)
                                                    <a href="{{ route('akun-admin.formEdit', $admin->id_admin) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                @endif
                                                @if (Auth::user()->role === 'Ketua RT')
                                                    @if (Auth::user()->id_admin !== $admin->id)
                                                        <form action="{{ route('akun-admin.destroy', $admin->id_admin) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
