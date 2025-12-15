<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\DataPenerimaBansos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $totalWarga = AnggotaKeluarga::count();
        $totalPenerima = DataPenerimaBansos::where('status_acc', 'Disetujui')->count();

        $bansosDist = DataPenerimaBansos::where('status_acc', 'Disetujui')
        ->join('bansos', 'data_penerima_bansos.id_bansos', '=', 'bansos.id_bansos')
        ->select('bansos.nama_bansos', DB::raw('count(*) as total'))
        ->groupBy('bansos.nama_bansos')
        ->pluck('total', 'bansos.nama_bansos');

        return view('welcome', compact('totalWarga', 'totalPenerima', 'bansosDist'));
    }
}
