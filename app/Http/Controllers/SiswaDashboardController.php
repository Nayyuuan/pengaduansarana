<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $nis = session('nis');
        if (!$nis) { return redirect('/login'); }
        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        if (!$siswa) { session()->flush(); return redirect('/login'); }

        $total = DB::table('input_aspirasi')->where('nis', $nis)->count();
        $diproses = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->where('input_aspirasi.nis', $nis)->where('aspirasi.status', 'Proses')->count();
        $selesai = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->where('input_aspirasi.nis', $nis)->where('aspirasi.status', 'Selesai')->count();

        $laporan = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->where('input_aspirasi.nis', $nis)
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'kategori.ket_kategori')
            ->orderBy('input_aspirasi.created_at', 'desc')->get();

        return view('dashboard-siswa', compact('siswa', 'total', 'diproses', 'selesai', 'laporan'));
    }

    public function create()
    {
        $nis = session('nis');
        if (!$nis) { return redirect('/login'); }
        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        $kategori = DB::table('kategori')->get();
        return view('buat-aduan', compact('kategori', 'siswa'));
    }

    public function store(Request $request)
    {
        $nis = session('nis');
        $request->validate([
            'id_kategori' => 'required',
            'lokasi' => 'required',
            'ket' => 'required',
            'foto' => 'required|image|max:2048'
        ]);

        $namaFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('upload_aspirasi'), $namaFoto);
        }

        $idTerakhir = DB::table('input_aspirasi')->insertGetId([
            'nis' => $nis, 'id_kategori' => $request->id_kategori, 'lokasi' => $request->lokasi,
            'ket' => $request->ket, 'foto' => $namaFoto, 'created_at' => now(), 'updated_at' => now()
        ]);

        DB::table('aspirasi')->insert([
            'id_aspirasi' => $idTerakhir, 'status' => 'Menunggu', 'id_kategori' => $request->id_kategori,
            'feedback' => null, 'created_at' => now(), 'updated_at' => now()
        ]);

        DB::table('log_aktivitas')->insert(['nis' => $nis, 'aktivitas' => 'Mengirim laporan pengaduan baru', 'created_at' => now()]);

        return redirect()->route('dashboard.siswa')->with('success', 'Mantap! Laporanmu sudah terkirim.');
    }

    public function history()
    {
        $nis = session('nis');
        if (!$nis) { return redirect('/login'); }
        $siswa = DB::table('siswa')->where('nis', $nis)->first();

        $laporan = DB::table('input_aspirasi')
            ->join('aspirasi', 'input_aspirasi.id_pelaporan', '=', 'aspirasi.id_aspirasi')
            ->join('kategori', 'input_aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->where('input_aspirasi.nis', $nis)
            ->select('input_aspirasi.*', 'aspirasi.status', 'aspirasi.feedback', 'kategori.ket_kategori')
            ->orderBy('input_aspirasi.created_at', 'desc')->get();

        return view('history-siswa', compact('siswa', 'laporan'));
    }
}