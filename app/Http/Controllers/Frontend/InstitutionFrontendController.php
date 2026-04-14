<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionFrontendController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::where('status', true);
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $institutions = $query->latest()->get();
        $categories = Institution::getCategories();

        $breadcrumbTrail = [
            ['label' => 'Our Institute', 'url' => null]
        ];

        return view('frontend.institutions.index', compact('institutions', 'categories', 'breadcrumbTrail'));
    }

    public function show($slug)
    {
        $institution = Institution::where('slug', $slug)
            ->where('status', true)
            ->with(['results', 'principal', 'ptaMembers', 'awards', 'galleries', 'sections'])
            ->firstOrFail();

        $otherInstitutions = Institution::where('id', '!=', $institution->id)
            ->where('status', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $breadcrumbTrail = [
            ['label' => 'Our Institute', 'url' => route('institutions.index')],
            ['label' => $institution->name, 'url' => null]
        ];

        return view('frontend.institutions.show', compact('institution', 'otherInstitutions', 'breadcrumbTrail'));
    }

}
