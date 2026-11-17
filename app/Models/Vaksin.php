<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaksin extends Model
{
    use HasFactory;

    protected $table = 'vaksin';

    protected $fillable = [
        'nama_vaksin',
        'usia_rekomendasi_bulan',
        'deskripsi',
    ];

    public function riwayatImunisasi()
    {
        return $this->hasMany(RiwayatImunisasi::class, 'vaksin_id', 'id');
    }
}
