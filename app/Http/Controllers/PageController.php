<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageController extends Controller
{
    /**
     * Show a page based on the given slug.
     *
     * Caching strategy (database cache safe):
     *  - Cache ONLY scalar values / plain arrays — never full Eloquent objects.
     *  - The sidebar menu Eloquent collection is fetched fresh each request,
     *    but the top-parent ID that drives the query is cached (tiny int).
     *  - This completely prevents the MySQL max_allowed_packet error.
     */
    public function show($slug)
    {
        try {
            // ── 1. Page data — cache plain array only ────────────────────────
            $pageArr = Cache::remember('page:data:' . $slug, 86400, function () use ($slug) {
                $page = Page::where('slug', $slug)
                    ->where('status', true)
                    ->firstOrFail();

                return [
                    'id'      => $page->id,
                    'title'   => $page->title,
                    'slug'    => $page->slug,
                    'content' => $page->content,
                    'image'   => $page->image,
                    'pdf'     => $page->pdf,
                    'menu_id' => $page->menu_id,
                    'status'  => $page->status,
                ];
            });

            // Re-hydrate a lightweight Page model for the view
            $activeSection           = new Page();
            $activeSection->id       = $pageArr['id'];
            $activeSection->title    = $pageArr['title'];
            $activeSection->slug     = $pageArr['slug'];
            $activeSection->content  = $pageArr['content'];
            $activeSection->image    = $pageArr['image'];
            $activeSection->pdf      = $pageArr['pdf'];
            $activeSection->menu_id  = $pageArr['menu_id'];
            $activeSection->status   = $pageArr['status'];
            $activeSection->exists   = true;

            // ── 2. Active menu ID — cache the tiny ID only ────────────────────
            $activeMenuId = Cache::remember('page:menu_id:' . $slug, 86400, function () use ($pageArr) {
                // Try via page's menu_id first
                if (!empty($pageArr['menu_id'])) {
                    $menu = Menu::where('id', $pageArr['menu_id'])
                        ->where('status', true)
                        ->value('id');
                    if ($menu) return $menu;
                }
                // Fallback: find by URL
                return Menu::where('url', '/' . $pageArr['slug'])
                    ->where('status', true)
                    ->value('id');
            });

            $activeMenu = $activeMenuId ? Menu::with('parent')->find($activeMenuId) : null;

            // ── 3. Top-parent ID — cache the tiny int only ────────────────────
            $topParent = null;
            $menus     = collect();

            if ($activeMenu) {
                $topParentId = Cache::remember('menu:top_parent_id:' . $activeMenu->id, 86400, function () use ($activeMenu) {
                    return $this->getTopParentId($activeMenu);
                });

                if ($topParentId) {
                    // Fetch the FULL Eloquent tree fresh (NOT stored in cache)
                    // so the view can use ->childrenRecursive, ->link, ->page, etc.
                    $topParent = Menu::with(['page', 'childrenRecursive.page'])
                        ->where('id', $topParentId)
                        ->where('status', true)
                        ->first();

                    $menus = $topParent ? Menu::with(['page', 'childrenRecursive.page'])
                        ->where('id', $topParentId)
                        ->where('status', true)
                        ->get() : collect();
                }
            }

            // ── 4. Decode content blocks ──────────────────────────────────────
            $blocks = [];
            if (!empty($activeSection->content)) {
                $decoded = json_decode($activeSection->content, true);
                $blocks  = $decoded['blocks'] ?? $decoded ?? [];
            }

            return view('frontend.pages.show', compact(
                'activeSection', 'menus', 'activeMenu', 'topParent', 'blocks'
            ));

        } catch (ModelNotFoundException $e) {
            Log::error("Page not found for slug: {$slug}", ['exception' => $e->getMessage()]);
            abort(404, 'Page not found.');
        } catch (\Throwable $e) {
            Log::error("Error in PageController@show for slug: {$slug}", [
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
            abort(500, 'Something went wrong. Please try again later.');
        }
    }

    /**
     * Walk up to find the top-most parent ID.
     * Returns an int (tiny cache value — safe for database cache).
     */
    private function getTopParentId(Menu $menu): int
    {
        if ($menu->parent_id) {
            /** @var Menu $parent */
            $parent = Menu::find($menu->parent_id);
            if ($parent) {
                return Cache::remember('menu:top_parent_id:' . $parent->id, 86400, function () use ($parent) {
                    return $this->getTopParentId($parent);
                });
            }
        }
        return $menu->id;
    }
}
