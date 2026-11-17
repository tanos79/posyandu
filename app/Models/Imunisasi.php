<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    use HasFactory;

    protected $table = 'imunisasi';

    public $timestamps = false;

    protected $fillable = [
        'id_anak',
        'jenis_vaksin',
        'tgl_jadwal',
        'status',
        'tgl_selesai',
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id');
    }
}
