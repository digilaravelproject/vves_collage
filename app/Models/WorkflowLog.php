<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pending_action_id
 * @property int $user_id
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PendingAction $action
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|WorkflowLog query()
 * @method static WorkflowLog create(array $attributes = [])
 * @method static WorkflowLog|null find(mixed $id, array $columns = ['*'])
 */
class WorkflowLog extends Model
{
    protected $fillable = [
        'pending_action_id',
        'user_id',
        'status',
        'note',
    ];

    /**
     * The associated pending action.
     */
    public function action()
    {
        return $this->belongsTo(PendingAction::class, 'pending_action_id');
    }

    /**
     * The Checker who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
