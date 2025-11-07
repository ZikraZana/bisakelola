<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin; // Use the Admin model
// use App\Models\Blok; // Tidak perlu import model untuk validasi 'exists'
// use App\Models\Bagian; // Tidak perlu import model untuk validasi 'exists'

class AdminController extends Controller
{
    public function index()
    {
        return view('akun-admin.index');
    }

    public function formTambah()
    {
        // Anda mungkin perlu mengirim data 'bloks' dan 'bagians' ke view
        // $bloks = Blok::all();
        // $bagians = Bagian::all();
        // return view('akun-admin.form-tambah', compact('bloks', 'bagians'));

        // Untuk saat ini, kita biarkan view menggunakan data dummy
        return view('akun-admin.form-tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admins', // TAMBAH INI: unique
            'password' => 'required|string|min:8|confirmed', // UBAH INI: Tambah min:8
            'password_confirmation' => 'required|string|min:8', // TAMBAH INI
            'nama_lengkap' => 'required|string|max:255',
            'no_handphone' => 'nullable|string|max:20',
            'role' => 'required|in:Ketua Blok,Ketua Bagian', // UBAH INI: lebih spesifik

            // UBAH INI: Validasi dinamis di backend
            'blok' => 'nullable|required_if:role,ketua_blok|string|exists:blok,nama_blok', // Asumsi kolom di tabel 'bloks' adalah 'nama_blok'
            'bagian' => 'nullable|string', // Asumsi tabel 'bagians' & kolom 'id'

        ], [
            // Pesan Error Username
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username tidak boleh lebih dari :max karakter.',
            'username.unique' => 'Username ini sudah digunakan.', // TAMBAH INI

            // Pesan Error Password
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.', // TAMBAH INI
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            // TAMBAH INI: Pesan Error Konfirmasi Password
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.min' => 'Konfirmasi password minimal 8 karakter.',

            // Pesan Error Nama Lengkap
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari :max karakter.',

            // Pesan Error No Handphone
            'no_handphone.string' => 'Nomor handphone harus berupa teks.',
            'no_handphone.max' => 'Nomor handphone tidak boleh lebih dari :max karakter.',

            // Pesan Error Role
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',

            // UBAH INI: Pesan Error untuk Blok dan Bagian
            'blok.required_if' => 'Blok wajib dipilih untuk Ketua Blok.',
            'blok.exists' => 'Blok yang dipilih tidak valid.',

            'bagian.required_if' => 'Bagian wajib dipilih untuk Ketua Bagian.',
            'bagian.string' => 'Bagian harus berupa teks.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Admin::create([ // Use Admin model for creation
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'no_handphone' => $request->no_handphone,
            'role' => $request->role,
            'blok' => $request->blok, // Ini sudah benar
            'bagian' => $request->bagian, // Ini sudah benar
        ]);

        return redirect()->route('akun-admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }
}
