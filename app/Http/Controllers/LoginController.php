<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_page');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Tangkap nilai checkbox 'remember' (True/False)
        // name="remember" harus sesuai dengan yang ada di login_page.blade.php
        $remember = $request->boolean('remember');

        // 3. Proses Login dengan Remember Me
        // Tambahkan variabel $remember sebagai argumen kedua
        // Gunakan guard('admin') agar spesifik ke tabel admins
        if (Auth::guard('admin')->attempt($credentials, $remember)) {

            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('success', 'Login berhasil! Selamat datang, ' . Auth::guard('admin')->user()->nama_lengkap . '!');
        }

        // 4. Jika Gagal
        return back()->with('error', 'Username atau Password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
