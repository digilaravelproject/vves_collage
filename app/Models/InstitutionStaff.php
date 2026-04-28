<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasInstitutionScope;

/**
 * @property int $id
 * @property int $institution_id
 * @property string|null $photo
 * @property string|null $section
 * @property string $name
 * @property string|null $subject
 * @property string|null $qualification
 * @property string|null $experience
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Institution $institution
 * 
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin Illuminate\Database\Eloquent\Model
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class InstitutionStaff extends Model
{
    use HasInstitutionScope;

    protected $table = 'institution_staffs';
    protected $fillable = ['institution_id', 'photo', 'section', 'name', 'subject', 'qualification', 'experience'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
