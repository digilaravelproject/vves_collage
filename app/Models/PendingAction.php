<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingAction extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
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
}
