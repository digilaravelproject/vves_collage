<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InstagramFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = InstagramFeed::orderBy('sort_order')->paginate(20);
        return view('admin.instagram_feeds.index', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'embed_code' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['status'] = true; // explicitly set to active

        InstagramFeed::create($validated);
        Cache::forget('instagram_feed:active:sorted');

        return redirect()->back()->with('success', 'Instagram feed added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstagramFeed $instagramFeed)
    {
        $validated = $request->validate([
            'embed_code' => 'required|string',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable',
        ]);

        $validated['status'] = $request->has('status');

        $instagramFeed->update($validated);
        Cache::forget('instagram_feed:active:sorted');

        return redirect()->back()->with('success', 'Instagram feed updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstagramFeed $instagramFeed)
    {
        $instagramFeed->delete();
        Cache::forget('instagram_feed:active:sorted');
        return redirect()->back()->with('success', 'Instagram feed deleted successfully!');
    }

    /**
     * Toggle the status of the item.
     */
    public function toggleStatus(InstagramFeed $instagramFeed)
    {
        $instagramFeed->update(['status' => !$instagramFeed->status]);
        Cache::forget('instagram_feed:active:sorted');
        return response()->json(['success' => true]);
    }
}
