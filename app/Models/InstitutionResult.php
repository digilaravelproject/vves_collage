<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasInstitutionScope;

/**
 * @property int $id
 * @property int $institution_id
 * @property string|null $student_photo
 * @property string|null $student_name
 * @property string|null $subject
 * @property string|null $passing_year
 * @property string|null $percentage
 * @property string $title
 * @property string|null $description
 * @property string|null $year
 * @property string|null $medium
 * @property string|null $overall_result
 * @property array|null $grades
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
class InstitutionResult extends Model
{
    use HasInstitutionScope;

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
