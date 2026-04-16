{{--
=============================================================================
HALAMAN: DASHBOARD ADMIN
=============================================================================
Fungsi: Menampilkan halaman utama admin dengan statistik dan menu navigasi
Route: dashboard.admin (GET) atau /dashboard-admin
Controller: AdminController@index

DEPENDENSI:
- Layout: layouts.app (harus sudah didefinisikan)
- CSS: Bootstrap 5, Bootstrap Icons
- JavaScript: Bootstrap JS (opsional, untuk interaksi)
- Data: variabel dari controller (totalPengguna, totalAduan, menunggu, diproses, selesai)

STRUKTUR HALAMAN:
1. Banner selamat datang admin (gradasi maroon)
2. Card statistik (5 card: Total Pengguna, Total Laporan, Menunggu, Diproses, Selesai)
3. Menu utama (4 menu: Kelola Aspirasi, Riwayat, Data Kategori, Data Lokasi)

CARA KERJA:
- Semua data statistik dihitung di controller (AdminController@index)
- Menu menggunakan link ke route masing-masing
- Desain responsif dengan Bootstrap grid system
--}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- ==========================================
         BANNER SELAMAT DATANG ADMIN
         ========================================== 
         Warna: Gradasi linear dari #800000 (maroon) ke #330000
         Efek: border-radius 20px, teks putih
         
         CATATAN: 
         - Banner ini hanya tampilan, tidak ada fungsi interaktif
         - Bisa ditambahkan sapaan berdasarkan waktu (pagi/siang/sore) jika diperlukan
    --}}    
    <div class="card border-0 shadow-sm mb-5 text-white" 
         style="background: linear-gradient(135deg, #800000 0%, #330000 100%); border-radius: 20px;">
        <div class="card-body p-4 p-md-5">
            <h1 class="fw-bold mb-2">Panel Petugas Sarpras 🛠️</h1>
            <p class="lead mb-0 opacity-75">Selamat bertugas, Admin. Mari kita selesaikan keluhan warga sekolah hari ini.</p>
        </div>
    </div>

    {{-- ==========================================
         STATISTIK CARD (5 CARD)
         ========================================== 
         Menampilkan ringkasan data dalam bentuk card dengan warna border berbeda
         
         URUTAN CARD:
         1. TOTAL PENGGUNA    - Border bottom #800000 (maroon)
         2. TOTAL LAPORAN     - Border bottom #2d3436 (dark gray)
         3. MENUNGGU          - Border bottom #6c757d (gray)
         4. DI PROSES         - Border bottom #ffc107 (yellow)
         5. SELESAI           - Border bottom #198754 (green)
         
         DATA DARI CONTROLLER:
         - $totalPengguna : jumlah seluruh siswa (DB::table('siswa')->count())
         - $totalAduan    : jumlah seluruh aspirasi (DB::table('aspirasi')->count())
         - $menunggu      : aspirasi status 'Menunggu'
         - $diproses      : aspirasi status 'Proses'  
         - $selesai       : aspirasi status 'Selesai'
    --}}    
    <div class="row mb-5 g-4 justify-content-center">
        {{-- CARD 1: TOTAL PENGGUNA --}}
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #800000 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">TOTAL PENGGUNA</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #800000;">{{ $totalPengguna }}</h1>
                </div>
            </div>
        </div>
        {{-- CARD 2: TOTAL LAPORAN --}}
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #2d3436 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">TOTAL LAPORAN</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #2d3436;">{{ $totalAduan }}</h1>
                </div>
            </div>
        </div>
        {{-- CARD 3: MENUNGGU --}}
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #6c757d !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">MENUNGGU</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #6c757d;">{{ $menunggu }}</h1>
                </div>
            </div>
        </div>
        {{-- CARD 4: DI PROSES --}}
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #ffc107 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">DI PROSES</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #ffc107;">{{ $diproses }}</h1>
                </div>
            </div>
        </div>
        {{-- CARD 5: SELESAI --}}
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">SELESAI</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #198754;">{{ $selesai }}</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================
         MENU UTAMA ADMIN (4 MENU)
         ========================================== 
         Menu berbentuk card dengan icon dan deskripsi
         Semua menu adalah link ke halaman terkait
         
         DAFTAR MENU:
         1. Kelola Aspirasi → route('admin.kelola')
            - Icon: bi-megaphone-fill
            - Fungsi: Menampilkan dan menanggapi laporan
         
         2. Riwayat → route('admin.history')
            - Icon: bi-clock-history
            - Fungsi: Melihat riwayat semua laporan yang sudah ditanggapi
         
         3. Data Kategori → route('admin.kategori')
            - Icon: bi-tags-fill
            - Fungsi: CRUD kategori aspirasi
         
         4. Data Lokasi → route('admin.lokasi')
            - Icon: bi-geo-fill
            - Fungsi: CRUD lokasi/gedung
         
         EFEK HOVER:
         - Card naik ke atas (translateY(-10px))
         - Border berubah warna menjadi maroon
         - Box shadow lebih tebal
    --}}
    <div class="row justify-content-center g-4">
        {{-- MENU 1: KELOLA ASPIRASI --}}
        <div class="col-md-3">
            <a href="{{ route('admin.kelola') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(128, 0, 0, 0.1); color: #800000;">
                    <i class="bi bi-megaphone-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Kelola Aspirasi</h5>
                <p class="text-muted extra-small">Cek & Tanggapi</p>
            </a>
        </div>
        {{-- MENU 2: RIWAYAT --}}
        <div class="col-md-3">
            <a href="{{ route('admin.history') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(0, 0, 0, 0.1); color: #333;">
                    <i class="bi bi-clock-history fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Riwayat</h5>
                <p class="text-muted extra-small">Rekap Semua Data</p>
            </a>
        </div>
        {{-- MENU 3: DATA KATEGORI --}}
        <div class="col-md-3">
            <a href="{{ route('admin.kategori') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                    <i class="bi bi-tags-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Data Kategori</h5>
                <p class="text-muted extra-small">Atur Kategori</p>
            </a>
        </div>
        {{-- MENU 4: DATA LOKASI --}}
        <div class="col-md-3">
            <a href="{{ route('admin.lokasi') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                    <i class="bi bi-geo-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Data Lokasi</h5>
                <p class="text-muted extra-small">Atur Gedung/Ruangan</p>
            </a>
        </div>
    </div>
</div>

<style>
    .display-5 { font-size: 2.5rem; font-weight: 800; }
    .extra-small { font-size: 0.75rem; }
    .btn-menu-admin { border-radius: 20px; transition: 0.3s; border: 2px solid transparent !important; display: flex !important; }
    .btn-menu-admin:hover { transform: translateY(-10px); border-color: #800000 !important; box-shadow: 0 15px 30px rgba(128, 0, 0, 0.15) !important; }
    .icon-circle { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
</style>
@endsection