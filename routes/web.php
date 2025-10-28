<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

//========================= AREA ALL =========================//
Route::get('/', function () {
    return view('welcome');
});

//========================= AREA GUEST =========================//
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
});

//========================= AREA Admins =========================//
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    

    //=============== Dibawah Ini Simulasi Aja, JANGAN DIHAPUS ===============//

    // Route::get('/data_warga', [App\Http\Controllers\DataWargaController::class, 'index'])->name('data_warga.index');

    // Route::get('/form_tambah', function () {
    //     return view('__simulasi_aja__.form_tambah.index');
    // })->name('form_tambah.index');

    // Route::post('/data_warga/store', [App\Http\Controllers\DataWargaController::class, 'store'])->name('data_warga.store');
    // Route::post('/data_warga/edit/{id}', [App\Http\Controllers\DataWargaController::class, 'edit'])->name('data_warga.edit');
    // Route::post('/data_warga/update/{id}', [App\Http\Controllers\DataWargaController::class, 'update'])->name('data_warga.update');

});
