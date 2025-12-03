<?php

namespace App\Http\Controllers;

use App\Models\Blok;
use App\Models\Desil;
use App\Models\DataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataKeluargaController extends Controller
{
    public function index(Request $request) // BARU: Tambahkan Request $request
    {
        // 1. Ambil user yang login
        $user = Auth::user();

        // 2. Ambil data total blok (Tidak berubah)
        $totalBlok = Blok::withCount('dataKeluarga')
            ->pluck('data_keluarga_count', 'nama_blok');

        // 3. Ambil input dari request (filter & search)
        // Nama 'search_query', 'filter_blok', 'filter_desil' akan kita atur di Blade
        $searchQuery = $request->input('search_query');
        $filterBlok  = $request->input('filter_blok');
        $filterDesil = $request->input('filter_desil');
        $filterStatus = $request->input('filter_status');

        // 4. Siapkan query dasar dengan eager loading (Tidak berubah)
        $query = DataKeluarga::with(['anggotaKeluarga', 'blok', 'desil']);

        // 5. BARU: Terapkan logika PENCARIAN
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                // Cari di No. KK (tabel data_keluarga)
                $q->where('no_kk', 'like', "%{$searchQuery}%")
                    // ATAU cari di NIK/Nama Anggota (tabel relasi)
                    ->orWhereHas('anggotaKeluarga', function ($subQ) use ($searchQuery) {
                        $subQ->where('nik_anggota', 'like', "%{$searchQuery}%")
                            ->orWhere('nama_lengkap', 'like', "%{$searchQuery}%");
                    });
            });
        }

        // 6. BARU: Terapkan logika FILTER BLOK
        if ($filterBlok) {
            // Gunakan whereHas untuk filter berdasarkan relasi 'blok'
            $query->whereHas('blok', function ($q) use ($filterBlok) {
                $q->where('nama_blok', $filterBlok);
            });
        }

        // 7. BARU: Terapkan logika FILTER DESIL
        if ($filterDesil) {
            // Gunakan whereHas untuk filter berdasarkan relasi 'desil'
            $query->whereHas('desil', function ($q) use ($filterDesil) {
                $q->where('tingkat_desil', $filterDesil);
            });
        }

        // 8. BARU: Logika FILTER STATUS
        // Kita pakai !is_null agar nilai '0' (Nonaktif) tetap terbaca
        if (!is_null($filterStatus) && $filterStatus !== '') {
            $query->where('status', $filterStatus);
        }

        // 9. Terapkan logika sorting (UPDATED)
        // Urutan: Blok User (jika ada) -> Status Aktif (1) ke Nonaktif (0) -> Tanggal Dibuat
        if ($user && $user->role === 'Ketua Blok') {
            $query->orderByRaw("CASE 
                            WHEN id_blok = ? THEN 1 
                            ELSE 2 
                            END ASC", [$user->id_blok])
                ->orderBy('status', 'DESC') // BARU: Aktif (1) di atas
                ->orderBy('created_at', 'DESC');
        } else {
            $query->orderBy('status', 'DESC') // BARU: Aktif (1) di atas
                ->orderBy('created_at', 'DESC');
        }

        // 9. Ambil data dengan paginasi
        $validPerPages = [10, 25, 50]; // Tentukan jumlah yang valid
        $perPageInput = $request->input('per_page', 10); // Ambil input, default-nya 10

        // Pastikan nilainya valid, jika tidak, kembalikan ke 10
        $perPage = in_array($perPageInput, $validPerPages) ? $perPageInput : 10;

        // 9B. Ambil data dengan paginasi
        // BARU: Ganti '10' dengan variabel $perPage
        $dataKeluarga = $query->paginate($perPage)->withQueryString();

        // 10. Kirim semua data ke view
        return view('data-warga.index', [
            'dataKeluarga' => $dataKeluarga,
            'totalBlok'    => $totalBlok,
            'searchQuery'  => $searchQuery,
            'filterBlok'   => $filterBlok,
            'filterDesil'  => $filterDesil,
            'filterStatus' => $filterStatus,
            'perPage'      => $perPage, // <-- BARU: Kirim nilai perPage ke view
        ]);
    }

    public function status(Request $request, DataKeluarga $dataKeluarga)
    {

        // Validasi sedikit biar aman (0 atau 1)
        $request->validate([
            'status' => 'required|boolean'
        ]);

        // Update status
        $dataKeluarga->update([
            'status' => $request->status
        ]);

        // Pesan dinamis
        $statusMsg = $request->status == 1 ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Status keluarga berhasil $statusMsg.");
    }


    public function formTambah()
    {
        return view('data-warga.form-tambah');
    }

    public function store(Request $request)
    {
        // 1. Validasi Data
        $validator = Validator::make($request->all(), [
            'no_kk' => 'required|numeric|digits:16|unique:data_keluarga,no_kk',
            'blok' => 'required|string|exists:blok,nama_blok',
            'desil' => 'nullable|exists:desil,tingkat_desil',

            'anggota_keluarga' => [
                'required',
                'array',
                'min:1',
                // Aturan kustom untuk mengecek minimal satu Kepala Keluarga
                function ($attribute, $value, $fail) {
                    // $value adalah seluruh array 'anggota_keluarga'
                    $hasKepalaKeluarga = collect($value)
                        ->where('status_dalam_keluarga', 'Kepala Keluarga')
                        ->isNotEmpty(); // true jika ada minimal 1

                    if (!$hasKepalaKeluarga) {
                        // Jika tidak ada, gagalkan validasi dengan pesan kustom
                        $fail('Harus ada setidaknya satu anggota keluarga dengan status "Kepala Keluarga".');
                    }
                },
            ],

            'anggota_keluarga.*.nik' => 'required|numeric|digits:16|distinct|unique:data_anggota_keluarga,nik_anggota',
            'anggota_keluarga.*.nama' => 'required|string|max:255',
            'anggota_keluarga.*.tempat_lahir' => 'required|string|max:100',
            'anggota_keluarga.*.tanggal_lahir' => 'required|date|before:today',
            'anggota_keluarga.*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'anggota_keluarga.*.agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghuchu',
            'anggota_keluarga.*.status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Mati,Cerai Hidup',
            'anggota_keluarga.*.status_dalam_keluarga' => 'required|in:Kepala Keluarga,Istri,Anak',
            'anggota_keluarga.*.pendidikan' => 'required|string',
            'anggota_keluarga.*.pekerjaan' => 'required|string',
        ], [
            'no_kk.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'no_kk.numeric' => 'Nomor Kartu Keluarga harus berupa angka.',
            'no_kk.digits' => 'Nomor Kartu Keluarga harus terdiri dari 16 angka',
            'no_kk.unique' => 'Nomor Kartu Keluarga sudah terdaftar.',
            'blok.required' => 'Blok wajib diisi.',
            'blok.exists' => 'Blok tidak valid.',
            'desil.numeric' => 'Desil harus berupa angka.',
            'desil.exists' => 'Desil tidak valid.',
            'anggota_keluarga.required' => 'Minimal harus ada satu anggota keluarga.',
            'anggota_keluarga.array' => 'Data anggota keluarga tidak valid.',
            'anggota_keluarga.min' => 'Minimal harus ada satu anggota keluarga.',
            'anggota_keluarga.*.nik.required' => 'NIK anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nik.numeric' => 'NIK anggota keluarga harus berupa angka.',
            'anggota_keluarga.*.nik.digits' => 'NIK anggota keluarga harus terdiri dari 16 angka',
            'anggota_keluarga.*.nik.distinct' => 'NIK anggota keluarga tidak boleh sama dalam satu form.', // Pesan u/ distinct
            'anggota_keluarga.*.nik.unique' => 'NIK anggota keluarga sudah terdaftar.',
            'anggota_keluarga.*.nama.required' => 'Nama lengkap anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nama.max' => 'Nama lengkap anggota keluarga tidak boleh lebih dari :max karakter.',
            'anggota_keluarga.*.tempat_lahir.required' => 'Tempat lahir anggota keluarga wajib diisi.',
            'anggota_keluarga.*.tempat_lahir.max' => 'Tempat lahir anggota keluarga tidak boleh lebih dari :max karakter.',
            'anggota_keluarga.*.tanggal_lahir.required' => 'Tanggal lahir anggota keluarga wajib diisi.',
            'anggota_keluarga.*.tanggal_lahir.date' => 'Tanggal lahir anggota keluarga harus berupa tanggal yang valid.',
            'anggota_keluarga.*.tanggal_lahir.before' => 'Tanggal lahir anggota keluarga tidak boleh melebihi tanggal hari ini.',
            'anggota_keluarga.*.jenis_kelamin.required' => 'Jenis kelamin anggota keluarga wajib dipilih.',
            'anggota_keluarga.*.jenis_kelamin.in' => 'Jenis kelamin anggota keluarga tidak valid.',
            'anggota_keluarga.*.agama.required' => 'Agama anggota keluarga wajib dipilih.',
            'anggota_keluarga.*.agama.in' => 'Agama anggota keluarga tidak valid.',
            'anggota_keluarga.*.status_perkawinan.required' => 'Status perkawinan anggota keluarga wajib dipilih.',
            'anggota_keluarga.*.status_perkawinan.in' => 'Status perkawinan anggota keluarga tidak valid.',
            'anggota_keluarga.*.status_dalam_keluarga.required' => 'Status dalam keluarga wajib dipilih.',
            'anggota_keluarga.*.status_dalam_keluarga.in' => 'Status dalam keluarga tidak valid.',
            'anggota_keluarga.*.pendidikan.required' => 'Pendidikan anggota keluarga wajib diisi.',
            'anggota_keluarga.*.pekerjaan.required' => 'Pekerjaan anggota keluarga wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('data-warga.formTambah')
                ->withErrors($validator)
                ->withInput();
        }

        // 2. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // 3. Handle Blok dan Desil (Gunakan firstOrCreate)
            $blok = Blok::firstOrCreate(
                ['nama_blok' => $request->blok],
            );
            $desil = Desil::firstOrCreate(
                ['tingkat_desil' => $request->desil]
            );
            $admin = Auth::user();


            // 4. Simpan DataKeluarga
            $dataKeluarga = DataKeluarga::create([
                'id_admin' => $admin->id_admin,
                'no_kk' => $request->no_kk,
                'id_blok' => $blok->id_blok,
                'id_desil' => $desil->id_desil,
            ]);

            // 5. Simpan AnggotaKeluarga
            foreach ($request->anggota_keluarga as $anggota) {
                // Mapping nama field form ke nama kolom database
                $dataAnggota = [
                    'nik_anggota' => $anggota['nik'],
                    'nama_lengkap' => $anggota['nama'],
                    'tempat_lahir' => $anggota['tempat_lahir'],
                    'tanggal_lahir' => $anggota['tanggal_lahir'],
                    'jenis_kelamin' => $anggota['jenis_kelamin'],
                    'agama' => $anggota['agama'],
                    'status_perkawinan' => $anggota['status_perkawinan'],
                    'status_dalam_keluarga' => $anggota['status_dalam_keluarga'],
                    'pendidikan' => $anggota['pendidikan'],
                    'pekerjaan' => $anggota['pekerjaan'],
                ];

                // Simpan menggunakan relasi
                $dataKeluarga->anggotaKeluarga()->create($dataAnggota);
            }

            // 6. Commit Transaksi
            DB::commit();

            return redirect()->route('data-warga.index')->with('success', 'Data keluarga berhasil ditambahkan.');
        } catch (\Exception $e) {
            // 7. Rollback jika ada error
            DB::rollBack();
            Log::error('Error storing data warga: ' . $e->getMessage());

            return redirect()->route('data-warga.formTambah')
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Menampilkan form untuk mengedit data
     */
    public function formEdit(DataKeluarga $dataKeluarga)
    {
        $user = Auth::user();

        if (
            $user->role !== 'Ketua RT' &&
            !($user->role === 'Ketua Blok' && $user->id_blok === $dataKeluarga->id_blok)
        ) {
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK MENGEDIT DATA WARGA INI.');
        }

        $dataKeluarga->load('anggotaKeluarga', 'blok', 'desil');

        return view('data-warga.form-edit', compact('dataKeluarga'));
    }

    /**
     * Memproses data update
     */
    public function update(Request $request, DataKeluarga $dataKeluarga)
    {
        $user = Auth::user();

        // ---- KODE SINGKAT ----
        if (
            $user->role !== 'Ketua RT' &&
            !($user->role === 'Ketua Blok' && $user->id_blok === $dataKeluarga->id_blok)
        ) {
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK MENGEDIT DATA WARGA INI.');
        }

        // 1. Validasi Data
        $validator = Validator::make($request->all(), [
            'no_kk' => ['required', 'numeric', 'digits:16', Rule::unique('data_keluarga', 'no_kk')->ignore($dataKeluarga->id_keluarga, 'id_keluarga')],
            'blok' => 'required|exists:blok,nama_blok',
            'desil' => 'nullable|exists:desil,tingkat_desil',

            'anggota_keluarga' => [
                'required',
                'array',
                'min:1',
                // Aturan kustom untuk mengecek minimal satu Kepala Keluarga
                function ($attribute, $value, $fail) {
                    $hasKepalaKeluarga = collect($value)
                        ->where('status_dalam_keluarga', 'Kepala Keluarga')
                        ->isNotEmpty();
                    if (!$hasKepalaKeluarga) {
                        $fail('Harus ada setidaknya satu anggota keluarga dengan status "Kepala Keluarga".');
                    }
                },
            ],

            'anggota_keluarga.*.nik' => [
                'required',
                'numeric',
                'digits:16',
                'distinct',
                Rule::unique('data_anggota_keluarga', 'nik_anggota')
                    ->where(function ($query) use ($dataKeluarga) {
                        // Cek NIK unik, TAPI abaikan NIK yang dimiliki oleh anggota keluarga ini
                        return $query->where('id_keluarga', '!=', $dataKeluarga->id_keluarga);
                    })
            ],
            'anggota_keluarga.*.nama' => 'required|string|max:255',
            'anggota_keluarga.*.tempat_lahir' => 'required|string|max:100',
            'anggota_keluarga.*.tanggal_lahir' => 'required|date|before:today',
            'anggota_keluarga.*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'anggota_keluarga.*.agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghuchu',
            'anggota_keluarga.*.status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Mati, Cerai Hidup',
            'anggota_keluarga.*.status_dalam_keluarga' => 'required|in:Kepala Keluarga,Istri,Anak',
            'anggota_keluarga.*.pendidikan' => 'required|string',
            'anggota_keluarga.*.pekerjaan' => 'required|string',
        ], [
            'no_kk.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'no_kk.numeric' => 'Nomor Kartu Keluarga harus berupa angka.',
            'no_kk.digits' => 'Nomor Kartu Keluarga harus terdiri dari 16 angka',
            'no_kk.unique' => 'Nomor Kartu Keluarga sudah terdaftar untuk keluarga lain.',
            'blok.required' => 'Blok wajib diisi.',
            'blok.string' => 'Blok harus berupa teks.',
            'blok.max' => 'Blok tidak boleh lebih dari :max karakter.',
            'desil.required' => 'Desil wajib diisi.',
            'desil.numeric' => 'Desil harus berupa angka.',
            'desil.exists' => 'Desil tidak valid.',
            'anggota_keluarga.required' => 'Minimal harus ada satu anggota keluarga.',
            'anggota_keluarga.array' => 'Data anggota keluarga tidak valid.',
            'anggota_keluarga.min' => 'Minimal harus ada satu anggota keluarga.',
            'anggota_keluarga.*.nik.required' => 'NIK anggota keluarga wajib diisi.',
            'anggota_keluarga.*.nik.numeric' => 'NIK anggota keluarga harus berupa angka.',
            'anggota_keluarga.*.nik.digits' => 'NIK anggota keluarga harus terdiri dari 16 angka',
            'anggota_keluarga.*.nik.distinct' => 'NIK anggota keluarga tidak boleh sama dalam satu form.',
            'anggota_keluarga.*.nik.unique' => 'NIK anggota keluarga sudah terdaftar untuk anggota keluarga lain.',
            'anggota_keluarga.*.nama.required' => 'Nama lengkap anggota keluarga wajib diisi.',
            'anggota_keluarga.*.tanggal_lahir.required' => 'Tanggal lahir anggota keluarga wajib diisi.',
            'anggota_keluarga.*.tanggal_lahir.date' => 'Tanggal lahir anggota keluarga harus berupa tanggal yang valid.',
            'anggota_keluarga.*.tanggal_lahir.before' => 'Tanggal lahir anggota keluarga tidak boleh melebihi tanggal hari ini.',
            'anggota_keluarga.*.status_dalam_keluarga.required' => 'Status dalam keluarga wajib dipilih.',
            'anggota_keluarga.*.status_dalam_keluarga.in' => 'Status dalam keluarga tidak valid.',
            'anggota_keluarga.*.pendidikan.required' => 'Pendidikan anggota keluarga wajib diisi.',
            'anggota_keluarga.*.pekerjaan.required' => 'Pekerjaan anggota keluarga wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('data-warga.formEdit', $dataKeluarga->id_keluarga)
                ->withErrors($validator)
                ->withInput();
        }

        // 2. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // 3. Handle Blok dan Desil 
            $blok = Blok::firstOrCreate(
                ['nama_blok' => $request->blok],
            );
            $desil = Desil::firstOrCreate(
                ['tingkat_desil' => $request->desil]
            );
            $admin = Auth::user();


            // 4. Update DataKeluarga
            $dataKeluarga->update([
                'id_admin' => $admin->id_admin,
                'no_kk' => $request->no_kk,
                'id_blok' => $blok->id_blok,
                'id_desil' => $desil->id_desil,
            ]);

            // 5. Update AnggotaKeluarga (Metode: Hapus & Buat Ulang)
            $dataKeluarga->anggotaKeluarga()->delete();

            // Loop dan buat ulang anggota keluarga
            foreach ($request->anggota_keluarga as $anggota) {
                $dataAnggota = [
                    'nik_anggota' => $anggota['nik'],
                    'nama_lengkap' => $anggota['nama'],
                    'tempat_lahir' => $anggota['tempat_lahir'],
                    'tanggal_lahir' => $anggota['tanggal_lahir'],
                    'jenis_kelamin' => $anggota['jenis_kelamin'],
                    'agama' => $anggota['agama'],
                    'status_perkawinan' => $anggota['status_perkawinan'],
                    'status_dalam_keluarga' => $anggota['status_dalam_keluarga'],
                    'pendidikan' => $anggota['pendidikan'],
                    'pekerjaan' => $anggota['pekerjaan'],
                ];
                $dataKeluarga->anggotaKeluarga()->create($dataAnggota);
            }

            // 6. Commit Transaksi
            DB::commit();

            return redirect()->route('data-warga.index')->with('success', 'Data keluarga berhasil diperbarui.');
        } catch (\Exception $e) {
            // 7. Rollback jika ada error
            DB::rollBack();
            Log::error('Error updating data warga: ' . $e->getMessage());

            return redirect()->route('data-warga.edit', $dataKeluarga->id_keluarga)
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }
}
