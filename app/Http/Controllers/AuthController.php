<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login() { return view('login'); }
    public function register() { return view('register'); }

    public function prosesLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $admin = DB::table('admin')->where('username', $username)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            session(['role' => 'admin', 'username' => $admin->username]);
            DB::table('log_aktivitas')->insert([
                'username' => $admin->username,
                'aktivitas' => 'Login ke sistem',
                'created_at' => now()
            ]);
            return redirect('/dashboard-admin')->with('success', 'Halo Admin! Selamat bekerja.');
        }

        $siswa = DB::table('siswa')->where('nis', $username)->first();
        if ($siswa && Hash::check($password, $siswa->password)) {
            session(['role' => 'siswa', 'nis' => $siswa->nis]);
            DB::table('log_aktivitas')->insert([
                'nis' => $siswa->nis,
                'aktivitas' => 'Login ke sistem',
                'created_at' => now()
            ]);
            return redirect('/dashboard-siswa')->with('success', 'Yey! Berhasil masuk. Halo ' . $siswa->nama);
        }

        return back()->with('error', 'Akses ditolak! NIS atau Password salah.');
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'kelas' => 'required',
            'password' => 'required|confirmed|min:6',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $namaFoto = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/foto_profile'), $namaFoto);
        }

        DB::table('siswa')->insert([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'foto_profile' => $namaFoto,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/login')->with('success','Akun berhasil dibuat! Silakan login ya.');
    }

    public function logout()
    {
        $nis = session('nis');
        $username = session('username');
        DB::table('log_aktivitas')->insert([
            'nis' => $nis,
            'username' => $username,
            'aktivitas' => 'Logout dari sistem',
            'created_at' => now()
        ]);
        session()->flush();
        return redirect('/login')->with('success', 'Sampai jumpa lagi!');
    }
}