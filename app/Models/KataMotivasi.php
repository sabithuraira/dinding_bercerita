<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KataMotivasi extends Model
{
    use HasFactory;

    protected $table = 'kata_motivasi';

    protected $fillable = [
        'kata_motivasi',
        'dikutip_dari',
        'created_nip',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'integer',
        ];
    }
}
