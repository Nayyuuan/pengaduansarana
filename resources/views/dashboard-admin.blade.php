@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- Banner Admin --}}
    <div class="card border-0 shadow-sm mb-5 text-white" 
         style="background: linear-gradient(135deg, #800000 0%, #330000 100%); border-radius: 20px;">
        <div class="card-body p-4 p-md-5">
            <h1 class="fw-bold mb-2">Panel Petugas Sarpras 🛠️</h1>
            <p class="lead mb-0 opacity-75">Selamat bertugas, Admin. Mari kita selesaikan keluhan warga sekolah hari ini.</p>
        </div>
    </div>

    {{-- Statistik Card --}}
    <div class="row mb-5 g-4 justify-content-center">
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #800000 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">TOTAL PENGGUNA</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #800000;">{{ $totalPengguna }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #2d3436 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">TOTAL LAPORAN</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #2d3436;">{{ $totalAduan }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #6c757d !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">MENUNGGU</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #6c757d;">{{ $menunggu }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #ffc107 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">DI PROSES</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #ffc107;">{{ $diproses }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 20px; border-bottom: 5px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold small mb-3">SELESAI</h6>
                    <h1 class="fw-bold mb-0 display-5" style="color: #198754;">{{ $selesai }}</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- MENU UTAMA ADMIN (DIPERBARUI JADI 4 KOTAK) --}}
    <div class="row justify-content-center g-4">
        <div class="col-md-3">
            <a href="{{ route('admin.kelola') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(128, 0, 0, 0.1); color: #800000;">
                    <i class="bi bi-megaphone-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Kelola Aspirasi</h5>
                <p class="text-muted extra-small">Cek & Tanggapi</p>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.history') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(0, 0, 0, 0.1); color: #333;">
                    <i class="bi bi-clock-history fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Riwayat</h5>
                <p class="text-muted extra-small">Rekap Semua Data</p>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.kategori') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center d-flex flex-column align-items-center justify-content-center">
                <div class="icon-circle mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                    <i class="bi bi-tags-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Data Kategori</h5>
                <p class="text-muted extra-small">Atur Kategori</p>
            </a>
        </div>
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