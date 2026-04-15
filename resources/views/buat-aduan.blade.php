@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <a href="{{ route('dashboard.siswa') }}" class="btn btn-sm mb-3 fw-bold" 
       style="color: #800000; border: 1px solid #800000; border-radius: 8px;">
        ← Kembali ke Dashboard
    </a>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 15px;">
                <div class="card-header text-white fw-bold py-3 text-center" style="background: #800000;">
                    BUAT ADUAN / ASPIRASI BARU
                </div>
                
                <div class="card-body p-4 bg-white">
                    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- KATEGORI SARANA -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Kategori Sarana</label>
                            <select name="id_kategori" class="form-select py-2" style="border-radius: 10px; border: 1px solid #ddd;" required>
                                <option value="">-- Pilih Kategori Sarana --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- LOKASI (DINAMIS DARI DATABASE) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Lokasi Utama</label>
                            <select name="id_lokasi" class="form-select py-2" style="border-radius: 10px; border: 1px solid #ddd;" required>
                                <option value="">-- Pilih Lokasi Kejadian --</option>
                                @foreach($lokasi as $lok)
                                    <option value="{{ $lok->id_lokasi }}">{{ $lok->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- DETAIL LAPORAN -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Detail Laporan / Keterangan</label>
                            <textarea name="ket" class="form-control" rows="4" 
                                      placeholder="Jelaskan detailnya." 
                                      required style="border-radius: 10px; border: 1px solid #ddd;"></textarea>
                        </div>

                        <!-- FOTO -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Bukti Kerusakan</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required style="border-radius: 10px;">
                            <small class="text-muted">Wajib upload bukti foto (JPG/PNG, Max 2MB)</small>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn text-white py-2 fw-bold" 
                                    style="background: #b03030; border-radius: 10px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(176, 48, 48, 0.2);">
                                Kirim Laporan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection