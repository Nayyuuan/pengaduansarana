<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================
        // TABEL ADMIN
        // =====================
        Schema::create('admin', function (Blueprint $table) {
            $table->string('username', 30)->primary();
            $table->string('password');
            $table->timestamps();
        });

        // =====================
        // TABEL SISWA
        // =====================
        Schema::create('siswa', function (Blueprint $table) {
            $table->char('nis', 30)->primary(); 
            $table->string('nama', 150);
            $table->string('kelas', 10);
            $table->string('foto_profile')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        // =====================
        // TABEL KATEGORI
        // =====================
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('ket_kategori', 30);
            $table->timestamps();
        });

        // =====================
        // TABEL ASPIRASI
        // =====================
        Schema::create('aspirasi', function (Blueprint $table) {
        $table->unsignedBigInteger('id_aspirasi')->primary(); 
        $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
        $table->unsignedBigInteger('id_kategori');
        $table->text('feedback')->nullable();
        $table->timestamps();

        $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // =====================
        // TABEL INPUT ASPIRASI
        // =====================
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->id('id_pelaporan');
            $table->char('nis', 30); 
            $table->unsignedBigInteger('id_kategori');
            $table->string('lokasi', 50);
            $table->string('ket', 100);
            $table->string('foto', 100)->nullable();
            $table->timestamps();

            $table->foreign('nis')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // =====================
        // TABEL LOG AKTIVITAS
        // =====================
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

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('input_aspirasi');
        Schema::dropIfExists('aspirasi');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('admin');
    }
};