<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingAction;
use App\Models\WorkflowLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowController extends Controller
{
    public function index()
    {
        $pendingActions = PendingAction::with('maker', 'model')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);
            
        return view('admin.workflow.index', compact('pendingActions'));
    }

    public function show(PendingAction $pendingAction)
    {
        $pendingAction->load('maker', 'model', 'logs.user');
        
        // Prepare comparison data
        $currentData = $pendingAction->model ? $pendingAction->model->toArray() : [];
        $proposedData = $pendingAction->payload;

        return view('admin.workflow.show', compact('pendingAction', 'currentData', 'proposedData'));
    }

    public function approve(Request $request, PendingAction $pendingAction)
    {
        // Prevent self-approval
        if ($pendingAction->maker_id === Auth::id()) {
            return back()->with('error', 'Security Policy: You cannot approve your own submitted actions.');
        }

        try {
            DB::transaction(function () use ($pendingAction, $request) {
                $modelClass = $pendingAction->model_type;
                
                if ($pendingAction->action === 'CREATE') {
                    $modelClass::create($pendingAction->payload);
                } elseif ($pendingAction->action === 'UPDATE') {
                    $model = $modelClass::findOrFail($pendingAction->model_id);
                    $model->update($pendingAction->payload);
                } elseif ($pendingAction->action === 'DELETE') {
                    $model = $modelClass::findOrFail($pendingAction->model_id);
                    $model->delete();
                }

                $pendingAction->update([
                    'status' => 'approved',
                    'checker_id' => Auth::id(),
                ]);

                WorkflowLog::create([
                    'pending_action_id' => $pendingAction->id,
                    'user_id' => Auth::id(),
                    'status' => 'approved',
                    'note' => $request->notes,
                ]);
            });

            return redirect()->route('admin.workflow.index')->with('success', 'Action approved and applied successfully.');
        } catch (\Exception $e) {
            Log::error('Workflow Approval Error: ' . $e->getMessage());
            return back()->with('error', 'Error applying changes: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, PendingAction $pendingAction)
    {
        $request->validate(['notes' => 'required|string|max:500']);

        $pendingAction->update([
            'status' => 'rejected',
            'checker_id' => Auth::id(),
        ]);

        WorkflowLog::create([
            'pending_action_id' => $pendingAction->id,
            'user_id' => Auth::id(),
            'status' => 'rejected',
            'note' => $request->notes,
        ]);

        return redirect()->route('admin.workflow.index')->with('success', 'Action has been rejected.');
    }
}
