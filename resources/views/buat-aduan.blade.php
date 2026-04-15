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

                        <!-- KATEGORI -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #555;">Kategori Sarana</label>
                            <select name="id_kategori" id="kategori_select" class="form-select py-2" style="border-radius: 10px; border: 1px solid #ddd;" required onchange="cekKategori(this)">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                                @endforeach
                                <option value="lainnya" style="font-weight: bold; color: #800000;">+ Lainnya (Tulis Sendiri)</option>
                            </select>
                        </div>

                        <!-- INPUT KATEGORI BARU (Awalnya Tersembunyi) -->
                        <div class="mb-4" id="box_lainnya" style="display: none;">
                            <label class="form-label fw-bold text-danger">Tulis Kategori Baru</label>
                            <input type="text" name="kategori_baru" id="kategori_baru" class="form-control py-2 shadow-sm" 
                                   placeholder="Contoh: Kaca Jendela, Tembok, dll" style="border-color: #800000; border-radius: 10px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Lokasi Kejadian/Sarana</label>
                            <input type="text" name="lokasi" class="form-control py-2" placeholder="Contoh: Ruang D 102, Bengkel DKV..." required style="border-radius: 10px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Detail Laporan</label>
                            <textarea name="ket" class="form-control" rows="5" placeholder="Jelaskan detail kerusakan..." required style="border-radius: 10px;"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Bukti Kerusakan</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required style="border-radius: 10px;">
                            <small class="text-muted">Wajib upload foto bukti (JPG/PNG, Max 2MB)</small>
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

{{-- JAVASCRIPT BUAT MUNCULIN INPUT LAINNYA --}}
<script>
function cekKategori(select) {
    const boxLainnya = document.getElementById('box_lainnya');
    const inputLainnya = document.getElementById('kategori_baru');

    if (select.value === 'lainnya') {
        boxLainnya.style.display = 'block';
        inputLainnya.required = true;
        inputLainnya.focus();
    } else {
        boxLainnya.style.display = 'none';
        inputLainnya.required = false;
    }
}
</script>
@endsection