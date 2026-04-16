{{--
=============================================================================
HALAMAN: KELOLA ASPIRASI (ADMIN)
=============================================================================
Fungsi: Menampilkan daftar semua aspirasi/laporan dengan fitur filter, pencarian, 
        paginasi, dan modal tanggapan.
Route: admin.kelola (GET)
Controller: AdminController@kelola

DEPENDENSI:
- Layout: layouts.app
- CSS: Bootstrap 5, DataTables CSS (CDN)
- JS: jQuery, DataTables JS (CDN), Bootstrap JS
- Session flash message untuk notifikasi sukses/error

FITUR YANG TERSEDIA:
1. Tabel interaktif dengan searching, sorting, pagination (DataTables)
2. Filter berdasarkan NIS, Kategori, Status, Tanggal (dari controller)
3. Modal tanggapi untuk update status dan feedback
4. Modal preview foto laporan
5. Badge status dengan warna berbeda (Menunggu=secondary, Proses=warning, Selesai=success)

PERINGATAN PENTING:
- File foto laporan disimpan di folder public/upload_aspirasi/
- File foto feedback disimpan di folder public/upload_feedback/
- Pastikan kedua folder tersebut memiliki permission write (755)
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
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- HEADER HALAMAN --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark text-start">Kelola Aspirasi Warga Sekolah</h2>
        {{-- 
            CATATAN: Filter tambahan (NIS, Kategori, Status, Tanggal) seharusnya ada di atas tabel
            Jika tidak muncul, periksa apakah filter sudah dipindahkan ke view atau masih di controller
        --}}
    </div>

    ==========================================
        TABEL DATA ASPIRASI (DENGAN DATATABLES)
    ==========================================
    <div class="card shadow-sm border-0 rounded-3 p-4">
        <div class="table-responsive">
            <table id="myTable" class="table table-hover align-middle mb-0">
                <thead style="background-color: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4 text-start">No</th>
                        <th class="py-3 text-start">Pelapor & Kategori</th>
                        <th class="py-3 text-start">Lokasi & Detail</th>
                        <th class="py-3 text-start">Foto Laporan</th>
                        <th class="py-3 text-start">Status</th>
                        <th class="py-3 pe-4 text-start">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- LOOPING DATA LAPORAN --}}
                    @forelse($laporan as $l)
                    <tr>
                        <td class="ps-4 fw-bold text-start">{{ $loop->iteration }}</td>
                        
                        {{-- KOLOM: Pelapor & Kategori --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $l->nama }}</div>
                            <div class="small text-muted">{{ $l->ket_kategori }}</div>
                        </td>

                        {{-- KOLOM: Lokasi & Detail Aduan --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $l->nama_lokasi }}</div>
                            <div class="small text-muted text-wrap" style="max-width: 250px;">{{ $l->ket }}</div>
                        </td>
                        
                        {{-- KOLOM: Foto Laporan (dengan modal preview) --}}
                        <td class="text-start">
                            @if($l->foto)
                                {{-- 
                                    Foto thumbnail 55x55px, klik untuk preview modal
                                    Data atribut: data-bs-toggle dan data-bs-target untuk Bootstrap Modal
                                --}}
                                <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" width="55" height="55" class="rounded shadow-sm border" style="object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $l->id_pelaporan }}">
                            @else
                                <span class="text-muted small italic">No Photo</span>
                            @endif
                        </td>

                        {{-- KOLOM: Status (dengan badge warna berbeda) --}}
                        <td class="text-start">
                            @php
                                $status_color = 'secondary';
                                if($l->status == 'Proses') $status_color = 'warning text-dark';
                                if($l->status == 'Selesai') $status_color = 'success';
                            @endphp
                            <span class="badge rounded-pill bg-{{ $status_color }} px-3">{{ $l->status }}</span>
                        </td>

                        {{-- KOLOM: Tombol Aksi (Tanggapi) --}}
                        <td class="pe-4 text-start">
                            <button class="btn btn-maroon btn-sm px-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">Tanggapi</button>
                        </td>
                    </tr>

                    {{-- ==========================================
                         MODAL TANGGAPI (Per Laporan)
                         ========================================== 
                         Fungsi: Form untuk memberikan tanggapan/feedback ke siswa
                         Data yang dikirim:
                         - status: Menunggu/Proses/Selesai
                         - feedback: teks balasan petugas
                         - foto_feedback: optional, foto bukti perbaikan
                         
                         ROUTE: POST admin.tanggapi
                         CONTROLLER: AdminController@tanggapi
                    --}}
                    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0" style="border-radius: 20px;">
                                <form action="{{ route('admin.tanggapi', $l->id_pelaporan) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body p-4 text-start">
                                        <h5 class="fw-bold text-maroon mb-3">Kasih Tanggapan</h5>
                                        <div class="mb-3 p-3 bg-light rounded-3 small">
                                            <p class="mb-1 text-muted">Aduan: <b>"{{ $l->ket }}"</b></p>
                                        </div>
                                        
                                        {{-- Field: Status (dropdown) --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Status</label>
                                            <select name="status" class="form-select shadow-none" required>
                                                <option value="Menunggu" {{ $l->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Proses" {{ $l->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                                <option value="Selesai" {{ $l->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>
                                        
                                    {{-- Field: Feedback/Balasan Petugas (textarea) --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Balasan Petugas</label>
                                            <textarea name="feedback" class="form-control shadow-none" rows="4" required>{{ $l->feedback }}</textarea>
                                        </div>
                                    
                                    {{-- Field: Foto Bukti (optional) --}}
                                        <div class="mb-0">
                                            <label class="form-label fw-bold small">Foto Bukti (Optional)</label>
                                            <input type="file" name="foto_feedback" class="form-control shadow-none" accept="image/*">
                                        {{-- 
                                            CATATAN: 
                                            - Foto disimpan di folder public/upload_feedback/
                                            - Nama file akan di-generate dengan format: feedback_timestamp_namafile
                                            - Ekstensi yang diperbolehkan: jpg, jpeg, png (dari validasi controller)
                                        --}}
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- ==========================================
                         MODAL PREVIEW FOTO LAPORAN
                         ========================================== 
                         Fungsi: Menampilkan foto laporan dalam ukuran besar
                         Klik pada foto thumbnail akan membuka modal ini
                         Klik pada gambar di modal akan menutup modal (data-bs-dismiss)
                    --}}
                    @if($l->foto)
                    <div class="modal fade" id="modalFoto{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-transparent border-0 text-center"><img src="{{ asset('upload_aspirasi/'.$l->foto) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal"></div></div>
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
        $('#myTable').DataTable({
            "searching": true, 
            "paging": true,
            "ordering": true,
            "language": {
                "sSearch": "Cari Laporan:",
                "sLengthMenu": "Tampilkan _MENU_ data",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "oPaginate": {
                    "sPrevious": "←",
                    "sNext": "→"
                }
            }
        });
    });
</script>

    ==========================================
     CUSTOM STYLE KHUSUS HALAMAN INI
    ========================================== 
<style>
    /* Style tombol maroon (warna utama) */
    .btn-maroon { background: #800000; color: white; border: none; }
    .btn-maroon:hover { background: #550000; color: white; }
    .text-maroon { color: #800000; }

    /* Style tombol kembali */
    .btn-back { display: inline-block; padding: 6px 20px; color: #800000; border: 1.5px solid #800000; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; }
    .btn-back:hover { background-color: #800000; color: white; }

    /* 
    STYLE KHUSUS DATATABLES PAGINATION BUTTON
    Menyesuaikan tampilan tombol paginasi agar sesuai tema maroon
    */
    .dt-paging-button {
        border: 1px solid #ddd !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        color: #800000 !important;
        padding: 5px 12px !important;
    }
    .dt-paging-button.current {
        background: #ffffff !important;
        color: white !important;
        border: none !important;
    }
    .dt-paging-button:hover:not(.current) {
        background: #fdf2f2 !important;
        color: #800000 !important;
        border-color: #800000 !important;
    }
</style>

{{-- 
    ==========================================
    CATATAN PENTING UNTUK MAINTENANCE
    ==========================================
    
    1. DEPENDENSI CDN:
       - Pastikan koneksi internet tersedia untuk load CDN
       - Jika offline/gensim, download dan hosting lokal file DataTables
    
    2. FILTER TAMBAHAN:
       - Filter berdasarkan NIS, Kategori, Status, Tanggal TIDAK ada di view ini
       - Filter tersebut seharusnya ditampilkan di atas tabel
       - Jika diperlukan, pindahkan filter dari controller ke view dengan form GET
    
    3. PERFORMANCE:
       - DataTables memuat semua data sekaligus di client-side
       - Jika data > 1000 baris, pertimbangkan Server-side DataTables
       - Server-side memerlukan AJAX dan processing di controller
    
    4. VALIDASI & ERROR:
       - Validasi file foto_feedback dilakukan di controller
       - Error akan muncul via session flash
       - Pastikan layout app menampilkan @if(session('error'))
    
    5. POTENSI BUG:
       - $loop->iteration tidak reset saat pagination (hanya untuk halaman pertama)
       - Solusi: Gunakan ($laporan->currentPage() - 1) * $laporan->perPage() + $loop->iteration
       - Tapi karena DataTables client-side, nomor urut akan berubah saat sorting
    
    6. KEAMANAN:
       - Pastikan route admin sudah dilindungi middleware
       - CSRF token sudah disertakan di form modal
    
    7. YANG PERLU DITAMBAHKAN:
       - Konfirmasi sebelum update status (opsional)
       - Loading spinner saat submit form
       - Preview foto feedback sebelum upload
       - Filter tambahan di atas tabel (NIS, Kategori, Status, Tanggal)
--}}
@endsection