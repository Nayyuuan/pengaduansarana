<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Sarana</title>

    {{-- BOOTSTRAP 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- GOOGLE FONTS (Optional: Biar font lebih modern) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
        background: #fdfaf0; 
        font-family: 'Inter', sans-serif;
        }
        .navbar {
        background: linear-gradient(90deg, #800000, #b03030) !important;
        }
        .dropdown-menu {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(128,0,0,0.1); 
        }
    </style>
</head>

<body>

{{-- ================= NAVBAR UTAMA ================= --}}
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm"
     style="background: linear-gradient(90deg, #5b6ee1, #4a63d4);">

    <div class="container">
        {{-- Nama Aplikasi di Pojok Kiri --}}
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard.siswa') }}">
            <span>Sistem Pengaduan Sarana</span>
        </a>

        {{-- Tombol Hamburger untuk tampilan Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu Navigasi --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center gap-2">
                
                {{-- Link Menu --}}
                <a href="{{ route('dashboard.siswa') }}" class="nav-link text-white px-3">Dashboard</a>
                <a href="{{ route('laporan.create') }}" class="nav-link text-white px-3">Buat Aduan</a>
                {{-- History kita arahkan ke dashboard karena tabel riwayat ada di sana --}}
                <a href="{{ route('siswa.history') }}" class="nav-link text-white px-3">History</a>

                {{-- CEK APAKAH USER SUDAH LOGIN --}}
                @if(session('nis') && isset($siswa))
                <div class="dropdown ms-3">
                    
                    {{-- Foto Profil Mini (Klik untuk buka menu) --}}
                    <img src="{{ asset('storage/foto_profile/'.$siswa->foto_profile) }}"
                         width="40" height="40"
                         class="rounded-circle border border-2 border-white dropdown-toggle shadow-sm"
                         style="cursor:pointer; object-fit:cover;"
                         data-bs-toggle="dropdown">

                    {{-- AREA DROPDOWN PROFIL --}}
                    <div class="dropdown-menu dropdown-menu-end p-0 overflow-hidden" style="width: 260px;">
                        
                        {{-- Bagian Atas Dropdown (Header) --}}
                        <div class="p-4 text-center bg-light border-bottom">
                            <img src="{{ asset('storage/foto_profile/'.$siswa->foto_profile) }}"
                                 width="70" height="70"
                                 class="rounded-circle mb-3 shadow-sm border border-3 border-white"
                                 style="object-fit:cover;">
                            <h6 class="fw-bold mb-0">{{ $siswa->nama }}</h6>
                            <small class="text-muted">Siswa Aktif</small>
                        </div>

                        {{-- Bagian Tengah Dropdown (Detail Data) --}}
                        <div class="p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">NIS</span>
                                <span class="fw-bold small">{{ $siswa->nis }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted small">Kelas</span>
                                <span class="fw-bold small">{{ $siswa->kelas }}</span>
                            </div>

                            <hr class="my-2 opacity-25">

                            {{-- TOMBOL LOGOUT --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" 
                                        style="border-radius: 10px;">
                                    {{-- Icon Pintu Keluar (SVG) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-1.439 1.439a.5.5 0 0 0 .708.708l3-3z"/>
                                    </svg>
                                    Logout Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</nav>


{{-- ================= AREA CONTENT ================= --}}
<div class="py-4">
    @yield('content')
</div>


{{-- BOOTSTRAP JS (Wajib ada untuk fungsi Dropdown & Toggle) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>