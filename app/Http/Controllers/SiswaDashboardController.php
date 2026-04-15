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
        'lokasi' => 'required',
        'ket' => 'required',
        'foto' => 'required|image|max:2048'
        ]);

    $idKategoriFinal = $request->id_kategori;

    if ($request->id_kategori == 'lainnya') {
        // Cek dulu apakah kategori baru sudah diisi?
        if (!$request->filled('kategori_baru')) {
            return back()->with('error', 'Harap isi nama kategori barunya!');
        }

        // Simpan ke tabel kategori dulu
        $idKategoriFinal = DB::table('kategori')->insertGetId([
            'ket_kategori' => $request->kategori_baru,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    // 1. Simpan Foto (Kodingan kamu yang lama)
    $namaFoto = null;
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $namaFoto = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('upload_aspirasi'), $namaFoto);
    }

    // 2. SIMPAN KE input_aspirasi & AMBIL ID-NYA
    $idTerakhir = DB::table('input_aspirasi')->insertGetId([
        'nis' => $nis,
        'id_kategori' => $idKategoriFinal, 
        'lokasi' => $request->lokasi,
        'ket' => $request->ket,
        'foto' => $namaFoto,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // 3. SIMPAN KE aspirasi
    DB::table('aspirasi')->insert([
        'id_aspirasi' => $idTerakhir, 
        'status' => 'Menunggu',
        'id_kategori' => $idKategoriFinal, 
        'feedback' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // 4. LOG AKTIVITAS
    DB::table('log_aktivitas')->insert([
        'nis' => $nis,
        'aktivitas' => 'Mengirim laporan baru (Kategori: ' . ($request->kategori_baru ?? 'Pilihan list') . ')',
        'created_at' => now()
    ]);

    return redirect()->route('dashboard.siswa')->with('success', 'Laporan berhasil terkirim!');
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required',
            'lokasi' => 'required',
            'ket' => 'required',
            'foto' => 'nullable|image|max:2048'
        ]);

        // Cek apakah ada foto baru yang diupload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('upload_aspirasi'), $namaFoto);
            
            // Update tabel input_aspirasi beserta fotonya
            DB::table('input_aspirasi')->where('id_pelaporan', $id)->update([
                'id_kategori' => $request->id_kategori,
                'lokasi' => $request->lokasi,
                'ket' => $request->ket,
                'foto' => $namaFoto,
                'updated_at' => now()
            ]);
        } else {
            // Update tanpa ganti foto
            DB::table('input_aspirasi')->where('id_pelaporan', $id)->update([
                'id_kategori' => $request->id_kategori,
                'lokasi' => $request->lokasi,
                'ket' => $request->ket,
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hapus data di kedua tabel (karena relasi cascade, tapi buat jaga-jaga kita hapus manual)
        DB::table('aspirasi')->where('id_aspirasi', $id)->delete();
        DB::table('input_aspirasi')->where('id_pelaporan', $id)->delete();

        return back()->with('success', 'Laporan berhasil dibatalkan!');
    }
}