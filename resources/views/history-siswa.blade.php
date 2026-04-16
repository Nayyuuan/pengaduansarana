@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <a href="{{ route('dashboard.siswa') }}" class="btn btn-sm mb-3 fw-bold" 
       style="color: #800000; border: 1px solid #800000; border-radius: 8px;">
        ← Kembali ke Dashboard
    </a>
    <div class="row justify-content-center">
    <div class="mb-4 text-start">
        <h2 class="fw-bold text-dark">Riwayat Laporan Saya</h2>
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #800000; color: white;">
                        <tr>
                            <th class="py-3 ps-4 text-start">No</th>
                            <th class="py-3 text-start">Kategori</th>
                            <th class="py-3 text-start">Lokasi & Keterangan</th>
                            <th class="py-3 text-center">Foto Laporan</th>
                            <th class="py-3 text-start">Status</th>
                            <th class="py-3 text-start">Feedback Admin</th>
                            <th class="py-3 text-center">Foto Feedback</th>
                            <th class="py-3 text-start">Aksi</th> 
                            <th class="py-3 pe-4 text-start">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $l)
                        @php
                            $status_color = 'secondary';
                            if($l->status == 'Proses') $status_color = 'warning';
                            if($l->status == 'Selesai') $status_color = 'success';
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold text-start">{{ $loop->iteration }}</td>
                            
                            <td class="text-start">
                                <span class="fw-bold text-dark">{{ $l->ket_kategori }}</span>
                            </td>

                            <td class="text-start">
                                <div class="fw-bold text-maroon small">{{ $l->nama_lokasi }}</div>
                                <div class="text-muted small text-wrap" style="max-width: 200px;">{{ $l->ket }}</div>
                            </td>
                            
                            <td class="text-center">
                                @if($l->foto)
                                    <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" width="55" height="55" 
                                         class="rounded-3 shadow-sm border img-laporan" 
                                         style="object-fit: cover; cursor: pointer;" 
                                         data-bs-toggle="modal" data-bs-target="#modalFotoSiswa{{ $l->id_pelaporan }}">
                                @else
                                    <span class="text-muted extra-small italic">No Photo</span>
                                @endif
                            </td>

                            <td class="text-start">
                                <span class="badge rounded-pill bg-{{ $status_color }} {{ $l->status == 'Proses' ? 'text-dark' : '' }} px-3" style="font-size: 0.75rem;">
                                    {{ $l->status }}
                                </span>
                            </td>

                            <td class="text-start">
                                @if($l->feedback)
                                    <div class="p-2 border-start border-4 border-{{ $status_color }} bg-light small text-dark" style="min-width: 150px;">
                                        {{ $l->feedback }}
                                    </div>
                                @else
                                    <span class="text-muted small italic">Belum ditanggapi</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(isset($l->foto_feedback) && $l->foto_feedback)
                                    <img src="{{ asset('upload_feedback/'.$l->foto_feedback) }}" width="55" height="55" 
                                         class="rounded-3 shadow-sm border border-success img-laporan" 
                                         style="object-fit: cover; cursor: pointer;" 
                                         data-bs-toggle="modal" data-bs-target="#modalFeedback{{ $l->id_pelaporan }}">
                                @else
                                    <i class="bi bi-camera-video-off text-muted"></i>
                                @endif
                            </td>

                            <td class="text-start">
                                @if($l->status == 'Menunggu')
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $l->id_pelaporan }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('laporan.destroy', $l->id_pelaporan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <i class="bi bi-lock-fill text-muted"></i>
                                @endif
                            </td>

                            <td class="pe-4 text-start">
                                <div class="small fw-bold">{{ date('d M Y', strtotime($l->created_at)) }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ date('H:i', strtotime($l->created_at)) }} WIB</div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="fw-bold text-dark">Edit Laporan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('laporan.update', $l->id_pelaporan) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Kategori</label>
                                                <select name="id_kategori" class="form-select" required>
                                                    @foreach(DB::table('kategori')->get() as $k)
                                                        <option value="{{ $k->id_kategori }}" {{ $l->id_kategori == $k->id_kategori ? 'selected' : '' }}>{{ $k->ket_kategori }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Lokasi</label>
                                                <select name="id_lokasi" class="form-select" required>
                                                    @foreach($lokasi as $lok)
                                                        <option value="{{ $lok->id_lokasi }}" {{ $l->id_lokasi == $lok->id_lokasi ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Keterangan</label>
                                                <textarea name="ket" class="form-control" rows="3" required>{{ $l->ket }}</textarea>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold small">Ganti Foto Bukti (Optional)</label>
                                                <input type="file" name="foto" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px; background: #800000; color:white;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if($l->foto)
                        <div class="modal fade" id="modalFotoSiswa{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-transparent border-0 text-center"><img src="{{ asset('upload_aspirasi/'.$l->foto) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal" style="cursor: zoom-out;"></div></div>
                        </div>
                        @endif

                        @if(!empty($l->foto_feedback))
                        <div class="modal fade" id="modalFeedback{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-transparent border-0 text-center">
                                    <img src="{{ asset('upload_feedback/'.$l->foto_feedback) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal" style="cursor: zoom-out;">
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr><td colspan="9" class="text-center py-5 text-muted">Belum ada data laporan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-maroon { color: #800000; }
    .extra-small { font-size: 0.7rem; }
    .table thead th { font-weight: 600; font-size: 0.8rem; border: none; }
    .table tbody td { font-size: 0.8rem; }
    
    /* FIX: Spesifikasikan agar hanya gambar di dalam tabel yang jadi kotak */
    table img.img-laporan { 
        border-radius: 8px !important; 
        transition: 0.3s; 
    }
    table img.img-laporan:hover { 
        transform: scale(1.1); 
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important; 
    }
</style>
@endsection