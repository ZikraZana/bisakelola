<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataKeluargaController;

//========================= AREA ALL =========================//
Route::get('/', function () {
    return view('welcome');
});

//========================= AREA GUEST =========================//
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

//========================= AREA Admins =========================//
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    Route::get('/data_warga', function () {
        return view('__simulasi_aja__.data_warga.index');
    })->name('data_warga.index');

    Route::get('/data_warga/tambah_data', [DataKeluargaController::class, 'formTambah'])->name('data_warga.tambah');
    
});
