<?php

namespace App\Http\Controllers;

use App\Models\Blok;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin; // Use the Admin model
// use App\Models\Blok; // Tidak perlu import model untuk validasi 'exists'
// use App\Models\Bagian; // Tidak perlu import model untuk validasi 'exists'

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        $bloks = Blok::all();
        return view('akun-admin.index', compact('admins', 'bloks'));
    }

    public function formTambah()
    {
        // Anda mungkin perlu mengirim data 'bloks' dan 'bagians' ke view
        $bloks = Blok::all();
        // $bagians = Bagian::all();
        // return view('akun-admin.form-tambah', compact('bloks', 'bagians'));

        // Untuk saat ini, kita biarkan view menggunakan data dummy
        return view('akun-admin.form-tambah', compact('bloks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'nama_lengkap' => 'required|string|max:255',
            'no_handphone' => 'nullable|string|max:20',
            'role' => 'required|in:Ketua Blok,Ketua Bagian',

            'id_blok' => 'nullable|required_if:role,Ketua Blok|integer|exists:blok,id_blok',
            'bagian' => 'nullable|string',

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
            'id_blok.required_if' => 'Blok wajib dipilih untuk Ketua Blok.',
            'id_blok.exists' => 'Blok yang dipilih tidak valid.',
            'id_blok.integer' => 'Blok yang dipilih tidak valid.',

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
            'id_blok' => $request->id_blok,
            'bagian' => $request->bagian, // Ini sudah benar
        ]);

        return redirect()->route('akun-admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function formEdit(Admin $admin)
    {
        // $admin sudah didapat dari Route-Model Binding
        $bloks = Blok::all();

        return view('akun-admin.form-edit', compact('admin', 'bloks'));
    }

    public function update(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],

            // Password dibuat nullable (opsional)
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',

            'nama_lengkap' => 'required|string|max:255',
            'no_handphone' => 'nullable|string|max:20',
            'role' => 'required|in:Ketua Blok,Ketua Bagian',
            'id_blok' => 'nullable|required_if:role,Ketua Blok|integer|exists:blok,id_blok',
            'bagian' => 'nullable|string',
        ], [

            // Pesan Error Username
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username tidak boleh lebih dari :max karakter.',
            'username.unique' => 'Username ini sudah digunakan.',

            // Pesan Error Password
            'password.nullable' => 'Password boleh kosong jika tidak ingin diubah.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            // Pesan Error Konfirmasi Password
            'password_confirmation.nullable' => 'Konfirmasi password boleh kosong jika tidak ingin diubah.',
            'password_confirmation.string' => 'Konfirmasi password harus berupa teks.',
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

            // Pesan Error untuk Blok
            'id_blok.required_if' => 'Blok wajib dipilih untuk Ketua Blok.',
            'id_blok.exists' => 'Blok yang dipilih tidak valid.',
            'id_blok.integer' => 'Blok yang dipilih tidak valid.',

            // Pesan Error untuk Bagian
            'bagian.nullable' => 'Bagian boleh kosong jika tidak ingin diubah.',
            'bagian.string' => 'Bagian harus berupa teks.',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil semua data yang tervalidasi
        $data = $validator->validated();

        // Cek apakah password diisi atau tidak
        if (empty($data['password'])) {
            // Jika kosong, hapus dari array agar tidak meng-update password
            unset($data['password']);
        } else {
            // Jika diisi, hash password baru
            $data['password'] = Hash::make($data['password']);
        }

        // Jika role berubah menjadi 'Ketua Blok', pastikan 'bagian' dihapus
        if ($data['role'] === 'Ketua Blok') {
            $data['bagian'] = null;
        }
        if ($data['role'] === 'Ketua Bagian') {
            $data['id_blok'] = null;
        }

        // Update data admin
        $admin->update($data);

        return redirect()->route('akun-admin.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();
            return redirect()->route('akun-admin.index')->with('success', 'Admin berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('akun-admin.index')->with('error', 'Terjadi kesalahan saat menghapus admin: ' . $e->getMessage());
        }
    }
}
