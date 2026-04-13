@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- Banner Admin --}}
    <div class="card border-0 shadow-sm mb-5 text-white" 
         style="background: linear-gradient(135deg, #800000 0%, #330000 100%); border-radius: 20px;">
        <div class="card-body p-4 p-md-5">
            <h1 class="fw-bold mb-2">Panel Petugas Sarpras 🛠️</h1>
            <p class="lead mb-0 opacity-75">Selamat bertugas, Admin. Mari kita selesaikan keluhan siswa hari ini.</p>
        </div>
    </div>

    {{-- Statistik Card --}}
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 15px;">
                <h6 class="text-muted small fw-bold">TOTAL SISWA</h6>
                <h2 class="fw-bold" style="color: #800000;">{{ $totalSiswa }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 15px;">
                <h6 class="text-muted small fw-bold text-dark">TOTAL LAPORAN</h6>
                <h2 class="fw-bold text-dark">{{ $totalAduan }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 15px;">
                <h6 class="text-muted small fw-bold text-warning">MENUNGGU</h6>
                <h2 class="fw-bold text-warning">{{ $menunggu }}</h2>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-4 text-center h-100" style="border-radius: 15px;">
                <h6 class="text-muted small fw-bold text-success">SELESAI</h6>
                <h2 class="fw-bold text-success">{{ $selesai }}</h2>
            </div>
        </div>
    </div>

    {{-- MENU UTAMA ADMIN (Dua Tombol Berdampingan) --}}
    <div class="row justify-content-center g-4">
        <div class="col-md-5">
            <a href="{{ route('admin.kelola') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center">
                <div class="icon-circle mb-3 mx-auto" style="background: rgba(128, 0, 0, 0.1); color: #800000;">
                    <i class="bi bi-megaphone-fill fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Kelola Aspirasi</h4>
                <p class="text-muted small">Lihat laporan masuk, ganti status, dan berikan feedback ke siswa.</p>
            </a>
        </div>

        <div class="col-md-5">
            <a href="{{ route('admin.history') }}" class="card border-0 shadow btn-menu-admin p-4 text-decoration-none h-100 text-center">
                <div class="icon-circle mb-3 mx-auto" style="background: rgba(0, 0, 0, 0.1); color: #333;">
                    <i class="bi bi-clock-history fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Riwayat & Laporan</h4>
                <p class="text-muted small">Pantau jejak penanganan aspirasi dan cetak laporan hasil kerja.</p>
            </a>
        </div>
    </div>

</div>

<style>
    .btn-menu-admin {
        border-radius: 20px;
        transition: 0.3s;
        border: 2px solid transparent !important;
    }
    .btn-menu-admin:hover {
        transform: translateY(-10px);
        border-color: #800000 !important;
        box-shadow: 0 15px 30px rgba(128, 0, 0, 0.15) !important;
    }
    .icon-circle {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>
@endsection