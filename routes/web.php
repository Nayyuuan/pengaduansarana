<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AdminController; // Tambahkan ini ya!

// ==========================================
// LANDING PAGE (HALAMAN UTAMA)
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// AUTHENTICATION (LOGIN, REGISTER, LOGOUT)
// ==========================================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin']);

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'prosesRegister']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// FITUR SISWA (DASHBOARD, LAPOR, HISTORY)
// ==========================================
Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])->name('dashboard.siswa');

// Halaman Form Buat Aduan
Route::get('/buat-aduan', [SiswaDashboardController::class, 'create'])->name('laporan.create');

// Proses Simpan Aduan (Ke 3 Tabel & Upload Foto)
Route::post('/laporan/store', [SiswaDashboardController::class, 'store'])->name('laporan.store');

// Halaman Riwayat Laporan Siswa
Route::get('/history-siswa', [SiswaDashboardController::class, 'history'])->name('siswa.history');

// ==========================================
// FITUR ADMIN (KELOLA LAPORAN)
// ==========================================
// Dashboard Utama Admin
Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('dashboard.admin');

// Halaman List Semua Laporan untuk Admin
Route::get('/kelola-aspirasi', [AdminController::class, 'kelola'])->name('admin.kelola');

// Proses Update Status & Feedback dari Admin
Route::post('/tanggapi-aspirasi/{id}', [AdminController::class, 'tanggapi'])->name('admin.tanggapi');
// Hitori Admin
Route::get('/history-admin', [AdminController::class, 'history'])->name('admin.history');