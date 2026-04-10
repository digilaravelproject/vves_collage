<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\HandlesImageUploads;

class WebsiteSettingController extends Controller
{
    use AuthorizesRequests, HandlesImageUploads;
    public function index()
    {
        $this->authorize('manage settings');

        $data = [
            'college_name' => Setting::get('college_name'),
            'banner_heading' => Setting::get('banner_heading'),
            'banner_subheading' => Setting::get('banner_subheading'),
            'banner_button_text' => Setting::get('banner_button_text'),
            'banner_button_link' => Setting::get('banner_button_link'),
            'college_logo' => Setting::get('college_logo'),

            // MODIFIED: Fetch Light and Dark Images
            'top_banner_image' => Setting::get('top_banner_image'), // Light
            'top_banner_image_dark' => Setting::get('top_banner_image_dark'), // Dark (New)

            'favicon' => Setting::get('favicon'),
            'banner_media' => $this->getBannerMedia(),

            // MODIFIED: Fetch Background Audio
            'background_audio' => Setting::get('background_audio'),
            'college_song_lyrics' => Setting::get('college_song_lyrics'),

            // Contact & Social & Footer
            'address' => Setting::get('address'),
            'email' => Setting::get('email'),
            'phone' => Setting::get('phone'),
            'phone_alternate' => Setting::get('phone_alternate'),
            'facebook_url' => Setting::get('facebook_url'),
            'twitter_url' => Setting::get('twitter_url'),
            'instagram_url' => Setting::get('instagram_url'),
            'youtube_url' => Setting::get('youtube_url'),
            'linkedin_url' => Setting::get('linkedin_url'),
            'library_enabled' => Setting::get('library_enabled'),
            'footer_about' => Setting::get('footer_about'),
            'map_embed_url' => Setting::get('map_embed_url'),
            'footer_links' => ($tmp = Setting::get('footer_links')) ? json_decode($tmp, true) : [],

            // SEO Settings
            'meta_title' => Setting::get('meta_title'),
            'meta_description' => Setting::get('meta_description'),
            'meta_image' => Setting::get('meta_image'),
            'footer_logo' => Setting::get('footer_logo'),
            'contact_centers' => ($tmp = Setting::get('contact_centers')) ? json_decode($tmp, true) : [],
        ];

        return view('admin.settings.website', compact('data'));
    }

