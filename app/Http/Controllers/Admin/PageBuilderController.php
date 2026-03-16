<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Exception;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageBuilderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the pages.
     */
     public function index(Request $request): ViewView|JsonResponse|RedirectResponse
{
    $this->authorize('view pages');
    try {
        $query = Page::query()->latest();

        // Search Logic (Title aur Slug dono par)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        // Pagination (10 pages per page)
        $pages = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.pagebuilder.partials._table_rows', compact('pages'))->render(),
                'pagination' => (string) $pages->links()
            ]);
        }

        return view('admin.pagebuilder.index', compact('pages'));
    } catch (Exception $e) {
        Log::error('PageBuilder Index Error: ' . $e->getMessage());
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
            Log::error('PageBuilder Index Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to load pages.');
        }
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(): ViewView|RedirectResponse
    {
        $this->authorize('create pages');
        try {
            return view('admin.pagebuilder.create');
        } catch (Exception $e) {
            Log::error('PageBuilder Create View Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to open create form.');
        }
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create pages');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable',
            'image' => 'nullable|image|max:20480',
        ]);

        try {
            $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);

            if ($request->hasFile('image')) {
                $validated['image'] = $this->storeRegularFile($request->file('image'), 'uploads/pages');
            }

            Page::create($validated);

            // Warm up cache for the new page
            Artisan::call('cache:warm-pages');

            return redirect()->route('admin.pagebuilder.index')->with('success', 'Page created successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Store Error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to create page.');
        }
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page): ViewView|RedirectResponse
    {
        $this->authorize('edit pages');
        try {
            return view('admin.pagebuilder.edit', compact('page'));
        } catch (Exception $e) {
            Log::error('PageBuilder Edit Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to load edit form.');
        }
    }

    /**
     * Update a specific page in storage.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('edit pages');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable',
            'image' => 'nullable|image|max:20480',
        ]);

        try {
            if ($request->hasFile('image')) {
                $this->deleteOldFile($page->image);
                $validated['image'] = $this->storeRegularFile($request->file('image'), 'uploads/pages');
            }

            $page->update($validated);

            // Clear outdated caches and warm up new ones
            $this->clearAllCaches($page);
            Artisan::call('cache:warm-pages');

            return redirect()->route('admin.pagebuilder.index')->with('success', 'Page updated successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Update Error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to update page.');
        }
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        $this->authorize('delete pages');
        try {
            // Clear cache before deletion
            $this->clearAllCaches($page);

            $this->deleteOldFile($page->image);
            $page->delete();

            // Rebuild cache to reflect deletion
            Artisan::call('cache:warm-pages');

            return back()->with('success', 'Page deleted successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Delete Error: ' . $e->getMessage());

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
            $duplicate->title = $page->title . ' (Copy)';
            $duplicate->slug = $page->slug . '-' . Str::random(5);
            $duplicate->status = false; // Start as disabled
            $duplicate->save();

            return redirect()->route('admin.pagebuilder.index')->with('success', 'Page duplicated successfully!');
        } catch (Exception $e) {
            Log::error('PageBuilder Duplicate Error: ' . $e->getMessage());
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

            // Refresh caches
            $this->clearAllCaches($page);
            Artisan::call('cache:warm-pages');

            $message = $page->status ? 'Page enabled successfully!' : 'Page disabled successfully!';
            if ($page->menu) {
                $message .= ' Related menu item also updated.';
            }

            return back()->with('success', $message);
        } catch (Exception $e) {
            Log::error('PageBuilder Toggle Status Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to update page status.');
        }
    }

    /**
     * Show the page builder interface for the specified page.
     */
    public function builder(Page $page): ViewView|RedirectResponse
    {
        $this->authorize('edit pages');
        try {
            $allPages = Page::select('id', 'title', 'slug')->orderBy('title')->get();
            return view('admin.pagebuilder.builder', compact('page', 'allPages'));
        } catch (Exception $e) {
            Log::error('PageBuilder Builder Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to load page builder.');
        }
    }

    /**
     * Preview the page content in the page builder.
     */
    public function preview(Page $page): ViewView
    {
        $this->authorize('edit pages');

        // This is similar to PageController@show but without forcing status=true
        $activeSection = $page;
        $slug = $page->slug;

        // Fetch menu context if available
        $activeMenu = $page->menu_id ? Menu::with('parent')->find($page->menu_id) : null;
        if (!$activeMenu) {
            $activeMenu = Menu::where('url', '/' . $slug)->where('status', true)->first();
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
        if (!empty($page->content)) {
            $decoded = json_decode($page->content, true);
            $blocks = $decoded['blocks'] ?? $decoded ?? [];
        }

        // We use the same frontend view to ensure visual consistency
        return view('frontend.pages.show', compact(
            'activeSection', 'menus', 'activeMenu', 'topParent', 'blocks'
        ));
    }

    /**
     * Save the page builder content (JSON) to the specified page.
     */
    public function saveBuilder(Request $request, Page $page): JsonResponse
    {
        $this->authorize('edit pages');

        $validated = $request->validate([
            'content' => 'required|json',
        ]);

        try {
            $page->update(['content' => $validated['content']]);

            // Clear cache and queue a warm-up
            $this->clearAllCaches($page);
            Artisan::queue('cache:warm-pages');

            return response()->json([
                'success' => true,
                'message' => 'Page saved! Cache is rebuilding in background.',
            ]);
        } catch (Exception $e) {
            Log::error('PageBuilder Save Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save content.',
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
            if (!Storage::disk('public')->exists($subFolder)) {
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
                $finalName = $cleanName . '.' . $ext;

                if ($customPath && $customPath !== '/') {
                    $subFolder = trim($subFolder . '/' . $customPath, '/');
                }
            } else {
                $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $cleanOriginal = preg_replace('/[^A-Za-z0-9_\-\. ]/', '', $original);
                $finalName = $cleanOriginal . '_' . time() . '.' . $ext;
            }

            // Always store in standard Laravel public storage (storage/app/public/...)
            // Accessible via URL /storage/...
            $path = $file->storeAs($subFolder, $finalName, 'public');
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path,
                'filename' => $finalName,
            ]);
        } catch (Exception $e) {
            Log::error('Upload Media Error: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Upload failed.'], 500);
        }
    }

    /**
     * Store a file in the public disk.
     */
    private function storeRegularFile($file, string $path): ?string
    {
        try {
            if ($file) {
                return $file->store($path, 'public');
            }

            return null;
        } catch (Exception $e) {
            Log::error('Store Regular File Error: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Delete a file from the public disk if it exists.
     */
    private function deleteOldFile(?string $filePath): void
    {
        try {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        } catch (Exception $e) {
            Log::warning('Delete Old File Warning: ' . $e->getMessage());
        }
    }

    /**
     * Clears all relevant caches for a page and its related menu hierarchies.
     * Keys must match exactly what PageController stores.
     */
    private function clearAllCaches(Page $page): void
    {
        try {
            // ── Page caches (keyed by slug) ──────────────────────────────────
            Cache::forget('page:data:'    . $page->slug);
            Cache::forget('page:menu_id:' . $page->slug);

            // ── Menu caches (keyed by menu ID) ───────────────────────────────
            if ($page->menu) {
                $menu = $page->menu;

                // Clear top-parent-ID cache for this menu and all ancestors
                $current = $menu;
                while ($current) {
                    Cache::forget('menu:top_parent_id:' . $current->id);
                    $current = $current->parent_id ? $current->parent : null;
                }

                // Find top parent to clear the sidebar tree cache
                $topParent = $menu;
                while ($topParent->parent_id && $topParent->parent) {
                    $topParent = $topParent->parent;
                }
                Cache::forget('menu:tree:' . $topParent->id);
            }
        } catch (Exception $e) {
            Log::error('Failed to clear cache for page ID ' . $page->id . ': ' . $e->getMessage());
        }
    }
}
