@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark">Riwayat Penanganan Aspirasi</h2>
        <p class="text-muted">Daftar seluruh aspirasi yang telah diproses oleh sistem.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #800000;">
                        <tr class="text-maroon">
                            <th class="py-3 ps-4">No</th>
                            <th class="py-3">Waktu Lapor</th>
                            <th class="py-3">Identitas Pelapor</th>
                            <th class="py-3">Isi Aspirasi</th>
                            <th class="py-3">Status Akhir</th>
                            <th class="py-3 pe-4">Tanggapan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $h)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="small fw-bold">{{ date('d M Y', strtotime($h->created_at)) }}</div>
                                <div class="extra-small text-muted">{{ date('H:i', strtotime($h->created_at)) }} WIB</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $h->nama }}</div>
                                <div class="extra-small text-muted">NIS: {{ $h->nis }}</div>
                            </td>
                            <td>
                                <span class="badge bg-maroon-subtle text-maroon mb-1">{{ $h->ket_kategori }}</span>
                                <div class="small text-truncate" style="max-width: 200px;">{{ $h->ket }}</div>
                            </td>
                            <td>
                                @if($h->status == 'Menunggu')
                                    <span class="badge bg-secondary">Menunggu</span>
                                @elseif($h->status == 'Proses')
                                    <span class="badge bg-warning text-dark">Proses</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                @if($h->feedback)
                                    <div class="p-2 rounded bg-light border-start border-3 border-success small">
                                        {{ $h->feedback }}
                                    </div>
                                @else
                                    <span class="text-muted small italic">Belum ditanggapi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Data riwayat masih kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-maroon { color: #800000; }
    .bg-maroon-subtle { background-color: #fdf2f2; color: #800000; border: 1px solid #ffcccc; }
    .extra-small { font-size: 0.75rem; }

    /* STYLE TOMBOL KEMBALI */
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