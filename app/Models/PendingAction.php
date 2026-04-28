<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasInstitutionScope;

/**
 * App\Models\PendingAction
 *
 * @property int $id
 * @property string $model_type
 * @property int|null $model_id
 * @property int|null $institution_id
 * @property string $action
 * @property array $payload
 * @property int $maker_id
 * @property string $status
 * @property int|null $checker_id
 * @property string|null $checker_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $maker
 * @property-read \App\Models\User|null $checker
 * @property-read \App\Models\Institution|null $institution
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkflowLog[] $logs
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin Illuminate\Database\Eloquent\Model
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class PendingAction extends Model
{
    use HasInstitutionScope;

    protected $fillable = [
        'model_type',
        'model_id',
        'institution_id',
        'action',
        'payload',
        'maker_id',
        'status',
        'checker_id',
        'checker_note',
    ];

    protected $casts = [
        'payload' => 'json',
    ];

    /**
     * Get the parent model (polymorphic).
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * The Maker who submitted the request.
     */
    public function maker()
    {
        return $this->belongsTo(User::class, 'maker_id');
    }

    /**
     * The Checker who reviewed the request.
     */
    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    /**
     * Audit logs for this action.
     */
    public function logs()
    {
        return $this->hasMany(WorkflowLog::class);
    }

    /**
     * The Institution this action belongs to (if any).
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
