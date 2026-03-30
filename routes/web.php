<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaDashboardController;

Route::get('/', function () {
    return view('welcome');
});

// LOGIN & REGISTER 
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'prosesRegister']);

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD SISWA
Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])->name('dashboard.siswa');

// HALAMAN FORM 
Route::get('/buat-aduan', [SiswaDashboardController::class, 'create'])->name('laporan.create');

// PROSES SIMPAN 
Route::post('/laporan/store', [SiswaDashboardController::class, 'store'])->name('laporan.store');

// HISTORY
Route::get('/history-siswa', [SiswaDashboardController::class, 'history'])->name('siswa.history');