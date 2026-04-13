@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark">Kelola Aspirasi Siswa 📋</h2>
        <p class="text-muted">Gunakan filter untuk mencari laporan tertentu dan berikan tanggapan segera.</p>
    </div>

    <!-- CARD FILTER -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.kelola') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="small fw-bold">Filter Siswa</label>
                    <select name="nis" class="form-select">
                        <option value="">-- Semua Siswa --</option>
                        @foreach($semuaSiswa as $s)
                            <option value="{{ $s->nis }}" {{ request('nis') == $s->nis ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Filter Kategori</label>
                    <select name="id_kategori" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($semuaKategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ request('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->ket_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-maroon w-100 fw-bold">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead style="background-color: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4">No</th>
                        <th class="py-3">Pelapor</th>
                        <th class="py-3">Detail & Lokasi</th>
                        <th class="py-3">Foto</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $l)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $loop->iteration }}</td>
                        <td class="text-start">
                            <div class="fw-bold">{{ $l->nama }}</div>
                            <small class="text-muted">{{ $l->nis }}</small>
                        </td>
                        <td class="text-start">
                            <span class="badge bg-light text-maroon border mb-1">{{ $l->ket_kategori }}</span>
                            <div class="small fw-bold text-dark">{{ $l->lokasi }}</div>
                            <div class="small text-muted text-truncate" style="max-width: 200px;">{{ $l->ket }}</div>
                        </td>
                        <td>
                            @if($l->foto)
                                <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" width="50" height="50" class="rounded border shadow-sm" style="object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $l->id_pelaporan }}">
                            @else
                                <span class="text-muted small italic">No Photo</span>
                            @endif
                        </td>
                        <td>
                            @if($l->status == 'Menunggu')
                                <span class="badge rounded-pill bg-secondary px-3">Menunggu</span>
                            @elseif($l->status == 'Proses')
                                <span class="badge rounded-pill bg-warning text-dark px-3">Diproses</span>
                            @else
                                <span class="badge rounded-pill bg-success px-3">Selesai</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <button class="btn btn-maroon btn-sm px-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">Tanggapi</button>
                        </td>
                    </tr>

                    <!-- MODAL TANGGAPI -->
                    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0" style="border-radius: 20px;">
                                <form action="{{ route('admin.tanggapi', $l->id_pelaporan) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <h5 class="fw-bold text-maroon mb-3">Kasih Tanggapan</h5>
                                        <div class="mb-3 p-3 bg-light rounded-3 small">
                                            <p class="mb-1 text-muted">Aspirasi: <b>"{{ $l->ket }}"</b></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Update Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="Menunggu" {{ $l->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Proses" {{ $l->status == 'Proses' ? 'selected' : '' }}>Sedang Diproses</option>
                                                <option value="Selesai" {{ $l->status == 'Selesai' ? 'selected' : '' }}>Selesai / Tuntas</option>
                                            </select>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold">Pesan Feedback</label>
                                            <textarea name="feedback" class="form-control" rows="4" placeholder="Balasan untuk siswa..." required>{{ $l->feedback }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Tanggapan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL FOTO -->
                    @if($l->foto)
                    <div class="modal fade" id="modalFoto{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0">
                                <div class="modal-body p-0 text-center">
                                    <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" class="img-fluid rounded-4">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Laporan tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-maroon { background: #800000; color: white; border: none; transition: 0.3s; }
    .btn-maroon:hover { background: #550000; color: white; transform: translateY(-2px); }
    .text-maroon { color: #800000; }
    .btn-back { display: inline-block; padding: 6px 20px; color: #800000; border: 1.5px solid #800000; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; }
    .btn-back:hover { background-color: #800000; color: white; }
</style>
@endsection