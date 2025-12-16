<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use App\Models\DataPenerimaBansos;
use App\Models\Bansos;
use App\Models\Admin; // Pastikan nama Model Admin Anda benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;

class DataPenerimaBansosController extends Controller
{
    /**
     * Tampilkan halaman index (tabel)
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search_query');
        $filterStatusAcc = $request->input('filter_status_acc');
        $filterStatusTerima = $request->input('filter_status_terima');
        $perPage = $request->input('per_page', 10);
        $user = Auth::user();

        $query = DataPenerimaBansos::with([
            'keluarga.anggotaKeluarga',
            'keluarga.blok',
            'keluarga.desil',
            'bansos',
            'adminPengaju'
        ]);

        if ($filterStatusAcc) {
            $query->where('status_acc', $filterStatusAcc);
        }
        if ($filterStatusTerima) {
            $query->where('status_bansos_diterima', $filterStatusTerima);
        }

        // PENCARIAN
        if ($searchQuery) {
            $query->whereHas('keluarga', function ($q) use ($searchQuery) {
                $q->where('no_kk', 'like', "%{$searchQuery}%")
                    ->orWhereHas('anggotaKeluarga', function ($subQ) use ($searchQuery) {
                        $subQ->where('nama_lengkap', 'like', "%{$searchQuery}%")
                            ->orWhere('nik_anggota', 'like', "%{$searchQuery}%");
                    });
            });
        }

        $dataPenerima = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // STATISTIK
        $statsQuery = DataPenerimaBansos::query();
        if ($user->role !== 'Ketua RT') {
            $statsQuery->where('id_admin_pengaju', $user->id_admin);
        }
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'diajukan' => (clone $statsQuery)->where('status_acc', 'Diajukan')->count(),
            'disetujui' => (clone $statsQuery)->where('status_acc', 'Disetujui')->count(),
            'ditolak' => (clone $statsQuery)->where('status_acc', 'Ditolak')->count(),
        ];

        return view('data-penerima-bansos.index', compact(
            'dataPenerima',
            'stats',
            'searchQuery',
            'filterStatusAcc',
            'filterStatusTerima',
            'perPage'
        ));
    }

    /**
     * Tampilkan form tambah data
     */
    public function formTambah()
    {
        // 1. Ambil SEMUA data warga aktif + relasinya
        $rawWarga = DataKeluarga::with(['anggotaKeluarga', 'blok', 'desil'])
            ->where('status', 1)
            ->whereDoesntHave('penerimaBansos', function ($query) {
                $query->whereIn('status_acc', ['Diajukan', 'Disetujui']);
            })
            ->get();

        // 2. Mapping data agar formatnya rapi untuk JSON (Frontend)
        // Kita pre-process di sini agar Alpine tidak berat melakukan loop cari kepala keluarga
        $dataWarga = $rawWarga->map(function ($item) {
            $kepala = $item->anggotaKeluarga->firstWhere('status_dalam_keluarga', 'Kepala Keluarga');

            return [
                'no_kk' => $item->no_kk,
                'nama_kepala' => $kepala ? $kepala->nama_lengkap : 'Tidak Ada Data',
                'nik_kepala' => $kepala ? $kepala->nik_anggota : '-',
                'blok' => $item->blok ? $item->blok->nama_blok : '-',
                'desil' => $item->desil ? $item->desil->tingkat_desil : null,
                // Kolom pencarian gabungan (biar search di Alpine gampang)
                'searchable' => strtolower($item->no_kk . ' ' . ($kepala->nama_lengkap ?? '')),
            ];
        });

        // 3. Kirim ke view
        return view('data-penerima-bansos.form-tambah', compact('dataWarga'));
    }

    /**
     * Simpan data baru dari form dinamis
     */
    public function store(Request $request)
    {
        // 1. VALIDASI
        $rules = [
            'pengajuan' => 'required|array|min:1',
            'pengajuan.*.no_kk' => 'required|numeric|exists:data_keluarga,no_kk',
            'pengajuan.*.keterangan_pengajuan' => 'required|string|max:1000',
        ];

        // Pesan validasi gagal
        $messages = [
            'pengajuan.required' => 'Terjadi kesalahan, tidak ada data pengajuan yang dikirim.',
            'pengajuan.array' => 'Format data pengajuan tidak valid.',
            'pengajuan.min' => 'Anda harus mengisi minimal satu baris pengajuan.',

            'pengajuan.*.no_kk.required' => 'No. KK wajib diisi.',
            'pengajuan.*.no_kk.numeric' => 'No. KK harus berupa angka.',
            'pengajuan.*.no_kk.exists' => 'No. KK tidak terdaftar di Data Warga.',

            'pengajuan.*.keterangan_pengajuan.required' => 'Keterangan wajib diisi.',
            'pengajuan.*.keterangan_pengajuan.string' => 'Keterangan harus berupa teks.',
            'pengajuan.*.keterangan_pengajuan.max' => 'Keterangan tidak boleh lebih dari :max karakter.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 2. PROSES DATA
        $idAdminPengaju = Auth::user()->id_admin;
        $dataToInsert = [];
        $now = now();
        $noKkList = [];

        foreach ($request->pengajuan as $data) {

            if (in_array($data['no_kk'], $noKkList)) {
                return redirect()->back()
                    ->with('error', 'No. KK ' . $data['no_kk'] . ' diinput lebih dari satu kali.')
                    ->withInput();
            }
            $noKkList[] = $data['no_kk'];

            $keluarga = DataKeluarga::where('no_kk', $data['no_kk'])->first(['id_keluarga']);

            $dataToInsert[] = [
                'id_keluarga' => $keluarga->id_keluarga,
                'keterangan_pengajuan' => $data['keterangan_pengajuan'],
                'id_admin_pengaju' => $idAdminPengaju,
                'status_acc' => 'Diajukan',
                'status_bansos_diterima' => 'Belum',
                'id_bansos' => null,
                'periode' => null,
                'id_admin_penyetuju' => null,
                'keterangan_acc' => null,
                'tanggal_pengambilan_bansos' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. SIMPAN KE DATABASE
        DB::beginTransaction();
        try {
            DataPenerimaBansos::insert($dataToInsert);
            DB::commit();

            // ==========================================================
            // DIPERBAIKI: Menggunakan nama route yang benar
            // ==========================================================
            return redirect()->route('data-penerima-bansos.index')
                ->with('success', count($dataToInsert) . ' data pengajuan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing data bansos: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data pengajuan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan form untuk mengedit data pengajuan
     */
    public function formEdit(DataPenerimaBansos $dataPenerimaBansos)
    {
        // TODO: Buat view 'penerima-bansos.form-edit'
        return view('data-penerima-bansos.form-edit', compact('dataPenerimaBansos'));
    }

    /**
     * Update data pengajuan di database
     */
    public function update(Request $request, DataPenerimaBansos $dataPenerimaBansos)
    {
        // TODO: Tambahkan logika validasi dan update

        // ==========================================================
        // DIPERBAIKI: Menggunakan nama route yang benar
        // ==========================================================
        // return redirect()->route('data-penerima-bansos.index')
        //                  ->with('success', 'Data pengajuan berhasil di-update.');

        dd($request->all(), $dataPenerimaBansos);
    }
}
