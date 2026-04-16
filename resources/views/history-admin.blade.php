{{--
=============================================================================
HALAMAN: RIWAYAT PENANGANAN ASPIRASI (ADMIN)
=============================================================================
Fungsi: Menampilkan riwayat semua aspirasi/laporan yang sudah ditanggapi admin
Route: admin.history (GET)
Controller: AdminController@history

DEPENDENSI:
- Layout: layouts.app (harus sudah didefinisikan)
- CSS: Bootstrap 5, DataTables CSS (CDN)
- JS: jQuery, DataTables JS (CDN), Bootstrap JS
- Data: variabel $history dari controller (berisi semua laporan dengan feedback)

PERBEDAAN DENGAN HALAMAN KELOLA ASPIRASI:
- Halaman kelola: untuk menanggapi laporan (interaktif)
- Halaman history: hanya untuk melihat riwayat (read-only)
- History menampilkan feedback dan foto bukti dari admin
- Urutan diurutkan berdasarkan updated_at terbaru (yang baru ditanggapi di atas)

STRUKTUR HALAMAN:
1. Tombol kembali ke dashboard admin
2. Tabel riwayat dengan DataTables (search, pagination, sort)
3. Modal preview foto feedback

DATA YANG DITAMPILKAN:
- Pelapor (nama siswa)
- Kategori aspirasi
- Lokasi dan detail aduan
- Status (Menunggu/Proses/Selesai)
- Feedback admin (tanggapan)
- Foto bukti perbaikan (foto_feedback)
- Waktu terakhir update (updated_at)

CATATAN PENTING:
- Hanya laporan yang sudah memiliki feedback yang meaningful
- Laporan dengan status 'Menunggu' biasanya tidak muncul atau feedback kosong
- Foto feedback disimpan di folder public/upload_feedback/
--}}
@extends('layouts.app')

