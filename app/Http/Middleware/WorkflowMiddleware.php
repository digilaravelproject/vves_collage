<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WorkflowMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Only trap write actions for Non-Admins
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            
            // Bypass if user has authority or is Super Admin
            if ($user && ($user->hasRole('Admin') || $user->can('bypass_checker'))) {
                return $next($request);
            }

            // 2. Identify if this route is workflow-enabled
            $routeName = $request->route()->getName();
            $mapping = $this->getRouteModelMapping($routeName);

            if ($mapping) {
                return $this->createPendingAction($request, $mapping);
            }
        }

        return $next($request);
    }

    /**
     * Define which routes trigger the Maker-Checker workflow.
     */
    protected function getRouteModelMapping($routeName)
    {
        $config = [
            // Institutions
            'admin.institutions.store' => ['model' => \App\Models\Institution::class, 'action' => 'CREATE'],
            'admin.institutions.update' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.destroy' => ['model' => \App\Models\Institution::class, 'action' => 'DELETE'],
            'admin.institutions.toggle-status' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.save-result' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.save-principal' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.save-pta' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.save-award' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.save-staff' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            
            // Page Builder
            'admin.pagebuilder.store' => ['model' => \App\Models\Page::class, 'action' => 'CREATE'],
            'admin.pagebuilder.update' => ['model' => \App\Models\Page::class, 'action' => 'UPDATE'],
            'admin.pagebuilder.delete' => ['model' => \App\Models\Page::class, 'action' => 'DELETE'],
            'admin.pagebuilder.builder.save' => ['model' => \App\Models\Page::class, 'action' => 'UPDATE'],

            // Core Modules
            'admin.banners.store' => ['model' => \App\Models\Banner::class, 'action' => 'CREATE'],
            'admin.banners.update' => ['model' => \App\Models\Banner::class, 'action' => 'UPDATE'],
            'admin.banners.destroy' => ['model' => \App\Models\Banner::class, 'action' => 'DELETE'],

            'admin.announcements.store' => ['model' => \App\Models\Announcement::class, 'action' => 'CREATE'],
            'admin.announcements.update' => ['model' => \App\Models\Announcement::class, 'action' => 'UPDATE'],
            'admin.announcements.destroy' => ['model' => \App\Models\Announcement::class, 'action' => 'DELETE'],

            'admin.event-items.store' => ['model' => \App\Models\EventItem::class, 'action' => 'CREATE'],
            'admin.event-items.update' => ['model' => \App\Models\EventItem::class, 'action' => 'UPDATE'],
            'admin.event-items.destroy' => ['model' => \App\Models\EventItem::class, 'action' => 'DELETE'],

            'admin.testimonials.store' => ['model' => \App\Models\Testimonial::class, 'action' => 'CREATE'],
            'admin.testimonials.update' => ['model' => \App\Models\Testimonial::class, 'action' => 'UPDATE'],
            'admin.testimonials.destroy' => ['model' => \App\Models\Testimonial::class, 'action' => 'DELETE'],
        ];

        return $config[$routeName] ?? null;
    }

    /**
     * Trap the request and save to pending_actions.
     */
    protected function createPendingAction(Request $request, $mapping)
    {
        $route = $request->route();
        $modelId = null;

        // 1. Try to find the model ID from route parameters
        foreach ($route->parameters() as $param) {
            if ($param instanceof \Illuminate\Database\Eloquent\Model) {
                $modelId = $param->id;
                break;
            } elseif (is_numeric($param)) {
                $modelId = $param;
                break;
            }
        }

        // 2. Sanitize Payload (Remove Laravel internal tokens)
        $payload = $request->except(['_token', '_method']);

        // 3. Save to Pending Actions
        \App\Models\PendingAction::create([
            'model_type' => $mapping['model'],
            'model_id' => $modelId,
            'action' => $mapping['action'],
            'payload' => $payload,
            'maker_id' => Auth::id(),
            'status' => 'pending',
        ]);

        // 4. Return compatible response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Changes saved as draft and submitted for approval.'
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Your changes have been submitted for review.');
    }
}
