<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpadaAnswer extends Model
{
    use HasFactory;

    protected $table = 'spada_answer';

    protected $fillable = [
        'question_id',
        'answer'
    ];

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SpadaQuestion::class, 'question_id');
    }
}
