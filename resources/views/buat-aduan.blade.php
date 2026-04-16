{{--
=============================================================================
HALAMAN: FORM BUAT ADUAN / ASPIRASI (SISWA)
=============================================================================
Fungsi: Menampilkan form untuk siswa membuat aduan/aspirasi baru
Route: laporan.create (GET) atau buat-aduan (GET)
Controller: SiswaDashboardController@create

DEPENDENSI:
- Layout: layouts.app (harus sudah didefinisikan)
- CSS: Bootstrap 5
- JavaScript: Bootstrap JS (untuk interaksi form)
- Session: session('nis') harus aktif (siswa sudah login)

DATA YANG DIKIRIM DARI CONTROLLER:
- $kategori: daftar semua kategori dari tabel kategori
- $lokasi: daftar semua lokasi dari tabel lokasi
- $siswa: (opsional) data siswa yang sedang login

FLOW PROSES:
1. Siswa memilih kategori sarana (dari tabel kategori)
2. Siswa memilih lokasi (dari tabel lokasi)
3. Siswa mengisi detail aduan
4. Siswa upload foto bukti (wajib)
5. Submit ke route laporan.store (POST)

PERINGATAN PENTING:
- Foto WAJIB diupload (required di validasi controller)
- Format foto yang diperbolehkan: image (jpg, jpeg, png)
- Maksimal ukuran foto: 2MB (2048 KB)
- Semua field wajib diisi (required)
--}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <a href="{{ route('dashboard.siswa') }}" class="btn btn-sm mb-3 fw-bold" 
       style="color: #800000; border: 1px solid #800000; border-radius: 8px;">
        ← Kembali ke Dashboard
    </a>
    <div class="row justify-content-center">
        <div class="col-md-8">
        ==========================================
            CARD FORM BUAT ADUAN
        ==========================================
            <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 15px;">
                <div class="card-header text-white fw-bold py-3 text-center" style="background: #800000;">
                    BUAT ADUAN / ASPIRASI BARU
                </div>
                
                <div class="card-body p-4 bg-white">
                    {{-- 
                        FORM BUAT ADUAN
                        METHOD: POST
                        ACTION: route('laporan.store')
                        ENCTYPE: multipart/form-data (WAJIB untuk upload file)
                        
                        CATATAN: 
                        - Route name 'laporan.store' harus terdefinisi di web.php
                        - Controller: SiswaDashboardController@store
                    --}}
                    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ==========================================
                             FIELD 1: KATEGORI SARANA (Dropdown)
                             ========================================== 
                             Sumber data: $kategori dari controller
                             Database: kategori.id_kategori, kategori.ket_kategori
                        --}}                       
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Kategori Sarana</label>
                            <select name="id_kategori" class="form-select py-2" style="border-radius: 10px; border: 1px solid #ddd;" required>
                                <option value="">-- Pilih Kategori Sarana --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                @endforeach
                            </select>
                            {{-- 
                                CATATAN: 
                                - Jika $kategori kosong, dropdown akan kosong
                                - Sebaiknya tambahkan pengecekan: @if($kategori->isEmpty()) 
                                  <option disabled>Belum ada kategori, hubungi admin</option>
                                - Kategori bisa ditambah oleh admin di halaman kelola kategori
                            --}}                        </div>

                        {{-- ==========================================
                             FIELD 2: LOKASI UTAMA (Dropdown)
                             ========================================== 
                             Sumber data: $lokasi dari controller
                             Database: lokasi.id_lokasi, lokasi.nama_lokasi
                             
                             PERUBAHAN PENTING:
                             - Sebelumnya hardcoded, sekarang dinamis dari database
                             - Pastikan tabel lokasi sudah terisi data oleh admin
                        --}}                       
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Lokasi Utama</label>
                            <select name="id_lokasi" class="form-select py-2" style="border-radius: 10px; border: 1px solid #ddd;" required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($lokasi as $lok)
                                    <option value="{{ $lok->id_lokasi }}">{{ $lok->nama_lokasi }}</option>
                                @endforeach
                            </select>
                            {{-- 
                                CATATAN: 
                                - Jika $lokasi kosong, dropdown akan kosong
                                - Sebaiknya tambahkan pesan: "Belum ada lokasi, silahkan hubungi admin"
                                - Lokasi bisa ditambah oleh admin di halaman kelola lokasi
                            --}}
                        </div>

                        {{-- ==========================================
                             FIELD 3: DETAIL LAPORAN / KETERANGAN (Textarea)
                             ========================================== 
                             Database: input_aspirasi.ket (max 100 karakter)
                             
                             PERINGATAN: 
                             - Kolom ket di database hanya 100 karakter (sesuai migration)
                             - Jika siswa mengisi >100 karakter, akan terpotong atau error
                             - REKOMENDASI: Ubah tipe data kolom ket menjadi TEXT di migration
                        --}}                        
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Detail Laporan / Keterangan</label>
                            <textarea name="ket" class="form-control" rows="4" 
                                      placeholder="Jelaskan detailnya." 
                                      required style="border-radius: 10px; border: 1px solid #ddd;"></textarea>
                            {{-- 
                                TAMBAHKAN validasi client-side:
                                <small class="text-muted">Maksimal 100 karakter</small>
                                maxlength="100"
                            --}}
                        </div>

                        {{-- ==========================================
                             FIELD 4: FOTO BUKTI (File Upload)
                             ========================================== 
                             Penyimpanan: folder public/upload_aspirasi/
                             Nama file: timestamp_namafile.jpg
                             
                             VALIDASI (di controller):
                             - required: wajib diisi
                             - image: harus file gambar
                             - max:2048 (maksimal 2MB)
                             
                             FORMAT YANG DIPERBOLEHKAN:
                             - JPG, JPEG, PNG (dari validasi mime types)
                        --}}                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Bukti Kerusakan</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required style="border-radius: 10px;">
                            <small class="text-muted">Wajib upload bukti foto (JPG/PNG, Max 2MB)</small>
                        </div>
                        {{-- ==========================================
                             BUTTON SUBMIT
                             ========================================== --}}
                        <div class="d-grid mt-2">
                            <button type="submit" class="btn text-white py-2 fw-bold" 
                                    style="background: #b03030; border-radius: 10px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(176, 48, 48, 0.2);">
                                Kirim Laporan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- 
    ==========================================
    CATATAN PENTING UNTUK MAINTENANCE
    ==========================================
    
    1. VALIDASI DATA:
       - Semua field sudah menggunakan atribut 'required' di HTML
       - Namun validasi SERVER-SIDE tetap WAJIB di controller
       - Jangan hanya mengandalkan validasi client-side!
    
    2. POTENSI ERROR & SOLUSI:
       
       | Error | Penyebab | Solusi |
       |-------|----------|--------|
       | Dropdown kosong | Tabel kategori/lokasi kosong | Tambahkan data default atau pesan error |
       | Upload gagal | Folder upload_aspirasi tidak writable | Set permission folder ke 755/775 |
       | File terlalu besar | Ukuran >2MB | Tambah validasi client-side max file size |
       | Teks terpotong | Kolom 'ket' hanya 100 char | Ubah migration: $table->text('ket') |
       | 404 saat submit | Route 'laporan.store' tidak ada | Cek web.php, pastikan route terdaftar |
    
    3. KEAMANAN:
       - CSRF protection aktif (@csrf)
       - Session 'nis' harus dicek di controller
       - Validasi file upload (type, size) sudah di controller
       - Nama file di-generate dengan time() untuk mencegah duplicate
    
    4. USER EXPERIENCE (UX) YANG PERU DITAMBAHKAN:
       - Preview foto sebelum upload (JavaScript)
       - Indikator loading saat submit (disable button + spinner)
       - Auto-resize textarea sesuai isi
       - Character counter untuk field 'ket' (karena terbatas 100)
       - Konfirmasi "Yakin ingin mengirim laporan?" sebelum submit
       - Tampilkan error validasi dengan lebih jelas (bukan hanya alert)
    
    5. DEPENDENSI YANG DIPERLUKAN:
       - Bootstrap 5 (CSS & JS)
       - Font Awesome atau Bootstrap Icons (opsional)
       - jQuery (tidak wajib, vanilla JS lebih disarankan)
    
    6. STRUKTUR DATABASE TERKAIT:
       
       Tabel 'input_aspirasi':
       - id_pelaporan (auto increment)
       - nis (foreign key ke siswa)
       - id_kategori (foreign key ke kategori)
       - id_lokasi (foreign key ke lokasi)
       - ket (VARCHAR 100 - PERLU DIUBAH KE TEXT)
       - foto (VARCHAR 100 - path file)
       - timestamps
       
       Tabel 'aspirasi':
       - id_aspirasi (sama dengan id_pelaporan)
       - status (enum: Menunggu/Proses/Selesai)
       - feedback (text, nullable)
       - foto_feedback (string, nullable)
       - timestamps
    
    7. REKOMENDASI PERBAIKAN KE DEPAN:
       
       a. UBAH TIPE DATA:
          Schema::table('input_aspirasi', function ($table) {
              $table->text('ket')->change(); // dari varchar(100) ke text
          });
       
       b. TAMBAHKAN VALIDASI UNIK (opsional):
          - Cegah siswa spam laporan dalam waktu singkat
          - Tambahkan cooldown 5 menit antar laporan
       
       c. TAMBAHKAN NOTIFIKASI:
          - Email notifikasi ke admin saat ada laporan baru
          - WhatsApp notifikasi (jika memungkinkan)
       
       d. TAMBAHKAN DRAFT:
          - Simpan sementara form yang belum selesai
          - Gunakan localStorage atau session
    
    8. TESTING CHECKLIST:
       - [ ] Semua dropdown terisi data
       - [ ] Upload foto berhasil (cek folder)
       - [ ] Data tersimpan di kedua tabel (input_aspirasi & aspirasi)
       - [ ] Redirect ke dashboard setelah sukses
       - [ ] Session flash message muncul
       - [ ] Log aktivitas tercatat
       - [ ] Error handling untuk file terlalu besar
       - [ ] Error handling untuk kategori/lokasi kosong
    
    9. PERBANDINGAN DENGAN VERSI SEBELUMNYA:
       - Lokasi sudah dinamis dari database (tidak hardcoded lagi)
       - Perbaikan ini memungkinkan admin menambah/mengedit lokasi
       - Pastikan migration lokasi sudah dijalankan sebelum menggunakan fitur ini
--}}
@endsection