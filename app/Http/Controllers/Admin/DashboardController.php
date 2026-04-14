<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingAction;
use App\Models\Testimonial;
use App\Models\EventItem;
use App\Models\Announcement;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Common stats for Super Admin or those with view dashboard permission
        $stats = [
            'pendingTestimonials' => Testimonial::where('status', false)->count(),
            'upcomingEvents' => EventItem::where('event_date', '>=', now())->count(),
            'activeAnnouncements' => Announcement::where('status', true)->count(),
            'galleryImages' => GalleryImage::count(),
        ];

        // Workflow Stats for Checker
        $workflow = [
            'pendingToApprove' => 0,
            'myProcessedToday' => 0,
            'myRecentSubmissions' => [],
        ];

        if ($user->can('workflow.view')) {
            // Actions pending for this checker (excluding their own if self-approval is blocked)
            $workflow['pendingToApprove'] = PendingAction::where('status', 'pending')
                ->where('maker_id', '!=', $user->id)
                ->count();
                
            $workflow['myProcessedToday'] = PendingAction::where('checker_id', $user->id)
                ->whereDate('updated_at', now()->toDateString())
                ->count();
        }

        // Stats for Makers (their own submissions)
        $workflow['myPendingCount'] = PendingAction::where('maker_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $workflow['myRecentSubmissions'] = PendingAction::where('maker_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'workflow'));
    }
}
