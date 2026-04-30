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
            
            // Bypass if user has authority or is Admin/Super Admin
            if ($user && ($user->hasRole(['Admin', 'admin', 'Super Admin']) || $user->can('bypass_checker'))) {
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
     * 
     * @param string|null $routeName
     * @return array|null
     */
    protected function getRouteModelMapping(?string $routeName): ?array
    {
        $config = [
            
            // Page Builder
            'admin.pagebuilder.store' => ['model' => \App\Models\Page::class, 'action' => 'CREATE'],
            'admin.pagebuilder.update' => ['model' => \App\Models\Page::class, 'action' => 'UPDATE'],
            'admin.pagebuilder.destroy' => ['model' => \App\Models\Page::class, 'action' => 'DELETE'],
            'admin.pagebuilder.builder.save' => ['model' => \App\Models\Page::class, 'action' => 'UPDATE'],

            // Institutions
            'admin.institutions.store' => ['model' => \App\Models\Institution::class, 'action' => 'CREATE'],
            'admin.institutions.update' => ['model' => \App\Models\Institution::class, 'action' => 'UPDATE'],
            'admin.institutions.destroy' => ['model' => \App\Models\Institution::class, 'action' => 'DELETE'],
            'admin.institutions.bulk-gallery' => ['model' => \App\Models\InstitutionGallery::class, 'action' => 'BULK_CREATE'],

            // Core Content Modules
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

            'admin.gallery-images.store' => ['model' => \App\Models\GalleryImage::class, 'action' => 'CREATE'],
            'admin.gallery-images.update' => ['model' => \App\Models\GalleryImage::class, 'action' => 'UPDATE'],
            'admin.gallery-images.destroy' => ['model' => \App\Models\GalleryImage::class, 'action' => 'DELETE'],

            'admin.why-choose-us.store' => ['model' => \App\Models\WhyChooseUs::class, 'action' => 'CREATE'],
            'admin.why-choose-us.update' => ['model' => \App\Models\WhyChooseUs::class, 'action' => 'UPDATE'],
            'admin.why-choose-us.destroy' => ['model' => \App\Models\WhyChooseUs::class, 'action' => 'DELETE'],

            'admin.popups.store' => ['model' => \App\Models\Popup::class, 'action' => 'CREATE'],
            'admin.popups.update' => ['model' => \App\Models\Popup::class, 'action' => 'UPDATE'],
            'admin.popups.destroy' => ['model' => \App\Models\Popup::class, 'action' => 'DELETE'],

            'admin.academic-calendars.store' => ['model' => \App\Models\AcademicCalendar::class, 'action' => 'CREATE'],
            'admin.academic-calendars.update' => ['model' => \App\Models\AcademicCalendar::class, 'action' => 'UPDATE'],
            'admin.academic-calendars.destroy' => ['model' => \App\Models\AcademicCalendar::class, 'action' => 'DELETE'],
        ];

        return $config[$routeName] ?? null;
    }

    /**
     * Trap the request and save to pending_actions.
     * 
     * @param Request $request
     * @param array $mapping
     * @return Response
     */
    protected function createPendingAction(Request $request, array $mapping): Response
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

        // 3. Infer Institution ID
        $institutionId = $this->inferInstitutionId($request, $mapping['model'], $modelId);

        // 4. Save to Pending Actions
        \App\Models\PendingAction::create([
            'model_type' => $mapping['model'],
            'model_id' => $modelId,
            'institution_id' => $institutionId,
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

    /**
     * Infer the institution_id for scoping and tracking.
     * 
     * @param Request $request
     * @param string $modelClass
     * @param int|string|null $modelId
     * @return int|null
     */
    protected function inferInstitutionId(Request $request, string $modelClass, int|string|null $modelId): ?int
    {
        // 1. If we have a model ID, fetch the existing record's institution_id
        if ($modelId) {
            try {
                $instance = $modelClass::withoutGlobalScopes()->find($modelId);
                if ($instance) {
                    if ($instance instanceof \App\Models\Institution) {
                        return (int) $instance->id;
                    }
                    if (isset($instance->institution_id)) {
                        return (int) $instance->institution_id;
                    }
                }
            } catch (\Exception $e) {
                // Silently fail if model not found or has no institution_id
            }
        }

        // 2. Check Request Payload
        if ($request->has('institution_id')) {
            return (int) $request->input('institution_id');
        }

        // 3. Fallback: If user only has ONE institution, assume it's for that one
        $user = Auth::user();
        if ($user && $user->institutions->count() === 1) {
            return (int) $user->institutions->first()->id;
        }

        return null;
    }
}
