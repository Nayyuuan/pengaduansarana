<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaDashboardController extends Controller
{
    // ==========================================
    // 1. DASHBOARD UTAMA SISWA
    // ==========================================
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

        // --- HITUNG STATISTIK (DITAMBAH MENUNGGU) ---
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

        // Ambil data laporan
        $laporan = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->where('input_aspirasi.nis', $nis)
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'kategori.ket_kategori')
            ->orderBy('input_aspirasi.created_at', 'desc')
            ->get();

        return view('dashboard-siswa', compact('siswa', 'total', 'menunggu', 'diproses', 'selesai', 'laporan'));
    }

    // ==========================================
    // 2. HALAMAN FORM BUAT ADUAN
    // ==========================================
    public function create()
    {
        $nis = session('nis');
        if (!$nis) {
            return redirect('/login');
        }

        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        $kategori = DB::table('kategori')->get();

        return view('buat-aduan', compact('kategori', 'siswa'));
    }

    // ==========================================
    // 3. PROSES SIMPAN ADUAN BARU
    // ==========================================
    public function store(Request $request)
    {
        $nis = session('nis');

        $request->validate([
            'id_kategori' => 'required',
            'lokasi' => 'required',
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
            'lokasi' => $request->lokasi,
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
            'aktivitas' => 'Mengirim laporan pengaduan baru di ' . $request->lokasi,
            'created_at' => now()
        ]);

        return redirect()->route('dashboard.siswa')->with('success', 'Laporan Berhasil Terkirim!');
    }

    // ==========================================
    // 4. HALAMAN RIWAYAT (HISTORY)
    // ==========================================
    public function history()
    {
        $nis = session('nis');
        if (!$nis) {
            return redirect('/login');
        }

        $siswa = DB::table('siswa')->where('nis', $nis)->first();

        $laporan = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->where('input_aspirasi.nis', $nis)
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'kategori.ket_kategori')
            ->orderBy('input_aspirasi.created_at', 'desc')
            ->get();

        return view('history-siswa', compact('siswa', 'laporan'));
    }

    // ==========================================
    // 5. PROSES UPDATE/EDIT ADUAN
    // ==========================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required',
            'lokasi' => 'required',
            'ket' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('upload_aspirasi'), $namaFoto);

            DB::table('input_aspirasi')->where('id_pelaporan', $id)->update([
                'id_kategori' => $request->id_kategori,
                'lokasi' => $request->lokasi,
                'ket' => $request->ket,
                'foto' => $namaFoto,
                'updated_at' => now()
            ]);
        } else {
            DB::table('input_aspirasi')->where('id_pelaporan', $id)->update([
                'id_kategori' => $request->id_kategori,
                'lokasi' => $request->lokasi,
                'ket' => $request->ket,
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Laporan berhasil diperbarui!');
    }

    // ==========================================
    // 6. PROSES HAPUS/BATALKAN ADUAN
    // ==========================================
    public function destroy($id)
    {
        DB::table('aspirasi')->where('id_aspirasi', $id)->delete();
        DB::table('input_aspirasi')->where('id_pelaporan', $id)->delete();

        return back()->with('success', 'Laporan berhasil dibatalkan!');
    }
}