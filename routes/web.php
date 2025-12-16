<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataKeluargaController;
use App\Http\Controllers\KelolaBansosController;
use App\Http\Controllers\DataPenerimaBansosController;

//========================= AREA ALL =========================//
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//========================= AREA GUEST =========================//
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/forgot-password', function () {
        return view('auth.forgot_password');
    })->name('forgot.password');
});

//========================= AREA Admins =========================//
Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


    // Data Warga
    Route::prefix('data-warga')->name('data-warga.')->group(function () {
        // GET /data-warga (Menampilkan semua data)
        Route::get('/', [DataKeluargaController::class, 'index'])->name('index');
        Route::get('/tambah-data', [DataKeluargaController::class, 'formTambah'])->name('formTambah')->middleware('role:Ketua RT,Ketua Blok');
        Route::post('/', [DataKeluargaController::class, 'store'])->name('store');
        Route::get('/{dataKeluarga:id_keluarga}/edit', [DataKeluargaController::class, 'formEdit'])->name('formEdit');
        Route::put('/{dataKeluarga:id_keluarga}', [DataKeluargaController::class, 'update'])->name('update');
        Route::put('/{dataKeluarga:id_keluarga}/status', [DataKeluargaController::class, 'status'])->name('status');
    });

    // Data Penerima Bansos
    Route::prefix('data-penerima-bansos')->name('data-penerima-bansos.')->middleware('role:Ketua RT,Ketua Blok')->group(function () {
        Route::get('/', [DataPenerimaBansosController::class, 'index'])->name('index');

        // 2. Tampilkan form tambah data
        Route::get('/tambah-data', [DataPenerimaBansosController::class, 'formTambah'])->name('formTambah')->middleware('role:Ketua RT');

        // 3. Simpan data baru (dari form dinamis)
        Route::post('/', [DataPenerimaBansosController::class, 'store'])->name('store');

        // 4. Tampilkan form edit (nanti kita buat)
        // Menggunakan route model binding dengan PK 'id_penerima_bansos'
        Route::get('/{dataPenerimaBansos:id_penerima_bansos}/edit', [DataPenerimaBansosController::class, 'formEdit'])->name('formEdit');

        // 5. Update data (nanti kita buat)
        Route::put('/{dataPenerimaBansos:id_penerima_bansos}', [DataPenerimaBansosController::class, 'update'])->name('update');

        // Catatan: Anda juga perlu route untuk Dinsos (Persetujuan),
        // tapi kita bisa tambahkan itu di grup terpisah nanti.

    });

    // Akun Admin
    Route::prefix('akun-admin')->name('akun-admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/tambah-admin', [AdminController::class, 'formTambah'])->name('formTambah')->middleware('role:Ketua RT');
        Route::post('/', [AdminController::class, 'store'])->name('store')->middleware('role:Ketua RT');
        Route::get('/{admin:id_admin}/edit', [AdminController::class, 'formEdit'])->name('formEdit');
        Route::put('/{admin:id_admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin:id_admin}', [AdminController::class, 'destroy'])->name('destroy');
    });


    // 2. AREA DINSOS (HANYA KETUA RT)
    Route::prefix('kelola-bansos')
        ->name('kelola-bansos.')
        ->middleware('role:Ketua RT') // <-- Proteksi Keras di sini
        ->group(function () {
            Route::get('/', [KelolaBansosController::class, 'index'])->name('index');
            Route::get('/{id}/proses', [KelolaBansosController::class, 'edit'])->name('edit'); // Form Keputusan
            Route::put('/{id}', [KelolaBansosController::class, 'update'])->name('update'); // Simpan Keputusan

            Route::patch('/{id}/penyaluran', [KelolaBansosController::class, 'updatePenyaluran'])->name('updatePenyaluran');
        });
});
