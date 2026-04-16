{{--
=============================================================================
HALAMAN: DASHBOARD SISWA
=============================================================================
Fungsi: Menampilkan halaman utama siswa setelah login dengan statistik dan informasi
Route: dashboard.siswa (GET) atau /dashboard-siswa
Controller: SiswaDashboardController@index

DEPENDENSI:
- Layout: layouts.app (harus sudah didefinisikan)
- CSS: Bootstrap 5, Bootstrap Icons
- JavaScript: Bootstrap JS (opsional)
- Data: variabel dari controller (siswa, total, menunggu, diproses, selesai)

STRUKTUR HALAMAN:
1. Banner selamat datang (dengan nama siswa)
2. Statistik card (4 card: Total Laporan, Menunggu, Diproses, Selesai)
3. Informasi pelaporan (tips dan aturan)

DATA DARI CONTROLLER (SiswaDashboardController@index):
- $siswa: object data siswa (berisi nis, nama, kelas, foto_profile, dll)
- $total: jumlah seluruh laporan siswa
- $menunggu: jumlah laporan dengan status 'Menunggu'
- $diproses: jumlah laporan dengan status 'Proses'
- $selesai: jumlah laporan dengan status 'Selesai'

CATATAN PENTING:
- Halaman ini hanya bisa diakses oleh siswa yang sudah login (session nis ada)
- Jika session tidak ada, akan redirect ke halaman login
--}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- ==========================================
         BANNER SELAMAT DATANG SISWA
         ========================================== 
         Menampilkan sambutan personal dengan nama siswa
         Warna: Gradasi linear dari #800000 (maroon) ke #b03030 (maroon terang)
         Efek: border-radius 20px, teks putih
         
         DATA YANG DITAMPILKAN:
         - Nama siswa dari $siswa->nama
         
         CATATAN:
         - Pastikan variabel $siswa tidak null
         - Jika $siswa null, akan terjadi error (harusnya sudah dicek di controller)
    --}}    
    <div class="card border-0 shadow-sm mb-4 text-white" 
         style="background: linear-gradient(135deg, #800000 0%, #b03030 100%); border-radius: 20px;">
        <div class="card-body p-4 p-md-5">
            <h1 class="fw-bold mb-2">Halo, {{ $siswa->nama }}! 👋</h1>
            <p class="lead mb-0 opacity-75">Bantu kami menjaga kenyamanan sekolah dengan melaporkan kerusakan sarana di sekitarmu.</p>
        </div>
    </div>

    {{-- ==========================================
         STATISTIK CARD (4 CARD)
         ========================================== 
         Menampilkan ringkasan laporan siswa dalam bentuk card
         
         URUTAN CARD:
         1. TOTAL LAPORAN - Warna icon #800000 (maroon)
            Menampilkan jumlah seluruh laporan yang pernah dibuat siswa
         
         2. MENUNGGU - Warna icon #6c757d (gray)
            Menampilkan laporan yang belum ditanggapi admin
         
         3. DIPROSES - Warna icon #ffc107 (yellow)
            Menampilkan laporan yang sedang dalam proses perbaikan
         
         4. SELESAI - Warna icon #198754 (green)
            Menampilkan laporan yang sudah selesai diperbaiki
         
         DATA DARI CONTROLLER:
         - $total : total seluruh laporan siswa
         - $menunggu : laporan status 'Menunggu'
         - $diproses : laporan status 'Proses'
         - $selesai : laporan status 'Selesai'
         
         RUMUS VALIDASI:
         - total = menunggu + diproses + selesai (seharusnya)
         - Jika tidak sama, cek konsistensi data di database
    --}}
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


    {{-- ==========================================
         INFORMASI PELAPORAN (TIPS & ATURAN)
         ========================================== 
         Menampilkan panduan singkat untuk siswa dalam membuat laporan
         
         ISI INFORMASI:
         1. Pastikan lokasi sarana yang dilaporkan sudah detail
         2. Admin akan mengecek laporan maksimal 2x24 jam
         3. Status "Selesai" berarti perbaikan telah tuntas
         
         CATATAN:
         - Konten ini statis (hardcoded) dan bisa diubah sesuai kebijakan sekolah
         - Untuk mengubah konten, edit langsung di view ini
         - Jika ingin dinamis, pindahkan ke database (tabel pengaturan)
    --}}
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