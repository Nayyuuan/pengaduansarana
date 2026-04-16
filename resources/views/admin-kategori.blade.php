{{--
=============================================================================
HALAMAN: KELOLA KATEGORI SARANA (ADMIN)
=============================================================================
Fungsi: Menampilkan daftar kategori dan menyediakan fitur CRUD (Tambah, Edit, Hapus)
Route: admin.kategori.index (GET)
Controller: AdminController@kategori

DEPENDENSI:
- Layout: layouts.app (harus sudah didefinisikan)
- CSS: Bootstrap 5, Bootstrap Icons
- JavaScript: Bootstrap JS (untuk modal)
- Session flash message: diperlukan untuk menampilkan success/error dari controller

CARA KERJA:
1. Menampilkan semua data kategori dari variabel $kategori (dikirim dari controller)
2. Tombol "Tambah Kategori" membuka modal untuk input data baru
3. Setiap baris memiliki tombol Edit (buka modal edit) dan Hapus (konfirmasi dulu)
4. Data dikirim ke route:
   - Store: POST admin.kategori.store
   - Update: POST admin.kategori.update (dengan method spoofing)
   - Destroy: DELETE admin.kategori.destroy

CATATAN PENTING UNTUK MAINTENANCE:
- Hapus kategori akan mempengaruhi laporan yang terkait (foreign key constraint)
- Controller sudah memiliki proteksi: kategori tidak bisa dihapus jika sudah dipakai laporan
- Pastikan route names sesuai dengan yang didefinisikan di web.php
--}}

@extends('layouts.app')

@section('content')
<div class="container py-4 text-start">

    ==========================================
        TOMBOL KEMBALI KE DASHBOARD
    ==========================================   
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
            <h2 class="fw-bold text-dark mb-0">Kategori Sarana</h2>
        </div>
        <button class="btn btn-maroon rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </button>
    </div>

    {{-- ==========================================
         TABEL DAFTAR KATEGORI
         ========================================== 
         CATATAN: Tabel ini menggunakan styling manual BUKAN DataTables
         - Jika jumlah data banyak (>100), pertimbangkan untuk:
           1. Menambahkan fitur paginasi di controller
           2. Mengganti dengan DataTables untuk sorting & search
    --}} 
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4" style="width: 80px;">No</th>
                        <th class="py-3">Nama Kategori</th>
                        <th class="py-3 pe-4 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $k)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $k->ket_kategori }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Tombol Edit & Hapus --}}
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id_kategori }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- 
                                    FORM HAPUS KATEGORI
                                    PERINGATAN: 
                                    - Method DELETE menggunakan spoofing (POST + _method)
                                    - Ada konfirmasi JavaScript sebelum submit
                                    - Controller akan menolak jika kategori sudah dipakai laporan
                                --}}                                <form action="{{ route('admin.kategori.destroy', $k->id_kategori) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Laporan yang berhubungan mungkin akan terpengaruh.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- ==========================================
                         MODAL EDIT KATEGORI (Per Kategori)
                         ========================================== 
                         ID modal unik menggunakan id_kategori untuk menghindari duplikasi
                    --}}
                    <div class="modal fade" id="modalEdit{{ $k->id_kategori }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold text-maroon">Ubah Nama Kategori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.kategori.update', $k->id_kategori) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <label class="form-label small fw-bold">Nama Kategori</label>
                                        <input type="text" name="ket_kategori" class="form-control py-2" value="{{ $k->ket_kategori }}" required style="border-radius: 10px;">
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted italic">Belum ada kategori yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==========================================
     MODAL TAMBAH KATEGORI
     ========================================== 
     Modal ini dipanggil dari tombol "Tambah Kategori" di header
     ID: modalTambah
--}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-maroon">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label small fw-bold">Nama Kategori</label>
                    <input type="text" name="ket_kategori" class="form-control py-2" placeholder="Misal: Sarana Perpustakaan" required style="border-radius: 10px;">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Tambah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==========================================
     STYLE KHUSUS HALAMAN INI
     ========================================== --}}
<style>
    .btn-maroon { background: #800000; color: white; border: none; transition: 0.3s; }
    .btn-maroon:hover { background: #550000; color: white; transform: translateY(-2px); }
    .text-maroon { color: #800000; }
    
    /* Style tombol kembali */
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
    CATATAN PENTING UNTUK PENGEMBANGAN
    ==========================================
    
    1. VALIDASI & ERROR HANDLING:
       - Validasi dilakukan di controller (AdminController@kategoriStore)
       - Error message akan muncul via session flash
       - Pastikan layout app memiliki @if(session('error')) atau @if(session('success'))
    
    2. KEAMANAN:
       - Semua route sudah dilindungi middleware/checkAdmin di controller
       - CSRF protection aktif (@csrf di setiap form)
       - Method spoofing (@method) digunakan untuk PUT dan DELETE
    
    3. PERFORMANCE:
       - Tabel tidak memiliki paginasi, jika data > 100 akan berat
       - Saran: tambahkan paginate() di controller dan pagination links di view
    
    4. AKSESIBILITAS:
       - Warna maroon (#800000) dengan teks putih kontrasnya cukup baik
       - Modal menggunakan focus trap (built-in Bootstrap)
    
    5. YANG PERLU DITAMBAHKAN:
       - Indikator loading saat submit form
       - Toast notification untuk feedback sukses/error
       - Konfirmasi hapus yang lebih informatif (menampilkan nama kategori)
       - Fitur search kategori jika jumlah data banyak
--}}
@endsection