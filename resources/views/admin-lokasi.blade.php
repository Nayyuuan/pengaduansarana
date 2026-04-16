{{--
=============================================================================
HALAMAN: KELOLA LOKASI SARANA (ADMIN)
=============================================================================
Fungsi: Menampilkan daftar lokasi/gedung dan menyediakan fitur CRUD (Tambah, Edit, Hapus)
Route: admin.lokasi.index (GET)
Controller: AdminController@lokasi

DEPENDENSI:
- Layout: layouts.app (harus sudah didefinisikan)
- CSS: Bootstrap 5, Bootstrap Icons
- JavaScript: Bootstrap JS (untuk modal)
- Session flash message: diperlukan untuk menampilkan success/error dari controller

STRUKTUR DATABASE TERKAIT:
- Tabel: lokasi
- Kolom: id_lokasi (primary key), nama_lokasi (string 50), timestamps
- Relasi: Digunakan oleh tabel input_aspirasi (foreign key id_lokasi)

PERINGATAN PENTING:
- Lokasi yang sudah digunakan dalam laporan TIDAK BISA dihapus
- Proteksi hapus sudah diatur di controller (AdminController@lokasiDestroy)
- Jika forced delete diperlukan, hapus dulu semua laporan terkait lokasi tersebut
--}}

@extends('layouts.app')

@section('content')
<div class="container py-4 text-start">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    ==========================================
        HEADER HALAMAN
    ==========================================
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Lokasi Sarana</h2>
        {{-- 
            CATATAN: Halaman ini mengelola data lokasi (gedung/ruangan)
            Contoh data: Gedung A, Ruang Lab Komputer, Lapangan Olahraga, Kantin, dll.
        --}}
        </div>
        <button class="btn btn-maroon rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLokasi">
            <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
        </button>
    </div>

    {{-- ==========================================
         TABEL DAFTAR LOKASI
         ========================================== 
         CATATAN: 
         - Tabel ini menggunakan styling manual BUKAN DataTables
         - Jika jumlah data lokasi banyak (>50), pertimbangkan:
           1. Menambahkan fitur pencarian (search box)
           2. Mengganti dengan DataTables untuk kemudahan sorting & search
         - Lokasi biasanya tidak sebanyak kategori, jadi tabel biasa sudah cukup
    --}}    
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4" style="width: 80px;">No</th>
                        <th class="py-3">Nama Lokasi / Gedung</th>
                        <th class="py-3 pe-4 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- LOOPING DATA LOKASI --}}
                    @forelse($lokasi as $l)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                        {{-- Nama lokasi --}}
                        <td>
                            <span class="fw-bold text-dark">{{ $l->nama_lokasi }}</span>
                        </td>
                        
                        {{-- Tombol aksi (Edit & Hapus) --}}
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalEditLokasi{{ $l->id_lokasi }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- 
                                    FORM HAPUS LOKASI
                                    PERINGATAN: 
                                    - Method DELETE menggunakan spoofing (POST + _method)
                                    - Ada konfirmasi JavaScript sebelum submit
                                    - Controller akan menolak jika lokasi sudah dipakai laporan
                                    - Error message akan muncul via session flash
                                --}}                               
                                <form action="{{ route('admin.lokasi.destroy', $l->id_lokasi) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini? Data laporan yang menggunakan lokasi ini mungkin akan terpengaruh.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                   {{-- ==========================================
                         MODAL EDIT LOKASI (Per Lokasi)
                         ========================================== 
                         ID modal: modalEditLokasi{{ $l->id_lokasi }}
                         Fungsi: Mengubah nama lokasi yang sudah ada
                         
                         ROUTE: PUT admin.lokasi.update
                         CONTROLLER: AdminController@lokasiUpdate
                    --}}                    
                    <div class="modal fade" id="modalEditLokasi{{ $l->id_lokasi }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold text-maroon">Ubah Nama Lokasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                {{-- 
                                    Form update menggunakan method spoofing PUT
                                    action route: admin.lokasi.update
                                --}}
                                <form action="{{ route('admin.lokasi.update', $l->id_lokasi) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <label class="form-label small fw-bold">Nama Lokasi</label>
                                        <input type="text" name="nama_lokasi" class="form-control py-2" value="{{ $l->nama_lokasi }}" required style="border-radius: 10px;">
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    {{-- TAMPILAN KETIKA DATA KOSONG --}}
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted italic">Belum ada data lokasi yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==========================================
     MODAL TAMBAH LOKASI
     ========================================== 
     ID modal: modalTambahLokasi
     Fungsi: Menambahkan lokasi baru ke database
     
     ROUTE: POST admin.lokasi.store
     CONTROLLER: AdminController@lokasiStore
     
     CATATAN:
     - Nama lokasi harus unik (disarankan, meskipun tidak divalidasi di controller saat ini)
     - Contoh nama lokasi: Gedung Serba Guna, Laboratorium IPA, Perpustakaan, dll.
