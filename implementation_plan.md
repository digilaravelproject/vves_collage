# Implementation Plan: System-wide Image Compression & WebP Conversion

The objective is to ensure all images uploaded to the system are automatically compressed (max 1MB) and converted to the modern WebP format for improved page load speeds and storage efficiency.

## User Review Required

> [!IMPORTANT]
> - This change affects approximately 25-30 controllers. While the logic is safe, it will modify how files are stored (paths/extensions).
> - I will install `intervention/image` (v3) to handle the image processing, as it is more professional and robust than the basic GD library.
> - Existing images will not be converted automatically; only new uploads will follow the new format.

## Proposed Changes

### Core Infrastructure

#### [NEW] [HandlesImageUploads.php](file:///c:/xampp_old/htdocs/Digi_Laravel_Prrojects/vves_college/app/Traits/HandlesImageUploads.php)
Create a centralized Trait to handle image processing.
- `compressAndUpload($file, $directory, $quality = 80)`: Main method to convert to WebP, compress to < 1MB, and save.
- `deleteImage($path)`: Helper to safely delete old files.

#### [MODIFY] [composer.json](file:///c:/xampp_old/htdocs/Digi_Laravel_Prrojects/vves_college/composer.json)
- Add `intervention/image:^3.0` dependency.

---

### Component Refactoring (Approx 25 Controllers)

I will systematically update the following controllers to use the `HandlesImageUploads` trait:

#### [MODIFY] Controllers with existing manual uploads:
- `BannerController.php`
- `GalleryImageController.php` (Remove local `convertToWebp`)
- `TestimonialController.php` (Remove local `convertToWebp`)
- `WhyChooseUsController.php`
- `EventItemController.php`
- `AcademicCalendarController.php`
- `InstitutionController.php` (Handling Multiple Upload Points)
- `WebsiteSettingController.php` (Logo, Favicon, etc.)
- `TrustSectionController.php`
- `PopupController.php`
- `PageBuilderController.php`
- `MediaController.php`
- `HomepageSetupController.php`
- Any other discovered controllers during execution.

---

## Technical Approach

1. **Intervention Image integration**: Use the `webp()` encoder with a configurable quality (default 80) to ensure the file size is minimized while maintaining visual quality.
2. **Size Enforcement**: Check the resulting WebP file size. If it still exceeds 1MB (unlikely for WebP at 80% quality), the quality will be dynamically reduced.
3. **Refactoring Step**:
   - Add `use HandlesImageUploads;` to the Controller.
   - Replace `$file->store(...)` with `$this->compressAndUpload($file, 'path/to/folder')`.
   - Update Validation messages to reflect that we accept various formats but store as WebP.

## Open Questions

- **Resizing?**: Should I also implement a max-width/height (e.g., 1920px) to prevent people from uploading 8K images that take forever to process?
- **PDFs/Videos**: Currently, some controllers handle PDFs and Videos. These will be ignored by the compressor trait and handled normally. Is this acceptable?

## Verification Plan

### Automated Tests
- Create a test script to upload various image types (JPG, PNG, TIFF) and verify they are saved as WebP.
- Verify file sizes are below 1MB.

### Manual Verification
- Test uploads in the Admin panel for:
  - Website Settings (Logos)
  - Institution Photos
  - Media Library
- Check the `storage/app/public/uploads` folder to confirm files are .webp.
