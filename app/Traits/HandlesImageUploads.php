<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

/**
 * Trait HandlesImageUploads
 * 
 * Provides centralized image optimization and deletion helpers.
 * 
 * @method \Intervention\Image\Interfaces\ImageInterface read(mixed $input)
 */
trait HandlesImageUploads
{
    /**
     * Compress and upload an image, converting it to WebP.
     *
     * @param UploadedFile|null $file The uploaded file instance
     * @param string $directory The directory to store the image in (e.g. 'uploads/banners')
     * @param int $quality The quality of the WebP image (0-100)
     * @param int|null $maxWidth The maximum width of the image (null for no resize)
     * @return string|null The stored file path relative to disk root, or null if no file
     */
    public function compressAndUpload(?UploadedFile $file, string $directory, int $quality = 80, ?int $maxWidth = 1920, ?string $filename = null): ?string
    {
        if (!$file) {
            return null;
        }

        // Generate a unique filename using WebP extension if not provided
        // Strip extension if provided in filename to force .webp
        if ($filename) {
            $filename = pathinfo($filename, PATHINFO_FILENAME);
        } else {
            $filename = uniqid() . '_' . time();
        }
        
        $finalFilename = $filename . '.webp';
        $path = $directory . '/' . $finalFilename;

        // Initialize ImageManager with GD driver explicitly
        $manager = new ImageManager(new Driver());

        // Read image from uploaded file
        $image = $manager->decode($file);

        // Resize if it exceeds maxWidth
        if ($maxWidth !== null && $image->width() > $maxWidth) {
            $image->scaleDown(width: $maxWidth);
        }

        // Encode as WebP with specified quality
        $encoded = $image->encode(new WebpEncoder(quality: $quality));

        // Store the encoded image using Laravel's Storage facade (public disk)
        Storage::disk('public')->put($path, (string) $encoded);

        return $path; // returning relative path inside 'public' disk
    }

    /**
     * Delete an image from storage safely.
     *
     * @param string|null $path The path to the image
     * @return bool True if deleted or empty, false on failure
     */
    public function deleteImage(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return true; 
    }
}
