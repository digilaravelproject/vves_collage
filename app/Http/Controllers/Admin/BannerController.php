<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use FFMpeg\Filters\Video\ResizeFilter;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class BannerController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'media' => 'required|file|mimes:jpg,jpeg,png,webp,svg,mp4,mov,avi|max:51200',
            'mobile_media' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:20480',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('media');
            $mime = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
            
            $banner = new Banner($validated);
            $banner->media_type = $mediaType;
            $banner->is_active = $request->has('is_active');
            $banner->order = Banner::max('order') + 1;

            if ($mediaType === 'image') {
                $banner->media_path = $this->processImage($file);
            } else {
                $banner->media_path = $this->processVideo($file);
            }

            if ($request->hasFile('mobile_media')) {
                $banner->mobile_media_path = $this->processImage($request->file('mobile_media'));
            }

            $banner->save();
            DB::commit();

            return redirect()->route('admin.banners.index')->with('success', 'Banner slide created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create banner: " . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg,mp4,mov,avi|max:51200',
            'mobile_media' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:20480',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $banner->fill($validated);
            $banner->is_active = $request->has('is_active');

            if ($request->hasFile('media')) {
                // Delete old file
                Storage::disk('public')->delete($banner->media_path);

                $file = $request->file('media');
                $mime = $file->getMimeType();
                $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
                $banner->media_type = $mediaType;

                if ($mediaType === 'image') {
                    $banner->media_path = $this->processImage($file);
                } else {
                    $banner->media_path = $this->processVideo($file);
                }
            }

            if ($request->hasFile('mobile_media')) {
                // Delete old mobile file if exists
                if ($banner->mobile_media_path) {
                    Storage::disk('public')->delete($banner->mobile_media_path);
                }
                $banner->mobile_media_path = $this->processImage($request->file('mobile_media'));
            }

            $banner->save();
            DB::commit();

            return redirect()->route('admin.banners.index')->with('success', 'Banner slide updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update banner: " . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            Storage::disk('public')->delete($banner->media_path);
            if ($banner->mobile_media_path) {
                Storage::disk('public')->delete($banner->mobile_media_path);
            }
            $banner->delete();
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();
        return response()->json(['success' => true, 'is_active' => $banner->is_active]);
    }

    public function reorder(Request $request)
    {
        $orders = $request->input('orders');
        foreach ($orders as $item) {
            Banner::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        return response()->json(['success' => true]);
    }

    private function processImage($file)
    {
        $ext = $file->getClientOriginalExtension();
        if ($ext === 'svg') {
            return $file->store('banners', 'public');
        }

        return $this->compressAndUpload($file, 'banners');
    }

    private function processVideo($file)
    {
        $tempPath = $file->store('temp');
        $fullTempPath = Storage::path($tempPath);

        $filename = 'video_' . uniqid() . '.mp4';
        $finalPath = 'banners/' . $filename;
        $fullDestPath = Storage::disk('public')->path($finalPath);
        Storage::disk('public')->makeDirectory(dirname($finalPath));

        // Binary Paths (checking shared logic from WebsiteSettingController)
        $ffmpegPath = '/usr/bin/ffmpeg';
        $ffprobePath = '/usr/bin/ffprobe';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $ffmpegPath = 'C:\\ffmpeg\\bin\\ffmpeg.exe';
            $ffprobePath = 'C:\\ffmpeg\\bin\\ffprobe.exe';
        }

        try {
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => $ffmpegPath,
                'ffprobe.binaries' => $ffprobePath,
                'timeout'          => 3600,
                'ffmpeg.threads'   => 4,
            ]);

            $video = $ffmpeg->open($fullTempPath);
            $video->filters()->resize(new Dimension(1280, 720), ResizeFilter::RESIZEMODE_FIT, true);

            $format = new X264('aac', 'libx264');
            $format->setKiloBitrate(1500);
            $format->setAdditionalParameters(['-movflags', '+faststart', '-crf', '24']);

            $video->save($format, $fullDestPath);

            Storage::delete($tempPath);
            return $finalPath;
        } catch (\Exception $e) {
            Storage::delete($tempPath);
            throw $e;
        }
    }
}
