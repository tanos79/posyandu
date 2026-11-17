<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'no_hp',
        'password',
        'id_anak',
        'role',
        'foto_profil',
        'alamat',
        'tgl_bergabung',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'user_id', 'id');
    }

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id');
    }
}
