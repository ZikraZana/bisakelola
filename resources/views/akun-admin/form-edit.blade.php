@extends('layouts.layout')

@section('title')
    Form Edit Data
@endsection

@section('title_nav')
    Form Edit Data
@endsection

@section('content')
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4 p-md-5">

            {{-- UBAH ACTION DAN TAMBAH @method('PUT') --}}
            <form action="{{ route('akun-admin.update', $admin->id_admin) }}" method="POST">
                @csrf
                @method('PUT')

                <h4 class="fw-bold mb-3">Data Akun Sub Admin</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        {{-- UBAH VALUE: Gunakan old() dengan data $admin --}}
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" placeholder="Masukkan Username" value="{{ old('username', $admin->username) }}"
                            required>
                        @error('username')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        {{-- UBAH: Hapus required, tambahkan placeholder --}}
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Kosongkan jika tidak ingin diubah">
                        @error('password')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                        {{-- UBAH: Hapus required --}}
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="konfirmasi_password" name="password_confirmation" placeholder="Konfirmasi password baru">
                        @error('password_confirmation')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        {{-- UBAH VALUE --}}
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap"
                            value="{{ old('nama_lengkap', $admin->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_handphone" class="form-label">Nomor Handphone</label>
                        {{-- UBAH VALUE --}}
                        <input type="text" class="form-control @error('no_handphone') is-invalid @enderror"
                            id="no_handphone" name="no_handphone" placeholder="Masukkan Nomor Handphone"
                            value="{{ old('no_handphone', $admin->no_handphone) }}"> {{-- required dihapus agar konsisten dgn controller --}}
                        @error('no_handphone')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        @php
                            $isDisabled =
                                Auth::user()->role == 'Ketua Blok' ||
                                Auth::user()->role == 'Ketua Bagian' ||
                                Auth::user()->id_admin == $admin->id_admin;
                            $isKetuaRtEditingOther =
                                Auth::user()->role == 'Ketua RT' && Auth::user()->id_admin != $admin->id_admin;
                        @endphp
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role"
                            required {{ $isDisabled ? 'disabled' : '' }}>
                            @if ($isKetuaRtEditingOther)
                                {{-- Ketua RT mengedit orang lain: tampilkan hanya 2 opsi (tanpa Ketua RT) --}}
                                <option value="Ketua Blok"
                                    {{ old('role', $admin->role) == 'Ketua Blok' ? 'selected' : '' }}>Ketua Blok</option>
                                <option value="Ketua Bagian"
                                    {{ old('role', $admin->role) == 'Ketua Bagian' ? 'selected' : '' }}>Ketua Bagian
                                </option>
                            @else
                                {{-- Default: semua opsi --}}
                                <option value="Ketua RT" {{ old('role', $admin->role) == 'Ketua RT' ? 'selected' : '' }}>
                                    Ketua RT</option>
                                <option value="Ketua Blok"
                                    {{ old('role', $admin->role) == 'Ketua Blok' ? 'selected' : '' }}>Ketua Blok</option>
                                <option value="Ketua Bagian"
                                    {{ old('role', $admin->role) == 'Ketua Bagian' ? 'selected' : '' }}>Ketua Bagian
                                </option>
                            @endif
                        </select>

                        @if ($isDisabled)
                            <input type="hidden" name="role" value="{{ $admin->role }}" />
                        @endif
                        @error('role')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    {{-- Input dinamis untuk Ketua Blok --}}
                    <div class="col-md-6" id="kolom-blok" style="display: none;">
                        <label for="blok_id" class="form-label">Pilih Blok</label>
                        <select class="form-control @error('id_blok') is-invalid @enderror" id="blok_id" name="id_blok"
                            {{ Auth::user()->role == 'Ketua Blok' || Auth::user()->role == 'Ketua Bagian' ? 'disabled' : '' }}>
                            <option value="">Pilih Blok</option>
                            @foreach ($bloks as $blok)
                                {{-- UBAH SELECTED LOGIC --}}
                                <option value="{{ $blok->id_blok }}"
                                    {{ old('id_blok', $admin->id_blok) == $blok->id_blok ? 'selected' : '' }}>
                                    {{ $blok->nama_blok }}
                                </option>
                            @endforeach
                        </select>

                        @if (Auth::user()->role == 'Ketua Blok' || Auth::user()->role == 'Ketua Bagian')
                            <input type="hidden" name="id_blok" value="{{ $admin->id_blok }}" />
                        @endif

                        @error('id_blok')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    <div class="col-md-6" id="kolom-bagian" style="display: none;">
                        <label for="bagian_id" class="form-label">Pilih Bagian</label>
                        {{-- 
                            Nama input harus 'bagian' agar sesuai dengan controller
                        --}}
                        <select class="form-control @error('bagian') is-invalid @enderror" id="bagian_id" name="bagian"
                            {{ Auth::user()->role == 'Ketua Blok' || Auth::user()->role == 'Ketua Bagian' ? 'disabled' : '' }}>
                            <option value="">Pilih Bagian</option>

                            {{-- UBAH INI: Gunakan old('bagian') --}}
                            <option value="Keuangan" {{ old('bagian', $admin->bagian) == 'Keuangan' ? 'selected' : '' }}>
                                Bagian Keuangan
                            </option>
                            <option value="SDM" {{ old('bagian', $admin->bagian) == 'SDM' ? 'selected' : '' }}>Bagian
                                SDM</option>
                            <option value="Operasional"
                                {{ old('bagian', $admin->bagian) == 'Operasional' ? 'selected' : '' }}>Bagian
                                Operasional
                            </option>
                        </select>

                        @if (Auth::user()->role == 'Ketua Blok' || Auth::user()->role == 'Ketua Bagian')
                            <input type="hidden" name="bagian" value="{{ $admin->bagian }}" />
                        @endif
                        {{-- UBAH INI: @error('bagian') --}}
                        @error('bagian')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('akun-admin.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Perbarui Akun</button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Script JavaScript dari form-tambah bisa di-copy-paste ke sini,
         tidak perlu diubah sama sekali --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const roleSelect = document.getElementById('role');
            const kolomBlok = document.getElementById('kolom-blok');
            const kolomBagian = document.getElementById('kolom-bagian');
            const selectBlok = document.getElementById('blok_id');
            const selectBagian = document.getElementById('bagian_id');

            function toggleDynamicFields() {
                const selectedRole = roleSelect.value;

                // Logika untuk Ketua Blok
                if (selectedRole === 'Ketua Blok') {
                    kolomBlok.style.display = 'block';
                    selectBlok.required = true;
                } else {
                    kolomBlok.style.display = 'none';
                    selectBlok.required = false;
                    // Hapus baris selectBlok.value = '' agar nilai tersimpan tetap ada
                }

                // Logika untuk Ketua Bagian
                if (selectedRole === 'Ketua Bagian') {
                    kolomBagian.style.display = 'block';
                    selectBagian.required = true;
                } else {
                    kolomBagian.style.display = 'none';
                    selectBagian.required = false;
                    // Hapus baris selectBagian.value = ''
                }
            }

            // Panggil fungsi ini saat halaman dimuat untuk
            // menampilkan kolom yang benar berdasarkan data $admin
            toggleDynamicFields();

            roleSelect.addEventListener('change', toggleDynamicFields);
        });
    </script>
@endpush
