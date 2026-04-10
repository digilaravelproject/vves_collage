<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\HandlesImageUploads;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HomepageSetupController extends Controller
{
    use HandlesImageUploads;

    /**
     * Show the homepage setup page.
     */
    public function index()
    {
        $layout = Setting::get('homepage_layout') ?: '{"blocks":[]}';
        $notifications = (new NotificationService())->getRestNotifications();
        $icons = ['🎓', '🏆', '🎭', '📚', '🔔', '📅'];

        return view('admin.homepage.setup', compact('layout', 'notifications', 'icons'));
    }

    /**
     * Save the homepage layout.
     */
    public function save(Request $request)
    {

        $validated = $request->validate([
            'blocks' => 'required|array', // 'blocks' ek array hona chahiye
        ]);

        try {
            $jsonContentToSave = json_encode(['blocks' => $validated['blocks']]);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to encode JSON: ' . json_last_error_msg());
            }


            Setting::set('homepage_layout', $jsonContentToSave);

            // Clear Cache
            \Illuminate\Support\Facades\Cache::forget('homepage_layout_blocks');

            // Success response dein
            return response()->json([
                'success' => true,
                'message' => 'Homepage layout saved successfully.'
            ]);
        } catch (\Throwable $e) {
            Log::error('Homepage layout save failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX Media upload for homepage blocks.
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,webp|max:20480',
        ]);

        try {
            $file = $request->file('file');
            $path = $this->compressAndUpload($file, 'uploads/homepage');
            $url = \Illuminate\Support\Facades\Storage::url($path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path
            ]);
        } catch (\Exception $e) {
            Log::error('Homepage Upload Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed.'], 500);
        }
    }
}
