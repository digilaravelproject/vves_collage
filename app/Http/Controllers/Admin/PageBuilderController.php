<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Traits\HandlesImageUploads;
use Exception;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * PageBuilderController handles the administrative tasks for creating, editing, and managing
 * custom pages using a block-based builder.
 * 
 * @method void authorize(string $ability, mixed|array $arguments = [])
 */
class PageBuilderController extends Controller
{
    use AuthorizesRequests, HandlesImageUploads;

    /**
     * Display a listing of the pages.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): ViewView|JsonResponse|RedirectResponse
    {
        $this->authorize('view pages');
        try {
            $query = Page::query()->latest();

            // Search Logic (Title aur Slug dono par)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('slug', 'LIKE', "%{$search}%");
                });
            }

            // Pagination (10 pages per page)
            $pages = $query->paginate(10);

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.pagebuilder.partials._table_rows', compact('pages'))->render(),
                    'pagination' => (string) $pages->links(),
                ]);
            }

            return view('admin.pagebuilder.index', compact('pages'));
        } catch (Exception $e) {
            Log::error('PageBuilder Index Error: '.$e->getMessage());

            return back()->with('error', 'Failed to load pages.');
        }
    }

    public function index_old(): ViewView|RedirectResponse
    {
        $this->authorize('view pages');
        try {
            $pages = Page::latest()->get();

            return view('admin.pagebuilder.index', compact('pages'));
        } catch (Exception $e) {
            Log::error('PageBuilder Index Error: '.$e->getMessage());

            return back()->with('error', 'Failed to load pages.');
        }
    }

    /**
     * Show the form for creating a new page.
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(): ViewView|RedirectResponse
    {
        $this->authorize('create pages');
        try {
            return view('admin.pagebuilder.create');
        } catch (Exception $e) {
            Log::error('PageBuilder Create View Error: '.$e->getMessage());

            return back()->with('error', 'Failed to open create form.');
        }
    }

    /**
     * Store a newly created page in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create pages');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable',
            'image' => 'nullable|image|max:20480',
            'breadcrumb_image' => 'nullable|image|max:20480',
            'breadcrumb_note' => 'nullable|string|max:255',
        ]);

        try {
            $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

            if ($request->hasFile('image')) {
                $validated['image'] = $this->compressAndUpload($request->file('image'), 'uploads/pages');
            }

            if ($request->hasFile('breadcrumb_image')) {
                $validated['breadcrumb_image'] = $this->compressAndUpload($request->file('breadcrumb_image'), 'uploads/breadcrumbs');
            }

            Page::create($validated);

            return redirect()->route('admin.pagebuilder.index')->with('success', 'Page created successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Store Error: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to create page.');
        }
    }

    /**
     * Show the form for editing the specified page.
     * 
     * @param \App\Models\Page $page
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Page $page): ViewView|RedirectResponse
    {
        $this->authorize('edit pages');
        try {
            return view('admin.pagebuilder.edit', compact('page'));
        } catch (Exception $e) {
            Log::error('PageBuilder Edit Error: '.$e->getMessage());

            return back()->with('error', 'Failed to load edit form.');
        }
    }

    /**
     * Update a specific page in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('edit pages');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,'.$page->id,
            'content' => 'nullable',
            'image' => 'nullable|image|max:20480',
            'breadcrumb_image' => 'nullable|image|max:20480',
            'breadcrumb_note' => 'nullable|string|max:255',
        ]);

        try {
            if ($request->hasFile('image')) {
                $this->deleteImage($page->image);
                $validated['image'] = $this->compressAndUpload($request->file('image'), 'uploads/pages');
            }

            if ($request->hasFile('breadcrumb_image')) {
                $this->deleteImage($page->breadcrumb_image);
                $validated['breadcrumb_image'] = $this->compressAndUpload($request->file('breadcrumb_image'), 'uploads/breadcrumbs');
            }

            $page->update($validated);

            // Clear outdated caches
            $this->clearAllCaches($page);

            return redirect()->route('admin.pagebuilder.index')->with('success', 'Page updated successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Update Error: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to update page.');
        }
    }

    /**
     * Remove the specified page from storage.
     * 
     * @param \App\Models\Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Page $page): RedirectResponse
    {
        $this->authorize('delete pages');
        try {
            // Clear cache before deletion
            $this->clearAllCaches($page);

            $this->deleteImage($page->image);
            $page->delete();

            return back()->with('success', 'Page deleted successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Delete Error: '.$e->getMessage());

            return back()->with('error', 'Failed to delete page.');
        }
    }

    /**
     * Duplicate the specified page.
     */
    public function duplicate(Page $page): RedirectResponse
    {
        $this->authorize('create pages');
        try {
            $duplicate = $page->replicate();
            $duplicate->title = $page->title.' (Copy)';
            $duplicate->slug = $page->slug.'-'.Str::random(5);
            $duplicate->status = false; // Start as disabled
            $duplicate->save();

            return redirect()->route('admin.pagebuilder.index')->with('success', 'Page duplicated successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Duplicate Error: '.$e->getMessage());

            return back()->with('error', 'Failed to duplicate page.');
        }
    }

