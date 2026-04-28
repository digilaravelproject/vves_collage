<?php

namespace App\Traits;

use App\Models\PendingAction;
use Illuminate\Support\Facades\Auth;

trait InterceptsWorkflow
{
    /**
     * Determine if the current action should be staged as a pending action.
     */
    protected function shouldStage()
    {
        $user = Auth::user();
        
        // Super Admin or users with 'bypass_checker' permission can skip staging
        if ($user->hasRole('Super Admin') || $user->can('bypass_checker')) {
            return false;
        }

        // Makers must stage their changes
        return $user->hasRole('Maker');
    }

    /**
     * Stage an action for approval.
     */
    protected function stageAction($model, string $action, array $payload, ?int $institutionId = null)
    {
        // Try to infer institution_id if not provided
        if (!$institutionId) {
            if ($model instanceof \App\Models\Institution) {
                $institutionId = $model->id;
            } elseif (is_object($model) && isset($model->institution_id)) {
                $institutionId = $model->institution_id;
            } elseif (isset($payload['institution_id'])) {
                $institutionId = $payload['institution_id'];
            }
        }

        return PendingAction::create([
            'model_type' => is_string($model) ? $model : get_class($model),
            'model_id' => is_string($model) ? null : $model->id,
            'institution_id' => $institutionId,
            'action' => strtoupper($action),
            'payload' => $payload,
            'maker_id' => Auth::id(),
            'status' => 'pending',
        ]);
    }
}
