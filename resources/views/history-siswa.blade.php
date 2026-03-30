@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <!-- Tombol Kembali ke Dashboard -->
    <a href="{{ route('dashboard.siswa') }}" class="btn btn-sm mb-3 fw-bold" 
       style="color: #800000; border: 1px solid #800000; border-radius: 8px;">
        ← Kembali ke Dashboard
    </a>

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
                            <th class="py-3">Status</th>
                            <th class="py-3">Feedback Admin</th>
                            <th class="py-3 pe-4">Waktu Pembuatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $l)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $l->lokasi }}</span></td>
                            <td class="text-start" style="max-width: 250px;">{{ $l->ket }}</td>
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
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data laporan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling khusus tombol outline maroon */
    .btn-outline-maroon {
        color: #800000;
        border-color: #800000;
        font-size: 0.9rem;
        font-weight: 500;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-outline-maroon:hover {
        background-color: #800000;
        color: white;
    }

    .table thead th {
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
    }

    .table tbody td {
        font-size: 0.9rem;
    }
</style>
@endsection