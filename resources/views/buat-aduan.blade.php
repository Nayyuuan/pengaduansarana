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
                    {{-- PENTING: Tambahkan enctype="multipart/form-data" --}}
                    <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Kategori Sarana</label>
                            <select name="id_kategori" class="form-select py-2" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Lokasi Kejadian/Sarana</label>
                            <input type="text" name="lokasi" class="form-control py-2" placeholder="Contoh: Lab Komp" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Detail Laporan</label>
                            <textarea name="ket" class="form-control" rows="5" placeholder="Jelaskan detail kerusakan..." required></textarea>
                        </div>

                        {{-- INPUT FOTO --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Bukti Kerusakan</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                            <small class="text-muted">Wajib upload foto bukti (JPG/PNG, Max 2MB)</small>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn text-white py-2 fw-bold" 
                                    style="background: #b03030; border-radius: 10px;">
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