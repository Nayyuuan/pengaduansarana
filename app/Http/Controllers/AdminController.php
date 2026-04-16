<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Memeriksa apakah pengguna saat ini adalah admin berdasarkan session
     * 
     * @return bool True jika role adalah admin, false jika tidak
     */
    private function checkAdmin() {
        return session('role') === 'admin';
    }

    // ==========================================
    // 1. DASHBOARD ADMIN
    // ==========================================
    
    /**
     * Menampilkan halaman dashboard admin dengan statistik ringkasan
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * Statistik yang ditampilkan:
     * - totalPengguna : jumlah seluruh siswa
     * - totalAduan    : jumlah seluruh aspirasi
     * - menunggu      : aspirasi status Menunggu
     * - diproses      : aspirasi status Proses
     * - selesai       : aspirasi status Selesai
     */

    public function index()
    {
        if (!$this->checkAdmin()) { return redirect('/login')->with('error', 'Login Admin dulu ya!'); }
        $totalPengguna = DB::table('siswa')->count();
        $totalAduan    = DB::table('aspirasi')->count();
        $menunggu      = DB::table('aspirasi')->where('status', 'Menunggu')->count();
        $diproses      = DB::table('aspirasi')->where('status', 'Proses')->count();
        $selesai       = DB::table('aspirasi')->where('status', 'Selesai')->count();

        return view('dashboard-admin', compact('totalPengguna', 'totalAduan', 'menunggu', 'diproses', 'selesai'));
    }

    // ==========================================
    // 2. KELOLA ASPIRASI (FITUR FILTER)
    // ==========================================

    /**
     * Menampilkan dan memfilter daftar aspirasi/laporan siswa
     * 
     * @param Request $request Berisi parameter filter: nis, id_kategori, status, tanggal
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * Filter yang tersedia:
     * - nis         : filter berdasarkan NIS siswa
     * - id_kategori : filter berdasarkan kategori aspirasi
     * - status      : filter berdasarkan status (Menunggu/Proses/Selesai)
     * - tanggal     : filter berdasarkan tanggal pembuatan laporan
     */
    public function kelola(Request $request)
    {
        if (!$this->checkAdmin()) { return redirect('/login'); }
        $semuaSiswa = DB::table('siswa')->get();
        $semuaKategori = DB::table('kategori')->get();
        $query = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('siswa', 'input_aspirasi.nis', '=', 'siswa.nis')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->join('lokasi', 'input_aspirasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'aspirasi.foto_feedback', 'siswa.nama', 'kategori.ket_kategori','lokasi.nama_lokasi');

        if ($request->filled('nis')) { $query->where('input_aspirasi.nis', $request->nis); }
        if ($request->filled('id_kategori')) { $query->where('input_aspirasi.id_kategori', $request->id_kategori); }
        if ($request->filled('status')) { $query->where('aspirasi.status', $request->status); }
        if ($request->filled('tanggal')) { $query->whereDate('input_aspirasi.created_at', $request->tanggal); }

        $laporan = $query->orderBy('input_aspirasi.created_at', 'desc')->get();
        return view('admin-kelola', compact('laporan', 'semuaSiswa', 'semuaKategori'));
    }

    // ==========================================
    // 3. TANGGAPI LAPORAN (UPLOAD FOTO)
    // ==========================================

    /**
     * Memberikan tanggapan/feedback terhadap aspirasi tertentu
     * 
     * @param Request $request Berisi: status, feedback, dan opsional foto_feedback
     * @param int|string $id ID aspirasi (id_aspirasi)
     * @return \Illuminate\Http\RedirectResponse
     * 
     * Proses:
     * 1. Upload file foto feedback ke folder public/upload_feedback (jika ada)
     * 2. Update status dan feedback di tabel aspirasi
     * 3. Nama file foto disimpan di kolom foto_feedback
     */
    public function tanggapi(Request $request, $id)
    {
        $namaFoto = null;
        if ($request->hasFile('foto_feedback')) {
            $file = $request->file('foto_feedback');
            $namaFoto = "feedback_" . time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('upload_feedback'), $namaFoto);
        }
        $updateData = ['status' => $request->status, 'feedback' => $request->feedback, 'updated_at' => now()];
        if ($namaFoto) { $updateData['foto_feedback'] = $namaFoto; }
        DB::table('aspirasi')->where('id_aspirasi', $id)->update($updateData);
        return back()->with('success', 'Tanggapan berhasil dikirim!');
    }

    // ==========================================
    // 4. HISTORY ADMIN
    // ==========================================

    /**
     * Menampilkan riwayat seluruh aspirasi yang sudah ditanggapi
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * Data diurutkan berdasarkan updated_at terbaru (waktu terakhir tanggapan/update)
     */
    public function history()
    {
        if (!$this->checkAdmin()) { return redirect('/login'); }
        $history = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('siswa', 'input_aspirasi.nis', '=', 'siswa.nis')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->join('lokasi', 'input_aspirasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'aspirasi.foto_feedback', 'siswa.nama', 'kategori.ket_kategori', 'lokasi.nama_lokasi')
            ->orderBy('aspirasi.updated_at', 'desc')->get();
        return view('history-admin', compact('history'));
    }

    // ==========================================
    // 5. CRUD KATEGORI (DENGAN PROTEKSI HAPUS)
    // ==========================================

    /**
     * Menampilkan daftar semua kategori aspirasi
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function kategori() {
        if (!$this->checkAdmin()) { return redirect('/login'); }
        $kategori = DB::table('kategori')->get();
        return view('admin-kategori', compact('kategori'));
    }

    /**
     * Menambahkan kategori baru ke database
     * 
     * @param Request $request Berisi ket_kategori (nama kategori)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function kategoriStore(Request $request) {
        DB::table('kategori')->insert(['ket_kategori' => $request->ket_kategori, 'created_at' => now()]);
        return back()->with('success', 'Kategori berhasil ditambah!');
    }

    /**
     * Mengupdate data kategori yang sudah ada
     * 
     * @param Request $request Berisi ket_kategori yang baru
     * @param int $id ID kategori (id_kategori)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function kategoriUpdate(Request $request, $id) {
        DB::table('kategori')->where('id_kategori', $id)->update(['ket_kategori' => $request->ket_kategori, 'updated_at' => now()]);
        return back()->with('success', 'Kategori berhasil diubah!');
    }

    /**
     * Menghapus kategori (dengan proteksi relasi)
     * 
     * @param int $id ID kategori yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     * 
     * CATATAN PENTING:
     * - Kategori TIDAK BISA dihapus jika sudah digunakan di tabel input_aspirasi
     * - Hal ini untuk menjaga integritas data dan menghindari error foreign key
     */
    public function kategoriDestroy($id) {
        if (!$this->checkAdmin()) { return redirect('/login'); }

        // PROTEKSI: Cek apakah kategori sudah dipakai di laporan manapun
        $terpakai = DB::table('input_aspirasi')->where('id_kategori', $id)->exists();
        if ($terpakai) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah ada laporan siswa yang menggunakan kategori ini!');
        }

        DB::table('kategori')->where('id_kategori', $id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // ==========================================
    // 6. CRUD LOKASI (DENGAN PROTEKSI HAPUS)
    // ==========================================

    /**
     * Menampilkan daftar semua lokasi
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function lokasi() {
        if (!$this->checkAdmin()) { return redirect('/login'); }
        $lokasi = DB::table('lokasi')->get();
        return view('admin-lokasi', compact('lokasi'));
    }

    /**
     * Menambahkan lokasi baru ke database
     * 
     * @param Request $request Berisi nama_lokasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lokasiStore(Request $request) {
        DB::table('lokasi')->insert(['nama_lokasi' => $request->nama_lokasi, 'created_at' => now()]);
        return back()->with('success', 'Lokasi berhasil ditambah!');
    }

    /**
     * Mengupdate data lokasi yang sudah ada
     * 
     * @param Request $request Berisi nama_lokasi baru
     * @param int $id ID lokasi (id_lokasi)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lokasiUpdate(Request $request, $id) {
        DB::table('lokasi')->where('id_lokasi', $id)->update(['nama_lokasi' => $request->nama_lokasi, 'updated_at' => now()]);
        return back()->with('success', 'Lokasi berhasil diubah!');
    }

    /**
     * Menghapus lokasi (dengan proteksi relasi)
     * 
     * @param int $id ID lokasi yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     * 
     * CATATAN PENTING:
     * - Lokasi TIDAK BISA dihapus jika sudah digunakan di tabel input_aspirasi
     * - Hal ini untuk menjaga agar data laporan tetap memiliki referensi lokasi yang valid
     */
    public function lokasiDestroy($id) {
        if (!$this->checkAdmin()) { return redirect('/login'); }

        // PROTEKSI: Cek apakah lokasi sudah dipakai di laporan manapun
        $terpakai = DB::table('input_aspirasi')->where('id_lokasi', $id)->exists();
        if ($terpakai) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena terdapat data laporan yang tersambung dengan lokasi ini!');
        }

        DB::table('lokasi')->where('id_lokasi', $id)->delete();
        return back()->with('success', 'Lokasi berhasil dihapus!');
    }
}