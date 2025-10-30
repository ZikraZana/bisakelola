<?php

namespace App\Http\Controllers;

use App\Models\Blok;
use App\Models\Desil;
use App\Models\DataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DataKeluargaController extends Controller
{
    public function index()
    {
        $dataKeluarga = DataKeluarga::all();

        return view('__simulasi_aja__.data_warga.index', compact('dataKeluarga'));
    }


    public function formTambah()
    {
        return view('__simulasi_aja__.data_warga.form_tambah');
    }

    public function store(Request $request)
    {
        // 1. Validasi Data
        $validator = Validator::make($request->all(), [
            'no_kk' => 'required|int',
            'blok' => 'required|string|max:100', // Validasi nama blok
            'desil' => 'required|int', // Validasi nama desil
            'anggota_keluarga' => 'required|array|min:1',
            'anggota_keluarga.*.nik' => 'required|string|max:255',
            'anggota_keluarga.*.nama' => 'required|string|max:255',
            'anggota_keluarga.*.tempat_lahir' => 'required|string|max:100',
            'anggota_keluarga.*.tanggal_lahir' => 'required|date',
            'anggota_keluarga.*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'anggota_keluarga.*.agama' => 'required|string',
            'anggota_keluarga.*.status_perkawinan' => 'required|string',
            'anggota_keluarga.*.status_dalam_keluarga' => 'required|string',
            'anggota_keluarga.*.pendidikan' => 'required|string',
            'anggota_keluarga.*.pekerjaan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('data_warga.tambah')
                ->withErrors($validator)
                ->withInput();
        }

        // 2. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // 3. Handle Blok dan Desil (Gunakan firstOrCreate)
            // Asumsi Model Blok punya kolom 'nama_blok' dan Desil punya 'tingkat_desil'
            $blok = Blok::firstOrCreate(
                ['nama_blok' => $request->blok], // Sesuaikan 'nama_blok'
            );
            $desil = Desil::firstOrCreate(
                ['tingkat_desil' => $request->desil] // Sesuaikan 'tingkat_desil'
            );

            // 4. Simpan DataKeluarga
            $dataKeluarga = DataKeluarga::create([
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

            return redirect()->route('data_warga.index')->with('success', 'Data keluarga berhasil ditambahkan.');
        } catch (\Exception $e) {
            // 7. Rollback jika ada error
            DB::rollBack();
            Log::error('Error storing data warga: ' . $e->getMessage());

            return redirect()->route('data_warga.tambah')
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Menampilkan form untuk mengedit data
     */
    public function edit(DataKeluarga $dataKeluarga)
    {
        // $dataKeluarga sudah otomatis didapatkan dari Route Model Binding
        // Kita load relasinya agar bisa ditampilkan di form edit
        $dataKeluarga->load('anggotaKeluarga', 'blok', 'desil');

        return view('__simulasi_aja__.data_warga.form_edit', compact('dataKeluarga'));
    }

    /**
     * Memproses data update
     */
    public function update(Request $request, DataKeluarga $dataKeluarga)
    {
        // 1. Validasi Data (Mirip 'store', tapi dengan 'ignore' untuk data unik)
        $validator = Validator::make($request->all(), [
            'no_kk' => [
                'required',
                'int', // Sesuai 'store' Anda
                Rule::unique('data_keluarga', 'no_kk')->ignore($dataKeluarga->id_keluarga, 'id_keluarga')
            ],
            'blok' => 'required|string|max:100',
            'desil' => 'required|int', // Sesuai 'store' Anda
            'anggota_keluarga' => 'required|array|min:1',
            'anggota_keluarga.*.nik' => [
                'required',
                'string', // Sesuai 'store' Anda
                'max:255',
                // Pastikan NIK unik, tapi abaikan NIK milik anggota dari keluarga ini
                // (Karena kita akan hapus-dan-buat-ulang)
                Rule::unique('data_anggota_keluarga', 'nik_anggota')->where(function ($query) use ($dataKeluarga) {
                    return $query->where('id_keluarga', '!=', $dataKeluarga->id_keluarga);
                })
            ],
            'anggota_keluarga.*.nama' => 'required|string|max:255',
            'anggota_keluarga.*.tempat_lahir' => 'required|string|max:100',
            'anggota_keluarga.*.tanggal_lahir' => 'required|date',
            'anggota_keluarga.*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'anggota_keluarga.*.agama' => 'required|string',
            'anggota_keluarga.*.status_perkawinan' => 'required|string',
            'anggota_keluarga.*.status_dalam_keluarga' => 'required|string',
            'anggota_keluarga.*.pendidikan' => 'required|string',
            'anggota_keluarga.*.pekerjaan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('data_warga.edit', $dataKeluarga->id_keluarga)
                ->withErrors($validator)
                ->withInput();
        }

        // 2. Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // 3. Handle Blok dan Desil (Logika sama seperti 'store')
            $blok = Blok::firstOrCreate(
                ['nama_blok' => $request->blok],
            );
            $desil = Desil::firstOrCreate(
                ['tingkat_desil' => $request->desil]
            );

            // 4. Update DataKeluarga
            $dataKeluarga->update([
                'no_kk' => $request->no_kk,
                'id_blok' => $blok->id_blok,
                'id_desil' => $desil->id_desil,
            ]);

            // 5. Update AnggotaKeluarga (Metode: Hapus & Buat Ulang)
            // Ini adalah cara paling sederhana untuk form dinamis
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

            return redirect()->route('data_warga.index')->with('success', 'Data keluarga berhasil diperbarui.');
        } catch (\Exception $e) {
            // 7. Rollback jika ada error
            DB::rollBack();
            Log::error('Error updating data warga: ' . $e->getMessage());

            return redirect()->route('data_warga.edit', $dataKeluarga->id_keluarga)
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }
}
