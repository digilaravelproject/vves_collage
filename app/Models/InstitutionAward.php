<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasInstitutionScope;

/**
 * @property int $id
 * @property int $institution_id
 * @property string|null $photo
 * @property string $title
 * @property string|null $award_name
 * @property string|null $recipient_name
 * @property string|null $award_date
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Institution $institution
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionAward query()
 * @method static InstitutionAward create(array $attributes = [])
 * @method static InstitutionAward|null find(mixed $id, array $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 */
class InstitutionAward extends Model
{
    use HasInstitutionScope;

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
