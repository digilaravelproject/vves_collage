<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionAward extends Model
{
    protected $fillable = [
        'institution_id',
        'photo',
        'title',
        'award_name',
        'recipient_name',
        'award_date',
        'description',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
