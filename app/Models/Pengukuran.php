<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengukuran extends Model
{
    use HasFactory;

    protected $table = 'pengukuran';

    public $timestamps = false;

    protected $fillable = [
        'anak_id',
        'id_petugas',
        'kader_id',
        'tgl_pengukuran',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'catatan',
        'status_stunting',
        'vitamin_a',
        'imunisasi',
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id', 'id');
    }
}
