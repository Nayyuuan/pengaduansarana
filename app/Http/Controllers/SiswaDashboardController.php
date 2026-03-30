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

        // Ambil data siswa 
        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        if (!$siswa) { session()->flush(); return redirect('/login'); }

        $total = DB::table('aspirasi')->where('nis', $nis)->count();
        $diproses = DB::table('aspirasi')->where('nis', $nis)->where('status', 'Proses')->count();
        $selesai = DB::table('aspirasi')->where('nis', $nis)->where('status', 'Selesai')->count();

        $laporan = DB::table('aspirasi')
            ->leftJoin('kategori', 'aspirasi.id_kategori', '=', 'kategori.id_kategori')
            ->where('aspirasi.nis', $nis)
            ->select('aspirasi.*', 'kategori.ket_kategori')
            ->orderBy('aspirasi.created_at', 'desc')
            ->get();

        // Kirim $siswa ke view
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

    // Fungsi store 
    public function store(Request $request)
    {
        $nis = session('nis');
        if (!$nis) { return redirect('/login'); }

        $request->validate([
            'id_kategori' => 'required',
            'lokasi' => 'required|max:50',
            'ket' => 'required'
        ]);

        DB::table('aspirasi')->insert([
            'nis' => $nis,
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'ket' => $request->ket,
            'status' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('dashboard.siswa')->with('success', 'Laporan berhasil dikirim!');
    }

        public function history()
    {
        $nis = session('nis');
        if (!$nis) { return redirect('/login'); }
        $siswa = DB::table('siswa')->where('nis', $nis)->first();

        $laporan = DB::table('aspirasi')
        ->leftJoin('kategori', 'aspirasi.id_kategori', '=', 'kategori.id_kategori')
        ->where('aspirasi.nis', $nis)
        ->select('aspirasi.*', 'kategori.ket_kategori')
        ->orderBy('aspirasi.created_at', 'desc')
        ->get();

    return view('history-siswa', compact('siswa', 'laporan'));
    }
}