    /**
     * Toggle the status of the specified page.
     */
    public function toggleStatus(Page $page): RedirectResponse
    {
        $this->authorize('manage menus');

        try {
            // Toggle page status
            $page->update(['status' => ! $page->status]);

            // Sync status with related menu item if exists
            if ($page->menu) {
                $page->menu->update(['status' => $page->status]);
            }

            // Refresh caches (targeted)
            $this->clearAllCaches($page);

            $message = $page->status ? 'Page enabled successfully!' : 'Page disabled successfully!';
            if ($page->menu) {
                $message .= ' Related menu item also updated.';
            }

            return back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('PageBuilder Toggle Status Error: '.$e->getMessage());

            return back()->with('error', 'Failed to update page status.');
        }
    }

    /**
     * Show the page builder interface for the specified page.
     * 
     * @param \App\Models\Page $page
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function builder(Page $page): ViewView|RedirectResponse
    {
        $this->authorize('edit pages');
        try {
            $allPages = Page::select('id', 'title', 'slug')->orderBy('title')->get();

            return view('admin.pagebuilder.builder', compact('page', 'allPages'));
        } catch (Exception $e) {
            Log::error('PageBuilder Builder Error: '.$e->getMessage());

            return back()->with('error', 'Failed to load page builder.');
        }
    }

    /**
     * Preview the page content in the page builder.
     * 
     * @param \App\Models\Page $page
     * @return \Illuminate\Contracts\View\View
     */
    public function preview(Page $page): ViewView
    {
        $this->authorize('edit pages');

        // This is similar to PageController@show but without forcing status=true
        $activeSection = $page;
        $slug = $page->slug;

        // Fetch menu context if available
        $activeMenu = $page->menu_id ? Menu::with('parent')->find($page->menu_id) : null;
        if (! $activeMenu) {
            $activeMenu = Menu::where('url', '/'.$slug)->where('status', true)->first();
        }

        $topParent = null;
        $menus = collect();

        if ($activeMenu) {
            // Minimal logic to get top parent for the layout
            $current = $activeMenu;
            while ($current->parent_id) {
                $current = Menu::find($current->parent_id);
            }
            $topParent = Menu::with(['page', 'childrenRecursive.page'])
                ->where('id', $current->id)
                ->first();

            $menus = $topParent ? Menu::with(['page', 'childrenRecursive.page'])
                ->where('id', $topParent->id)
                ->get() : collect();
        }

        $blocks = [];
        if (! empty($page->content)) {
            $decoded = json_decode($page->content, true);
            $blocks = $decoded['blocks'] ?? $decoded ?? [];
        }

        // ── Build Breadcrumb Trail (Fix for preview) ──────────────────────
        $breadcrumbTrail = [];
        if ($activeMenu) {
            $trail = collect();
            $current = $activeMenu;
            while ($current) {
                $trail->prepend([
                    'label' => $current->title,
                    'url'   => $current->url ?? url($current->page?->slug ?? '#'),
                ]);
                $current = $current->parent;
            }
            $breadcrumbTrail = $trail->toArray();
        } else {
            $breadcrumbTrail = [
                ['label' => $activeSection->title, 'url' => null]
            ];
        }

        // We use the same frontend view to ensure visual consistency
        return view('frontend.pages.show', compact(
            'activeSection', 'menus', 'activeMenu', 'topParent', 'blocks', 'breadcrumbTrail'
        ));
    }

