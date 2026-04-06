<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Sarana</title>

    {{-- BOOTSTRAP 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { background: #fdfaf0; font-family: 'Inter', sans-serif; }
        .navbar { background: linear-gradient(90deg, #800000, #b03030) !important; }
        .dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(128,0,0,0.1); }
        
        /* Custom SweetAlert Style */
        .my-swal-popup {
            border-radius: 15px !important;
            border: 2px solid #800000 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard.siswa') }}">
            <span>Sistem Pengaduan Sarana</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="{{ route('dashboard.siswa') }}" class="nav-link text-white px-3">Dashboard</a>
                <a href="{{ route('laporan.create') }}" class="nav-link text-white px-3">Buat Aduan</a>
                <a href="{{ route('siswa.history') }}" class="nav-link text-white px-3">History</a>

                @if(session('nis') && isset($siswa))
                <div class="dropdown ms-3">
                    <img src="{{ asset('storage/foto_profile/'.$siswa->foto_profile) }}"
                         width="40" height="40"
                         class="rounded-circle border border-2 border-white dropdown-toggle shadow-sm"
                         style="cursor:pointer; object-fit:cover;" data-bs-toggle="dropdown">
                    <div class="dropdown-menu dropdown-menu-end p-0 overflow-hidden" style="width: 260px;">
                        <div class="p-4 text-center bg-light border-bottom">
                            <img src="{{ asset('storage/foto_profile/'.$siswa->foto_profile) }}"
                                 width="70" height="70" class="rounded-circle mb-3 shadow-sm border border-3 border-white" style="object-fit:cover;">
                            <h6 class="fw-bold mb-0">{{ $siswa->nama }}</h6>
                            <small class="text-muted">Siswa Aktif</small>
                        </div>
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
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
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

<div class="py-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top', {{-- POSISI DI ATAS TENGAH --}}
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#fffdf5', {{-- CREAM --}}
        color: '#800000', {{-- MAROON --}}
        iconColor: '#800000',
        customClass: {
            popup: 'my-swal-popup'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif
</script>
</body>
</html>