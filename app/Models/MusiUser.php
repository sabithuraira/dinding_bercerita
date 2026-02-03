<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusiUser extends Model
{
    use HasFactory;

    protected $table = 'musi_users';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'nip_baru',
        'urutreog',
        'kdorg',
        'nmorg',
        'nmjab',
        'flagwil',
        'kdprop',
        'kdkab',
        'kdkec',
        'nmwil',
        'kdgol',
        'nmgol',
        'kdstjab',
        'nmstjab',
        'kdesl',
        'foto',
        'kode_desa',
        'pimpinan_id',
        'pimpinan_nik',
        'pimpinan_nama',
        'pimpinan_jabatan',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'integer',
        ];
    }
}
