<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionSection;
use App\Traits\HandlesImageUploads;
use App\Traits\InterceptsWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    use HandlesImageUploads, InterceptsWorkflow;

    /**
     * Display a listing of institutions.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $institutions = Institution::query()->latest()->paginate(10);

        return view('admin.institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new institution.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Institution::getCategories();

        return view('admin.institutions.create', compact('categories'));
    }

    /**
     * Store a newly created institution in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:institutions,slug',
            'category' => 'required|string',
            'curriculum' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|max:5120',
            'year_of_establishment' => 'nullable|string',
            'status' => 'nullable',
            'google_maps_link' => 'nullable|url',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $data = $request->except(['featured_image', 'breadcrumb_image', 'academic_diary_pdf']);
                $data['slug'] = $request->slug ?: Str::slug($request->name);
                $data['status'] = $request->has('status');

                if ($request->hasFile('featured_image')) {
                    $data['featured_image'] = $this->compressAndUpload($request->file('featured_image'), 'uploads/institutions');
                }

                if ($this->shouldStage()) {
                    $this->stageAction(Institution::class, 'CREATE', $data);
                    return redirect()->route('admin.institutions.index')
                        ->with('success', 'New institution request submitted for approval.');
                }

                $institution = Institution::create($data);

                return redirect()->route('admin.institutions.edit', $institution->id)
                    ->with('success', 'Institution created successfully. Now you can add more details.');
            });
        } catch (\Exception $e) {
            Log::error('Institution Store Error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to create institution. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified institution.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Institution $institution)
    {
        $categories = Institution::getCategories();
        $institution->load(['results', 'principal', 'ptaMembers', 'awards', 'galleries', 'sections', 'staffs']);

        return view('admin.institutions.edit', compact('institution', 'categories'));
    }

    /**
     * Update the specified institution in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Institution $institution)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:institutions,slug,' . $institution->id,
            'category' => 'required|string',
            'curriculum' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|max:5120',
            'year_of_establishment' => 'nullable|string',
            'status' => 'nullable',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'website' => 'nullable|url',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'social_links' => 'nullable|array',
            'institutional_journey' => 'nullable|string',
            'about_sections' => 'nullable|array',
            'academic_activities' => 'nullable|string',
            'activities_facilities_blocks' => 'nullable|array',
            'co_curricular_activities' => 'nullable|string',
            'iso_certification' => 'nullable|string|max:255',
            'breadcrumb_image' => 'nullable|image|max:5120',
            'tagline' => 'nullable|string|max:255',
            'academic_diary_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'google_maps_link' => 'nullable|url',
            'breadcrumb_note' => 'nullable|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($request, $institution) {
                $data = $request->except(['featured_image', 'breadcrumb_image', 'academic_diary_pdf', 'sections', 'status_toggle_present']);

                if ($request->has('status_toggle_present')) {
                    $data['status'] = $request->has('status');
                }

                $isStaging = $this->shouldStage();

                if ($request->hasFile('featured_image')) {
                    // Only delete old image if NOT staging
                    if (!$isStaging && $institution->featured_image) {
                        $this->deleteImage($institution->featured_image);
                    }
                    $data['featured_image'] = $this->compressAndUpload($request->file('featured_image'), 'uploads/institutions');
                }

                if ($request->hasFile('breadcrumb_image')) {
                    if (!$isStaging && $institution->breadcrumb_image) {
                        $this->deleteImage($institution->breadcrumb_image);
                    }
                    $data['breadcrumb_image'] = $this->compressAndUpload($request->file('breadcrumb_image'), 'uploads/institutions/banners');
                }

                if ($request->hasFile('academic_diary_pdf')) {
                    if (!$isStaging && $institution->academic_diary_pdf) {
                        Storage::disk('public')->delete($institution->academic_diary_pdf);
                    }
                    $data['academic_diary_pdf'] = $request->file('academic_diary_pdf')->store('uploads/institutions/pdfs', 'public');
                }

                // Handle Results & Awards Nested Files
                if ($request->has('results_awards')) {
                    $results_awards = $request->results_awards;
                    foreach ($results_awards as $sIdx => $section) {
                        if (isset($section['items'])) {
                            foreach ($section['items'] as $iIdx => $item) {
                                $fileKey = "results_awards.$sIdx.items.$iIdx.photo";
                                if ($request->hasFile($fileKey)) {
                                    $results_awards[$sIdx]['items'][$iIdx]['photo'] = $this->compressAndUpload($request->file($fileKey), 'uploads/institutions/achievements');
                                } else {
                                    $results_awards[$sIdx]['items'][$iIdx]['photo'] = $item['existing_photo'] ?? null;
                                }
                                unset($results_awards[$sIdx]['items'][$iIdx]['existing_photo']);

                                if (isset($item['students'])) {
                                    foreach ($item['students'] as $stIdx => $student) {
                                        $stFileKey = "results_awards.$sIdx.items.$iIdx.students.$stIdx.photo";
                                        if ($request->hasFile($stFileKey)) {
                                            $results_awards[$sIdx]['items'][$iIdx]['students'][$stIdx]['photo'] = $this->compressAndUpload($request->file($stFileKey), 'uploads/institutions/students');
                                        } else {
                                            $results_awards[$sIdx]['items'][$iIdx]['students'][$stIdx]['photo'] = $student['existing_photo'] ?? null;
                                        }
                                        unset($results_awards[$sIdx]['items'][$iIdx]['students'][$stIdx]['existing_photo']);
                                    }
                                }
                            }
                        }
                    }
                    $data['results_awards'] = $results_awards;
                }

                // Add sections to data for staging
                if ($request->has('sections')) {
                    $data['sections'] = $request->sections;
                }

                if ($isStaging) {
                    $this->stageAction($institution, 'UPDATE', $data);
                    return back()->with('success', 'Changes submitted for approval. They will be visible once an approver reviews them.');
                }

                $institution->update($data);

                // Handle Sections (Direct update)
                if (isset($data['sections'])) {
                    foreach ($data['sections'] as $type => $content) {
                        InstitutionSection::updateOrCreate(
                            ['institution_id' => $institution->id, 'type' => $type],
                            ['content' => $content]
                        );
                    }
                }

                return back()->with('success', 'Institution updated successfully.');
            });
        } catch (\Exception $e) {
            Log::error('Institution Update Error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to update institution.');
        }
    }

    /**
     * Remove the specified institution from storage.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Institution $institution)
    {
        try {
            if ($this->shouldStage()) {
                $this->stageAction($institution, 'DELETE', []);
                return back()->with('success', 'Deletion request submitted for approval.');
            }

            $institution->delete();

            return redirect()->route('admin.institutions.index')->with('success', 'Institution deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Institution Delete Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete institution.');
        }
    }

    /**
     * Toggle the status of the specified institution.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Institution $institution)
    {
        try {
            $newStatus = !$institution->status;

            if ($this->shouldStage()) {
                $this->stageAction($institution, 'UPDATE', ['status' => $newStatus]);
                return back()->with('success', 'Status change submitted for approval.');
            }

            $institution->status = $newStatus;
            $institution->save();

            return back()->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Institution Toggle Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to update status.');
        }
    }

    // Sub-resource management
    /**
     * Save a result sub-item for the institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveResult(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'percentage' => 'nullable|string',
            'year' => 'nullable|string',
            'medium' => 'nullable|string',
            'overall_result' => 'nullable|string',
            'grades' => 'nullable|array',
            'description' => 'nullable|string',
            'student_photo' => 'nullable|image|max:2048',
            'student_name' => 'nullable|string',
            'subject' => 'nullable|string',
            'passing_year' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('student_photo')) {
                $validated['student_photo'] = $this->compressAndUpload($request->file('student_photo'), 'uploads/institutions/results');
            }

            if ($this->shouldStage()) {
                $this->stageAction(\App\Models\InstitutionResult::class, 'CREATE', array_merge($validated, ['institution_id' => $institution->id]));
                return back()->with('success', 'New result submitted for approval.');
            }

            $institution->results()->create($validated);

            return back()->with('success', 'Result added successfully.');
        } catch (\Exception $e) {
            Log::error('Institution SaveResult Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to save result.');
        }
    }

    /**
     * Save principal information for the institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePrincipal(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'designation' => 'nullable|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                if ($institution->principal && $institution->principal->photo && !$this->shouldStage()) {
                    $this->deleteImage($institution->principal->photo);
                }
                $validated['photo'] = $this->compressAndUpload($request->file('photo'), 'uploads/institutions/principals');
            }

            if ($this->shouldStage()) {
                $model = $institution->principal ?: \App\Models\InstitutionPrincipal::class;
                $action = $institution->principal ? 'UPDATE' : 'CREATE';
                $this->stageAction($model, $action, array_merge($validated, ['institution_id' => $institution->id]));
                return back()->with('success', 'Principal information update submitted for approval.');
            }

            $institution->principal()->updateOrCreate(['institution_id' => $institution->id], $validated);

            return back()->with('success', 'Principal information updated.');
        } catch (\Exception $e) {
            Log::error('Institution SavePrincipal Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to save principal info.');
        }
    }

    /**
     * Save a PTA member for the institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePtaMember(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'mobile' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $this->compressAndUpload($request->file('photo'), 'uploads/institutions/pta');
            }

            if ($this->shouldStage()) {
                $this->stageAction(\App\Models\InstitutionPTAMember::class, 'CREATE', array_merge($validated, ['institution_id' => $institution->id]));
                return back()->with('success', 'PTA Member addition submitted for approval.');
            }

            $institution->ptaMembers()->create($validated);

            return back()->with('success', 'PTA Member added successfully.');
        } catch (\Exception $e) {
            Log::error('Institution SavePta Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to save member.');
        }
    }

    /**
     * Save an award for the institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveAward(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'award_name' => 'nullable|string',
            'recipient_name' => 'nullable|string',
            'award_date' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $this->compressAndUpload($request->file('photo'), 'uploads/institutions/awards');
            }

            if ($this->shouldStage()) {
                $this->stageAction(\App\Models\InstitutionAward::class, 'CREATE', array_merge($validated, ['institution_id' => $institution->id]));
                return back()->with('success', 'Award addition submitted for approval.');
            }

            $institution->awards()->create($validated);

            return back()->with('success', 'Award added successfully.');
        } catch (\Exception $e) {
            Log::error('Institution SaveAward Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to save award.');
        }
    }

    /**
     * Upload gallery images for the institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadGallery(Request $request, Institution $institution)
    {
        $request->validate([
            'images' => 'required|array|max:30',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'images.max' => 'You can only upload up to 30 images at a time.',
            'images.*.max' => 'Each image must not exceed 10MB.',
        ]);

        try {
            if ($request->hasFile('images')) {
                $paths = [];
                foreach ($request->file('images') as $image) {
                    $paths[] = $this->compressAndUpload($image, 'uploads/institutions/gallery');
                }

                if ($this->shouldStage()) {
                    $this->stageAction(\App\Models\InstitutionGallery::class, 'BULK_CREATE', [
                        'institution_id' => $institution->id,
                        'images' => $paths
                    ]);
                    return back()->with('success', 'Gallery images upload submitted for approval.');
                }

                foreach ($paths as $path) {
                    $institution->galleries()->create(['image_path' => $path]);
                }
            }

            return back()->with('success', 'Images uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Institution UploadGallery Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to upload images. Please check if file sizes are within limits.');
        }
    }

    /**
     * Remove the specified image (featured or breadcrumb) from the institution.
     *
     * @param  \App\Models\Institution  $institution
     * @param  string  $image_type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Institution $institution, $image_type)
    {
        try {
            if (!in_array($image_type, ['featured', 'breadcrumb'])) {
                return back()->with('error', 'Invalid image type.');
            }

            $column = $image_type === 'featured' ? 'featured_image' : 'breadcrumb_image';
            $oldPath = $institution->$column;

            if ($this->shouldStage()) {
                $this->stageAction($institution, 'UPDATE', [$column => null]);
                return back()->with('success', 'Image removal request submitted for approval.');
            }

            if ($oldPath) {
                $this->deleteImage($oldPath);
            }

            $institution->update([$column => null]);

            return back()->with('success', ucfirst($image_type) . ' image removed successfully.');
        } catch (\Exception $e) {
            Log::error('Institution RemoveImage Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove image.');
        }
    }

    /**
     * Save a staff member for the institution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveStaff(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'staff_id' => 'nullable|exists:institution_staffs,id',
            'name' => 'required|string',
            'section' => 'nullable|string',
            'subject' => 'nullable|string',
            'qualification' => 'nullable|string',
            'experience' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            $data = $request->except(['photo', 'staff_id']);

            if ($request->hasFile('photo')) {
                $data['photo'] = $this->compressAndUpload($request->file('photo'), 'uploads/institutions/staff');
            }

            if ($this->shouldStage()) {
                $model = $request->staff_id ? $institution->staffs()->findOrFail($request->staff_id) : \App\Models\InstitutionStaff::class;
                $action = $request->staff_id ? 'UPDATE' : 'CREATE';
                $this->stageAction($model, $action, array_merge($data, ['institution_id' => $institution->id]));
                return back()->with('success', 'Staff member information submitted for approval.');
            }

            if ($request->staff_id) {
                $staff = $institution->staffs()->findOrFail($request->staff_id);
                if ($request->hasFile('photo') && $staff->photo) {
                    $this->deleteImage($staff->photo);
                }
                $staff->update($data);
                $msg = 'Staff member updated successfully.';
            } else {
                $institution->staffs()->create($data);
                $msg = 'Staff member added successfully.';
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            Log::error('Institution SaveStaff Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to save staff member.');
        }
    }

    /**
     * Delete a sub-item (result, award, etc.) of the institution.
     *
     * @param  \App\Models\Institution  $institution
     * @param  string  $type
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSubItem(Institution $institution, $type, $id)
    {
        try {
            switch ($type) {
                case 'result':
                    $modelClass = \App\Models\InstitutionResult::class;
                    break;
                case 'pta':
                    $modelClass = \App\Models\InstitutionPTAMember::class;
                    break;
                case 'award':
                    $modelClass = \App\Models\InstitutionAward::class;
                    break;
                case 'gallery':
                    $modelClass = \App\Models\InstitutionGallery::class;
                    break;
                case 'staff':
                    $modelClass = \App\Models\InstitutionStaff::class;
                    break;
                default:
                    return back()->with('error', 'Invalid type.');
            }

            $item = $modelClass::where('institution_id', $institution->id)->findOrFail($id);

            if ($this->shouldStage()) {
                $this->stageAction($item, 'DELETE', []);
                return back()->with('success', 'Deletion request submitted for approval.');
            }

            $filePath = $item->photo ?? $item->student_photo ?? $item->image_path ?? null;
            if ($filePath) {
                $this->deleteImage($filePath);
            }
            $item->delete();

            return back()->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Institution DeleteSubItem Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete item.');
        }
    }
}
