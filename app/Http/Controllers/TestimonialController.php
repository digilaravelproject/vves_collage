<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    use HandlesImageUploads;

    /**
     * Display a listing of the testimonials.
     */
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(15);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_name' => 'required|string|max:255',
                'student_image' => 'nullable|image|max:15360', // 15MB limit
                'testimonial_text' => 'required|string|max:1000',
                'status' => 'nullable|boolean',
            ]);

            if ($request->hasFile('student_image')) {
                // Convert to WebP format
                $webpPath = $this->compressAndUpload($request->file('student_image'), 'uploads/testimonials');
                if (!$webpPath) {
                    return back()->with('error', 'Unsupported image format.');
                }
                $validated['student_image'] = $webpPath;
            }

            // Ensure status is set correctly
            $validated['status'] = (bool)($validated['status'] ?? false);

            Testimonial::create($validated);

            return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created');
        } catch (\Exception $e) {
            Log::error("Error creating testimonial: " . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create testimonial.');
        }
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', ['testimonial' => $testimonial]);
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        try {
            $validated = $request->validate([
                'student_name' => 'required|string|max:255',
                'student_image' => 'nullable|image|max:15360', // 15MB limit
                'testimonial_text' => 'required|string|max:1000',
                'status' => 'nullable|boolean',
            ]);

            if ($request->hasFile('student_image')) {
                // Delete old image
                $this->deleteImage($testimonial->student_image);

                // Convert new image to WebP
                $webpPath = $this->compressAndUpload($request->file('student_image'), 'uploads/testimonials');
                if (!$webpPath) {
                    return back()->with('error', 'Unsupported image format.');
                }

                $validated['student_image'] = $webpPath;
            }

            // Ensure status is correctly updated
            $validated['status'] = (bool)($validated['status'] ?? $testimonial->status);

            $testimonial->update($validated);

            return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated');
        } catch (\Exception $e) {
            Log::error("Error updating testimonial: " . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update testimonial.');
        }
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            // Delete image file if exists
            $this->deleteImage($testimonial->student_image);

            $testimonial->delete();

            return back()->with('success', 'Testimonial deleted');
        } catch (\Exception $e) {
            Log::error("Error deleting testimonial: " . $e->getMessage());
            return back()->with('error', 'Failed to delete testimonial.');
        }
    }
}