    public function saveBuilder(Request $request, Page $page): JsonResponse
    {
        try {
            $this->authorize('edit pages');

            // Log request details for debugging large payloads
            $content = $request->input('content');
            $contentSize = strlen($content ?? '');
            Log::debug("Saving Page Builder content for Page ID: {$page->id}, Size: {$contentSize} bytes");

            // Validate JSON
            $validated = $request->validate([
                'content' => 'required|json',
            ]);

            $page->update(['content' => $validated['content']]);

            // Clear cache only for this page (Targeted)
            $this->clearAllCaches($page);

            return response()->json([
                'success' => true,
                'message' => 'Page saved successfully!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('PageBuilder Validation Error: '.json_encode($e->errors()));

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('PageBuilder Save Error: '.$e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server Error: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Media upload via AJAX for the page builder.
     */
    public function uploadMedia(Request $request, Page $page): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,webp,mp4,webm,mov,pdf|max:81920',
            'custom_name' => 'nullable|string|max:255',
        ]);

        try {
            $file = $request->file('file');
            $mime = $file->getMimeType();
            $customName = $validated['custom_name'] ?? null;

            $subFolder = match (true) {
                str_starts_with($mime, 'image/') => 'uploads/images',
                str_starts_with($mime, 'video/') => 'uploads/videos',
                $mime === 'application/pdf' => 'uploads/pdfs',
                default => 'uploads/others',
            };

            // Ensure directory exists in public disk
            if (! Storage::disk('public')->exists($subFolder)) {
                Storage::disk('public')->makeDirectory($subFolder);
            }

            $ext = $file->getClientOriginalExtension();
            $rawPathAndName = trim($validated['custom_name'] ?? '');
            $finalName = '';

            if ($rawPathAndName) {
                $filenamePart = pathinfo($rawPathAndName, PATHINFO_FILENAME);
                $customPath = trim(pathinfo($rawPathAndName, PATHINFO_DIRNAME), './');

                // Sanitize filename: allow alphanumeric, underscores, dashes, dots, and spaces.
                $cleanName = preg_replace('/[^A-Za-z0-9_\-\. ]/', '', $filenamePart);
                $finalName = $cleanName.'.'.$ext;

                if ($customPath && $customPath !== '/') {
                    $subFolder = trim($subFolder.'/'.$customPath, '/');
                }
            } else {
                $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $cleanOriginal = preg_replace('/[^A-Za-z0-9_\-\. ]/', '', $original);
                $finalName = $cleanOriginal.'_'.time().'.'.$ext;
            }

            // Always store in standard Laravel public storage (storage/app/public/...)
            // Accessible via URL /storage/...
            if (str_starts_with($mime, 'image/') && ! in_array(strtolower($ext), ['svg', 'gif', 'ico'])) {
                $path = $this->compressAndUpload($file, $subFolder);
                $finalName = basename($path);
            } else {
                $path = $file->storeAs($subFolder, $finalName, 'public');
            }
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path,
                'filename' => $finalName,
            ]);
        } catch (Exception $e) {
            Log::error('Upload Media Error: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Upload failed.'], 500);
        }
    }
    
    /**
     * List all media files for the image picker.
     */
    public function getMedia(Request $request): JsonResponse
    {
        try {
            $this->authorize('view pages');
            
            $mediaItems = collect();
            $disks = ['public'];
            
            foreach ($disks as $disk) {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $diskInstance */
                $diskInstance = Storage::disk($disk);
                $files = $diskInstance->allFiles('uploads');
                foreach ($files as $file) {
                    $mime = $diskInstance->mimeType($file);
                    
                    // Only include images and videos for the builder picker
                    if (str_starts_with($mime, 'image/') || str_starts_with($mime, 'video/')) {
                        $mediaItems->push([
                            'name' => basename($file),
                            'url' => $diskInstance->url($file),
                            'path' => $file,
                            'mime' => $mime,
                            'size' => $diskInstance->size($file),
                            'timestamp' => $diskInstance->lastModified($file),
                        ]);
                    }
                }
            }
            
            // Sort by newest first
            $mediaItems = $mediaItems->sortByDesc('timestamp')->values();
            
            return response()->json([
                'success' => true,
                'media' => $mediaItems
            ]);
        } catch (Exception $e) {
            Log::error('List Media Error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load media.'], 500);
        }
    }

    /**
     * Clears all relevant caches for a page and its related menu hierarchies.
     * Keys must match exactly what PageController stores.
     */
    private function clearAllCaches(Page $page): void
    {
        try {
            Log::debug("Clearing cache for page: {$page->slug}");

            // ── Page caches (keyed by slug) ──────────────────────────────────
            Cache::forget('page:data:'.$page->slug);
            Cache::forget('page:menu_id:'.$page->slug);
            Log::debug("Cleared page:data and page:menu_id for {$page->slug}");

            // ── Menu caches (keyed by menu ID) ───────────────────────────────
            if ($page->menu) {
                $menu = $page->menu;
                Log::debug("Traversing menu hierarchy for menu ID: {$menu->id}");

                // Clear top-parent-ID cache for this menu and all ancestors
                $current = $menu;
                while ($current) {
                    Cache::forget('menu:top_parent_id:'.$current->id);
                    Log::debug("Cleared menu:top_parent_id:{$current->id}");

                    // Safely move to parent
                    $current = ($current->parent_id && $current->parent) ? $current->parent : null;
                }

                // Find top parent to clear the sidebar tree cache
                $topParent = $menu;
                $safetyLimit = 0;
                while ($topParent->parent_id && $topParent->parent && $safetyLimit < 20) {
                    $topParent = $topParent->parent;
                    $safetyLimit++;
                }
                Cache::forget('menu:tree:'.$topParent->id);
                Log::debug("Cleared menu:tree:{$topParent->id}");
            }
        } catch (\Throwable $e) {
            Log::error('Failed to clear cache for page ID '.$page->id.': '.$e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
