<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    use HasFactory;

    protected $table = 'anak';

    protected $fillable = [
        'parent_id',
        'nik_anak',
        'nama_lengkap',
        'tempat_lahir',
        'jenis_kelamin',
        'tanggal_lahir',
        'berat_lahir',
        'tinggi_lahir',
        'nama_ibu',
        'no_hp',
    ];

    public function pengukuran()
    {
        return $this->hasMany(Pengukuran::class, 'anak_id', 'id');
    }

    public function riwayatImunisasi()
    {
        return $this->hasMany(RiwayatImunisasi::class, 'anak_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_anak', 'id');
    }
}
