<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'lokasi',
        'isi_laporan',
        'status',
        'feedback'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}