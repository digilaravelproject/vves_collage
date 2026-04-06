<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionStaff extends Model
{
    protected $table = 'institution_staffs';
    protected $fillable = ['institution_id', 'photo', 'section', 'name', 'subject', 'qualification', 'experience'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
