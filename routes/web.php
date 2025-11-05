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



    //========================= INI AREA SIMULASI BACKEND, JANGAN DIHAPUS ATAUPUN DIUBAH YAAA =========================//
    // Route::prefix('data-warga')->name('data_warga.')->group(function () {
    //     // GET /data-warga (Menampilkan semua data)
    //     Route::get('/', [DataKeluargaController::class, 'index'])->name('index');

    //     // GET /data-warga/tambah (Menampilkan form tambah)
    //     Route::get('/tambah_data', [DataKeluargaController::class, 'formTambah'])->name('tambah');

    //     // POST /data-warga (Menyimpan data baru)
    //     Route::post('/', [DataKeluargaController::class, 'store'])->name('store');

    //     // GET /data-warga/{id_keluarga}/edit (Menampilkan form edit)
    //     Route::get('/{dataKeluarga:id_keluarga}/edit', [DataKeluargaController::class, 'edit'])->name('edit');

    //     // PUT/PATCH /data-warga/{id_keluarga} (Mengupdate data)
    //     Route::put('/{dataKeluarga:id_keluarga}', [DataKeluargaController::class, 'update'])->name('update');

    //     // DELETE /data-warga/{id_keluarga} (Menghapus data)
    //     Route::delete('/{dataKeluarga:id_keluarga}', [DataKeluargaController::class, 'destroy'])->name('destroy');
    // });

    
});
