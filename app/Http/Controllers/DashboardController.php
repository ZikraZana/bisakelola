<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use App\Models\AnggotaKeluarga; // Sesuai nama model Anda
use App\Models\DataPenerimaBansos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. KARTU STATISTIK ---
        $totalKK = DataKeluarga::count();

        $totalWarga = AnggotaKeluarga::count();

        // Hitung penerima bansos (Hanya yang statusnya disetujui)
        $totalPenerimaBansos = DataPenerimaBansos::where('status_acc', 'Disetujui')->count();

        // Hitung antrian pengajuan
        $pendingBansos = DataPenerimaBansos::where('status_acc', 'Diajukan')->count();


        // --- 2. GRAFIK: DISTRIBUSI JENIS BANSOS (DONUT CHART) ---
        // Mengambil data dari tabel 'data_penerima_bansos' join ke 'bansos'
        $bansosDistribution = DataPenerimaBansos::where('status_acc', 'Disetujui')
            ->join('bansos', 'data_penerima_bansos.id_bansos', '=', 'bansos.id_bansos')
            ->select('bansos.nama_bansos', DB::raw('count(*) as total'))
            ->groupBy('bansos.nama_bansos')
            ->pluck('total', 'bansos.nama_bansos');
        // Hasil array: ['PKH' => 5, 'BLT' => 3]


        // --- 3. DATA UNTUK GRAFIK BAR (SEBARAN DESIL - GROUPING) ---

        // A. Ambil data mentah dari database
        $rawDesil = DataKeluarga::join('desil', 'data_keluarga.id_desil', '=', 'desil.id_desil')
            ->select('desil.tingkat_desil', DB::raw('count(*) as total'))
            ->groupBy('desil.tingkat_desil')
            ->pluck('total', 'desil.tingkat_desil');

        // B. Siapkan format final (Inisialisasi 0 agar grafik tetap rapi meski data kosong)
        $desilDistribution = [
            'Desil 1' => 0,
            'Desil 2' => 0,
            'Desil 3' => 0,
            'Desil 4' => 0,
            'Desil 5' => 0,
            'Desil 6+' => 0, // Wadah untuk Desil 6, 7, ..., NULL, atau 0
        ];

        // C. Masukkan data mentah ke wadah yang sesuai
        foreach ($rawDesil as $tingkat => $total) {
            // Jika tingkatnya 1 sampai 5, masukkan ke kuncinya masing-masing
            if ($tingkat >= 1 && $tingkat <= 5) {
                $desilDistribution["Desil $tingkat"] += $total;
            }
            // Sisanya (NULL, 0, 6, 7, dst) masukkan ke 'Desil 6+'
            else {
                $desilDistribution['Desil 6+'] += $total;
            }
        }


        // --- 4. TABEL AKTIVITAS TERBARU ---
        // Mengambil 5 pengajuan bansos terakhir
        $recentActivities = DataPenerimaBansos::with([
            'keluarga.anggotaKeluarga', // Untuk ambil nama kepala keluarga
            'adminPengaju'              // Untuk tahu siapa yang input
        ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalKK',
            'totalWarga',
            'totalPenerimaBansos',
            'pendingBansos',
            'bansosDistribution',
            'desilDistribution',
            'recentActivities'
        ));
    }
}
