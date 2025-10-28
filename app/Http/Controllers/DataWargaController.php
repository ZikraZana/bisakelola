<?php

namespace App\Http\Controllers;

use App\Models\DataWarga;
use Illuminate\Http\Request;

class DataWargaController extends Controller
{
    public function index()
    {
        $data_warga = DataWarga::all();
        return view('__simulasi_aja__.data_warga.index', compact('data_warga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|string|max:255',
            'nama_kepala_keluarga' => 'required|string|max:255',
            'nik_kepala_keluarga' => 'required|string|max:255',
        ]);

        DataWarga::create([
            'no_kk' => $request->no_kk,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
            'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
        ]);


        // Redirect kembali ke halaman data warga dengan pesan sukses
        return redirect()->route('data_warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data_warga = DataWarga::findOrFail($id);
        return view('__simulasi_aja__.form_edit.index', compact('data_warga'));
    }

}
