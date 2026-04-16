<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AdminController;

Route::get('/', function () { return view('welcome'); });

// AUTHENTICATION
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'prosesRegister']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// FITUR SISWA
Route::get('/dashboard-siswa', [SiswaDashboardController::class, 'index'])->name('dashboard.siswa');
Route::get('/buat-aduan', [SiswaDashboardController::class, 'create'])->name('laporan.create');
Route::post('/laporan/store', [SiswaDashboardController::class, 'store'])->name('laporan.store');
Route::get('/history-siswa', [SiswaDashboardController::class, 'history'])->name('siswa.history');
Route::post('/laporan/update/{id}', [SiswaDashboardController::class, 'update'])->name('laporan.update');
Route::post('/laporan/hapus/{id}', [SiswaDashboardController::class, 'destroy'])->name('laporan.destroy');

// FITUR ADMIN
Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('dashboard.admin');
Route::get('/kelola-aspirasi', [AdminController::class, 'kelola'])->name('admin.kelola');
Route::post('/tanggapi-aspirasi/{id}', [AdminController::class, 'tanggapi'])->name('admin.tanggapi');
Route::get('/history-admin', [AdminController::class, 'history'])->name('admin.history');

// --- CRUD KATEGORI ---
Route::get('/kelola-kategori', [AdminController::class, 'kategori'])->name('admin.kategori');
Route::post('/kategori/store', [AdminController::class, 'kategoriStore'])->name('admin.kategori.store');
Route::post('/kategori/update/{id}', [AdminController::class, 'kategoriUpdate'])->name('admin.kategori.update');
Route::post('/kategori/hapus/{id}', [AdminController::class, 'kategoriDestroy'])->name('admin.kategori.destroy');

// --- CRUD LOKASI ---
Route::get('/kelola-lokasi', [AdminController::class, 'lokasi'])->name('admin.lokasi');
Route::post('/lokasi/store', [AdminController::class, 'lokasiStore'])->name('admin.lokasi.store');
Route::post('/lokasi/update/{id}', [AdminController::class, 'lokasiUpdate'])->name('admin.lokasi.update');
Route::post('/lokasi/hapus/{id}', [AdminController::class, 'lokasiDestroy'])->name('admin.lokasi.destroy');