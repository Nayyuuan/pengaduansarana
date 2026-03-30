<?php

namespace App\Http\Controllers;

// menangani input form
use Illuminate\Http\Request;

// query database
use Illuminate\Support\Facades\DB;

// enkripsi password
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ======================
    // HALAMAN LOGIN
    // ======================
    public function login()
    {
        return view('login');
    }

    // ======================
    // HALAMAN REGISTER
    // ======================
    public function register()
    {
        return view('register');
    }

    // ======================
    // PROSES LOGIN
    // ======================
    public function prosesLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        // ===== CEK ADMIN =====
        $admin = DB::table('admin')
            ->where('username', $username)
            ->first();

        if ($admin && Hash::check($password, $admin->password)) {

            session([
                'role' => 'admin',
                'username' => $admin->username
            ]);

            return redirect('/dashboard-admin');
        }

        // ===== CEK SISWA =====
        $siswa = DB::table('siswa')
            ->where('nis', $username)
            ->first();

        if ($siswa && Hash::check($password, $siswa->password)) {

            session([
                'role' => 'siswa',
                'nis' => $siswa->nis
            ]);

            return redirect('/dashboard-siswa');
        }

        return back()->with('error', 'Username atau password salah');
    }

    // ======================
    // PROSES REGISTER SISWA
    // ======================
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

        // ===== UPLOAD FOTO =====
        if ($request->hasFile('foto_profile')) {

            $file = $request->file('foto_profile');

            // nama file unik
            $namaFoto = time().'_'.$file->getClientOriginalName();

            // simpan ke storage/app/public/foto_profile
            $file->storeAs('foto_profile', $namaFoto, 'public');
        }

        // simpan ke database
        DB::table('siswa')->insert([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'foto_profile' => $namaFoto,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/login')->with('success','Registrasi berhasil!');
    }

    // ======================
    // DASHBOARD SISWA
    // ======================
    public function dashboardSiswa()
    {
    $nis = session('nis');

    if(!$nis){
        return redirect('/login');
    }

    $siswa = DB::table('siswa')
        ->where('nis',$nis)
        ->first();

    return view('dashboard-siswa', compact('siswa'));
    }

    // ======================
    // DASHBOARD ADMIN
    // ======================
    public function dashboardAdmin()
    {
        // cek role admin
        if (session('role') !== 'admin') {
            return redirect('/login');
        }

        $admin = DB::table('admin')
            ->where('username', session('username'))
            ->first();

        return view('dashboard-admin', compact('admin'));
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}