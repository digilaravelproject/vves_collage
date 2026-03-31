<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionPTAMember extends Model
{
    protected $table = 'institution_pta_members';

    protected $fillable = [
        'institution_id',
        'name',
        'photo',
        'mobile',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
