<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatImunisasi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_imunisasi';

    protected $fillable = [
        'anak_id',
        'vaksin_id',
        'tgl_pemberian',
        'lokasi_pemberian',
        'catatan',
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id', 'id');
    }

    public function vaksin()
    {
        return $this->belongsTo(Vaksin::class, 'vaksin_id', 'id');
    }
}
