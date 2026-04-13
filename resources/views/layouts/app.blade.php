<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Sarana</title>

    {{-- BOOTSTRAP 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background: #fdfaf0; font-family: 'Plus Jakarta Sans', sans-serif; }
        .navbar { background: linear-gradient(90deg, #800000, #b03030) !important; }
        .dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(128,0,0,0.1); }
        .nav-link { font-weight: 600; transition: 0.3s; color: white !important; }
        
        /* Custom SweetAlert Style */
        .my-swal-popup { border-radius: 15px !important; border: 2px solid #800000 !important; }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        {{-- BRAND LOGO (Berfungsi sebagai tombol Dashboard) --}}
        <a class="navbar-brand fw-bold" 
           href="{{ session('role') == 'admin' ? route('dashboard.admin') : route('dashboard.siswa') }}">
            Sistem Pengaduan Sarana
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center gap-2">
                
                {{-- MENU BERDASARKAN ROLE --}}
                @if(session('role') == 'admin')
                    {{-- Navigasi ADMIN DIHAPUS agar bersih (Pindah ke Logo & Dashboard) --}}
                @else
                    {{-- Navigasi khusus SISWA (Tetap ada karena Siswa butuh akses cepat) --}}
                    <a href="{{ route('dashboard.siswa') }}" class="nav-link px-3">Dashboard</a>
                    <a href="{{ route('laporan.create') }}" class="nav-link px-3">Buat Aduan</a>
                    <a href="{{ route('siswa.history') }}" class="nav-link px-3">History</a>
                @endif

                {{-- AREA PROFIL DINAMIS --}}
                @if(session('role'))
                <div class="dropdown ms-3">
                    @php
                        $avatar = (session('role') == 'siswa' && isset($siswa) && $siswa->foto_profile) 
                                  ? asset('storage/foto_profile/'.$siswa->foto_profile) 
                                  : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
                    @endphp
                    
                    <img src="{{ $avatar }}" width="40" height="40"
                         class="rounded-circle border border-2 border-white dropdown-toggle shadow-sm"
                         style="cursor:pointer; object-fit:cover;" data-bs-toggle="dropdown">

                    <div class="dropdown-menu dropdown-menu-end p-0 overflow-hidden" style="width: 280px;">
                        
                        <div class="p-4 text-center bg-light border-bottom">
                            <img src="{{ $avatar }}" width="70" height="70"
                                 class="rounded-circle mb-3 shadow-sm border border-3 border-white" style="object-fit:cover;">
                            
                            @if(session('role') == 'siswa' && isset($siswa))
                                <h6 class="fw-bold mb-0 text-dark">{{ $siswa->nama }}</h6>
                                <small class="text-muted">Siswa Sekolah</small>
                            @else
                                <h6 class="fw-bold mb-0 text-dark">{{ session('username') }}</h6>
                                <small class="text-muted">Petugas Sarpras</small>
                            @endif
                        </div>

                        <div class="p-3">
                            @if(session('role') == 'siswa' && isset($siswa))
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">NIS</span>
                                    <span class="fw-bold small">{{ $siswa->nis }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted small">Kelas</span>
                                    <span class="fw-bold small">{{ $siswa->kelas }}</span>
                                </div>
                                <hr class="my-2 opacity-25">
                            @endif

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 fw-bold py-2" style="border-radius: 10px;">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
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

<div class="py-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#fffdf5',
        color: '#800000',
        iconColor: '#800000',
        customClass: { popup: 'my-swal-popup' },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
    @endif
</script>

</body>
</html>