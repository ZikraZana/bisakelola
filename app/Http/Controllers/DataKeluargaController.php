<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataKeluargaController extends Controller
{
    public function index()
    {
        return view('__simulasi_aja__.data_warga.index');
    }


    public function formTambah()
    {
        return view('__simulasi_aja__.data_warga.form_tambah');
    }
}
