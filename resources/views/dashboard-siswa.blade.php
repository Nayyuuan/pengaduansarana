@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- BANNER SELAMAT DATANG --}}
    <div class="card border-0 shadow-sm mb-4 text-white" 
         style="background: linear-gradient(135deg, #800000 0%, #b03030 100%); border-radius: 20px;">
        <div class="card-body p-4 p-md-5">
            <h1 class="fw-bold mb-2">Halo, {{ $siswa->nama }}! 👋</h1>
            <p class="lead mb-0 opacity-75">Bantu kami menjaga kenyamanan sekolah dengan melaporkan kerusakan sarana di sekitarmu.</p>
        </div>
    </div>

    {{-- STATISTIK UTAMA (4 KOTAK) --}}
    <div class="row mb-4">
        {{-- Total Laporan --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(128, 0, 0, 0.1); color: #800000;">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold">TOTAL LAPORAN</h6>
                    <h2 class="fw-bold mb-0" style="color: #800000;">{{ $total }}</h2>
                </div>
            </div>
        </div>

        {{-- Menunggu --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(108, 117, 125, 0.1); color: #6c757d;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold text-secondary">MENUNGGU</h6>
                    <h2 class="fw-bold mb-0 text-secondary">{{ $menunggu }}</h2>
                </div>
            </div>
        </div>
        
        {{-- Sedang Diproses --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <i class="bi bi-gear-wide-connected fs-4"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold text-warning">DIPROSES</h6>
                    <h2 class="fw-bold mb-0 text-warning">{{ $diproses }}</h2>
                </div>
            </div>
        </div>

        {{-- Laporan Selesai --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(25, 135, 84, 0.1); color: #198754;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold text-success">SELESAI</h6>
                    <h2 class="fw-bold text-success mb-0">{{ $selesai }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- INFORMASI PELAPORAN --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background: #fff;">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <span class="p-1 rounded" style="background: #800000; width: 5px; height: 20px; display: inline-block;"></span> 
                    Informasi Pelaporan
                </h5>
                <ol class="text-muted mb-0">
                    <li class="mb-2">Pastikan lokasi sarana yang dilaporkan sudah detail.</li>
                    <li class="mb-2">Admin akan mengecek laporanmu maksimal dalam waktu 2x24 jam.</li>
                    <li>Status <b>"Selesai"</b> berarti perbaikan telah tuntas dilakukan oleh petugas.</li>
                </ol>
            </div>
        </div>
    </div>

</div>
@endsection