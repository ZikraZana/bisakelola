@extends('layouts.layout')

@section('title')
    Form Tambah
@endsection

@section('title_nav')
    Form Tambah
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Data Warga</h4>
                    </div>

                    {{-- TAMBAHAN: Menampilkan ringkasan error --}}
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
                    {{-- AKHIR TAMBAHAN --}}

                    <form action="{{ route('data_warga.store') }}" method="POST">
                        <div class="card-body">
                            @csrf
                            <div class="form-group">
                                <label for="no_kk">Nomor KK</label>
                                <input type="number" class="form-control @error('no_kk') is-invalid @enderror"
                                    id="no_kk" name="no_kk" required value="{{ old('no_kk') }}">
                                @error('no_kk')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="blok">Blok</label>
                                <input type="text" class="form-control @error('blok') is-invalid @enderror"
                                    id="blok" name="blok" required value="{{ old('blok') }}">
                                @error('blok')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="desil">Desil</label>
                                <input type="number" class="form-control @error('desil') is-invalid @enderror"
                                    id="desil" name="desil" required value="{{ old('desil') }}">
                                @error('desil')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Jumlah Anggota Keluarga</label>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" name="jumlah" min="1" value="{{ old('jumlah', 1) }}" required>
                                @error('jumlah')
                                    <i class="text-danger small">{{ $message }}</i>
                                @enderror
                            </div>

                        </div>

                        <div class="card-body">
                            <div id="anggota-keluarga-container">

                                {{-- 
                                  INI ADALAH BLOK UNTUK MENAMPILKAN ERROR
                                  "HARUS ADA KEPALA KELUARGA"
                                --}}
                                @error('anggota_keluarga')
                                    <div class="alert alert-danger mb-3">
                                        {{ $message }}
                                    </div>
                                @enderror
                                {{-- AKHIR BLOK TAMBAHAN --}}

                                @php
                                    $anggota_list = old('anggota_keluarga', [0 => []]);
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
                                                            value="Laki-laki" required
                                                            @if (isset($anggota['jenis_kelamin']) && $anggota['jenis_kelamin'] == 'Laki-laki') checked @endif>
                                                        <label class="form-check-label"
                                                            for="jenis_kelamin_anggota_{{ $index }}_l">Laki-laki</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="anggota_keluarga[{{ $index }}][jenis_kelamin]"
                                                            id="jenis_kelamin_anggota_{{ $index }}_p"
                                                            value="Perempuan" required
                                                            @if (isset($anggota['jenis_kelamin']) && $anggota['jenis_kelamin'] == 'Perempuan') checked @endif>
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
                                                            @if (isset($anggota['agama']) && $anggota['agama'] == 'Islam') selected @endif>Islam
                                                        </option>
                                                        <option value="Kristen"
                                                            @if (isset($anggota['agama']) && $anggota['agama'] == 'Kristen') selected @endif>Kristen
                                                        </option>
                                                        <option value="Katolik"
                                                            @if (isset($anggota['agama']) && $anggota['agama'] == 'Katolik') selected @endif>Katolik
                                                        </option>
                                                        <option value="Hindu"
                                                            @if (isset($anggota['agama']) && $anggota['agama'] == 'Hindu') selected @endif>Hindu
                                                        </option>
                                                        <option value="Buddha"
                                                            @if (isset($anggota['agama']) && $anggota['agama'] == 'Buddha') selected @endif>Buddha
                                                        </option>
                                                        <option value="Konghuchu"
                                                            @if (isset($anggota['agama']) && $anggota['agama'] == 'Konghuchu') selected @endif>Konghucu
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
                                                            @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Belum Kawin') selected @endif>Belum Kawin
                                                        </option>
                                                        <option value="Kawin"
                                                            @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Kawin') selected @endif>Kawin
                                                        </option>
                                                        <option value="Cerai"
                                                            @if (isset($anggota['status_perkawinan']) && $anggota['status_perkawinan'] == 'Cerai') selected @endif>Cerai
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
                                                            @if (isset($anggota['status_dalam_keluarga']) && $anggota['status_dalam_keluarga'] == 'Kepala Keluarga') selected @endif>Kepala
                                                            Keluarga</option>
                                                        <option value="Istri"
                                                            @if (isset($anggota['status_dalam_keluarga']) && $anggota['status_dalam_keluarga'] == 'Istri') selected @endif>Istri
                                                        </option>
                                                        <option value="Anak"
                                                            @if (isset($anggota['status_dalam_keluarga']) && $anggota['status_dalam_keluarga'] == 'Anak') selected @endif>Anak
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
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'Tidak Sekolah') selected @endif>Tidak
                                                            Sekolah</option>
                                                        <option value="SD"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'SD') selected @endif>SD</option>
                                                        <option value="SMP"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'SMP') selected @endif>SMP</option>
                                                        <option value="SMA"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'SMA') selected @endif>SMA</option>
                                                        <option value="D1"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D1') selected @endif>D1</option>
                                                        <option value="D2"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D2') selected @endif>D2</option>
                                                        <option value="D3"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D3') selected @endif>D3</option>
                                                        <option value="D4/S1"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'D4/S1') selected @endif>D4/S1
                                                        </option>
                                                        <option value="S2"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'S2') selected @endif>S2</option>
                                                        <option value="S3"
                                                            @if (isset($anggota['pendidikan']) && $anggota['pendidikan'] == 'S3') selected @endif>S3</option>
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
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Belum/Tidak Bekerja') selected @endif>Belum/Tidak
                                                            Bekerja</option>
                                                        <option value="Pelajar/Mahasiswa"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Pelajar/Mahasiswa') selected @endif>
                                                            Pelajar/Mahasiswa</option>
                                                        <option value="Pegawai Negeri Sipil"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Pegawai Negeri Sipil') selected @endif>Pegawai
                                                            Negeri Sipil</option>
                                                        <option value="Tentara Nasional Indonesia"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Tentara Nasional Indonesia') selected @endif>Tentara
                                                            Nasional Indonesia</option>
                                                        <option value="Kepolisian RI"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Kepolisian RI') selected @endif>Kepolisian
                                                            RI</option>
                                                        <option value="Petani/Pekebun"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Petani/Pekebun') selected @endif>
                                                            Petani/Pekebun</option>
                                                        <option value="Peternak"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Peternak') selected @endif>Peternak
                                                        </option>
                                                        <option value="Nelayan"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Nelayan') selected @endif>Nelayan
                                                        </option>
                                                        <option value="Karyawan Swasta"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Karyawan Swasta') selected @endif>Karyawan
                                                            Swasta</option>
                                                        <option value="Wiraswasta"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Wiraswasta') selected @endif>Wiraswasta
                                                        </option>
                                                        <option value="Ibu Rumah Tangga"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Ibu Rumah Tangga') selected @endif>Ibu Rumah
                                                            Tangga</option>
                                                        <option value="Pensiunan"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Pensiunan') selected @endif>Pensiunan
                                                        </option>
                                                        <option value="Lainnya"
                                                            @if (isset($anggota['pekerjaan']) && $anggota['pekerjaan'] == 'Lainnya') selected @endif>Lainnya
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
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('data_warga.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Kode JavaScript kamu sudah benar, saya hanya menambahkan pembersihan error --}}
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
