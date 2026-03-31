<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionPrincipal extends Model
{
    protected $fillable = [
        'institution_id',
        'photo',
        'name',
        'designation',
        'description',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
