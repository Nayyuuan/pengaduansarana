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

    {{-- STATISTIK UTAMA --}}
    <div class="row mb-4">
        {{-- Total Laporan --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(128, 0, 0, 0.1); color: #800000;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                            <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                            <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zM11 4.5h-2V2l3 3h-1zM4 1v13h8V5H9a1 1 0 0 1-1-1V1H4z"/>
                        </svg>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold">TOTAL LAPORAN</h6>
                    <h2 class="fw-bold mb-0" style="color: #800000;">{{ $total }}</h2>
                </div>
            </div>
        </div>
        
        {{-- Sedang Diproses --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.634l.244-.163c.846-.565 1.972.562 1.408 1.408l-.163.244a.96.96 0 0 0 .634 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.634 1.622l.163.244c.565.846-.562 1.972-1.408 1.408l-.244-.163a.96.96 0 0 0-1.622.634l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.634l-.244.163c-.846.565-1.972-.562-1.408-1.408l.163-.244a.96.96 0 0 0-.634-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .634-1.622l-.163-.244c-.565-.846.562-1.972 1.408-1.408l.244.163a.96.96 0 0 0 1.622-.634l.071-.286z"/>
                        </svg>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold">SEDANG DIPROSES</h6>
                    <h2 class="fw-bold mb-0 text-warning">{{ $diproses }}</h2>
                </div>
            </div>
        </div>

        {{-- Laporan Selesai --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 py-3 text-center h-100" style="border-radius: 15px; background: #fff;">
                <div class="card-body">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 55px; height: 55px; background: rgba(25, 135, 84, 0.1); color: #198754;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </div>
                    <h6 class="text-muted mb-1 small fw-bold">LAPORAN SELESAI</h6>
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
                    <span class="p-1 rounded" style="background: #800000;"></span> 
                    Informasi Pelaporan
                </h5>
                <ol class="text-muted mb-0">
                    <li class="mb-2">Pastikan lokasi sarana yang dilaporkan sudah detail (contoh: Bengkel RPL Bawah D 102).</li>
                    <li class="mb-2">Admin akan mengecek laporanmu maksimal dalam waktu 2x24 jam.</li>
                    <li>Status <b>"Selesai"</b> berarti perbaikan telah tuntas dilakukan oleh petugas.</li>
                </ol>
            </div>
        </div>
    </div>

</div>
@endsection