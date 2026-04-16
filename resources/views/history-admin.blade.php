@extends('layouts.app')

@section('content')
{{-- 1. CSS DATATABLES --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">

<div class="container py-4 text-start">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark text-start">Riwayat Penanganan Aspirasi</h2>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm border-0 rounded-3 p-4">
        <div class="table-responsive">
            <table id="historyTable" class="table table-hover align-middle mb-0">
                <thead style="background-color: #800000; color: white;">
                    <tr>
                        <th class="py-3 ps-4 text-start">No</th>
                        <th class="py-3 text-start">Pelapor & Kategori</th>
                        <th class="py-3 text-start">Lokasi & Detail</th>
                        <th class="py-3 text-start">Status</th>
                        <th class="py-3 text-start">Feedback Admin</th>
                        <th class="py-3 text-start">Foto Bukti</th> {{-- FOTO FEEDBACK ADMIN --}}
                        <th class="py-3 pe-4 text-start">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    @php
                        $status_color = 'secondary';
                        if($h->status == 'Proses') $status_color = 'warning';
                        if($h->status == 'Selesai') $status_color = 'success';
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-start">{{ $loop->iteration }}</td>
                        
                        {{-- PELAPOR + KATEGORI --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $h->nama }}</div>
                            <div class="small text-muted">{{ $h->ket_kategori }}</div>
                        </td>

                        {{-- LOKASI + DETAIL --}}
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $h->nama_lokasi }}</div>
                            <div class="small text-muted text-wrap" style="max-width: 200px;">{{ $h->ket }}</div>
                        </td>

                        {{-- STATUS --}}
                        <td class="text-start">
                            <span class="badge rounded-pill bg-{{ $status_color }} {{ $h->status == 'Proses' ? 'text-dark' : '' }} px-3">
                                {{ $h->status }}
                            </span>
                        </td>

                        {{-- FEEDBACK ADMIN --}}
                        <td class="text-start">
                            @if($h->feedback)
                                <div class="p-2 border-start border-4 border-{{ $status_color }} bg-light small text-dark" style="min-width: 150px;">
                                    {{ $h->feedback }}
                                </div>
                            @else
                                <span class="text-muted small italic">Belum ditanggapi</span>
                            @endif
                        </td>

                        {{-- FOTO BUKTI DARI ADMIN --}}
                        <td class="text-start">
                            @if(!empty($h->foto_feedback))
                                <img src="{{ asset('upload_feedback/'.$h->foto_feedback) }}" width="50" height="50" class="rounded shadow-sm border img-thumbnail" style="object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalFeedback{{ $h->id_pelaporan }}">
                            @else
                                <span class="text-muted small italic">No Bukti</span>
                            @endif
                        </td>

                        {{-- WAKTU --}}
                        <td class="pe-4 text-start">
                            <div class="small fw-bold">{{ date('d/m/Y', strtotime($h->created_at)) }}</div>
                            <div class="extra-small text-muted">{{ date('H:i', strtotime($h->created_at)) }} WIB</div>
                        </td>
                    </tr>

                    {{-- MODAL FOTO FEEDBACK --}}
                    @if(!empty($h->foto_feedback))
                    <div class="modal fade" id="modalFeedback{{ $h->id_pelaporan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0 text-center">
                                <img src="{{ asset('upload_feedback/'.$h->foto_feedback) }}" class="img-fluid rounded-4 shadow-lg" data-bs-dismiss="modal" style="cursor: zoom-out;">
                            </div>
                        </div>
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
        $('#historyTable').DataTable({
            "language": {
                "sSearch": "Cari Riwayat:",
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
    .text-maroon { color: #800000; }
    .btn-back { display: inline-block; padding: 6px 20px; color: #800000; border: 1.5px solid #800000; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: 0.3s; }
    .btn-back:hover { background-color: #800000; color: white; }
    .img-thumbnail:hover { transform: scale(1.1); transition: 0.2s; }
    .extra-small { font-size: 0.75rem; }

    /* STYLE PAGINATION ANGKA */
    .dt-paging-button {
        border: 1px solid #ddd !important;
        margin: 0 3px !important;
        border-radius: 8px !important;
        color: #800000 !important;
        padding: 5px 12px !important;
    }
    .dt-paging-button.current {
        background: #800000 !important;
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