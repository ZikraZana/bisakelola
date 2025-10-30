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

                    {{-- Menampilkan error validasi --}}
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
                    {{-- Akhir Error --}}

                    {{-- PERUBAHAN: Form action ke route update dan method PUT --}}
                    <form action="{{ route('data_warga.update', $dataKeluarga->id_keluarga) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="no_kk">Nomor KK</label>
                                {{-- PERUBAHAN: Isi value dengan data yang ada --}}
                                <input type="number" class="form-control" id="no_kk" name="no_kk"
                                    value="{{ old('no_kk', $dataKeluarga->no_kk) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="blok">Blok</label>
                                {{-- PERUBAHAN: Isi value dengan data relasi --}}
                                <input type="text" class="form-control" id="blok" name="blok"
                                    value="{{ old('blok', $dataKeluarga->blok?->nama_blok) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="desil">Desil</label>
                                {{-- PERUBAHAN: Tipe diubah ke text (konsisten) & isi value --}}
                                <input type="number" class="form-control" id="desil" name="desil"
                                    value="{{ old('desil', $dataKeluarga->desil?->tingkat_desil) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah Anggota Keluarga</label>
                                {{-- PERUBAHAN: Isi value dengan jumlah anggota --}}
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1"
                                    value="{{ old('jumlah', $dataKeluarga->anggotaKeluarga->count()) }}" required>
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="anggota-keluarga-container">

                                {{-- PERUBAHAN: Loop semua anggota keluarga yang ada --}}
                                @foreach ($dataKeluarga->anggotaKeluarga as $index => $anggota)
                                    <div class="card mb-3 anggota-keluarga-item">
                                        <div class="card-header">
                                            <h5 class="card-title anggota-keluarga-title">Anggota Keluarga
                                                {{ $index + 1 }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">

                                                {{-- NIK Anggota --}}
                                                <div class="form-group col-md-4">
                                                    <label for="nik_anggota_{{ $index }}">NIK Anggota</label>
                                                    <input type="number" class="form-control"
                                                        id="nik_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][nik]"
                                                        value="{{ old('anggota_keluarga.' . $index . '.nik', $anggota->nik_anggota) }}"
                                                        required>
                                                </div>

                                                {{-- Nama Anggota --}}
                                                <div class="form-group col-md-4">
                                                    <label for="nama_anggota_{{ $index }}">Nama Anggota</label>
                                                    <input type="text" class="form-control"
                                                        id="nama_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][nama]"
                                                        value="{{ old('anggota_keluarga.' . $index . '.nama', $anggota->nama_lengkap) }}"
                                                        required>
                                                </div>

                                                {{-- Tempat Lahir --}}
                                                <div class="form-group col-md-4">
                                                    <label for="tempat_lahir_anggota_{{ $index }}">Tempat
                                                        Lahir</label>
                                                    <input type="text" class="form-control"
                                                        id="tempat_lahir_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][tempat_lahir]"
                                                        value="{{ old('anggota_keluarga.' . $index . '.tempat_lahir', $anggota->tempat_lahir) }}"
                                                        required>
                                                </div>

                                                {{-- Tanggal Lahir --}}
                                                <div class="form-group col-md-4">
                                                    <label for="tanggal_lahir_anggota_{{ $index }}">Tanggal
                                                        Lahir</label>
                                                    <input type="date" class="form-control"
                                                        id="tanggal_lahir_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][tanggal_lahir]"
                                                        value="{{ old('anggota_keluarga.' . $index . '.tanggal_lahir', $anggota->tanggal_lahir) }}"
                                                        required>
                                                </div>

                                                {{-- Jenis Kelamin --}}
                                                <div class="form-group col-md-4">
                                                    <label>Jenis Kelamin</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="anggota_keluarga[{{ $index }}][jenis_kelamin]"
                                                            id="jenis_kelamin_anggota_{{ $index }}_l"
                                                            value="Laki-laki" {{-- PERUBAHAN: Logika 'checked' --}}
                                                            {{ old('anggota_keluarga.' . $index . '.jenis_kelamin', $anggota->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label"
                                                            for="jenis_kelamin_anggota_{{ $index }}_l">Laki-laki</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="anggota_keluarga[{{ $index }}][jenis_kelamin]"
                                                            id="jenis_kelamin_anggota_{{ $index }}_p"
                                                            value="Perempuan" {{-- PERUBAHAN: Logika 'checked' --}}
                                                            {{ old('anggota_keluarga.' . $index . '.jenis_kelamin', $anggota->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label"
                                                            for="jenis_kelamin_anggota_{{ $index }}_p">Perempuan</label>
                                                    </div>
                                                </div>

                                                {{-- Agama --}}
                                                <div class="form-group col-md-4">
                                                    <label for="agama_anggota_{{ $index }}">Agama</label>
                                                    <select class="form-control" id="agama_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][agama]" required>
                                                        {{-- PERUBAHAN: Logika 'selected' --}}
                                                        @php $agama_val = old('anggota_keluarga.' . $index . '.agama', $anggota->agama); @endphp
                                                        <option value="">Pilih Agama</option>
                                                        <option value="Islam"
                                                            {{ $agama_val == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                        <option value="Kristen"
                                                            {{ $agama_val == 'Kristen' ? 'selected' : '' }}>
                                                            Kristen</option>
                                                        <option value="Katolik"
                                                            {{ $agama_val == 'Katolik' ? 'selected' : '' }}>Katolik
                                                            Katolik</option>
                                                        <option value="Hindu"
                                                            {{ $agama_val == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                        <option value="Buddha"
                                                            {{ $agama_val == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                        <option value="Konghuchu"
                                                            {{ $agama_val == 'Konghuchu' ? 'selected' : '' }}>Konghuchu
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- Status Perkawinan --}}
                                                <div class="form-group col-md-4">
                                                    <label for="status_perkawinan_anggota_{{ $index }}">Status
                                                        Perkawinan</label>
                                                    <select class="form-control"
                                                        id="status_perkawinan_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][status_perkawinan]"
                                                        required>
                                                        {{-- PERUBAHAN: Logika 'selected' --}}
                                                        @php $status_kawin_val = old('anggota_keluarga.' . $index . '.status_perkawinan', $anggota->status_perkawinan); @endphp
                                                        <option value="">Pilih Status Perkawinan</option>
                                                        <option value="Belum Kawin"
                                                            {{ $status_kawin_val == 'Belum Kawin' ? 'selected' : '' }}>
                                                            Belum Kawin</option>
                                                        <option value="Kawin"
                                                            {{ $status_kawin_val == 'Kawin' ? 'selected' : '' }}>Kawin
                                                        </option>
                                                        <option value="Cerai"
                                                            {{ $status_kawin_val == 'Cerai' ? 'selected' : '' }}>Cerai
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- Status Dalam Keluarga --}}
                                                <div class="form-group col-md-4">
                                                    <label for="status_dalam_keluarga_anggota_{{ $index }}">Status
                                                        Dalam Keluarga</label>
                                                    <select class="form-control"
                                                        id="status_dalam_keluarga_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][status_dalam_keluarga]"
                                                        required>
                                                        {{-- PERUBAHAN: Logika 'selected' --}}
                                                        @php $status_keluarga_val = old('anggota_keluarga.' . $index . '.status_dalam_keluarga', $anggota->status_dalam_keluarga); @endphp
                                                        <option value="">Pilih Status Dalam Keluarga</option>
                                                        <option value="Kepala Keluarga"
                                                            {{ $status_keluarga_val == 'Kepala Keluarga' ? 'selected' : '' }}>
                                                            Kepala Keluarga</option>
                                                        <option value="Istri"
                                                            {{ $status_keluarga_val == 'Istri' ? 'selected' : '' }}>Istri
                                                        </option>
                                                        <option value="Anak"
                                                            {{ $status_keluarga_val == 'Anak' ? 'selected' : '' }}>Anak
                                                        </option>
                                                    </select>
                                                </div>

                                                {{-- Pendidikan --}}
                                                <div class="form-group col-md-4">
                                                    <label for="pendidikan_anggota_{{ $index }}">Pendidikan</label>
                                                    <select class="form-control"
                                                        id="pendidikan_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][pendidikan]" required>
                                                        {{-- PERUBAHAN: Logika 'selected' --}}
                                                        @php $pendidikan_val = old('anggota_keluarga.' . $index . '.pendidikan', $anggota->pendidikan); @endphp
                                                        <option value="">Pilih Pendidikan</option>
                                                        <option value="Tidak Sekolah"
                                                            {{ $pendidikan_val == 'Tidak Sekolah' ? 'selected' : '' }}>
                                                            Tidak Sekolah</option>
                                                        <option value="SD"
                                                            {{ $pendidikan_val == 'SD' ? 'selected' : '' }}>SD</option>
                                                        <option value="SMP"
                                                            {{ $pendidikan_val == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                        <option value="SMA"
                                                            {{ $pendidikan_val == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                        <option value="D1"
                                                            {{ $pendidikan_val == 'D1' ? 'selected' : '' }}>D1</option>
                                                        <option value="D2"
                                                            {{ $pendidikan_val == 'D2' ? 'selected' : '' }}>D2</option>
                                                        <option value="D3"
                                                            {{ $pendidikan_val == 'D3' ? 'selected' : '' }}>D3</option>
                                                        <option value="D4/S1"
                                                            {{ $pendidikan_val == 'D4/S1' ? 'selected' : '' }}>D4/S1
                                                        </option>
                                                        <option value="S2"
                                                            {{ $pendidikan_val == 'S2' ? 'selected' : '' }}>S2</option>
                                                        <option value="S3"
                                                            {{ $pendidikan_val == 'S3' ? 'selected' : '' }}>S3</option>
                                                    </select>
                                                </div>

                                                {{-- Pekerjaan --}}
                                                <div class="form-group col-md-4">
                                                    <label for="pekerjaan_anggota_{{ $index }}">Pekerjaan</label>
                                                    <select class="form-control"
                                                        id="pekerjaan_anggota_{{ $index }}"
                                                        name="anggota_keluarga[{{ $index }}][pekerjaan]" required>
                                                        {{-- PERUBAHAN: Logika 'selected' --}}
                                                        @php $pekerjaan_val = old('anggota_keluarga.' . $index . '.pekerjaan', $anggota->pekerjaan); @endphp
                                                        <option value="">Pilih Pekerjaan</option>
                                                        <option value="Belum/Tidak Bekerja"
                                                            {{ $pekerjaan_val == 'Belum/Tidak Bekerja' ? 'selected' : '' }}>
                                                            Belum/Tidak Bekerja</option>
                                                        <option value="Pelajar/Mahasiswa"
                                                            {{ $pekerjaan_val == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>
                                                            Pelajar/Mahasiswa</option>
                                                        <option value="Pegawai Negeri Sipil"
                                                            {{ $pekerjaan_val == 'Pegawai Negeri Sipil' ? 'selected' : '' }}>
                                                            Pegawai Negeri Sipil</option>
                                                        <option value="Tentara Nasional Indonesia"
                                                            {{ $pekerjaan_val == 'Tentara Nasional Indonesia' ? 'selected' : '' }}>
                                                            Tentara Nasional Indonesia</option>
                                                        <option value="Kepolisian RI"
                                                            {{ $pekerjaan_val == 'Kepolisian RI' ? 'selected' : '' }}>
                                                            Kepolisian RI</option>
                                                        <option value="Petani/Pekebun"
                                                            {{ $pekerjaan_val == 'Petani/Pekebun' ? 'selected' : '' }}>
                                                            Petani/Pekebun</option>
                                                        <option value="Peternak"
                                                            {{ $pekerjaan_val == 'Peternak' ? 'selected' : '' }}>Peternak
                                                        </option>
                                                        <option value="Nelayan"
                                                            {{ $pekerjaan_val == 'Nelayan' ? 'selected' : '' }}>Nelayan
                                                        </option>
                                                        <option value="Karyawan Swasta"
                                                            {{ $pekerjaan_val == 'Karyawan Swasta' ? 'selected' : '' }}>
                                                            Karyawan Swasta</option>
                                                        <option value="Wiraswasta"
                                                            {{ $pekerjaan_val == 'Wiraswasta' ? 'selected' : '' }}>
                                                            Wiraswasta</option>
                                                        <option value="Ibu Rumah Tangga"
                                                            {{ $pekerjaan_val == 'Ibu Rumah Tangga' ? 'selected' : '' }}>
                                                            Ibu Rumah Tangga</option>
                                                        <option value="Pensiunan"
                                                            {{ $pekerjaan_val == 'Pensiunan' ? 'selected' : '' }}>Pensiunan
                                                        </option>
                                                        <option value="Lainnya"
                                                            {{ $pekerjaan_val == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                                        </option>
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
                                @endforeach
                                {{-- AKHIR LOOP --}}

                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-3" id="add-anggota-keluarga">Tambah
                                Anggota Keluarga</button>
                        </div>
                        <div class="card-footer">
                            {{-- PERUBAHAN: Teks tombol --}}
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('data_warga.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- JavaScript tetap sama persis dengan form_tambah --}}
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
