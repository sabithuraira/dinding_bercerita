<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpadaQuestion extends Model
{
    use HasFactory;

    protected $table = 'spada_question';

    protected $fillable = [
        'question',
        'type_question',
        'start_active',
        'last_active',
        'validate_rule',
        'satker'
    ];

    protected $casts = [
        'start_active' => 'date',
        'last_active' => 'date',
    ];

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SpadaAnswer::class, 'question_id');
    }
}
