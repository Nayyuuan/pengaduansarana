<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat seluruh tabel database
     * 
     * URUTAN PEMBUATAN TABEL PENTING!
     * Tabel yang tidak memiliki foreign key dibuat terlebih dahulu
     * Tabel dengan foreign key dibuat setelah tabel referensinya
     * 
     * URUTAN YANG BENAR:
     * 1. admin (tidak ada foreign key)
     * 2. siswa (tidak ada foreign key)
     * 3. kategori (tidak ada foreign key)
     * 4. lokasi (tidak ada foreign key)
     * 5. aspirasi (foreign key ke kategori)
     * 6. input_aspirasi (foreign key ke siswa, kategori, lokasi)
     * 7. log_aktivitas (foreign key ke siswa dan admin)
     */
    public function up(): void
    {
        // ==========================================
        // 1. TABEL ADMIN
        // ==========================================
        /**
         * Tabel untuk menyimpan data administrator
         * 
         * FIELD:
         * - username: primary key, maksimal 30 karakter (digunakan untuk login)
         * - password: hash password menggunakan bcrypt
         * - timestamps: created_at dan updated_at otomatis
         * 
         * CATATAN: Admin login menggunakan username, bukan email
         */
        Schema::create('admin', function (Blueprint $table) {
            $table->string('username', 30)->primary();
            $table->string('password');
            $table->timestamps();
        });

        // ==========================================
        // 2. TABEL SISWA
        // ==========================================
        /**
         * Tabel untuk menyimpan data siswa/pengguna
         * 
         * FIELD:
         * - nis: primary key (Nomor Induk Siswa), char 30 karakter
         * - nama: nama lengkap siswa, maksimal 150 karakter
         * - kelas: kelas siswa, misal: 10A, 11B, 12C (10 karakter cukup)
         * - foto_profile: path/nama file foto profil (nullable)
         * - password: hash password menggunakan bcrypt
         * - timestamps: created_at dan updated_at otomatis
         * 
         * CATATAN: 
         * - NIS digunakan untuk login siswa
         * - Foto profil disimpan di storage/foto_profile/
         */
        Schema::create('siswa', function (Blueprint $table) {
            $table->char('nis', 30)->primary(); 
            $table->string('nama', 150);
            $table->string('kelas', 10);
            $table->string('foto_profile')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        // ==========================================
        // 3. TABEL KATEGORI
        // ==========================================
        /**
         * Tabel untuk menyimpan kategori aspirasi/pengaduan
         * 
         * FIELD:
         * - id_kategori: auto-increment primary key
         * - ket_kategori: keterangan kategori, misal: Fasilitas, Kebersihan, Keamanan (30 karakter)
         * - timestamps: created_at dan updated_at otomatis
         * 
         * RELASI: Digunakan oleh tabel aspirasi dan input_aspirasi
         * 
         * CATATAN: 
         * - Kategori yang sudah digunakan tidak boleh dihapus (sudah diproteksi di controller)
         * - Sebaiknya isi minimal: Fasilitas, Kebersihan, Kesehatan, Keamanan
         */        
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('ket_kategori', 30);
            $table->timestamps();
        });

        // ==========================================
        // 4. TABEL LOKASI
        // ==========================================
        /**
         * Tabel untuk menyimpan lokasi kejadian aspirasi
         * 
         * FIELD:
         * - id_lokasi: auto-increment primary key
         * - nama_lokasi: nama lokasi, misal: Gedung A, Lapangan, Kantin (50 karakter)
         * - timestamps: created_at dan updated_at otomatis
         * 
         * RELASI: Digunakan oleh tabel input_aspirasi
         * 
         * CATATAN:
         * - Lokasi yang sudah digunakan tidak boleh dihapus (sudah diproteksi di controller)
         * - Sebaiknya isi sesuai dengan area/lokasi yang ada di sekolah
         */
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id('id_lokasi');
            $table->string('nama_lokasi', 50);
            $table->timestamps();
        });

        // ==========================================
        // 5. TABEL ASPIRASI (DITAMBAH FOTO FEEDBACK)
        // ==========================================
        /**
         * Tabel untuk menyimpan status dan feedback dari admin
         * 
         * FIELD:
         * - id_aspirasi: primary key (berelasi 1:1 dengan input_aspirasi.id_pelaporan)
         * - status: enum status pengaduan (Menunggu, Proses, Selesai) default Menunggu
         * - id_kategori: foreign key ke tabel kategori
         * - feedback: teks tanggapan/feedback dari admin (nullable)
         * - foto_feedback: foto bukti perbaikan dari admin (nullable)
         * - timestamps: created_at dan updated_at otomatis
         * 
         * RELASI:
         * - id_aspirasi = id_pelaporan di tabel input_aspirasi (1:1 relationship)
         * - id_kategori foreign key ke kategori (onDelete cascade)
         * 
         * CATATAN PENTING:
         * - Tabel ini dipisah dari input_aspirasi untuk memisahkan data laporan (statis) 
         *   dengan status/feedback (dinamis)
         * - Foto_feedback disimpan di folder public/upload_feedback/
         * - Saat laporan dihapus, data di tabel ini juga ikut terhapus (cascade dari input_aspirasi? TIDAK!)
         *   Perhatikan: tidak ada foreign key dari input_aspirasi ke aspirasi, jadi hapus manual di controller
         */
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_aspirasi')->primary(); 
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->unsignedBigInteger('id_kategori');
            $table->text('feedback')->nullable();
            $table->string('foto_feedback')->nullable(); // <-- INI PERBAIKANNYA NAY! (Untuk Bukti Perbaikan)
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // ==========================================
        // 6. TABEL INPUT ASPIRASI (DENGAN ID_LOKASI)
        // ==========================================
        /**
         * Tabel untuk menyimpan data detail laporan/aspirasi dari siswa
         * 
         * FIELD:
         * - id_pelaporan: auto-increment primary key
         * - nis: foreign key ke tabel siswa (pelapor)
         * - id_kategori: foreign key ke tabel kategori
         * - id_lokasi: foreign key ke tabel lokasi
         * - ket: keterangan/detail aduan, maksimal 100 karakter
         * - foto: foto bukti aduan (nullable)
         * - timestamps: created_at dan updated_at otomatis
         * 
         * RELASI:
         * - nis → siswa (onDelete cascade: jika siswa dihapus, laporannya ikut terhapus)
         * - id_kategori → kategori (onDelete cascade)
         * - id_lokasi → lokasi (onDelete cascade)
         * - id_pelaporan = id_aspirasi di tabel aspirasi (1:1, tapi TIDAK ada foreign key)
         * 
         * CATATAN PENTING UNTUK MAINTENANCE:
         * - Tidak ada foreign key dari id_pelaporan ke aspirasi.id_aspirasi
         * - Saat menghapus laporan, HAPUS MANUAL kedua tabel (aspirasi dan input_aspirasi)
         * - Foto aduan disimpan di folder public/upload_aspirasi/
         * - File foto TIDAK otomatis terhapus saat data dihapus (perlu penanganan manual)
         * - Kolom ket hanya 100 karakter (mungkin perlu TEXT jika aduan panjang)
         */        
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->id('id_pelaporan');
            $table->char('nis', 30); 
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_lokasi'); 
            $table->string('ket', 100);
            $table->string('foto', 100)->nullable();
            $table->timestamps();

            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('cascade');
        });

        // ==========================================
        // 7. TABEL LOG AKTIVITAS
        // ==========================================
        /**
         * Tabel untuk mencatat semua aktivitas login/logout user
         * 
         * FIELD:
         * - id: auto-increment primary key
         * - nis: foreign key ke siswa (nullable, untuk aktivitas siswa)
         * - username: foreign key ke admin (nullable, untuk aktivitas admin)
         * - aktivitas: deskripsi aktivitas, misal: "Login ke sistem", "Logout dari sistem"
         * - timestamps: created_at dan updated_at otomatis
         * 
         * RELASI:
         * - nis → siswa (onDelete cascade)
         * - username → admin (onDelete cascade)
         * 
         * CATATAN PENTING:
         * - nis dan username bisa NULL karena:
         *   * Jika siswa logout, hanya nis yang terisi
         *   * Jika admin logout, hanya username yang terisi
         *   * Tidak mungkin keduanya terisi sekaligus dalam satu baris
         * - Digunakan untuk audit trail dan monitoring aktivitas user
         * - Data log sebaiknya dibersihkan secara berkala (misal: data > 3 bulan)
         * 
         * SARAN PENAMBAHAN:
         * - Bisa ditambahkan kolom ip_address untuk keamanan
         * - Bisa ditambahkan kolom user_agent untuk informasi perangkat
         */
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->char('nis', 30)->nullable(); 
            $table->string('username', 30)->nullable(); 
            $table->string('aktivitas');
            $table->timestamps();
            
            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreign('username')->references('username')->on('admin')->onDelete('cascade');
        });
    }

    /**
     * Rollback migration: menghapus semua tabel
     * 
     * URUTAN PENGHAPUSAN (kebalikan dari pembuatan):
     * 1. log_aktivitas (foreign key ke siswa dan admin)
     * 2. input_aspirasi (foreign key ke siswa, kategori, lokasi)
     * 3. aspirasi (foreign key ke kategori)
     * 4. lokasi (tidak memiliki foreign key ke tabel lain)
     * 5. kategori (tidak memiliki foreign key ke tabel lain)
     * 6. siswa (tidak memiliki foreign key ke tabel lain)
     * 7. admin (tidak memiliki foreign key ke tabel lain)
     * 
     * CATATAN: Urutan ini penting untuk menghindari error foreign key constraint
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('input_aspirasi');
        Schema::dropIfExists('aspirasi');
        Schema::dropIfExists('lokasi'); 
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('admin');
    }
};