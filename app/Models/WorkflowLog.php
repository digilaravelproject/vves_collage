<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
