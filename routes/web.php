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
});
