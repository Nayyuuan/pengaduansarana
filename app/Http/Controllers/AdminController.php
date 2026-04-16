<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function checkAdmin() {
        return session('role') === 'admin';
    }

    // ==========================================
    // 1. DASHBOARD ADMIN (DI-UPDATE: TOTAL PENGGUNA & DIPROSES)
    // ==========================================
    public function index()
    {
        if (!$this->checkAdmin()) { return redirect('/login')->with('error', 'Login Admin dulu ya!'); }

        // Mengambil data statistik untuk dashboard
        $totalPengguna = DB::table('siswa')->count(); // Nama variabel diubah jadi lebih universal
        $totalAduan    = DB::table('aspirasi')->count();
        $menunggu      = DB::table('aspirasi')->where('status', 'Menunggu')->count();
        $diproses      = DB::table('aspirasi')->where('status', 'Proses')->count(); // Kolom baru
        $selesai       = DB::table('aspirasi')->where('status', 'Selesai')->count();

        return view('dashboard-admin', compact('totalPengguna', 'totalAduan', 'menunggu', 'diproses', 'selesai'));
    }

    // ==========================================
    // 2. KELOLA ASPIRASI (FITUR FILTER)
    // ==========================================
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
            ->select(
                'input_aspirasi.*', 
                'aspirasi.status', 
                'aspirasi.feedback', 
                'aspirasi.foto_feedback', 
                'siswa.nama', 
                'kategori.ket_kategori',
                'lokasi.nama_lokasi'
            );

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
    public function tanggapi(Request $request, $id)
    {
        $request->validate([
            'foto_feedback' => 'nullable|image|max:2048'
        ]);

        $namaFoto = null;
        if ($request->hasFile('foto_feedback')) {
            $file = $request->file('foto_feedback');
            $namaFoto = "feedback_" . time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('upload_feedback'), $namaFoto);
        }

        $updateData = [
            'status' => $request->status,
            'feedback' => $request->feedback,
            'updated_at' => now()
        ];

        if ($namaFoto) {
            $updateData['foto_feedback'] = $namaFoto;
        }

        DB::table('aspirasi')->where('id_aspirasi', $id)->update($updateData);

        DB::table('log_aktivitas')->insert([
            'username' => session('username'),
            'aktivitas' => 'Memberikan tanggapan pada laporan #' . $id,
            'created_at' => now()
        ]);

        return back()->with('success', 'Tanggapan berhasil dikirim!');
    }

    // ==========================================
    // 4. HISTORY ADMIN
    // ==========================================
    public function history()
    {
        if (!$this->checkAdmin()) { return redirect('/login'); }

        $history = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('siswa', 'input_aspirasi.nis', '=', 'siswa.nis')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->join('lokasi', 'input_aspirasi.id_lokasi', '=', 'lokasi.id_lokasi')
            ->select(
                'input_aspirasi.*', 
                'aspirasi.status', 
                'aspirasi.feedback', 
                'aspirasi.foto_feedback',
                'siswa.nama', 
                'kategori.ket_kategori',
                'lokasi.nama_lokasi'
            )
            ->orderBy('aspirasi.updated_at', 'desc')
            ->get();

        return view('history-admin', compact('history'));
    }
}