--}}
<div class="modal fade" id="modalTambahLokasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-maroon">Tambah Lokasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lokasi.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label small fw-bold">Nama Lokasi / Ruangan</label>
                    <input type="text" name="nama_lokasi" class="form-control py-2" placeholder="Misal: Gedung Olahraga" required style="border-radius: 10px;">
                    {{-- 
                        CATATAN: 
                        - placeholder hanya contoh, bisa disesuaikan
                        - Validasi minimal panjang sebaiknya ditambahkan di controller
                        - Maksimal 50 karakter sesuai dengan struktur database
                    --}}
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Tambah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==========================================
     CUSTOM STYLE KHUSUS HALAMAN INI
     ========================================== --}}
<style>
    .btn-maroon { background: #800000; color: white; border: none; transition: 0.3s; }
    .btn-maroon:hover { background: #550000; color: white; transform: translateY(-2px); }
    .text-maroon { color: #800000; }
    
    .btn-back {
        display: inline-block;
        padding: 6px 20px;
        color: #800000;
        border: 1.5px solid #800000;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .btn-back:hover {
        background-color: #800000;
        color: white;
    }
</style>


{{-- 
    ==========================================
    CATATAN PENTING UNTUK MAINTENANCE
    ==========================================
    
    1. VALIDASI & ERROR HANDLING:
       - Validasi dilakukan di controller (AdminController@lokasiStore & lokasiUpdate)
       - Error message akan muncul via session flash
       - Pastikan layout app memiliki @if(session('error')) atau @if(session('success'))
       - Controller memiliki proteksi: lokasi tidak bisa dihapus jika sudah dipakai laporan
    
    2. KEAMANAN:
       - Semua route sudah dilindungi middleware/checkAdmin di controller
       - CSRF protection aktif (@csrf di setiap form)
       - Method spoofing (@method) digunakan untuk PUT dan DELETE
    
    3. PERFORMANCE:
       - Tabel tidak memiliki paginasi, jika data lokasi > 100 akan berat
       - Lokasi biasanya tidak sebanyak itu, tetapi tetap perlu diwaspadai
       - Saran: tambahkan paginate(50) di controller jika diperlukan
    
    4. VALIDASI UNIK NAMA LOKASI:
       - Saat ini tidak ada validasi unique untuk nama_lokasi
       - Sebaiknya tambahkan validasi: 'nama_lokasi' => 'required|unique:lokasi,nama_lokasi'
       - Ini untuk mencegah duplikasi data lokasi
    
    5. HUBUNGAN DENGAN TABEL LAIN:
       - lokasi.id_lokasi digunakan di input_aspirasi.id_lokasi (foreign key)
       - Jika lokasi dihapus padahal sudah dipakai, akan error foreign key constraint
       - Controller sudah mencegah hal ini dengan mengecek $terpakai
    
    6. AKSESIBILITAS:
       - Warna maroon (#800000) dengan teks putih kontrasnya cukup baik
       - Modal menggunakan focus trap (built-in Bootstrap)
    
    7. YANG PERLU DITAMBAHKAN:
       - Indikator loading saat submit form
       - Toast notification untuk feedback sukses/error
       - Konfirmasi hapus yang lebih informatif (menampilkan nama lokasi)
       - Validasi unique nama_lokasi (mencegah duplikasi)
       - Fitur search/pencarian lokasi jika jumlah data banyak
       - Soft delete untuk lokasi (daripada hard delete)
    
    8. PERBEDAAN DENGAN HALAMAN KATEGORI:
       - Struktur halaman hampir identik dengan admin-kategori.blade.php
       - Perbedaan hanya pada:
         * Nama tabel (lokasi vs kategori)
         * Field name (nama_lokasi vs ket_kategori)
         * Route names (admin.lokasi.* vs admin.kategori.*)
         * Placeholder dan label disesuaikan
       - Jika ada bug di salah satu, kemungkinan juga terjadi di yang lain
    
    9. REKOMENDASI REFACTORING:
       - Buat component Blade untuk tabel CRUD yang reusable
       - Gunakan trait atau base controller untuk CRUD yang mirip
       - Konsolidasi menjadi satu view dengan parameter type (kategori/lokasi)
--}}
@endsection