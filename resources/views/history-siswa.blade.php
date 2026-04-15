@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-3">
        <a href="{{ route('dashboard.siswa') }}" class="btn btn-sm mb-3 fw-bold" 
       style="color: #800000; border: 1px solid #800000; border-radius: 8px;">
        ← Kembali ke Dashboard
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark">Riwayat Aspirasi Saya</h2>
        <p class="text-muted">Daftar laporan kerusakan sarana yang telah kamu kirimkan.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead style="background-color: #800000; color: white;">
                        <tr>
                            <th class="py-3 ps-4">No</th>
                            <th class="py-3">Lokasi</th>
                            <th class="py-3">Keterangan</th>
                            <th class="py-3">Bukti Foto</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Feedback Admin</th>
                            <th class="py-3">Aksi</th> 
                            <th class="py-3 pe-4">Waktu Pembuatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $l)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $l->lokasi }}</span></td>
                            <td class="text-start" style="max-width: 200px;">{{ $l->ket }}</td>
                            <td>
                                @if($l->foto)
                                    <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" width="50" height="50" class="rounded border shadow-sm img-thumbnail" style="object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalFotoSiswa{{ $l->id_pelaporan }}">
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
                            <td>
                                @if($l->feedback)
                                    <div class="p-2 border-start border-3 border-danger bg-light small text-dark text-start">
                                        {{ $l->feedback }}
                                    </div>
                                @else
                                    <span class="text-muted small italic">Belum ada tanggapan</span>
                                @endif
                            </td>

                            {{-- TOMBOL EDIT & HAPUS (HANYA MUNCUL JIKA STATUS MENUNGGU) --}}
                            <td>
                                @if($l->status == 'Menunggu')
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $l->id_pelaporan }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('laporan.destroy', $l->id_pelaporan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan laporan ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <i class="bi bi-lock-fill text-muted" title="Sudah diproses, tidak bisa diubah"></i>
                                @endif
                            </td>

                            <td class="pe-4 text-start">
                                <div class="small">
                                    <div class="fw-bold">
                                        @php
                                            $hari = date('l', strtotime($l->created_at));
                                            $daftar_hari = [
                                                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                                                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                                            ];
                                        @endphp
                                        {{ $daftar_hari[$hari] }}, {{ date('d M Y', strtotime($l->created_at)) }}
                                    </div>
                                    <div class="text-muted">{{ date('H:i', strtotime($l->created_at)) }} WIB</div>
                                </div>
                            </td>
                        </tr>

                        <!-- MODAL EDIT -->
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
                                                <label class="form-label fw-bold small">Kategori Sarana</label>
                                                <select name="id_kategori" class="form-select shadow-sm" required>
                                                    {{-- Kita perlu variabel $kategori dari controller --}}
                                                    @foreach(DB::table('kategori')->get() as $k)
                                                        <option value="{{ $k->id_kategori }}" {{ $l->id_kategori == $k->id_kategori ? 'selected' : '' }}>{{ $k->ket_kategori }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Lokasi</label>
                                                <input type="text" name="lokasi" class="form-control shadow-sm" value="{{ $l->lokasi }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Keterangan</label>
                                                <textarea name="ket" class="form-control shadow-sm" rows="3" required>{{ $l->ket }}</textarea>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold small">Ganti Foto (Optional)</label>
                                                <input type="file" name="foto" class="form-control shadow-sm" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL PREVIEW FOTO -->
                        @if($l->foto)
                        <div class="modal fade" id="modalFotoSiswa{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body p-0 text-center">
                                        <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal" style="cursor: zoom-out;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data laporan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-maroon { background: #800000; color: white; border: none; }
    .btn-maroon:hover { background: #550000; color: white; }
    .btn-back { display: inline-block; padding: 6px 20px; color: #800000; border: 1.5px solid #800000; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; }
    .btn-back:hover { background-color: #800000; color: white; }
</style>
@endsection