<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionResult extends Model
{
    protected $fillable = [
        'institution_id',
        'student_photo',
        'student_name',
        'subject',
        'passing_year',
        'percentage',
        'title',
        'description',
        'year',
        'medium',
        'overall_result',
        'grades',
    ];

    protected $casts = [
        'grades' => 'json',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