@section('content')
{{-- ==========================================
     LOAD CSS DATATABLES (CDN)
     ========================================== 
     CATATAN: DataTables versi 2.0.3 digunakan untuk fitur pencarian & paginasi
     Jika versi berubah, periksa kompatibilitas dengan jQuery yang digunakan
--}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">

<div class="container py-4 text-start">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- ==========================================
         HEADER HALAMAN
         ========================================== --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark text-start">Riwayat Penanganan Aspirasi</h2>
    </div>

    {{-- ==========================================
         TABEL RIWAYAT (DENGAN DATATABLES)
         ========================================== 
         PERBEDAAN DENGAN TABEL KELOLA:
         1. Tidak ada kolom aksi (tanggapi)
         2. Ada kolom feedback admin
         3. Ada kolom foto bukti (dari admin)
         4. Kolom waktu menampilkan updated_at (kapan ditanggapi)
         
         SUMBER DATA:
         - $history dari AdminController@history
         - Data diurutkan dari updated_at terbaru (controller)
    --}}    
    <div class="card shadow-sm border-0 rounded-3 p-4">
        <div class="table-responsive">
            <table id="historyTable" class="table table-hover align-middle mb-0">
             {{-- HEADER TABEL --}}
                <thead style="background-color: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4 text-start">No</th>
                        <th class="py-3 text-start">Pelapor & Kategori</th>
                        <th class="py-3 text-start">Lokasi & Detail</th>
                        <th class="py-3 text-start">Status</th>
                        <th class="py-3 text-start">Feedback Admin</th>
                        <th class="py-3 text-center">Foto Bukti</th> {{-- CENTER --}}
                        <th class="py-3 pe-4 text-start">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                {{-- LOOPING DATA HISTORY --}}
                    @forelse($history as $h)
                    {{-- 
                        LOGIKA WARNA BADGE BERDASARKAN STATUS
                        - Menunggu: secondary (abu-abu)
                        - Proses: warning (kuning) dengan teks gelap
                        - Selesai: success (hijau)
                    --}}
                    @php
                        $status_color = 'secondary';
                        if($h->status == 'Proses') $status_color = 'warning';
                        if($h->status == 'Selesai') $status_color = 'success';
                    @endphp
                    {{-- Nomor urut --}}
                    <tr>
                        <td class="ps-4 fw-bold text-start">{{ $loop->iteration }}</td>
                    {{-- KOLOM: Pelapor & Kategori --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $h->nama }}</div>
                            <div class="small text-muted">{{ $h->ket_kategori }}</div>
                        </td>
                        {{-- KOLOM: Lokasi & Detail Aduan --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $h->nama_lokasi }}</div>
                            <div class="small text-muted text-wrap" style="max-width: 200px;">{{ $h->ket }}</div>
                        </td>
                        {{-- KOLOM: Status (dengan badge) --}}
                        <td class="text-start">
                            <span class="badge rounded-pill bg-{{ $status_color }} {{ $h->status == 'Proses' ? 'text-dark' : '' }} px-3">
                                {{ $h->status }}
                            </span>
                        </td>
                        {{-- 
                            KOLOM: Feedback Admin
                            Menampilkan tanggapan admin dengan styling border sesuai status
                            - Jika ada feedback: tampilkan dalam kotak dengan border
                            - Jika belum: tampilkan "Belum ditanggapi"
                        --}}
                        <td class="text-start">
                            @if($h->feedback)
                                <div class="p-2 border-start border-4 border-{{ $status_color }} bg-light small text-dark" style="min-width: 150px;">
                                    {{ $h->feedback }}
                                </div>
                            @else
                                <span class="text-muted small italic">Belum ditanggapi</span>
                            @endif
                        </td>

                        {{-- 
                            KOLOM: Foto Bukti (dari admin)
                            Menampilkan thumbnail foto feedback (55x55px)
                            Klik untuk preview modal
                            Foto disimpan di: public/upload_feedback/
                        --}}                        
                        <td class="text-center">
                            @if(!empty($h->foto_feedback))
                                <img src="{{ asset('upload_feedback/'.$h->foto_feedback) }}" width="55" height="55" 
                                     class="rounded shadow-sm border img-laporan" 
                                     style="object-fit: cover; cursor: pointer;" 
                                     data-bs-toggle="modal" data-bs-target="#modalFeedback{{ $h->id_pelaporan }}">
                            @else
                                <span class="text-muted small italic">No Bukti</span>
                            @endif
                        </td>

                        <td class="pe-4 text-start">
                            @if($h->status == 'Menunggu')
                                <span class="text-muted small italic">-</span>
                            @else
                                <div class="small fw-bold">{{ date('d/m/Y', strtotime($h->updated_at)) }}</div>
                                <div class="extra-small text-muted">{{ date('H:i', strtotime($h->updated_at)) }} WIB</div>
                            @endif
                        </td>
                    {{-- ==========================================
                         MODAL PREVIEW FOTO FEEDBACK
                         ========================================== 
                         Fungsi: Menampilkan foto feedback dalam ukuran besar
                         Klik pada gambar thumbnail akan membuka modal ini
                         Klik pada gambar di modal akan menutup modal (data-bs-dismiss)
                    --}}

                    </tr>

                    @if(!empty($h->foto_feedback))
                    <div class="modal fade" id="modalFeedback{{ $h->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0 text-center">
                                <img src="{{ asset('upload_feedback/'.$h->foto_feedback) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal" style="cursor: zoom-out;">
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    {{-- 
                        KETIKA DATA KOSONG: 
                        DataTables tetap akan menampilkan pesan "No data available in table"
                        Bagian @empty sebenarnya tidak diperlukan karena DataTables menangani sendiri
                        Namun tetap disediakan untuk fallback jika DataTables gagal load
                    --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==========================================
     LOAD JAVASCRIPT UNTUK DATATABLES
     ========================================== 
     URUTAN PENTING:
     1. jQuery harus di-load pertama (dependency DataTables)
     2. DataTables JS
     3. Inisialisasi DataTables
     
     CATATAN: Jika Bootstrap JS sudah di-load di layout, pastikan tidak conflict dengan jQuery
--}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

<script>
    $(document).ready( function () {
        $('#historyTable').DataTable({
            "language": {
                "sSearch": "Cari Riwayat:",
                "oPaginate": { "sPrevious": "←", "sNext": "→" }
            }
        });
    });
</script>

{{-- ==========================================
     CUSTOM STYLE KHUSUS HALAMAN INI
     ========================================== --}}
<style>
    .text-maroon { color: #800000; }
    .btn-back { display: inline-block; padding: 6px 20px; color: #800000; border: 1.5px solid #800000; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; }
    .btn-back:hover { background-color: #800000; color: white; }
    .extra-small { font-size: 0.75rem; }

    table img.img-laporan { 
        border-radius: 8px !important; 
        transition: 0.3s; 
    }
    table img.img-laporan:hover { 
        transform: scale(1.1); 
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important; 
    }

    .dt-paging-button {
        border: 1px solid #ddd !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        color: #800000 !important;
        padding: 5px 12px !important;
    }
    .dt-paging-button.current, .dt-paging-button.current:hover {
        background: #ffffff !important;
        color: white !important; 
        border: none !important;
    }
    .dt-paging-button:hover:not(.current) {
        background: #fdf2f2 !important;
        color: #800000 !important;
    }
</style>
@endsection