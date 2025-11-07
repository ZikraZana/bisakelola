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
            <form action="{{ route('data-warga.update', $dataKeluarga->id_keluarga) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif


                <h4 class="fw-bold mb-3">Data Keluarga</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="no_kk" class="form-label">Nomor Kartu Keluarga</label>
                        <input type="number" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                            name="no_kk" placeholder="Masukkan Nomor Kartu Keluarga"
                            value="{{ old('no_kk', $dataKeluarga->no_kk) }}" required>
                        @error('no_kk')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="blok" class="form-label">Blok Perumahan</label>
                        <select class="form-select @error('blok') is-invalid @enderror" id="blok" name="blok"
                            required>
                            <option value="">Pilih Blok Perumahan</option>
                            <option value="Lrg. Duren" @if (old('blok', $dataKeluarga->blok->nama_blok) == 'Lrg. Duren') selected @endif>Lrg. Duren</option>
                            <option value="Makakau" @if (old('blok', $dataKeluarga->blok->nama_blok) == 'Makakau') selected @endif>Makakau</option>
                            <option value="Matahari" @if (old('blok', $dataKeluarga->blok->nama_blok) == 'Matahari') selected @endif>Matahari</option>
                            <option value="Lrg. Gardu" @if (old('blok', $dataKeluarga->blok->nama_blok) == 'Lrg. Gardu') selected @endif>Lrg. Gardu</option>
                        </select>
                        @error('blok')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jumlah" class="form-label">Jumlah Anggota Keluarga</label>
                        {{-- Menggunakan logika dari controller/dummy Anda --}}
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                            name="jumlah" min="1"
                            value="{{ old('jumlah', $dataKeluarga->anggotaKeluarga->count()) }}" required>
                        @error('jumlah')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="desil" class="form-label">Status Desil</label>
                        <select class="form-select @error('desil') is-invalid @enderror" id="desil" name="desil"
                            required>
                            <option value="">Pilih Status Desil</option>
                            {{-- Menggunakan logika dari controller/dummy Anda --}}
                            <option value="1" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == '1') selected @endif>Desil 1</option>
                            <option value="2" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == '2') selected @endif>Desil 2</option>
                            <option value="3" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == '3') selected @endif>Desil 3</option>
                            <option value="4" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == '4') selected @endif>Desil 4</option>
                            <option value="5" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == '5') selected @endif>Desil 5</option>
                            <option value="6" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == '6') selected @endif>Desil 6</option>
                            <option value="" @if (old('desil', $dataKeluarga->desil->tingkat_desil) == null) selected @endif>Tidak ada desil</option>
                        </select>
                        @error('desil')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="fw-bold mb-3">Data Individu</h4>

                @error('anggota_keluarga')
                    <div class="alert alert-danger mb-3">
                        {{ $message }}
                    </div>
                @enderror

                <div id="anggota-keluarga-container">
                    @php
                        $anggota_list = []; 

                        if (old('anggota_keluarga')) {
                            $anggota_list = old('anggota_keluarga');
                        } elseif ($dataKeluarga && $dataKeluarga->anggotaKeluarga) {
                            $anggota_list = $dataKeluarga->anggotaKeluarga
                                ->map(function ($anggota) {
                                    return [
                                        'nik' => $anggota->nik_anggota,
                                        'nama' => $anggota->nama_lengkap,
                                        'tempat_lahir' => $anggota->tempat_lahir,
                                        'tanggal_lahir' => $anggota->tanggal_lahir,
                                        'jenis_kelamin' => $anggota->jenis_kelamin,
                                        'agama' => $anggota->agama,
                                        'status_perkawinan' => $anggota->status_perkawinan,
                                        'status_dalam_keluarga' => $anggota->status_dalam_keluarga,
                                        'pendidikan' => $anggota->pendidikan,
                                        'pekerjaan' => $anggota->pekerjaan,
                                    ];
                                })
                                ->all();
                        }
                    @endphp

                    @foreach ($anggota_list as $index => $anggota)
                        <div class="card mb-3 anggota-keluarga-item">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title anggota-keluarga-title mb-0">Anggota Keluarga {{ $index + 1 }}
                                </h5>
                                @if ($index > 0)
                                    <button type="button"
                                        class="btn btn-danger btn-sm remove-anggota-keluarga">Hapus</button>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    {{-- NIK --}}
                                    <div class="col-md-6">
                                        <label for="nik_anggota_{{ $index }}" class="form-label">NIK</label>
                                        <input type="number"
                                            class="form-control @error("anggota_keluarga.$index.nik") is-invalid @enderror"
                                            id="nik_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][nik]" placeholder="Masukkan NIK"
                                            value="{{ $anggota['nik'] ?? '' }}" required>
                                        @error("anggota_keluarga.$index.nik")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Agama --}}
                                    <div class="col-md-6">
                                        <label for="agama_anggota_{{ $index }}" class="form-label">Agama</label>
                                        <select
                                            class="form-select @error("anggota_keluarga.$index.agama") is-invalid @enderror"
                                            id="agama_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][agama]" required>
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam"
                                                {{ ($anggota['agama'] ?? '') == 'Islam' ? 'selected' : '' }}>Islam
                                            </option>
                                            <option value="Kristen"
                                                {{ ($anggota['agama'] ?? '') == 'Kristen' ? 'selected' : '' }}>
                                                Kristen</option>
                                            <option value="Katolik"
                                                {{ ($anggota['agama'] ?? '') == 'Katolik' ? 'selected' : '' }}>
                                                Katolik</option>
                                            <option value="Hindu"
                                                {{ ($anggota['agama'] ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu
                                            </option>
                                            <option value="Buddha"
                                                {{ ($anggota['agama'] ?? '') == 'Buddha' ? 'selected' : '' }}>
                                                Buddha</option>
                                            <option value="Konghuchu"
                                                {{ ($anggota['agama'] ?? '') == 'Konghuchu' ? 'selected' : '' }}>
                                                Konghucu</option>
                                        </select>
                                        @error("anggota_keluarga.$index.agama")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Nama Lengkap --}}
                                    <div class="col-md-6">
                                        <label for="nama_anggota_{{ $index }}" class="form-label">Nama
                                            Lengkap</label>
                                        <input type="text"
                                            class="form-control @error("anggota_keluarga.$index.nama") is-invalid @enderror"
                                            id="nama_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][nama]"
                                            placeholder="Masukkan Nama Lengkap" value="{{ $anggota['nama'] ?? '' }}"
                                            required>
                                        @error("anggota_keluarga.$index.nama")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Pendidikan Terakhir --}}
                                    <div class="col-md-6">
                                        <label for="pendidikan_anggota_{{ $index }}" class="form-label">Pendidikan
                                            Terakhir</label>
                                        <select
                                            class="form-select @error("anggota_keluarga.$index.pendidikan") is-invalid @enderror"
                                            id="pendidikan_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][pendidikan]" required>
                                            <option value="">Pilih Pendidikan</option>
                                            <option value="Tidak Sekolah"
                                                {{ ($anggota['pendidikan'] ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>
                                                Tidak Sekolah</option>
                                            <option value="SD"
                                                {{ ($anggota['pendidikan'] ?? '') == 'SD' ? 'selected' : '' }}>SD
                                            </option>
                                            <option value="SMP"
                                                {{ ($anggota['pendidikan'] ?? '') == 'SMP' ? 'selected' : '' }}>SMP
                                            </option>
                                            <option value="SMA"
                                                {{ ($anggota['pendidikan'] ?? '') == 'SMA' ? 'selected' : '' }}>SMA
                                            </option>
                                            <option value="D1"
                                                {{ ($anggota['pendidikan'] ?? '') == 'D1' ? 'selected' : '' }}>D1
                                            </option>
                                            <option value="D2"
                                                {{ ($anggota['pendidikan'] ?? '') == 'D2' ? 'selected' : '' }}>D2
                                            </option>
                                            <option value="D3"
                                                {{ ($anggota['pendidikan'] ?? '') == 'D3' ? 'selected' : '' }}>D3
                                            </option>
                                            <option value="D4/S1"
                                                {{ ($anggota['pendidikan'] ?? '') == 'D4/S1' ? 'selected' : '' }}>
                                                D4/S1</option>
                                            <option value="S2"
                                                {{ ($anggota['pendidikan'] ?? '') == 'S2' ? 'selected' : '' }}>S2
                                            </option>
                                            <option value="S3"
                                                {{ ($anggota['pendidikan'] ?? '') == 'S3' ? 'selected' : '' }}>S3
                                            </option>
                                        </select>
                                        @error("anggota_keluarga.$index.pendidikan")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div class="col-md-6">
                                        <label for="jenis_kelamin_anggota_{{ $index }}" class="form-label">Jenis
                                            Kelamin</label>
                                        <select
                                            class="form-select @error("anggota_keluarga.$index.jenis_kelamin") is-invalid @enderror"
                                            id="jenis_kelamin_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][jenis_kelamin]" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki"
                                                {{ ($anggota['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="Perempuan"
                                                {{ ($anggota['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                        @error("anggota_keluarga.$index.jenis_kelamin")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Pekerjaan --}}
                                    <div class="col-md-6">
                                        <label for="pekerjaan_anggota_{{ $index }}"
                                            class="form-label">Pekerjaan</label>
                                        <select
                                            class="form-select @error("anggota_keluarga.$index.pekerjaan") is-invalid @enderror"
                                            id="pekerjaan_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][pekerjaan]" required>
                                            <option value="">Pilih Pekerjaan</option>
                                            <option value="Belum/Tidak Bekerja"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Belum/Tidak Bekerja' ? 'selected' : '' }}>
                                                Belum/Tidak Bekerja
                                            </option>
                                            <option value="Pelajar/Mahasiswa"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>
                                                Pelajar/Mahasiswa
                                            </option>
                                            <option value="Pegawai Negeri Sipil"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Pegawai Negeri Sipil' ? 'selected' : '' }}>
                                                Pegawai Negeri Sipil
                                            </option>
                                            <option value="Tentara Nasional Indonesia"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Tentara Nasional Indonesia' ? 'selected' : '' }}>
                                                Tentara Nasional
                                                Indonesia</option>
                                            <option value="Kepolisian RI"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Kepolisian RI' ? 'selected' : '' }}>
                                                Kepolisian RI</option>
                                            <option value="Petani/Pekebun"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Petani/Pekebun' ? 'selected' : '' }}>
                                                Petani/Pekebun</option>
                                            <option value="Peternak"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Peternak' ? 'selected' : '' }}>
                                                Peternak</option>
                                            <option value="Nelayan"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Nelayan' ? 'selected' : '' }}>
                                                Nelayan</option>
                                            <option value="Karyawan Swasta"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Karyawan Swasta' ? 'selected' : '' }}>
                                                Karyawan Swasta</option>
                                            <option value="Wiraswasta"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Wiraswasta' ? 'selected' : '' }}>
                                                Wiraswasta</option>
                                            <option value="Ibu Rumah Tangga"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>
                                                Ibu Rumah Tangga
                                            </option>
                                            <option value="Pensiunan"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Pensiunan' ? 'selected' : '' }}>
                                                Pensiunan</option>
                                            <option value="Lainnya"
                                                {{ ($anggota['pekerjaan'] ?? '') == 'Lainnya' ? 'selected' : '' }}>
                                                Lainnya</option>
                                        </select>
                                        @error("anggota_keluarga.$index.pekerjaan")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Tempat Lahir --}}
                                    <div class="col-md-6">
                                        <label for="tempat_lahir_anggota_{{ $index }}" class="form-label">Tempat
                                            Lahir</label>
                                        <input type="text"
                                            class="form-control @error("anggota_keluarga.$index.tempat_lahir") is-invalid @enderror"
                                            id="tempat_lahir_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][tempat_lahir]"
                                            placeholder="Masukkan Tempat Lahir"
                                            value="{{ $anggota['tempat_lahir'] ?? '' }}" required>
                                        @error("anggota_keluarga.$index.tempat_lahir")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Status dalam Keluarga --}}
                                    <div class="col-md-6">
                                        <label for="status_dalam_keluarga_anggota_{{ $index }}"
                                            class="form-label">Status dalam Keluarga</label>
                                        <select
                                            class="form-select @error("anggota_keluarga.$index.status_dalam_keluarga") is-invalid @enderror"
                                            id="status_dalam_keluarga_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][status_dalam_keluarga]" required>
                                            <option value="">Pilih Status Dalam Keluarga</option>
                                            <option value="Kepala Keluarga"
                                                {{ ($anggota['status_dalam_keluarga'] ?? '') == 'Kepala Keluarga' ? 'selected' : '' }}>
                                                Kepala Keluarga</option>
                                            <option value="Istri"
                                                {{ ($anggota['status_dalam_keluarga'] ?? '') == 'Istri' ? 'selected' : '' }}>
                                                Istri</option>
                                            <option value="Anak"
                                                {{ ($anggota['status_dalam_keluarga'] ?? '') == 'Anak' ? 'selected' : '' }}>
                                                Anak
                                            </option>
                                        </select>
                                        @error("anggota_keluarga.$index.status_dalam_keluarga")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div class="col-md-6">
                                        <label for="tanggal_lahir_anggota_{{ $index }}" class="form-label">Tanggal
                                            Lahir</label>
                                        <input type="date"
                                            class="form-control @error("anggota_keluarga.$index.tanggal_lahir") is-invalid @enderror"
                                            id="tanggal_lahir_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][tanggal_lahir]"
                                            value="{{ $anggota['tanggal_lahir'] ?? '' }}" required>
                                        @error("anggota_keluarga.$index.tanggal_lahir")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>

                                    {{-- Status Perkawinan --}}
                                    <div class="col-md-6">
                                        <label for="status_perkawinan_anggota_{{ $index }}"
                                            class="form-label">Status Perkawinan</label>
                                        <select
                                            class="form-select @error("anggota_keluarga.$index.status_perkawinan") is-invalid @enderror"
                                            id="status_perkawinan_anggota_{{ $index }}"
                                            name="anggota_keluarga[{{ $index }}][status_perkawinan]" required>
                                            <option value="">Pilih Status Perkawinan</option>
                                            <option value="Belum Kawin"
                                                {{ ($anggota['status_perkawinan'] ?? '') == 'Belum Kawin' ? 'selected' : '' }}>
                                                Belum Kawin</option>
                                            <option value="Kawin"
                                                {{ ($anggota['status_perkawinan'] ?? '') == 'Kawin' ? 'selected' : '' }}>
                                                Kawin</option>
                                            <option value="Cerai"
                                                {{ ($anggota['status_perkawinan'] ?? '') == 'Cerai' ? 'selected' : '' }}>
                                                Cerai</option>
                                        </select>
                                        @error("anggota_keluarga.$index.status_perkawinan")
                                            <i class="text-danger small">{{ $message }}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-success btn-sm mt-3" id="add-anggota-keluarga">Tambah Anggota
                    Keluarga</button>


                <div class="d-flex justify-content-end mt-4">
                    {{-- FIX 3: Menggunakan route 'index' yang konsisten --}}
                    <a href="{{ route('data-warga.index') }}" class="btn btn-outline-secondary me-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- JavaScript Anda sudah benar dan tidak perlu diubah. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('anggota-keluarga-container');
            const addButton = document.getElementById('add-anggota-keluarga');
            const jumlahInput = document.getElementById('jumlah');

            // Inisialisasi jumlah anggota keluarga saat halaman dimuat
            updateJumlahInput();

            // --- FUNGSI UNTUK MENAMBAH ANGGOTA KELUARGA ---
            addButton.addEventListener('click', function() {
                const template = container.querySelector('.anggota-keluarga-item');
                if (!template) {
                    console.error('Template anggota keluarga tidak ditemukan!');
                    return;
                }
                const newForm = template.cloneNode(true);
                const newIndex = container.querySelectorAll('.anggota-keluarga-item').length;
                newForm.querySelector('.anggota-keluarga-title').textContent = 'Anggota Keluarga ' + (
                    newIndex + 1);

                newForm.querySelectorAll(
                    'input[type="text"], input[type="date"], input[type="number"], select').forEach(
                    input => {
                        if (input.tagName.toLowerCase() === 'select') {
                            input.selectedIndex = 0;
                        } else {
                            input.value = '';
                        }
                        input.classList.remove('is-invalid');
                    });

                newForm.querySelectorAll('.text-danger.small').forEach(err => err.remove());

                if (newIndex > 0 && !newForm.querySelector('.remove-anggota-keluarga')) {
                    const header = newForm.querySelector('.card-header');
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'btn btn-danger btn-sm remove-anggota-keluarga';
                    removeButton.textContent = 'Hapus';
                    header.appendChild(removeButton);
                }

                newForm.querySelectorAll('[name], [id], [for]').forEach(el => {
                    ['name', 'id', 'for'].forEach(attr => {
                        const value = el.getAttribute(attr);
                        if (value) {
                            const newValue = value.replace(/\[\d+\]/g, '[' + newIndex + ']')
                                .replace(/_\d+$/, '_' + newIndex);
                            el.setAttribute(attr, newValue);
                        }
                    });
                });
                container.appendChild(newForm);
                updateJumlahInput();
            });

            // --- FUNGSI UNTUK MENGHAPUS ANGGOTA KELUARGA ---
            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-anggota-keluarga')) {
                    if (container.querySelectorAll('.anggota-keluarga-item').length <= 1) {
                        console.warn('Minimal harus ada satu anggota keluarga.');
                        return;
                    }
                    const cardToRemove = e.target.closest('.anggota-keluarga-item');
                    if (cardToRemove) {
                        cardToRemove.remove();
                        updateAllIndexes();
                        updateJumlahInput();
                    }
                }
            });

            // --- FUNGSI UNTUK MEMPERBARUI SEMUA INDEX SETELAH PENGHAPUSAN ---
            function updateAllIndexes() {
                const allForms = container.querySelectorAll('.anggota-keluarga-item');
                allForms.forEach((form, index) => {
                    form.querySelector('.anggota-keluarga-title').textContent = 'Anggota Keluarga ' + (
                        index + 1);

                    const removeBtn = form.querySelector('.remove-anggota-keluarga');
                    if (removeBtn) {
                        if (index === 0) {
                            removeBtn.style.display = 'none';
                        } else {
                            removeBtn.style.display = 'block';
                        }
                    }

                    form.querySelectorAll('[name], [id], [for]').forEach(el => {
                        ['name', 'id', 'for'].forEach(attr => {
                            const value = el.getAttribute(attr);
                            if (value) {
                                const newValue = value.replace(/\[\d+\]/g, '[' + index +
                                    ']').replace(/_\d+$/, '_' + index);
                                el.setAttribute(attr, newValue);
                            }
                        });
                    });
                });
            }

            // --- FUNGSI UNTUK SINKRONISASI INPUT JUMLAH ANGGOTA ---
            function updateJumlahInput() {
                const count = container.querySelectorAll('.anggota-keluarga-item').length;
                jumlahInput.value = count;
            }

            // --- (OPSIONAL) SINKRONISASI JIKA USER MENGUBAH MANUAL INPUT JUMLAH ---
            jumlahInput.addEventListener('change', function() {
                const desiredCount = parseInt(this.value, 10);
                const currentCount = container.querySelectorAll('.anggota-keluarga-item').length;

                if (isNaN(desiredCount) || desiredCount <= 0) {
                    this.value = currentCount;
                    return;
                }

                if (desiredCount > currentCount) {
                    for (let i = 0; i < desiredCount - currentCount; i++) {
                        addButton.click();
                    }
                } else if (desiredCount < currentCount) {
                    for (let i = 0; i < currentCount - desiredCount; i++) {
                        const lastItem = container.querySelector(
                            '.anggota-keluarga-item:last-child');
                        if (lastItem && container.querySelectorAll('.anggota-keluarga-item')
                            .length > 1) {
                            lastItem.remove();
                        }
                    }
                    updateAllIndexes();
                }
            });

            // Panggil updateAllIndexes saat load untuk memastikan tombol hapus pertama disembunyikan
            updateAllIndexes();
        });
    </script>
@endpush
