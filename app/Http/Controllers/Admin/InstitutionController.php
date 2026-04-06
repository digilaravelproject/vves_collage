<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionResult;
use App\Models\InstitutionPrincipal;
use App\Models\InstitutionPTAMember;
use App\Models\InstitutionAward;
use App\Models\InstitutionGallery;
use App\Models\InstitutionSection;
use App\Models\InstitutionStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::latest()->paginate(10);
        return view('admin.institutions.index', compact('institutions'));
    }

    public function create()
    {
        $categories = Institution::getCategories();
        return view('admin.institutions.create', compact('categories'));
    }

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
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $data = $request->except('featured_image');
                $data['slug'] = $request->slug ?: Str::slug($request->name);
                $data['status'] = $request->has('status');

                if ($request->hasFile('featured_image')) {
                    $data['featured_image'] = $request->file('featured_image')->store('uploads/institutions', 'public');
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

    public function edit(Institution $institution)
    {
        $categories = Institution::getCategories();
        $institution->load(['results', 'principal', 'ptaMembers', 'awards', 'galleries', 'sections', 'staffs']);
        return view('admin.institutions.edit', compact('institution', 'categories'));
    }

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
            'academic_activities' => 'nullable|string',
            'co_curricular_activities' => 'nullable|string',
            'growth_graph' => 'nullable|image|max:5120',
        ]);

        try {
            return DB::transaction(function () use ($request, $institution) {
                $data = $request->except(['featured_image', 'growth_graph', 'sections', 'status_toggle_present']);
                if ($request->has('status_toggle_present')) {
                    $data['status'] = $request->has('status');
                }

                if ($request->hasFile('featured_image')) {
                    if ($institution->featured_image) {
                        Storage::disk('public')->delete($institution->featured_image);
                    }
                    $data['featured_image'] = $request->file('featured_image')->store('uploads/institutions', 'public');
                }

                if ($request->hasFile('growth_graph')) {
                    if ($institution->growth_graph) {
                        Storage::disk('public')->delete($institution->growth_graph);
                    }
                    $data['growth_graph'] = $request->file('growth_graph')->store('uploads/institutions/graphs', 'public');
                }

                $institution->update($data);

                // Handle Sections
                if ($request->has('sections')) {
                    foreach ($request->sections as $type => $content) {
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

    public function destroy(Institution $institution)
    {
        try {
            $institution->delete();
            return redirect()->route('admin.institutions.index')->with('success', 'Institution deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Institution Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete institution.');
        }
    }

    public function toggleStatus(Institution $institution)
    {
        try {
            $institution->status = !$institution->status;
            $institution->save();
            return back()->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Institution Toggle Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update status.');
        }
    }

    // Sub-resource management
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
        ]);

        try {
            if ($request->hasFile('student_photo')) {
                $validated['student_photo'] = $request->file('student_photo')->store('uploads/institutions/results', 'public');
            }

            $institution->results()->create($validated);
            return back()->with('success', 'Result added successfully.');
        } catch (\Exception $e) {
            Log::error('Institution SaveResult Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save result.');
        }
    }

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
                if ($institution->principal && $institution->principal->photo) {
                    Storage::disk('public')->delete($institution->principal->photo);
                }
                $validated['photo'] = $request->file('photo')->store('uploads/institutions/principals', 'public');
            }

            $institution->principal()->updateOrCreate(['institution_id' => $institution->id], $validated);
            return back()->with('success', 'Principal information updated.');
        } catch (\Exception $e) {
            Log::error('Institution SavePrincipal Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save principal info.');
        }
    }

    public function savePtaMember(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'mobile' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('uploads/institutions/pta', 'public');
            }

            $institution->ptaMembers()->create($validated);
            return back()->with('success', 'PTA Member added successfully.');
        } catch (\Exception $e) {
            Log::error('Institution SavePta Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save member.');
        }
    }

    public function saveAward(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('uploads/institutions/awards', 'public');
            }

            $institution->awards()->create($validated);
            return back()->with('success', 'Award added successfully.');
        } catch (\Exception $e) {
            Log::error('Institution SaveAward Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to save award.');
        }
    }

    public function uploadGallery(Request $request, Institution $institution)
    {
        $request->validate([
            'images.*' => 'required|image|max:5120',
        ]);

        try {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/institutions/gallery', 'public');
                    $institution->galleries()->create([
                        'image_path' => $path,
                    ]);
                }
            }
            return back()->with('success', 'Images uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Institution UploadGallery Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload images.');
        }
    }

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
                $data['photo'] = $request->file('photo')->store('uploads/institutions/staff', 'public');
            }

            if ($request->staff_id) {
                $staff = $institution->staffs()->findOrFail($request->staff_id);
                if ($request->hasFile('photo') && $staff->photo) {
                    Storage::disk('public')->delete($staff->photo);
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

    public function deleteSubItem(Institution $institution, $type, $id)
    {
        try {
            switch ($type) {
                case 'result': $item = $institution->results()->findOrFail($id); break;
                case 'pta': $item = $institution->ptaMembers()->findOrFail($id); break;
                case 'award': $item = $institution->awards()->findOrFail($id); break;
                case 'gallery': $item = $institution->galleries()->findOrFail($id); break;
                case 'staff': $item = $institution->staffs()->findOrFail($id); break;
                default: return back()->with('error', 'Invalid type.');
            }

            $filePath = $item->photo ?? $item->student_photo ?? $item->image_path ?? null;
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $item->delete();
            return back()->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Institution DeleteSubItem Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete item.');
        }
    }

}
