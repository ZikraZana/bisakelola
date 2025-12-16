@extends('layouts.layout')

@section('title')
    Form Tambah Data
@endsection

@section('title_nav')
    Form Tambah Data
@endsection

@section('content')
    {{-- Menggunakan card untuk membungkus form agar memiliki background putih dan padding --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4 p-md-5">

            <form action="{{ route('akun-admin.store') }}" method="POST">
                @csrf

                <h4 class="fw-bold mb-3">Data Akun Sub Admin</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" placeholder="Masukkan Username" value="{{ old('username') }}" required>
                        @error('username')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Masukkan Password" required>
                        @error('password')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                        {{-- UBAH INI: name="password_confirmation" dan @error('password_confirmation') --}}
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="konfirmasi_password" name="password_confirmation" placeholder="Konfirmasi Password"
                            required>
                        @error('password_confirmation')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap"
                            value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_handphone" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control @error('no_handphone') is-invalid @enderror"
                            id="no_handphone" name="no_handphone" placeholder="Masukkan Nomor Handphone"
                            value="{{ old('no_handphone') }}" required> {{-- Anda bisa hapus 'required' jika memang 'nullable' --}}
                        @error('no_handphone')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role"
                            required>
                            <option value="">Pilih Role</option> {{-- Default option --}}
                            <option value="Wakil Ketua RT" {{ old('role') == 'Wakil Ketua RT' ? 'selected' : '' }}>Wakil Ketua RT
                            </option>
                            <option value="Sekretaris RT" {{ old('role') == 'Sekretaris RT' ? 'selected' : '' }}>Sekretaris RT
                            </option>
                            <option value="Bendahara RT" {{ old('role') == 'Bendahara RT' ? 'selected' : '' }}>Bendahara RT
                            </option>
                            <option value="Ketua Blok" {{ old('role') == 'Ketua Blok' ? 'selected' : '' }}>Ketua Blok
                            </option>
                            <option value="Ketua Bagian" {{ old('role') == 'Ketua Bagian' ? 'selected' : '' }}>Ketua Bagian
                            </option>
                        </select>
                        @error('role')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>

                    {{-- AWAL TAMBAHAN: Input dinamis untuk Ketua Blok --}}
                    <div class="col-md-6" id="kolom-blok" style="display: none;">
                        <label for="blok_id" class="form-label">Pilih Blok</label>
                        {{-- 
                            Nama input harus 'blok' agar sesuai dengan controller
                        --}}
                        <select class="form-control @error('blok') is-invalid @enderror" id="blok_id" name="id_blok">
                            <option value="">Pilih Blok</option>

                            {{-- Loop data dari controller --}}
                            @foreach ($bloks as $blok)
                                {{-- Value-nya adalah ID, Teksnya adalah NAMA --}}
                                <option value="{{ $blok->id_blok }}"
                                    {{ old('id_blok') == $blok->id_blok ? 'selected' : '' }}>
                                    {{ $blok->nama_blok }}
                                </option>
                            @endforeach

                        </select>
                        {{-- Pastikan error message juga menggunakan 'id_blok' --}}
                        @error('id_blok')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    {{-- AKHIR TAMBAHAN --}}


                    {{-- AWAL TAMBAHAN: Input dinamis untuk Ketua Bagian --}}
                    <div class="col-md-6" id="kolom-bagian" style="display: none;">
                        <label for="bagian_id" class="form-label">Pilih Bagian</label>
                        {{-- 
                            Nama input harus 'bagian' agar sesuai dengan controller
                        --}}
                        <select class="form-control @error('bagian') is-invalid @enderror" id="bagian_id" name="bagian">
                            <option value="">Pilih Bagian</option>

                            {{-- UBAH INI: Gunakan old('bagian') --}}
                            
                            <option value="Keamanan & Ketertiban" {{ old('bagian') == 'Keamanan & Ketertiban' ? 'selected' : '' }}>Bagian Keamanan & Ketertiban</option>
                            <option value="Pemberdayaan Masyarakat" {{ old('bagian') == 'Pemberdayaan Masyarakat' ? 'selected' : '' }}>Bagian Pemberdayaan Masyarakat</option>
                            <option value="Pembangunan" {{ old('bagian') == 'Pembangunan' ? 'selected' : '' }}>Bagian Pembangunan</option>
                            <option value="Keagamaan" {{ old('bagian') == 'Keagamaan' ? 'selected' : '' }}>Bagian Keagamaan</option>
                            <option value="Humas, Sosial, Olahraga" {{ old('bagian') == 'Humas, Sosial, Olahraga' ? 'selected' : '' }}>Bagian Humas, Sosial, Olahraga</option>
                            <option value="Lembaga Adat Masyarakat" {{ old('bagian') == 'Lembaga Adat Masyarakat' ? 'selected' : '' }}>Bagian Lembaga Adat Masyarakat</option>
                            <option value="Kebersihan" {{ old('bagian') == 'Kebersihan' ? 'selected' : '' }}>Bagian Kebersihan</option>
                            <option value="Perlengkapan" {{ old('bagian') == 'Perlengkapan' ? 'selected' : '' }}>Bagian Perlengkapan</option>

                        </select>
                        {{-- UBAH INI: @error('bagian') --}}
                        @error('bagian')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    {{-- AKHIR TAMBAHAN --}}

                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('akun-admin.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Tambah Akun</button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Script JavaScript Anda sudah benar dan tidak perlu diubah --}}
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
                    selectBlok.value = '';
                }

                // Logika untuk Ketua Bagian
                if (selectedRole === 'Ketua Bagian') {
                    kolomBagian.style.display = 'block';
                    selectBagian.required = true;
                } else {
                    kolomBagian.style.display = 'none';
                    selectBagian.required = false;
                    selectBagian.value = '';
                }
            }
            roleSelect.addEventListener('change', toggleDynamicFields);
            toggleDynamicFields();
        });
    </script>
@endpush
