<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
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

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');


    // Data Warga
    Route::prefix('data-warga')->name('data-warga.')->group(function () {
        // GET /data-warga (Menampilkan semua data)
        Route::get('/', [DataKeluargaController::class, 'index'])->name('index');
        Route::get('/tambah-data', [DataKeluargaController::class, 'formTambah'])->name('formTambah')->middleware('role:Ketua RT, Ketua Blok');
        Route::post('/', [DataKeluargaController::class, 'store'])->name('store');
        Route::get('/{dataKeluarga:id_keluarga}/edit', [DataKeluargaController::class, 'formEdit'])->name('formEdit');
        Route::put('/{dataKeluarga:id_keluarga}', [DataKeluargaController::class, 'update'])->name('update');
    });

    // Akun Admin
    Route::prefix('akun-admin')->name('akun-admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/tambah-admin', [AdminController::class, 'formTambah'])->name('formTambah')->middleware('role:Ketua RT');
        Route::post('/', [AdminController::class, 'store'])->name('store')->middleware('role:Ketua RT');
        Route::get('/{admin:id}/edit', [AdminController::class, 'formEdit'])->name('formEdit');
        Route::put('/{admin:id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin:id}', [AdminController::class, 'destroy'])->name('destroy');
    });
});
