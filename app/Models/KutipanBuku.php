<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KutipanBuku extends Model
{
    use HasFactory;

    protected $table = 'kutipan_buku';

    protected $fillable = [
        'quote',
        'dikutip_dari',
        'date_show',
    ];

    protected function casts(): array
    {
        return [
            'date_show' => 'date',
        ];
    }
}
