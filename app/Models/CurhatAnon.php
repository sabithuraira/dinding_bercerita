<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurhatAnon extends Model
{
    use HasFactory;

    protected $table = 'curhat_anon';

    protected $fillable = [
        'content',
        'status_verifikasi',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(CurhatAnonComment::class, 'curhat_anon_id');
    }

    public function getListStatusVerifikasiAttribute()
    {
        return array(
            1 => "Belum Verifikasi",
            2 => "Disetujui",
            3 => "Tidak Disetujui",
        );
    }

    public function getListLabelStatusVerifikasiAttribute()
    {
        return array(
            1 => "Belum Verifikasi",
            2 => "Disetujui",
            3 => "Tidak Disetujui",
        );
    }
}
