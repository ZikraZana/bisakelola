@extends('layouts.layout')

@section('title')
    Form Edit
@endsection

@section('title_nav')
    Form Edit
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Data Warga</h4>
                    </div>

                    {{-- Menampilkan pesan error validasi (Ringkasan) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger m-3">
                            <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger m-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- 1. Form Action dan Method sudah benar --}}
                    <form action="{{ route('data_warga.update', $dataKeluarga->id_keluarga) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="no_kk">Nomor KK</label>
                                <input type="number" class="form-control @error('no_kk') is-invalid @enderror"
                                    id="no_kk" name="no_kk" required value="{{ old('no_kk', $dataKeluarga->no_kk) }}">
                                @error('no_kk')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="blok">Blok</label>
                                <input type="text" class="form-control @error('blok') is-invalid @enderror"
                                    id="blok" name="blok" required
                                    value="{{ old('blok', $dataKeluarga->blok->nama_blok) }}">
                                @error('blok')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="desil">Desil</label>
                                <input type="number" class="form-control @error('desil') is-invalid @enderror"
                                    id="desil" name="desil" required
                                    value="{{ old('desil', $dataKeluarga->desil->tingkat_desil) }}">
                                @error('desil')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Jumlah Anggota Keluarga</label>
                                {{-- 2. Value untuk jumlah sudah benar --}}
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" name="jumlah" min="1"
                                    value="{{ old('jumlah', $dataKeluarga->anggotaKeluarga->count()) }}" required>
                                @error('jumlah')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                        </div>

                        <div class="card-body">
                            <div id="anggota-keluarga-container">

                                {{-- 
                                  INI ADALAH BLOK YANG KAMU TAMBAHKAN
                                  UNTUK MENAMPILKAN ERROR "HARUS ADA KEPALA KELUARGA"
                                --}}
                                @error('anggota_keluarga')
                                    <div class="alert alert-danger mb-3">
                                        {{ $message }}
                                    </div>
                                @enderror
                                {{-- AKHIR BLOK TAMBAHAN --}}


                                {{-- 3. Logika untuk mengisi $anggota_list sudah benar --}}
                                @php
                                    if (old('anggota_keluarga')) {
                                        // 1. Ada data 'old' (karena validasi gagal), pakai data itu.
                                        $anggota_list = old('anggota_keluarga');
                                    } else {
                                        // 2. Tidak ada data 'old' (halaman baru dimuat).
                                        // Ambil dari database dan ubah formatnya.
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
                                        <div class="card-header">
                                            <h5 class="card-title anggota-keluarga-title">Anggota Keluarga
                                                {{ $index + 1 }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">

                                                <div class="form-group col-md-4">
                                                    <label for="nik_anggota_{{ $index }}">NIK Anggota</label>
                                                    <input type="number"
                                                        class="form-control @error("anggota_keluarga.$index.nik") is-invalid @enderror"
                                                        id="nik_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][nik]"
                                                        value="{{ $anggota['nik'] ?? '' }}" required>
                                                    @error("anggota_keluarga.$index.nik")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="nama_anggota_{{ $index }}">Nama Anggota</label>
                                                    <input type="text"
                                                        class="form-control @error("anggota_keluarga.$index.nama") is-invalid @enderror"
                                                        id="nama_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][nama]"
                                                        value="{{ $anggota['nama'] ?? '' }}" required>
                                                    @error("anggota_keluarga.$index.nama")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="tempat_lahir_anggota_{{ $index }}">Tempat
                                                        Lahir</label>
                                                    <input type="text"
                                                        class="form-control @error("anggota_keluarga.$index.tempat_lahir") is-invalid @enderror"
                                                        id="tempat_lahir_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][tempat_lahir]"
                                                        value="{{ $anggota['tempat_lahir'] ?? '' }}" required>
                                                    @error("anggota_keluarga.$index.tempat_lahir")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="tanggal_lahir_anggota_{{ $index }}">Tanggal
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

                                                <div class="form-group col-md-4">
                                                    <label>Jenis Kelamin</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="anggota_keluarga[{{ $index }}][jenis_kelamin]"
                                                            id="jenis_kelamin_anggota_{{ $index }}_l"
                                                            value="Laki-laki"
                                                            {{ ($anggota['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label"
                                                            for="jenis_kelamin_anggota_{{ $index }}_l">Laki-laki</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="anggota_keluarga[{{ $index }}][jenis_kelamin]"
                                                            id="jenis_kelamin_anggota_{{ $index }}_p"
                                                            value="Perempuan"
                                                            {{ ($anggota['jenis_kelamin'] ?? '') == 'Perempuan' ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label"
                                                            for="jenis_kelamin_anggota_{{ $index }}_p">Perempuan</label>
                                                    </div>
                                                    @error("anggota_keluarga.$index.jenis_kelamin")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="agama_anggota_{{ $index }}">Agama</label>
                                                    <select
                                                        class="form-control @error("anggota_keluarga.$index.agama") is-invalid @enderror"
                                                        id="agama_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][agama]" required>
                                                        <option value="">Pilih Agama</option>
                                                        <option value="Islam"
                                                            {{ ($anggota['agama'] ?? '') == 'Islam' ? 'selected' : '' }}>
                                                            Islam
                                                        </option>
                                                        <option value="Kristen"
                                                            {{ ($anggota['agama'] ?? '') == 'Kristen' ? 'selected' : '' }}>
                                                            Kristen
                                                        </option>
                                                        <option value="Katolik"
                                                            {{ ($anggota['agama'] ?? '') == 'Katolik' ? 'selected' : '' }}>
                                                            Katolik
                                                        </option>
                                                        <option value="Hindu"
                                                            {{ ($anggota['agama'] ?? '') == 'Hindu' ? 'selected' : '' }}>
                                                            Hindu
                                                        </option>
                                                        <option value="Buddha"
                                                            {{ ($anggota['agama'] ?? '') == 'Buddha' ? 'selected' : '' }}>
                                                            Buddha
                                                        </option>
                                                        <option value="Konghuchu"
                                                            {{ ($anggota['agama'] ?? '') == 'Konghuchu' ? 'selected' : '' }}>
                                                            Konghucu
                                                        </option>
                                                    </select>
                                                    @error("anggota_keluarga.$index.agama")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="status_perkawinan_anggota_{{ $index }}">Status
                                                        Perkawinan</label>
                                                    <select
                                                        class="form-control @error("anggota_keluarga.$index.status_perkawinan") is-invalid @enderror"
                                                        id="status_perkawinan_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][status_perkawinan]"
                                                        required>
                                                        <option value="">Pilih Status Perkawinan</option>
                                                        <option value="Belum Kawin"
                                                            {{ ($anggota['status_perkawinan'] ?? '') == 'Belum Kawin' ? 'selected' : '' }}>
                                                            Belum Kawin
                                                        </option>
                                                        <option value="Kawin"
                                                            {{ ($anggota['status_perkawinan'] ?? '') == 'Kawin' ? 'selected' : '' }}>
                                                            Kawin
                                                        </option>
                                                        <option value="Cerai"
                                                            {{ ($anggota['status_perkawinan'] ?? '') == 'Cerai' ? 'selected' : '' }}>
                                                            Cerai
                                                        </option>
                                                    </select>
                                                    @error("anggota_keluarga.$index.status_perkawinan")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="status_dalam_keluarga_anggota_{{ $index }}">Status
                                                        Dalam Keluarga</label>
                                                    <select
                                                        class="form-control @error("anggota_keluarga.$index.status_dalam_keluarga") is-invalid @enderror"
                                                        id="status_dalam_keluarga_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][status_dalam_keluarga]"
                                                        required>
                                                        <option value="">Pilih Status Dalam Keluarga</option>
                                                        <option value="Kepala Keluarga"
                                                            {{ ($anggota['status_dalam_keluarga'] ?? '') == 'Kepala Keluarga' ? 'selected' : '' }}>
                                                            Kepala
                                                            Keluarga</option>
                                                        <option value="Istri"
                                                            {{ ($anggota['status_dalam_keluarga'] ?? '') == 'Istri' ? 'selected' : '' }}>
                                                            Istri
                                                        </option>
                                                        <option value="Anak"
                                                            {{ ($anggota['status_dalam_keluarga'] ?? '') == 'Anak' ? 'selected' : '' }}>
                                                            Anak
                                                        </option>
                                                    </select>
                                                    @error("anggota_keluarga.$index.status_dalam_keluarga")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="pendidikan_anggota_{{ $index }}">Pendidikan</label>
                                                    <select
                                                        class="form-control @error("anggota_keluarga.$index.pendidikan") is-invalid @enderror"
                                                        id="pendidikan_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][pendidikan]" required>
                                                        <option value="">Pilih Pendidikan</option>
                                                        <option value="Tidak Sekolah"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>
                                                            Tidak
                                                            Sekolah</option>
                                                        <option value="SD"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'SD' ? 'selected' : '' }}>
                                                            SD</option>
                                                        <option value="SMP"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'SMP' ? 'selected' : '' }}>
                                                            SMP</option>
                                                        <option value="SMA"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'SMA' ? 'selected' : '' }}>
                                                            SMA</option>
                                                        <option value="D1"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'D1' ? 'selected' : '' }}>
                                                            D1</option>
                                                        <option value="D2"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'D2' ? 'selected' : '' }}>
                                                            D2</option>
                                                        <option value="D3"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'D3' ? 'selected' : '' }}>
                                                            D3</option>
                                                        <option value="D4/S1"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'D4/S1' ? 'selected' : '' }}>
                                                            D4/S1
                                                        </option>
                                                        <option value="S2"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'S2' ? 'selected' : '' }}>
                                                            S2</option>
                                                        <option value="S3"
                                                            {{ ($anggota['pendidikan'] ?? '') == 'S3' ? 'selected' : '' }}>
                                                            S3</option>
                                                    </select>
                                                    @error("anggota_keluarga.$index.pendidikan")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="pekerjaan_anggota_{{ $index }}">Pekerjaan</label>
                                                    <select
                                                        class="form-control @error("anggota_keluarga.$index.pekerjaan") is-invalid @enderror"
                                                        id="pekerjaan_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][pekerjaan]" required>
                                                        <option value="">Pilih Pekerjaan</option>
                                                        <option value="Belum/Tidak Bekerja"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Belum/Tidak Bekerja' ? 'selected' : '' }}>
                                                            Belum/Tidak
                                                            Bekerja</option>
                                                        <option value="Pelajar/Mahasiswa"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>
                                                            Pelajar/Mahasiswa</option>
                                                        <option value="Pegawai Negeri Sipil"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Pegawai Negeri Sipil' ? 'selected' : '' }}>
                                                            Pegawai
                                                            Negeri Sipil</option>
                                                        <option value="Tentara Nasional Indonesia"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Tentara Nasional Indonesia' ? 'selected' : '' }}>
                                                            Tentara
                                                            Nasional Indonesia</option>
                                                        <option value="Kepolisian RI"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Kepolisian RI' ? 'selected' : '' }}>
                                                            Kepolisian
                                                            RI</option>
                                                        <option value="Petani/Pekebun"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Petani/Pekebun' ? 'selected' : '' }}>
                                                            Petani/Pekebun</option>
                                                        <option value="Peternak"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Peternak' ? 'selected' : '' }}>
                                                            Peternak
                                                        </option>
                                                        <option value="Nelayan"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Nelayan' ? 'selected' : '' }}>
                                                            Nelayan
                                                        </option>
                                                        <option value="Karyawan Swasta"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Karyawan Swasta' ? 'selected' : '' }}>
                                                            Karyawan
                                                            Swasta</option>
                                                        <option value="Wiraswasta"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Wiraswasta' ? 'selected' : '' }}>
                                                            Wiraswasta
                                                        </option>
                                                        <option value="Ibu Rumah Tangga"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>
                                                            Ibu Rumah
                                                            Tangga</option>
                                                        <option value="Pensiunan"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Pensiunan' ? 'selected' : '' }}>
                                                            Pensiunan
                                                        </option>
                                                        <option value="Lainnya"
                                                            {{ ($anggota['pekerjaan'] ?? '') == 'Lainnya' ? 'selected' : '' }}>
                                                            Lainnya
                                                        </option>
                                                    </select>
                                                    @error("anggota_keluarga.$index.pekerjaan")
                                                        <i class="text-danger small">{{ $message }}</i>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 text-right">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-anggota-keluarga">Hapus
                                                    Anggota</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-3" id="add-anggota-keluarga">Tambah
                                Anggota Keluarga</button>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Data</button>
                            <a href="{{ route('data_warga.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Kode JavaScript kamu sudah benar dan tidak perlu diubah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('anggota-keluarga-container');
            const addButton = document.getElementById('add-anggota-keluarga');
            const jumlahInput = document.getElementById('jumlah');

            // Inisialisasi jumlah anggota keluarga saat halaman dimuat
            updateJumlahInput();

            // --- FUNGSI UNTUK MENAMBAH ANGGOTA KELUARGA ---
            addButton.addEventListener('click', function() {
                // Ambil card pertama sebagai template
                const template = container.querySelector('.anggota-keluarga-item');
                if (!template) {
                    console.error('Template anggota keluarga tidak ditemukan!');
                    return;
                }
                const newForm = template.cloneNode(true);
                const newIndex = container.querySelectorAll('.anggota-keluarga-item').length;
                newForm.querySelector('.anggota-keluarga-title').textContent = 'Anggota Keluarga ' + (
                    newIndex + 1);

                // Reset semua nilai input di form baru
                newForm.querySelectorAll(
                    'input[type="text"], input[type="date"], input[type="number"], select').forEach(
                    input => {
                        if (input.tagName.toLowerCase() === 'select') {
                            input.selectedIndex = 0; // Reset select box
                        } else {
                            input.value = ''; // Reset input text, date, dll.
                        }
                        // Hapus class error validasi jika ada
                        input.classList.remove('is-invalid');
                    });
                newForm.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.checked = false; // Uncheck radio buttons
                });

                // Hapus pesan error validasi yang ikut ter-clone
                newForm.querySelectorAll('.text-danger.small').forEach(err => err.remove());


                // Perbarui atribut 'name', 'id', dan 'for' untuk setiap elemen form
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
                        alert('Minimal harus ada satu anggota keluarga.');
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

                if (desiredCount > currentCount) {
                    for (let i = 0; i < desiredCount - currentCount; i++) {
                        addButton.click();
                    }
                } else if (desiredCount < currentCount && desiredCount > 0) {
                    for (let i = 0; i < currentCount - desiredCount; i++) {
                        container.querySelector(
                            '.anggota-keluarga-item:last-child .remove-anggota-keluarga').click();
                    }
                } else if (desiredCount <= 0) {
                    this.value = 1; // Minimal 1
                }
            });
        });
    </script>
@endpush
