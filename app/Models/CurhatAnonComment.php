<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurhatAnonComment extends Model
{
    use HasFactory;

    protected $table = 'curhat_anon_comment';

    protected $fillable = [
        'curhat_anon_id',
        'comment',
    ];

    public function curhatAnon(): BelongsTo
    {
        return $this->belongsTo(CurhatAnon::class, 'curhat_anon_id');
    }
}
