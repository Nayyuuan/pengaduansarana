@extends('layouts.app')

@section('content')
{{-- 1. CSS DATATABLES --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">

<div class="container py-4 text-start">
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark text-start">Kelola Aspirasi Warga Sekolah</h2>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm border-0 rounded-3 p-4">
        <div class="table-responsive">
            <table id="myTable" class="table table-hover align-middle mb-0">
                <thead style="background-color: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4 text-start">No</th>
                        <th class="py-3 text-start">Pelapor & Kategori</th>
                        <th class="py-3 text-start">Lokasi & Detail</th>
                        <th class="py-3 text-start">Foto Laporan</th>
                        <th class="py-3 text-start">Status</th>
                        <th class="py-3 pe-4 text-start">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $l)
                    <tr>
                        <td class="ps-4 fw-bold text-start">{{ $loop->iteration }}</td>
                        
                        {{-- PELAPOR + KATEGORI (NIS & BLOK DIHAPUS) --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $l->nama }}</div>
                            <div class="small text-muted">{{ $l->ket_kategori }}</div>
                        </td>

                        {{-- LOKASI + DETAIL --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $l->nama_lokasi }}</div>
                            <div class="small text-muted text-wrap" style="max-width: 250px;">{{ $l->ket }}</div>
                        </td>

                        <td class="text-start">
                            @if($l->foto)
                                <img src="{{ asset('upload_aspirasi/'.$l->foto) }}" width="55" height="55" class="rounded shadow-sm border" style="object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $l->id_pelaporan }}">
                            @else
                                <span class="text-muted small italic">No Photo</span>
                            @endif
                        </td>

                        <td class="text-start">
                            @php
                                $status_color = 'secondary';
                                if($l->status == 'Proses') $status_color = 'warning text-dark';
                                if($l->status == 'Selesai') $status_color = 'success';
                            @endphp
                            <span class="badge rounded-pill bg-{{ $status_color }} px-3">{{ $l->status }}</span>
                        </td>

                        <td class="pe-4 text-start">
                            <button class="btn btn-maroon btn-sm px-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $l->id_pelaporan }}">Tanggapi</button>
                        </td>
                    </tr>

                    <!-- MODAL TANGGAPI -->
                    <div class="modal fade" id="modalTanggapi{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0" style="border-radius: 20px;">
                                <form action="{{ route('admin.tanggapi', $l->id_pelaporan) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body p-4 text-start">
                                        <h5 class="fw-bold text-maroon mb-3">Kasih Tanggapan</h5>
                                        <div class="mb-3 p-3 bg-light rounded-3 small">
                                            <p class="mb-1 text-muted">Aduan: <b>"{{ $l->ket }}"</b></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Status</label>
                                            <select name="status" class="form-select shadow-none" required>
                                                <option value="Menunggu" {{ $l->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Proses" {{ $l->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                                <option value="Selesai" {{ $l->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Balasan Petugas</label>
                                            <textarea name="feedback" class="form-control shadow-none" rows="4" required>{{ $l->feedback }}</textarea>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label fw-bold small">Foto Bukti (Optional)</label>
                                            <input type="file" name="foto_feedback" class="form-control shadow-none" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold" style="border-radius: 10px;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL FOTO --}}
                    @if($l->foto)
                    <div class="modal fade" id="modalFoto{{ $l->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered"><div class="modal-content bg-transparent border-0 text-center"><img src="{{ asset('upload_aspirasi/'.$l->foto) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal"></div></div>
                    </div>
                    @endif

                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- JS DATATABLES --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

<script>
    $(document).ready( function () {
        $('#myTable').DataTable({
            "searching": true, 
            "paging": true,
            "ordering": true,
            "language": {
                "sSearch": "Cari Laporan:",
                "sLengthMenu": "Tampilkan _MENU_ data",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "oPaginate": {
                    "sPrevious": "←",
                    "sNext": "→"
                }
            }
        });
    });
</script>

<style>
    .btn-maroon { background: #800000; color: white; border: none; }
    .btn-maroon:hover { background: #550000; color: white; }
    .text-maroon { color: #800000; }
    .btn-back { display: inline-block; padding: 6px 20px; color: #800000; border: 1.5px solid #800000; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; }
    .btn-back:hover { background-color: #800000; color: white; }

    .dt-paging-button {
        border: 1px solid #ddd !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        color: #800000 !important;
        padding: 5px 12px !important;
    }
    .dt-paging-button.current {
        background: #ffffff !important;
        color: white !important;
        border: none !important;
    }
    .dt-paging-button:hover:not(.current) {
        background: #fdf2f2 !important;
        color: #800000 !important;
        border-color: #800000 !important;
    }
</style>
@endsection