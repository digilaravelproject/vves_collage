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
    public function __construct()
    {
        $this->middleware('can:workflow.view');
    }

    /**
     * Display a listing of pending actions.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = PendingAction::with(['maker', 'model', 'institution'])
            ->where('status', 'pending');

        // Maker: Only see their own submissions
        if ($user->hasRole('Maker') && !$user->hasRole(['Approver', 'Super Admin'])) {
            $query->where('maker_id', $user->id);
        }
        
        // Approver: Only see their assigned institutions
        if ($user->hasRole('Approver') && !$user->hasRole('Super Admin')) {
            $assignedIds = $user->institutions->pluck('id')->toArray();
            $query->whereIn('institution_id', $assignedIds);
        }

        $pendingActions = $query->latest()->paginate(15);
            
        return view('admin.workflow.index', compact('pendingActions'));
    }

    /**
     * Display the specified pending action.
     *
     * @param  \App\Models\PendingAction  $pendingAction
     * @return \Illuminate\Contracts\View\View
     */
    public function show(PendingAction $pendingAction)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security Check: Makers can only view their own
        if ($user->hasRole('Maker') && !$user->hasRole(['Approver', 'Super Admin'])) {
            if ($pendingAction->maker_id !== $user->id) {
                abort(403, 'Unauthorized access to this workflow item.');
            }
        }

        // Security Check: Approvers can only view their assigned institutions
        if ($user->hasRole('Approver') && !$user->hasRole('Super Admin')) {
            $assignedIds = $user->institutions->pluck('id')->toArray();
            if ($pendingAction->institution_id && !in_array($pendingAction->institution_id, $assignedIds)) {
                abort(403, 'You are not authorized to view workflow items for this institution.');
            }
        }

        $pendingAction->load(['maker', 'model', 'logs.user']);
        
        // Prepare comparison data
        $currentData = $pendingAction->model ? $pendingAction->model->toArray() : [];
        $proposedData = $pendingAction->payload;

        return view('admin.workflow.show', compact('pendingAction', 'currentData', 'proposedData'));
    }

    /**
     * Approve the specified pending action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PendingAction  $pendingAction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, PendingAction $pendingAction)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Prevent self-approval
        if ($pendingAction->maker_id === $user->id) {
            return back()->with('error', 'Security Policy: You cannot approve your own submitted actions.');
        }

        // Authorization Check: Scoped to assigned institutions
        if (!$user->hasRole('Super Admin')) {
            $assignedIds = $user->institutions->pluck('id')->toArray();
            if ($pendingAction->institution_id && !in_array($pendingAction->institution_id, $assignedIds)) {
                return back()->with('error', 'Security Policy: You are not authorized to approve actions for this institution.');
            }
            // If institution_id is null (e.g. CREATE Institution), only Super Admin or those with specific bypass can approve
            if (!$pendingAction->institution_id && !$user->can('bypass_checker')) {
                return back()->with('error', 'Security Policy: Only Super Admins can approve global or new institution creation requests.');
            }
        }

        try {
            DB::transaction(function () use ($pendingAction, $request) {
                $modelClass = $pendingAction->model_type;
                
                if ($pendingAction->action === 'CREATE') {
                    $model = $modelClass::create($pendingAction->payload);
                    
                    // If it's a new institution, link the maker automatically so they have access
                    if ($model instanceof \App\Models\Institution) {
                        $model->users()->attach($pendingAction->maker_id);
                    }
                } elseif ($pendingAction->action === 'UPDATE') {
                    $model = $modelClass::findOrFail($pendingAction->model_id);
                    $payload = $pendingAction->payload;

                    // Specific logic for Institution
                    if ($model instanceof \App\Models\Institution) {
                        // Extract sections if present
                        $sections = $payload['sections'] ?? null;
                        unset($payload['sections']);

                        // Update main model
                        $model->update($payload);

                        // Sync sections
                        if ($sections) {
                            foreach ($sections as $type => $content) {
                                \App\Models\InstitutionSection::updateOrCreate(
                                    ['institution_id' => $model->id, 'type' => $type],
                                    ['content' => $content]
                                );
                            }
                        }
                    } else {
                        $model->update($payload);
                    }
                } elseif ($pendingAction->action === 'BULK_CREATE') {
                    $payload = $pendingAction->payload;
                    $modelClass = $pendingAction->model_type;
                    
                    if ($modelClass === 'App\Models\InstitutionGallery' || $modelClass === \App\Models\InstitutionGallery::class) {
                        foreach ($payload['images'] as $path) {
                            $modelClass::create([
                                'institution_id' => $payload['institution_id'],
                                'image_path' => $path
                            ]);
                        }
                    }
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

    /**
     * Reject the specified pending action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PendingAction  $pendingAction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, PendingAction $pendingAction)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Prevent self-rejection
        if ($pendingAction->maker_id === $user->id) {
            return back()->with('error', 'Security Policy: You cannot reject your own submitted actions.');
        }

        // Authorization Check
        if (!$user->hasRole('Super Admin')) {
            $assignedIds = $user->institutions->pluck('id')->toArray();
            if ($pendingAction->institution_id && !in_array($pendingAction->institution_id, $assignedIds)) {
                return back()->with('error', 'Security Policy: You are not authorized to reject actions for this institution.');
            }
            // If institution_id is null (e.g. CREATE Institution), only Super Admin or those with specific bypass can reject
            if (!$pendingAction->institution_id && !$user->can('bypass_checker')) {
                return back()->with('error', 'Security Policy: Only Super Admins can reject global or new institution creation requests.');
            }
        }

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