    public function update(Request $request)
    {
        $this->authorize('manage settings');
        $validated = $request->validate([
            'college_name' => 'required|string|max:255',
            'banner_heading' => 'nullable|string|max:255',
            'banner_subheading' => 'nullable|string|max:255',
            'banner_button_text' => 'nullable|string|max:100',
            'banner_button_link' => 'nullable|url',

            'college_logo' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048',

            // MODIFIED: Validation for both images
            'top_banner_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'top_banner_image_dark' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048', // New Dark Mode Image
            'footer_logo' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'favicon' => 'nullable|mimes:jpg,jpeg,png,ico,webp,svg|max:1024',

            'banner_media' => 'nullable|array',
            'banner_media.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg,mp4,mov,avi|max:51200',

            // MODIFIED: Validation for Audio
            'background_audio' => 'nullable|mimes:mp3,wav,ogg,mpeg|mimetypes:audio/mpeg,audio/wav,audio/ogg|max:20480',// 20MB Max
            'college_song_lyrics' => 'nullable|string|max:500',

            // Contact & Social & Footer
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'library_enabled' => 'nullable|boolean',
            'linkedin_url' => 'nullable|url|max:255',
            'footer_about' => 'nullable|string|max:500',
            'map_embed_url' => 'nullable|string|max:2000',
            'footer_links' => 'nullable|array',
            'footer_links.*.title' => 'required_with:footer_links|string|max:80',
            'footer_links.*.url' => 'required_with:footer_links|url|max:255',

            // SEO Settings
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:2048',

            'contact_centers' => 'nullable|array',
            'contact_centers.*.name' => 'required_with:contact_centers|string|max:150',
            'contact_centers.*.address' => 'required_with:contact_centers|string|max:500',
            'contact_centers.*.phone' => 'nullable|string|max:100',
            'contact_centers.*.email' => 'nullable|email|max:150',
            'contact_centers.*.website' => 'nullable|string|max:150',
        ]);

        DB::beginTransaction();
        try {
            // Save general settings
            foreach ($validated as $key => $value) {
                // MODIFIED: Added new keys to skip list so we handle them manually
                if (in_array($key, [
                    'college_logo',
                    'top_banner_image',
                    'top_banner_image_dark', // Skip
                    'favicon',
                    'banner_media',
                    'meta_image',
                    'background_audio',
                    'footer_logo' // Skip
                ])) {
                    continue;
                }
                if ($key === 'footer_links' && is_array($value)) {
                    Setting::set('footer_links', json_encode(array_values($value)));
                    continue;
                }
                if ($key === 'contact_centers' && is_array($value)) {
                    Setting::set('contact_centers', json_encode(array_values($value)));
                    continue;
                }
                Setting::set($key, $value);
            }

            // Upload logo
            if ($request->hasFile('college_logo')) {
                if ($oldLogo = Setting::get('college_logo')) {
                    $this->deleteImage($oldLogo);
                }
                $path = $this->compressAndUpload($request->file('college_logo'), 'logos');
                Setting::set('college_logo', $path);
            }

            // Upload top image (LIGHT)
            if ($request->hasFile('top_banner_image')) {
                if ($oldtop_banner_image = Setting::get('top_banner_image')) {
                    $this->deleteImage($oldtop_banner_image);
                }
                $path = $this->compressAndUpload($request->file('top_banner_image'), 'banners');
                Setting::set('top_banner_image', $path);
            }

            // ADDED: Upload top image (DARK)
            if ($request->hasFile('top_banner_image_dark')) {
                if ($oldtop_banner_image_dark = Setting::get('top_banner_image_dark')) {
                    $this->deleteImage($oldtop_banner_image_dark);
                }
                $path = $this->compressAndUpload($request->file('top_banner_image_dark'), 'banners');
                Setting::set('top_banner_image_dark', $path);
            }

            // ADDED: Upload footer logo
            if ($request->hasFile('footer_logo')) {
                if ($oldFooterLogo = Setting::get('footer_logo')) {
                    $this->deleteImage($oldFooterLogo);
                }
                $path = $this->compressAndUpload($request->file('footer_logo'), 'logos');
                Setting::set('footer_logo', $path);
            }

            // Upload favicon
            if ($request->hasFile('favicon')) {
                if ($oldFavicon = Setting::get('favicon')) {
                    $this->deleteImage($oldFavicon);
                }
                $path = $this->compressAndUpload($request->file('favicon'), 'favicons');
                Setting::set('favicon', $path);
            }

            // ADDED: Upload Background Audio (Stored in 'music' folder)
            if ($request->hasFile('background_audio')) {
                // Delete old audio if it exists
                if ($oldAudio = Setting::get('background_audio')) {
                    Storage::disk('public')->delete($oldAudio);
                }

                // Store in 'music' folder
                $path = $request->file('background_audio')->store('music', 'public');
                Setting::set('background_audio', $path);
            }

            // Upload Meta Image
            if ($request->hasFile('meta_image')) {
                if ($oldImage = Setting::get('meta_image')) {
                    $this->deleteImage($oldImage);
                }
                $path = $this->compressAndUpload($request->file('meta_image'), 'seo');
                Setting::set('meta_image', $path);
            }

            // Upload banner media
            if ($request->hasFile('banner_media')) {
                $this->handleBannerMedia($request->file('banner_media'));
            }

            DB::commit();
            return back()->with('success', 'Website settings updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update settings: " . $e->getMessage());
            return back()->with('error', 'Failed to update settings. Please check logs. ' . $e->getMessage());
        }
    }

    /**
     * Handle multiple banner files (images/videos)
     * This will DELETE all old media first.
     */
    private function handleBannerMedia(array $files)
    {
        Log::info("Starting banner media upload for " . count($files) . " files.");

        // Delete old banner media
        $oldMedia = Setting::where('key', 'like', 'banner_media_%')->get();
        foreach ($oldMedia as $item) {
            try {
                $media = json_decode($item->value, true);
                if (isset($media['path'])) {
                    Storage::disk('public')->delete($media['path']);
                    Log::info("Deleted old media: " . $media['path']);
                }
                $item->delete();
            } catch (\Exception $e) {
                Log::error("Failed to delete old banner media {$item->key}: " . $e->getMessage());
            }
        }

        foreach ($files as $index => $file) {
            try {
                $mime = $file->getMimeType();
                $key = "banner_media_{$index}";
                Log::info("Processing file #{$index} ({$file->getClientOriginalName()}) with MIME: {$mime}");

                if (str_starts_with($mime, 'image/')) {
                    $this->compressImage($file, $key);
                } elseif (str_starts_with($mime, 'video/')) {
                    $this->compressVideo($file, $key);
                } else {
                    Log::warning("Unsupported media type for {$file->getClientOriginalName()}: {$mime}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to process banner media #{$index} ({$file->getClientOriginalName()}): " . $e->getMessage());
            }
        }
    }

    private function compressVideo($file, $key)
    {
        Log::info("Starting video upload natively (without FFMpeg): {$file->getClientOriginalName()}");
        try {
            // Save video natively without compression
            $path = $file->store('banners', 'public');
            
            Setting::set($key, json_encode([
                'type' => 'video',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]));

            Log::info("Video banner setting saved natively: {$key}");
        } catch (\Exception $e) {
            Log::error("Video upload failed for {$file->getClientOriginalName()}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Compress and save image
     */
    private function compressImage($file, $key)
    {
        try {
            $path = $this->compressAndUpload($file, 'banners');
            Setting::set($key, json_encode([
                'type' => 'image',
                'path' => $path,
            ]));
        } catch (\Exception $e) {
            Log::error("Image compression failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all banner media from database
     */
    private function getBannerMedia()
    {
        $media = [];
        $settings = Setting::where('key', 'like', 'banner_media_%')->orderBy('key')->get();

        foreach ($settings as $item) {
            $media[] = (object)[
                'key' => $item->key,
                'value' => $item->value
            ];
        }
        return $media;
    }

    /**
     * Delete a specific banner media item
     */
    public function deleteBannerMedia(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|starts_with:banner_media_'
        ]);

        DB::beginTransaction();
        try {
            $key = $validated['key'];
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                $media = json_decode($setting->value, true);

                // Delete file from public storage
                if (isset($media['path'])) {
                    Storage::disk('public')->delete($media['path']);
                }

                // Delete setting from database
                $setting->delete();

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Media deleted successfully.']);
            }

            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Media not found.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete media {$request->key}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error, could not delete media.'], 500);
        }
    }
}
