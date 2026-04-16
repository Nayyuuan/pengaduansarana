@extends('layouts.app')

@section('content')
<div class="container py-4 text-start">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Lokasi Sarana</h2>
        </div>
        <button class="btn btn-maroon rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLokasi">
            <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
        </button>
    </div>

    {{-- TABEL LOKASI BIASA --}}
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4" style="width: 80px;">No</th>
                        <th class="py-3">Nama Lokasi / Gedung</th>
                        <th class="py-3 pe-4 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasi as $l)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $l->nama_lokasi }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#modalEditLokasi{{ $l->id_lokasi }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.lokasi.destroy', $l->id_lokasi) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini? Data laporan yang menggunakan lokasi ini mungkin akan terpengaruh.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL EDIT LOKASI -->
                    <div class="modal fade" id="modalEditLokasi{{ $l->id_lokasi }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold text-maroon">Ubah Nama Lokasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.lokasi.update', $l->id_lokasi) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <label class="form-label small fw-bold">Nama Lokasi</label>
                                        <input type="text" name="nama_lokasi" class="form-control py-2" value="{{ $l->nama_lokasi }}" required style="border-radius: 10px;">
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted italic">Belum ada data lokasi yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH LOKASI -->
<div class="modal fade" id="modalTambahLokasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-maroon">Tambah Lokasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.lokasi.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label small fw-bold">Nama Lokasi / Ruangan</label>
                    <input type="text" name="nama_lokasi" class="form-control py-2" placeholder="Misal: Gedung Olahraga" required style="border-radius: 10px;">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Tambah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-maroon { background: #800000; color: white; border: none; transition: 0.3s; }
    .btn-maroon:hover { background: #550000; color: white; transform: translateY(-2px); }
    .text-maroon { color: #800000; }
    
    .btn-back {
        display: inline-block;
        padding: 6px 20px;
        color: #800000;
        border: 1.5px solid #800000;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .btn-back:hover {
        background-color: #800000;
        color: white;
    }
</style>
@endsection