<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasInstitutionScope;

/**
 * @property int $id
 * @property int $institution_id
 * @property string|null $photo
 * @property string $name
 * @property string|null $designation
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Institution $institution
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionPrincipal query()
 * @method static InstitutionPrincipal create(array $attributes = [])
 * @method static InstitutionPrincipal|null find(mixed $id, array $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 */
class InstitutionPrincipal extends Model
{
    use HasInstitutionScope;

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
