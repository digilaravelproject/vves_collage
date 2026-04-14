@extends('layouts.app')
@section('title', $institution->meta_title ?: $institution->name)
@section('meta_description', $institution->meta_description ?:
    Str::limit(strip_tags($institution->institutional_journey), 160))

@section('content')
    {{-- CSS Styles --}}
    @include('frontend.institutions.partials.styles')

    @php
        // Determine the first available tab
        $firstTab = '';

        // Prioritized list of tabs to check for content
        if (!empty(trim(strip_tags($institution->institutional_journey))) || (!empty($institution->about_sections) && count($institution->about_sections) > 0)) {
            $firstTab = 'about';
        } elseif ($institution->principal) {
            $firstTab = 'principal';
        } elseif ($institution->academic_diary_pdf) {
            $firstTab = 'academic_calendar';
        } elseif ($institution->staffs && $institution->staffs->count() > 0) {
            $firstTab = 'staffs';
        } elseif ($institution->ptaMembers && $institution->ptaMembers->count() > 0) {
            $firstTab = 'pta';
        } elseif ($institution->results_awards && count($institution->results_awards) > 0) {
            $firstTab = 'results_awards';
        } elseif ($institution->activities_facilities_blocks && count($institution->activities_facilities_blocks) > 0) {
            $firstTab = 'activities';
        } elseif ($institution->sections && $institution->sections->count() > 0) {
            // First valid dynamic section
            foreach ($institution->sections as $sec) {
                if (!empty(trim(strip_tags($sec->content)))) {
                    $firstTab = 'sec_' . $sec->id;
                    break;
                }
            }
        }
    @endphp

    <div class="w-full bg-white font-sans" x-data="{ activeTab: '{{ $firstTab }}' }">

        {{-- Top Hero Banner --}}
        @include('frontend.institutions.partials.banner')

        {{-- Main Content Area --}}
        <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

            {{--
                LAYOUT FIX: Replaced grid-cols-12 with flex row.
                This ensures the sidebar always stays strictly on the right side on large screens
                and prevents inner content from breaking the grid.
            --}}
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">

                {{-- Left Column (Tabs & Details) --}}
                <div class="w-full lg:w-[65%] xl:w-[70%] min-w-0 flex-shrink">
                    @if ($firstTab !== '')
                        {{-- Horizontal Slider Tabs (Buttons) --}}
                        @include('frontend.institutions.partials.tabs-navigation')

                        <div class="h-4"></div>

                        {{-- Tab Content Panes (The actual output data) --}}
                        <div class="min-h-[400px]">
                            @include('frontend.institutions.partials.tab-panes.about')
                            @include('frontend.institutions.partials.tab-panes.principal')
                            @include('frontend.institutions.partials.tab-panes.staffs')
                            @include('frontend.institutions.partials.tab-panes.pta')
                            @include('frontend.institutions.partials.tab-panes.academic-calendar')
                            @include('frontend.institutions.partials.tab-panes.results-awards')
                            @include('frontend.institutions.partials.tab-panes.activities')
                            @include('frontend.institutions.partials.tab-panes.dynamic-sections')
                        </div>
                    @else
                        <div
                            class="py-12 text-center text-gray-400 bg-[#F8F9FA] rounded-2xl border border-dashed border-gray-200">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                            </svg>
                            <p class="text-sm font-medium uppercase tracking-widest">Details are currently being updated.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Right Sidebar (Contact Card) --}}
                <div class="w-full lg:w-[35%] xl:w-[30%] shrink-0">
                    @include('frontend.institutions.partials.sidebar')
                </div>

            </div>
        </div>
    </div>

    {{-- Full Width Image Gallery --}}
    @if ($institution->galleries && $institution->galleries->count() > 0)
        @include('frontend.institutions.partials.gallery')
    @endif

    {{-- Other Institutes --}}
    @if (isset($otherInstitutions) && $otherInstitutions->count() > 0)
        @include('frontend.institutions.partials.other-institutes')
    @endif

    @push('scripts')
        @include('frontend.institutions.partials.scripts')
    @endpush
@endsection
