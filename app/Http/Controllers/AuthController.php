<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login
     * 
     * @return \Illuminate\View\View
     */
    public function login() { return view('login'); }

    /**
     * Menampilkan halaman form registrasi siswa
     * 
     * @return \Illuminate\View\View
     */
    public function register() { return view('register'); }

    /**
     * Memproses autentikasi login (Admin atau Siswa)
     * 
     * @param Request $request Berisi username/nis dan password
     * @return \Illuminate\Http\RedirectResponse
     * 
     * ALUR LOGIN:
     * 1. Cek ke tabel 'admin' dengan username
     *    - Jika ada dan password cocok → session role='admin'
     *    - Catat log aktivitas admin (username)
     *    - Redirect ke /dashboard-admin
     * 
     * 2. Cek ke tabel 'siswa' dengan nis
     *    - Jika ada dan password cocok → session role='siswa', nis
     *    - Catat log aktivitas siswa (nis)
     *    - Redirect ke /dashboard-siswa
     * 
     * 3. Jika keduanya gagal → kembali ke halaman login dengan pesan error
     * 
     * CATATAN PENTING:
     * - Admin login menggunakan 'username'
     * - Siswa login menggunakan 'nis'
     * - Password disimpan dalam bentuk hash (Hash::check)
     */
    public function prosesLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        // CEK LOGIN SEBAGAI ADMIN
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

        // CEK LOGIN SEBAGAI SISWA
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
    
    /**
     * Memproses pendaftaran akun siswa baru
     * 
     * @param Request $request Berisi: nis, nama, kelas, password, password_confirmation, foto_profile (opsional)
     * @return \Illuminate\Http\RedirectResponse
     * 
     * VALIDASI:
     * - nis: required, unique di tabel siswa
     * - nama: required
     * - kelas: required
     * - password: required, confirmed (harus sama dengan password_confirmation), minimal 6 karakter
     * - foto_profile: nullable, image, format jpg/jpeg/png, maksimal 2MB (2048 KB)
     * 
     * PROSES UPLOAD FOTO:
     * - File disimpan di folder: public/storage/foto_profile/
     * - Nama file: timestamp_nama_asli_file.jpg
     * - Jika tidak upload foto, kolom foto_profile bernilai null
     * 
     * CATATAN:
     * - Password disimpan dalam bentuk hash (Hash::make)
     * - Setelah registrasi berhasil, redirect ke halaman login
     */
    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required',
            'kelas' => 'required',
            'password' => 'required|confirmed|min:6',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // PROSES UPLOAD FOTO PROFILE (JIKA ADA)    
        $namaFoto = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/foto_profile'), $namaFoto);
        }

        // SIMPAN DATA SISWA KE DATABASE
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

    /**
     * Melakukan proses logout dan membersihkan session
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * PROSES LOGOUT:
     * 1. Mencatat aktivitas logout ke tabel log_aktivitas
     *    - Jika user adalah siswa → mengisi kolom nis
     *    - Jika user adalah admin → mengisi kolom username
     * 2. Menghapus semua data session (session()->flush())
     * 3. Redirect ke halaman login dengan pesan sukses
     * 
     * CATATAN:
     * - Method ini bisa diakses oleh admin maupun siswa
     * - Log aktivitas tetap tercatat meskipun salah satu kolom (nis/username) bernilai null
     */
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