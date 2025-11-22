<?php

namespace App\Http\Controllers;

use App\Models\DataPenerimaBansos;
use App\Models\Bansos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelolaBansosController extends Controller
{
    /**
     * Dashboard Dinsos: Melihat semua pengajuan masuk
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search_query');
        $filterStatus = $request->input('filter_status');

        // Eager load relasi yang dibutuhkan
        $query = DataPenerimaBansos::with([
            'keluarga.anggotaKeluarga',
            'keluarga.blok',
            'adminPengaju',
            'bansos'
        ]);

        // 1. Filter Status
        if ($filterStatus) {
            $query->where('status_acc', $filterStatus);
        }

        // 2. Pencarian
        if ($searchQuery) {
            $query->whereHas('keluarga', function ($q) use ($searchQuery) {
                $q->where('no_kk', 'like', "%{$searchQuery}%")
                    ->orWhereHas('anggotaKeluarga', function ($subQ) use ($searchQuery) {
                        $subQ->where('nama_lengkap', 'like', "%{$searchQuery}%")
                            ->orWhere('nik_anggota', 'like', "%{$searchQuery}%");
                    });
            });
        }

        // Data Tabel (Pagination)
        $dataPengajuan = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Hitung Statistik (Untuk 4 Card di atas)
        // Kita clone query agar tidak terganggu filter pagination, tapi tetap kena filter search jika mau (opsional)
        // Di sini saya hitung total global (tanpa filter) agar angka statistiknya tetap utuh
        $statsQuery = DataPenerimaBansos::query();
        $stats = [
            'total' => $statsQuery->count(),
            'diajukan' => (clone $statsQuery)->where('status_acc', 'Diajukan')->count(),
            'disetujui' => (clone $statsQuery)->where('status_acc', 'Disetujui')->count(),
            'ditolak' => (clone $statsQuery)->where('status_acc', 'Ditolak')->count(),
        ];

        return view('kelola-bansos.index', compact('dataPengajuan', 'stats', 'searchQuery', 'filterStatus'));
    }

    /**
     * Menampilkan Form Keputusan (Approval)
     */
    public function edit($id)
    {
        $pengajuan = DataPenerimaBansos::with([
            'keluarga.anggotaKeluarga',
            'keluarga.blok',
            'keluarga.desil',
            'adminPengaju'
        ])->findOrFail($id);

        $masterBansos = Bansos::all();

        return view('kelola-bansos.form-edit', compact('pengajuan', 'masterBansos'));
    }

    /**
     * Menyimpan Keputusan (Approve/Reject)
     */
    public function update(Request $request, $id)
    {
        $pengajuan = DataPenerimaBansos::findOrFail($id);

        $request->validate([
            'status_acc' => 'required|in:Diajukan,Disetujui,Ditolak',
            'id_bansos' => 'required_if:status_acc,Disetujui|nullable|exists:bansos,id_bansos',
            'periode' => 'required_if:status_acc,Disetujui|nullable|string',
            'keterangan_acc' => 'nullable|string|max:1000',
            'status_bansos_diterima' => 'required|in:Belum,Sudah Diterima',
        ], [
            'id_bansos.required_if' => 'Mohon pilih Jenis Bansos jika statusnya Disetujui.',
            'periode.required_if' => 'Mohon isi Periode jika statusnya Disetujui.',
        ]);

        $pengajuan->update([
            'id_admin_penyetuju' => Auth::user()->id_admin,
            'status_acc' => $request->status_acc,
            'id_bansos' => ($request->status_acc == 'Disetujui') ? $request->id_bansos : null,
            'periode' => ($request->status_acc == 'Disetujui') ? $request->periode : null,
            'keterangan_acc' => $request->keterangan_acc,
            'status_bansos_diterima' => $request->status_bansos_diterima,
            'tanggal_pengambilan_bansos' => ($request->status_bansos_diterima == 'Sudah Diterima') ? now() : null,
        ]);

        return redirect()->route('kelola-bansos.index')
            ->with('success', 'Keputusan pengajuan berhasil disimpan.');
    }

    /**
     * Update KHUSUS Status Penyaluran (Via Modal di Index)
     */
    public function updatePenyaluran(Request $request, $id)
    {
        $pengajuan = DataPenerimaBansos::findOrFail($id);

        // Validasi sederhana
        $request->validate([
            'status_bansos_diterima' => 'required|in:Belum,Sudah Diterima',
        ]);

        // Update hanya kolom terkait penyaluran
        $pengajuan->update([
            'status_bansos_diterima' => $request->status_bansos_diterima,
            // Jika diubah jadi 'Sudah', isi tanggal hari ini. Jika 'Belum', kosongkan tanggal.
            'tanggal_pengambilan_bansos' => ($request->status_bansos_diterima == 'Sudah Diterima') ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status penyaluran berhasil diperbarui.');
    }

}
