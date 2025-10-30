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
                    {{-- TAMBAHKAN KODE INI --}}
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
                    {{-- AKHIR KODE TAMBAHAN --}}
                    <form action="{{ route('data_warga.store') }}" method="POST">
                        <div class="card-body">
                            @csrf
                            <div class="form-group">
                                <label for="no_kk">Nomor KK</label>
                                <input type="number" class="form-control" id="no_kk" name="no_kk" required>
                            </div>
                            <div class="form-group">
                                <label for="blok">Blok</label>
                                <input type="text" class="form-control" id="blok" name="blok" required>
                            </div>
                            <div class="form-group">
                                <label for="desil">Desil</label>
                                <input type="number" class="form-control" id="desil" name="desil" required>
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah Anggota Keluarga</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1"
                                    value="1" required>
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="anggota-keluarga-container">
                                {{-- Initial Anggota Keluarga 1 --}}
                                <div class="card mb-3 anggota-keluarga-item">
                                    <div class="card-header">
                                        <h5 class="card-title anggota-keluarga-title">Anggota Keluarga 1</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="nik_anggota_0">NIK Anggota</label>
                                                <input type="number" class="form-control" id="nik_anggota_0"
                                                    name="anggota_keluarga[0][nik]" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="nama_anggota_0">Nama Anggota</label>
                                                <input type="text" class="form-control" id="nama_anggota_0"
                                                    name="anggota_keluarga[0][nama]" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="tempat_lahir_anggota_0">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="tempat_lahir_anggota_0"
                                                    name="anggota_keluarga[0][tempat_lahir]" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="tanggal_lahir_anggota_0">Tanggal Lahir</label>
                                                <input type="date" class="form-control" id="tanggal_lahir_anggota_0"
                                                    name="anggota_keluarga[0][tanggal_lahir]" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Jenis Kelamin</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="anggota_keluarga[0][jenis_kelamin]"
                                                        id="jenis_kelamin_anggota_0_l" value="Laki-laki" required>
                                                    <label class="form-check-label"
                                                        for="jenis_kelamin_anggota_0_l">Laki-laki</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="anggota_keluarga[0][jenis_kelamin]"
                                                        id="jenis_kelamin_anggota_0_p" value="Perempuan" required>
                                                    <label class="form-check-label"
                                                        for="jenis_kelamin_anggota_0_p">Perempuan</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="agama_anggota_0">Agama</label>
                                                <select class="form-control" id="agama_anggota_0"
                                                    name="anggota_keluarga[0][agama]" required>
                                                    <option value="">Pilih Agama</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Katolik">Katolik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Buddha">Buddha</option>
                                                    <option value="Konghuchu">Konghucu</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="status_perkawinan_anggota_0">Status Perkawinan</label>
                                                <select class="form-control" id="status_perkawinan_anggota_0"
                                                    name="anggota_keluarga[0][status_perkawinan]" required>
                                                    <option value="">Pilih Status Perkawinan</option>
                                                    <option value="Belum Kawin">Belum Kawin</option>
                                                    <option value="Kawin">Kawin</option>
                                                    <option value="Cerai">Cerai</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="status_dalam_keluarga_anggota_0">Status Dalam Keluarga</label>
                                                <select class="form-control" id="status_dalam_keluarga_anggota_0"
                                                    name="anggota_keluarga[0][status_dalam_keluarga]" required>
                                                    <option value="">Pilih Status Dalam Keluarga</option>
                                                    <option value="Kepala Keluarga">Kepala Keluarga</option>
                                                    <option value="Istri">Istri</option>
                                                    <option value="Anak">Anak</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="pendidikan_anggota_0">Pendidikan</label>
                                                <select class="form-control" id="pendidikan_anggota_0"
                                                    name="anggota_keluarga[0][pendidikan]" required>
                                                    <option value="">Pilih Pendidikan</option>
                                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA">SMA</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="D4/S1">D4/S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="pekerjaan_anggota_0">Pekerjaan</label>
                                                <select class="form-control" id="pekerjaan_anggota_0"
                                                    name="anggota_keluarga[0][pekerjaan]" required>
                                                    <option value="">Pilih Pekerjaan</option>
                                                    <option value="Belum/Tidak Bekerja">Belum/Tidak Bekerja</option>
                                                    <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                                    <option value="Pegawai Negeri Sipil">Pegawai Negeri Sipil</option>
                                                    <option value="Tentara Nasional Indonesia">Tentara Nasional Indonesia
                                                    </option>
                                                    <option value="Kepolisian RI">Kepolisian RI</option>
                                                    <option value="Petani/Pekebun">Petani/Pekebun</option>
                                                    <option value="Peternak">Peternak</option>
                                                    <option value="Nelayan">Nelayan</option>
                                                    <option value="Karyawan Swasta">Karyawan Swasta</option>
                                                    <option value="Wiraswasta">Wiraswasta</option>
                                                    <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                                    <option value="Pensiunan">Pensiunan</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 text-right">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-anggota-keluarga">Hapus
                                                Anggota</button>
                                        </div>
                                    </div>
                                </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('anggota-keluarga-container');
            const addButton = document.getElementById('add-anggota-keluarga');
            const jumlahInput = document.getElementById('jumlah');

            // --- FUNGSI UNTUK MENAMBAH ANGGOTA KELUARGA ---
            addButton.addEventListener('click', function() {
                // Ambil card pertama sebagai template
                const template = container.querySelector('.anggota-keluarga-item');
                if (!template) {
                    console.error('Template anggota keluarga tidak ditemukan!');
                    return;
                }

                // Clone template untuk membuat form baru
                const newForm = template.cloneNode(true);

                // Dapatkan index baru berdasarkan jumlah card yang sudah ada
                const newIndex = container.querySelectorAll('.anggota-keluarga-item').length;

                // Ganti judul card
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
                    });
                newForm.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.checked = false; // Uncheck radio buttons
                });


                // Perbarui atribut 'name', 'id', dan 'for' untuk setiap elemen form
                newForm.querySelectorAll('[name], [id], [for]').forEach(el => {
                    ['name', 'id', 'for'].forEach(attr => {
                        const value = el.getAttribute(attr);
                        if (value) {
                            // Menggunakan regex untuk mengganti angka di dalam kurung siku atau di akhir id
                            const newValue = value.replace(/\[\d+\]/g, '[' + newIndex + ']')
                                .replace(/_\d+$/, '_' + newIndex);
                            el.setAttribute(attr, newValue);
                        }
                    });
                });

                // Tambahkan form baru ke dalam container
                container.appendChild(newForm);

                // Update nilai pada input jumlah anggota keluarga
                updateJumlahInput();
            });

            // --- FUNGSI UNTUK MENGHAPUS ANGGOTA KELUARGA ---
            // Menggunakan event delegation karena tombol hapus dibuat dinamis
            container.addEventListener('click', function(e) {
                // Cek apakah yang diklik adalah tombol hapus
                if (e.target && e.target.classList.contains('remove-anggota-keluarga')) {
                    // Jangan hapus jika hanya ada satu anggota
                    if (container.querySelectorAll('.anggota-keluarga-item').length <= 1) {
                        alert('Minimal harus ada satu anggota keluarga.');
                        return;
                    }

                    // Dapatkan card parent terdekat dan hapus
                    const cardToRemove = e.target.closest('.anggota-keluarga-item');
                    if (cardToRemove) {
                        cardToRemove.remove();
                        // Setelah menghapus, perbarui semua index agar berurutan kembali
                        updateAllIndexes();
                        // Update juga nilai pada input jumlah
                        updateJumlahInput();
                    }
                }
            });

            // --- FUNGSI UNTUK MEMPERBARUI SEMUA INDEX SETELAH PENGHAPUSAN ---
            function updateAllIndexes() {
                const allForms = container.querySelectorAll('.anggota-keluarga-item');
                allForms.forEach((form, index) => {
                    // Update judul card
                    form.querySelector('.anggota-keluarga-title').textContent = 'Anggota Keluarga ' + (
                        index + 1);

                    // Update atribut 'name', 'id', dan 'for'
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
                    // Jika user menambah jumlah, klik tombol tambah berulang kali
                    for (let i = 0; i < desiredCount - currentCount; i++) {
                        addButton.click();
                    }
                } else if (desiredCount < currentCount && desiredCount > 0) {
                    // Jika user mengurangi jumlah, hapus card dari belakang
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
