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

            <form action="{{ route('data-warga.store') }}" method="POST" enctype="multipart/form-data"> {{-- Sesuaikan dengan route Anda --}}
                @csrf

                {{-- TAMBAHAN: Menampilkan ringkasan error dari file dummy --}}
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
                {{-- AKHIR TAMBAHAN --}}


                {{-- Bagian Data Keluarga (Disesuaikan dengan name & id dari dummy) --}}
                <h4 class="fw-bold mb-3">Data Keluarga</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="no_kk" class="form-label">Nomor Kartu Keluarga</label>
                        <input type="number" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                            name="no_kk" placeholder="Masukkan Nomor Kartu Keluarga" value="{{ old('no_kk') }}" required>
                        @error('no_kk')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="blok" class="form-label">Blok Perumahan</label>
                        <select class="form-select @error('blok') is-invalid @enderror" id="blok" name="blok"
                            required @if (Auth::user()->role === 'Ketua Blok') disabled @endif>
                            @if (Auth::user()->role === 'Ketua Blok')
                                <option value="{{ Auth::user()->blok->nama_blok }}" selected>
                                    {{ Auth::user()->blok->nama_blok }}
                                </option>
                            @else
                                <option value="">Pilih Blok Perumahan</option>
                                <option value="Lrg. Duren" @if (old('blok') == 'Lrg. Duren') selected @endif>Lrg. Duren
                                </option>
                                <option value="Makakau" @if (old('blok') == 'Makakau') selected @endif>Makakau</option>
                                <option value="Matahari" @if (old('blok') == 'Matahari') selected @endif>Matahari</option>
                                <option value="Lrg. Gardu" @if (old('blok') == 'Lrg. Gardu') selected @endif>Lrg. Gardu
                                </option>
                            @endif
                        </select>
                        @error('blok')
                            <i class="text-danger small">{{ $message }}</i>
                        @enderror
                        @if (Auth::user()->role === 'Ketua Blok')
                            <input type="hidden" name="blok" value="{{ Auth::user()->blok->nama_blok }}">
                        @enderror
                </div>
                <div class="col-md-6">
                    <label for="jumlah" class="form-label">Jumlah Anggota Keluarga</label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                        name="jumlah" min="1" value="{{ old('jumlah', 1) }}" required>
                    @error('jumlah')
                        <i class="text-danger small">{{ $message }}</i>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="desil" class="form-label">Status Desil</label>
                    <select class="form-select @error('desil') is-invalid @enderror" id="desil" name="desil">
                        <option value="">Pilih Status Desil</option>
                        <option value="1" @if (old('desil') == '1') selected @endif>Desil 1</option>
                        <option value="2" @if (old('desil') == '2') selected @endif>Desil 2</option>
                        <option value="3" @if (old('desil') == '3') selected @endif>Desil 3</option>
                        <option value="4" @if (old('desil') == '4') selected @endif>Desil 4</option>
                        <option value="5" @if (old('desil') == '5') selected @endif>Desil 5</option>
                        <option value="6" @if (old('desil') == '6') selected @endif>Desil 6</option>
                        <option value="" @if (old('desil') == '') selected @endif>Tidak ada desil
                        </option>
                    </select>
                    @error('desil')
                        <i class="text-danger small">{{ $message }}</i>
                    @enderror
                </div>
            </div>

            <hr class="my-4">

            {{-- Bagian Data Individu (Struktur dinamis dari dummy) --}}
            <h4 class="fw-bold mb-3">Data Individu</h4>

            {{-- BLOK ERROR DARI DUMMY --}}
            @error('anggota_keluarga')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
            @enderror
            {{-- AKHIR BLOK --}}

            <div id="anggota-keluarga-container">
                @php
                    $anggota_list = old('anggota_keluarga', [0 => []]);
                @endphp

                @foreach ($anggota_list as $index => $anggota)
                    <div class="card mb-3 anggota-keluarga-item">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title anggota-keluarga-title mb-0">Anggota Keluarga {{ $index + 1 }}
                            </h5>
                            {{-- Tombol Hapus hanya tampil jika anggota > 1 (dikelola JS) --}}
                            @if ($index > 0)
                                <button type="button"
                                    class="btn btn-danger btn-sm remove-anggota-keluarga">Hapus</button>
                            @endif
                        </div>
                        <div class="card-body">
                            {{-- Menggunakan layout 2 kolom dari Figma --}}
                            <div class="row g-3">

                                {{-- NIK --}}
                                <div class="col-md-6">
                                    <label for="nik_anggota_{{ $index }}" class="form-label">Nomor Induk
                                        Kependudukan (NIK)</label>
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
                                        <option value="Islam" @if (isset($anggota['agama']) && $anggota['agama'] == 'Islam') selected @endif>Islam
                                        </option>
                                        <option value="Kristen" @if (isset($anggota['agama']) && $anggota['agama'] == 'Kristen') selected @endif>
                                            Kristen</option>
                                        <option value="Katolik" @if (isset($anggota['agama']) && $anggota['agama'] == 'Katolik') selected @endif>
                                            Katolik</option>
                                        <option value="Hindu" @if (isset($anggota['agama']) && $anggota['agama'] == 'Hindu') selected @endif>Hindu
                                        </option>
                                        <option value="Buddha" @if (isset($anggota['agama']) && $anggota['agama'] == 'Buddha') selected @endif>
                                            Buddha</option>
                                        <option value="Konghuchu" @if (isset($anggota['agama']) && $anggota['agama'] == 'Konghuchu') selected @endif>
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
                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'Tidak Sekolah') selected @endif>Tidak Sekolah</option>
                                        <option value="SD" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'SD') selected @endif>SD
                                        </option>
                                        <option value="SMP" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'SMP') selected @endif>SMP
                                        </option>
                                        <option value="SMA" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'SMA') selected @endif>SMA
                                        </option>
                                        <option value="D1" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D1') selected @endif>D1
                                        </option>
                                        <option value="D2" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D2') selected @endif>D2
                                        </option>
                                        <option value="D3" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D3') selected @endif>D3
                                        </option>
                                        <option value="D4/S1" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D4/S1') selected @endif>
                                            D4/S1</option>
                                        <option value="S2" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'S2') selected @endif>S2
                                        </option>
                                        <option value="S3" @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'S3') selected @endif>S3
                                        </option>
                                    </select>
                                    @error("anggota_keluarga.$index.pendidikan")
                                        <i class="text-danger small">{{ $message }}</i>
                                    @enderror
                                </div>

                                {{-- Jenis Kelamin (Menggunakan Select dari Figma) --}}
                                <div class="col-md-6">
                                    <label for="jenis_kelamin_anggota_{{ $index }}" class="form-label">Jenis
                                        Kelamin</label>
                                    <select
                                        class="form-select @error("anggota_keluarga.$index.jenis_kelamin") is-invalid @enderror"
                                        id="jenis_kelamin_anggota_{{ $index }}"
                                        name="anggota_keluarga[{{ $index }}][jenis_kelamin]" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" @if (isset($anggota['jenis_kelamin']) && $anggota['jenis_kelamin'] == 'Laki-laki') selected @endif>
                                            Laki-laki</option>
                                        <option value="Perempuan" @if (isset($anggota['jenis_kelamin']) && $anggota['jenis_kelamin'] == 'Perempuan') selected @endif>
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
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Belum/Tidak Bekerja') selected @endif>Belum/Tidak Bekerja
                                        </option>
                                        <option value="Pelajar/Mahasiswa"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Pelajar/Mahasiswa') selected @endif>Pelajar/Mahasiswa
                                        </option>
                                        <option value="Pegawai Negeri Sipil"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Pegawai Negeri Sipil') selected @endif>Pegawai Negeri Sipil
                                        </option>
                                        <option value="Tentara Nasional Indonesia"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Tentara Nasional Indonesia') selected @endif>Tentara Nasional
                                            Indonesia</option>
                                        <option value="Kepolisian RI"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Kepolisian RI') selected @endif>Kepolisian RI</option>
                                        <option value="Petani/Pekebun"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Petani/Pekebun') selected @endif>Petani/Pekebun</option>
                                        <option value="Peternak" @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Peternak') selected @endif>
                                            Peternak</option>
                                        <option value="Nelayan" @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Nelayan') selected @endif>
                                            Nelayan</option>
                                        <option value="Karyawan Swasta"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Karyawan Swasta') selected @endif>Karyawan Swasta</option>
                                        <option value="Wiraswasta" @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Wiraswasta') selected @endif>
                                            Wiraswasta</option>
                                        <option value="Ibu Rumah Tangga"
                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Ibu Rumah Tangga') selected @endif>Ibu Rumah Tangga
                                        </option>
                                        <option value="Pensiunan" @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Pensiunan') selected @endif>
                                            Pensiunan</option>
                                        <option value="Lainnya" @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Lainnya') selected @endif>
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

                                    {{-- SELECT BOX --}}
                                    <select
                                        class="form-select @error("anggota_keluarga.$index.status_dalam_keluarga") is-invalid @enderror"
                                        id="status_dalam_keluarga_anggota_{{ $index }}"
                                        name="anggota_keluarga[{{ $index }}][status_dalam_keluarga]" required
                                        {{-- MODIFIKASI 1: Disable jika index 0 --}}
                                        @if ($index === 0) disabled style="background-color: #e9ecef;" @endif>

                                        <option value="">Pilih Status Dalam Keluarga</option>

                                        {{-- Opsi Kepala Keluarga (Otomatis Selected jika index 0) --}}
                                        <option value="Kepala Keluarga"
                                            @if (($anggota['status_dalam_keluarga'] ?? '') == 'Kepala Keluarga' || $index === 0) selected @endif>
                                            Kepala Keluarga
                                        </option>

                                        <option value="Istri" @if (($anggota['status_dalam_keluarga'] ?? '') == 'Istri') selected @endif>
                                            Istri
                                        </option>
                                        <option value="Anak" @if (($anggota['status_dalam_keluarga'] ?? '') == 'Anak') selected @endif>
                                            Anak
                                        </option>
                                        <option value="Famili Lain"
                                            @if (($anggota['status_dalam_keluarga'] ?? '') == 'Famili Lain') selected @endif>
                                            Famili Lain
                                        </option>
                                    </select>

                                    {{-- MODIFIKASI 2: Input Hidden khusus untuk Index 0 --}}
                                    {{-- Ini penting agar value 'Kepala Keluarga' tetap terkirim ke controller --}}
                                    @if ($index === 0)
                                        <input type="hidden"
                                            name="anggota_keluarga[{{ $index }}][status_dalam_keluarga]"
                                            value="Kepala Keluarga" class="status-hidden-input">
                                    @endif

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
                                            @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Belum Kawin') selected @endif>Belum Kawin</option>
                                        <option value="Kawin" @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Kawin') selected @endif>
                                            Kawin</option>
                                        <option value="Cerai Mati" @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Cerai Mati') selected @endif>
                                            Cerai Mati</option>
                                        <option value="Cerai Hidup" @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Cerai Hidup') selected @endif>
                                            Cerai Hidup</option>
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

            <h4 class="fw-bold mb-3 mt-4">Berkas Pendukung</h4>

            <div class="row g-3 mb-4">
                {{-- Upload KTP --}}
                <div class="col-md-6">
                    <label for="foto_ktp" class="form-label">Upload Foto/Scan KTP Kepala Keluarga</label>

                    <div id="dropKTP"
                        class="border border-2 border-dashed rounded p-4 text-center bg-light"
                        style="cursor: pointer;">
                        <input type="file" name="foto_ktp" id="foto_ktp"
                            class="d-none @error('foto_ktp') is-invalid @enderror"
                            accept="image/*,.pdf">

                        <p class="text-muted m-0">
                            <strong>Klik untuk mengunggah</strong> atau seret file<br>
                            <small>Gambar atau PDF (Maks. 5MB)</small>
                        </p>
                    </div>

                    @error('foto_ktp')
                        <i class="text-danger small">{{ $message }}</i>
                    @enderror

                    <div id="previewKTP" class="small mt-1 text-success"></div>
                </div>

                <!-- Upload KK -->
                <div class="col-md-6">
                    <label for="foto_kk" class="form-label">Upload Foto/Scan KK</label>

                    <div id="dropKK"
                        class="border border-2 border-dashed rounded p-4 text-center bg-light"
                        style="cursor: pointer;">
                        <input type="file" name="foto_kk" id="foto_kk"
                            class="d-none @error('foto_kk') is-invalid @enderror"
                            accept="image/*,.pdf">

                        <p class="text-muted m-0">
                            <strong>Klik untuk mengunggah</strong> atau seret file<br>
                            <small>Gambar atau PDF (Maks. 5MB)</small>
                        </p>
                    </div>

                    @error('foto_kk')
                        <i class="text-danger small">{{ $message }}</i>
                    @enderror

                    <div id="previewKK" class="small mt-1 text-success"></div>
                </div>

            </div>

            <script>
                function setupUpload(dropAreaId, inputId, previewId) {
                    const drop = document.getElementById(dropAreaId);
                    const input = document.getElementById(inputId);
                    const preview = document.getElementById(previewId);

                    drop.addEventListener("click", () => input.click());

                    drop.addEventListener("dragover", (e) => {
                        e.preventDefault();
                        drop.classList.add("bg-secondary-subtle");
                    });

                    drop.addEventListener("dragleave", () => {
                        drop.classList.remove("bg-secondary-subtle");
                    });

                    drop.addEventListener("drop", (e) => {
                        e.preventDefault();
                        drop.classList.remove("bg-secondary-subtle");
                        input.files = e.dataTransfer.files;
                        validate();
                    });

                    input.addEventListener("change", validate);

                    function validate() {
                        const file = input.files[0];
                        if (!file) return;

                        if (file.size > 5 * 1024 * 1024) {
                            preview.classList.add("text-danger");
                            preview.textContent = "File melebihi 5MB!";
                            input.value = "";
                            return;
                        }

                        preview.classList.remove("text-danger");
                        preview.textContent = "✔ " + file.name;
                    }
                }

                setupUpload("dropKTP", "foto_ktp", "previewKTP");
                setupUpload("dropKK", "foto_kk", "previewKK");
            </script>

            </div>
            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('data-warga.index') }}" class="btn btn-outline-secondary me-2">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan Data
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
{{-- Kode JavaScript dari file dummy Anda --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('anggota-keluarga-container');
        const addButton = document.getElementById('add-anggota-keluarga');
        const jumlahInput = document.getElementById('jumlah');

        // Inisialisasi jumlah anggota keluarga saat halaman dimuat
        updateJumlahInput();

        // --- FUNGSI UNTUK MENAMBAH ANGGOTA KELUARGA ---
        // --- FUNGSI UNTUK MENAMBAH ANGGOTA KELUARGA ---
        addButton.addEventListener('click', function() {
            // Ambil card pertama sebagai template
            const template = container.querySelector('.anggota-keluarga-item');
            const newForm = template.cloneNode(true);
            const newIndex = container.querySelectorAll('.anggota-keluarga-item').length;

            // Update Judul
            newForm.querySelector('.anggota-keluarga-title').textContent = 'Anggota Keluarga ' + (
                newIndex + 1);

            // --- MODIFIKASI DIMULAI DARI SINI ---

            // 1. Reset semua input standard (text, date, number)
            newForm.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]')
                .forEach(input => {
                    input.value = '';
                    input.classList.remove('is-invalid');
                });

            // 2. Reset Select Box & Hapus Atribut Disabled
            newForm.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0; // Reset pilihan ke paling atas
                select.classList.remove('is-invalid');

                // Hapus disabled dan style background abu-abu (jika itu hasil clone dari Kepala Keluarga)
                select.removeAttribute('disabled');
                select.style.backgroundColor = '';
            });

            // 3. HAPUS Input Hidden 'Kepala Keluarga' pada element baru
            // Karena anggota ke-2 dst harus dipilih manual, tidak boleh ada input hidden ini
            const hiddenStatusInput = newForm.querySelector('.status-hidden-input');
            if (hiddenStatusInput) {
                hiddenStatusInput.remove();
            }

            // --- AKHIR MODIFIKASI ---

            // Hapus pesan error validasi yang ikut ter-clone
            newForm.querySelectorAll('.text-danger.small').forEach(err => err.remove());

            // Tambahkan tombol hapus (kode lama Anda)
            if (newIndex > 0 && !newForm.querySelector('.remove-anggota-keluarga')) {
                const header = newForm.querySelector('.card-header');
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger btn-sm remove-anggota-keluarga';
                removeButton.textContent = 'Hapus';
                header.appendChild(removeButton);
            }

            // Update atribut name/id (kode lama Anda)
            newForm.querySelectorAll('[name], [id], [for]').forEach(el => {
                ['name', 'id', 'for'].forEach(attr => {
                    const value = el.getAttribute(attr);
                    if (value) {
                        // Update index array, misal [0] jadi [1]
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
                    // Di form baru, kita tidak menggunakan alert
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

                // Sembunyikan tombol hapus untuk item pertama
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
                this.value = 1; // Minimal 1
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
                    if (lastItem) {
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
