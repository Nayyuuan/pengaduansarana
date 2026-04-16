<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaDashboardController extends Controller
{
    // ==========================================
    // 1. DASHBOARD UTAMA SISWA
    // ==========================================

    /**
     * Menampilkan halaman dashboard utama siswa beserta statistik dan daftar laporan
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * ALUR FUNGSI:
     * 1. Cek session nis, jika tidak ada redirect ke login
     * 2. Ambil data siswa berdasarkan nis, jika tidak ditemukan hapus session
     * 3. Hitung statistik laporan siswa:
     *    - total: seluruh laporan siswa
     *    - menunggu: laporan dengan status 'Menunggu'
     *    - diproses: laporan dengan status 'Proses'
     *    - selesai: laporan dengan status 'Selesai'
     * 4. Ambil seluruh data laporan siswa untuk ditampilkan di dashboard
     * 
     * CATATAN:
     * - Statistik menggunakan JOIN dengan tabel aspirasi untuk mendapatkan status
     * - Laporan diurutkan dari yang terbaru (created_at DESC)
     * - Foto feedback dari admin ikut ditampilkan (foto_feedback)
     */
    public function index()
    {
        $nis = session('nis');
        if (!$nis) {
            return redirect('/login');
        }

        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        if (!$siswa) {
            session()->flush();
            return redirect('/login');
        }

        // --- HITUNG STATISTIK ---
        $total = DB::table('input_aspirasi')->where('nis', $nis)->count();

        $menunggu = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->where('input_aspirasi.nis', $nis)
            ->where('aspirasi.status', 'Menunggu')
            ->count();

        $diproses = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->where('input_aspirasi.nis', $nis)
            ->where('aspirasi.status', 'Proses')
            ->count();

        $selesai = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->where('input_aspirasi.nis', $nis)
            ->where('aspirasi.status', 'Selesai')
            ->count();

        // Ambil data laporan (DITAMBAH FOTO FEEDBACK)
        $laporan = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->join('lokasi', 'input_aspirasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->where('input_aspirasi.nis', $nis)
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'aspirasi.foto_feedback', 'kategori.ket_kategori', 'lokasi.nama_lokasi')
            ->orderBy('input_aspirasi.created_at', 'desc')
            ->get();

        return view('dashboard-siswa', compact('siswa', 'total', 'menunggu', 'diproses', 'selesai', 'laporan'));
    }

    // ==========================================
    // 2. HALAMAN FORM BUAT ADUAN
    // ==========================================

    /**
     * Menampilkan halaman form untuk membuat aduan/laporan baru
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * DATA YANG DISIAPKAN:
     * - $siswa: data siswa yang sedang login
     * - $kategori: seluruh data kategori dari tabel kategori
     * - $lokasi: seluruh data lokasi dari tabel lokasi
     * 
     * CATATAN:
     * - Pastikan tabel kategori dan lokasi sudah terisi data sebelum digunakan
     * - Session nis wajib ada, jika tidak redirect ke login
     */
    public function create()
    {
        $nis = session('nis');
        if (!$nis) {
            return redirect('/login');
        }

        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        $kategori = DB::table('kategori')->get();
        
        // AMBIL DATA LOKASI DARI DATABASE
        $lokasi = DB::table('lokasi')->get(); 

        return view('buat-aduan', compact('kategori', 'siswa', 'lokasi'));
    }

    // ==========================================
    // 3. PROSES SIMPAN ADUAN BARU
    // ==========================================

    /**
     * Menyimpan aduan/laporan baru ke database
     * 
     * @param Request $request Berisi: id_kategori, id_lokasi, ket, foto
     * @return \Illuminate\Http\RedirectResponse
     * 
     * VALIDASI:
     * - id_kategori: required (wajib pilih kategori)
     * - id_lokasi: required (wajib pilih lokasi)
     * - ket: required (deskripsi aduan wajib diisi)
     * - foto: required|image|max:2048 (foto wajib, format gambar, maksimal 2MB)
     * 
     * PROSES PENYIMPANAN:
     * 1. Upload file foto ke folder public/upload_aspirasi
     * 2. Simpan ke tabel input_aspirasi, ambil ID-nya (insertGetId)
     * 3. Simpan ke tabel aspirasi dengan status awal 'Menunggu'
     * 4. Catat log aktivitas di tabel log_aktivitas
     * 
     * CATATAN PENTING:
     * - id_aspirasi di tabel aspirasi = id_pelaporan di tabel input_aspirasi (relasi 1:1)
     * - Dua tabel terpisah: input_aspirasi (data laporan) dan aspirasi (status & feedback)
     * - Folder upload_aspirasi harus memiliki permission write
     */
    public function store(Request $request)
    {
        $nis = session('nis');

        $request->validate([
            'id_kategori' => 'required',
            'id_lokasi' => 'required',
            'ket' => 'required',
            'foto' => 'required|image|max:2048'
        ]);

        // Upload Foto
        $namaFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('upload_aspirasi'), $namaFoto);
        }

        // SIMPAN KE input_aspirasi & AMBIL ID-NYA
        $idTerakhir = DB::table('input_aspirasi')->insertGetId([
            'nis' => $nis,
            'id_kategori' => $request->id_kategori,
            'id_lokasi' => $request->id_lokasi,
            'ket' => $request->ket,
            'foto' => $namaFoto,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // SIMPAN KE aspirasi
        DB::table('aspirasi')->insert([
            'id_aspirasi' => $idTerakhir,
            'status' => 'Menunggu',
            'id_kategori' => $request->id_kategori,
            'feedback' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // LOG AKTIVITAS
        DB::table('log_aktivitas')->insert([
            'nis' => $nis,
            'aktivitas' => 'Mengirim laporan pengaduan baru',
            'created_at' => now()
        ]);

        return redirect()->route('dashboard.siswa')->with('success', 'Laporan Berhasil Terkirim!');
    }

    // ==========================================
    // 4. HALAMAN RIWAYAT (HISTORY)
    // ==========================================

    /**
     * Menampilkan halaman riwayat seluruh laporan siswa
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * PERBEDAAN DENGAN INDEX():
     * - Halaman history menampilkan SEMUA laporan tanpa batasan
     * - Halaman dashboard menampilkan laporan + statistik ringkas
     * - Data yang ditampilkan sama, hanya konteks halaman yang berbeda
     * 
     * CATATAN:
     * - Data laporan diurutkan dari yang terbaru
     * - Menampilkan feedback dan foto feedback dari admin jika sudah ada
     */
    public function history()
    {
        $nis = session('nis');
        if (!$nis) {
            return redirect('/login');
        }
        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        $lokasi = DB::table('lokasi')->get(); 

        $laporan = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->join('lokasi', 'input_aspirasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->where('input_aspirasi.nis', $nis)
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'aspirasi.foto_feedback', 'kategori.ket_kategori', 'lokasi.nama_lokasi')
            ->orderBy('input_aspirasi.created_at', 'desc')
            ->get();

        return view('history-siswa', compact('siswa', 'laporan', 'lokasi'));
    }

    // ==========================================
    // 5. PROSES UPDATE/EDIT ADUAN
    // ==========================================

    /**
     * Mengupdate data laporan yang sudah ada
     * 
     * @param Request $request Berisi: id_kategori, id_lokasi, ket, foto (opsional)
     * @param int $id ID laporan (id_pelaporan)
     * @return \Illuminate\Http\RedirectResponse
     * 
     * VALIDASI:
     * - id_kategori: required
     * - id_lokasi: required
     * - ket: required
     * - foto: nullable|image|max:2048 (opsional, jika ada harus gambar maksimal 2MB)
     * 
     * PROSES UPDATE:
     * - Jika upload foto baru: update semua field termasuk foto
     * - Jika tidak upload foto: update hanya id_kategori, id_lokasi, ket
     * 
     * CATATAN PENTING:
     * - Method ini TIDAK bisa mengubah status laporan
     * - Status hanya bisa diubah oleh admin melalui fitur tanggapi
     * - Foto lama tidak dihapus secara otomatis (perlu penanganan manual jika ingin menghapus)
     * - Sebaiknya tambahkan pengecekan kepemilikan laporan (apakah laporan milik siswa yang login)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required',
            'id_lokasi' => 'required',
            'ket' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('upload_aspirasi'), $namaFoto);

            DB::table('input_aspirasi')->where('id_pelaporan', $id)->update([
                'id_kategori' => $request->id_kategori,
                'id_lokasi' => $request->id_lokasi,
                'ket' => $request->ket,
                'foto' => $namaFoto,
                'updated_at' => now()
            ]);
        } else {
            DB::table('input_aspirasi')->where('id_pelaporan', $id)->update([
                'id_kategori' => $request->id_kategori,
                'id_lokasi' => $request->id_lokasi,
                'ket' => $request->ket,
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Laporan berhasil diperbarui!');
    }

    // ==========================================
    // 6. PROSES HAPUS/BATALKAN ADUAN
    // ==========================================

     /**
     * Menghapus laporan (soft delete / hard delete)
     * 
     * @param int $id ID laporan (id_aspirasi = id_pelaporan)
     * @return \Illuminate\Http\RedirectResponse
     * 
     * PROSES HAPUS:
     * 1. Hapus data dari tabel aspirasi berdasarkan id_aspirasi
     * 2. Hapus data dari tabel input_aspirasi berdasarkan id_pelaporan
     * 
     * CATATAN PENTING UNTUK MAINTENANCE:
     * - Method ini menghapus data SECARA PERMANEN (hard delete)
     * - Tidak ada notifikasi ke admin saat siswa membatalkan laporan
     * - Foto laporan di folder upload_aspirasi TIDAK ikut terhapus (perlu penanganan tambahan)
     * - Sebaiknya tambahkan pengecekan kepemilikan laporan untuk keamanan:
     *   $laporan = DB::table('input_aspirasi')->where('id_pelaporan', $id)->where('nis', session('nis'))->first();
     *   if (!$laporan) { return back()->with('error', 'Akses ditolak!'); }
     * 
     * REKOMENDASI PERBAIKAN:
     * - Pertimbangkan untuk menggunakan soft delete (deleted_at) daripada hard delete
     * - Atau ubah status menjadi 'Dibatalkan' agar tetap tercatat dalam history
     */
    public function destroy($id)
    {
        DB::table('aspirasi')->where('id_aspirasi', $id)->delete();
        DB::table('input_aspirasi')->where('id_pelaporan', $id)->delete();

        return back()->with('success', 'Laporan berhasil dibatalkan!');
    }
}