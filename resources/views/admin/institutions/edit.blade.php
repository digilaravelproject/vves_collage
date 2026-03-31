@extends('layouts.admin.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor { min-height: 200px; font-size: 16px; font-family: 'Outfit', sans-serif; }
        .ql-toolbar.ql-snow { border-top-left-radius: 1rem; border-top-right-radius: 1rem; background: #f9fafb; border-color: #e5e7eb; }
        .ql-container.ql-snow { border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; border-color: #e5e7eb; }
        .sidebar-link.active { background: #000165; color: white; box-shadow: 0 10px 15px -3px rgba(0, 1, 101, 0.2); }
    </style>
@endpush

@section('content')
    <div class="px-2 py-4 sm:p-6 lg:p-8 space-y-6" x-data="{ activeTab: 'general' }">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all">
            <div>
                <nav class="flex mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    <a href="{{ route('admin.institutions.index') }}" class="hover:text-blue-600">Institutions</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900">Edit Profile</span>
                </nav>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $institution->name }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.institutions.index') }}" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all border border-gray-100">
                    <i class="bi bi-arrow-left me-1.5"></i> Back
                </a>
                <form action="{{ route('admin.institutions.toggle-status', $institution->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold rounded-xl transition-all shadow-sm {{ $institution->status ? 'bg-green-50 text-green-700 border border-green-100 hover:bg-green-100' : 'bg-red-50 text-red-700 border border-red-100 hover:bg-red-100' }}">
                        <i class="bi bi-{{ $institution->status ? 'check-circle' : 'slash-circle' }} me-1.5"></i>
                        {{ $institution->status ? 'Active' : 'Disabled' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-3 space-y-2 lg:sticky lg:top-6 overflow-x-auto">
                <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm flex lg:flex-col gap-1 min-w-max lg:min-w-0">
                    <p class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Identity & Setup</p>
                    <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-info-circle me-3 text-base"></i> General Info
                    </button>
                    <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-telephone me-3 text-base"></i> Contact & Social
                    </button>

                    <button @click="activeTab = 'journey'" :class="activeTab === 'journey' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-journal-text me-3 text-base"></i> Journey
                    </button>
                    <button @click="activeTab = 'principal'" :class="activeTab === 'principal' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-person-badge me-3 text-base"></i> Principal Info
                    </button>
                    <button @click="activeTab = 'academic'" :class="activeTab === 'academic' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-calendar-event me-3 text-base"></i> Academic / Admin
                    </button>

                    <button @click="activeTab = 'results'" :class="activeTab === 'results' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-graph-up-arrow me-3 text-base"></i> Past Results
                    </button>
                    <button @click="activeTab = 'awards'" :class="activeTab === 'awards' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-trophy me-3 text-base"></i> Awards
                    </button>

                    <button @click="activeTab = 'infrastructure'" :class="activeTab === 'infrastructure' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-building me-3 text-base"></i> Infrastructure
                    </button>
                    <button @click="activeTab = 'activities'" :class="activeTab === 'activities' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-controller me-3 text-base"></i> Activities
                    </button>
                    <button @click="activeTab = 'scholarships'" :class="activeTab === 'scholarships' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-mortarboard me-3 text-base"></i> Scholarship
                    </button>
                    <button @click="activeTab = 'pta'" :class="activeTab === 'pta' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-people me-3 text-base"></i> PTA Members
                    </button>
                    <button @click="activeTab = 'gallery'" :class="activeTab === 'gallery' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-images me-3 text-base"></i> Gallery
                    </button>
                    <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'" class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-search me-3 text-base"></i> SEO
                    </button>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="lg:col-span-9 bg-white rounded-2xl border border-gray-100 shadow-xl min-h-[700px] overflow-hidden">
            
            {{-- Tab: General --}}
            <div x-show="activeTab === 'general'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-500">
                <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Institution Name</label>
                            <input type="text" name="name" value="{{ old('name', $institution->name) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-800">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Slug (URL)</label>
                            <input type="text" name="slug" value="{{ old('slug', $institution->slug) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-gray-500">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Category / Group</label>
                            <select name="category" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                                @foreach ($categories as $key => $label)
                                    <option value="{{ $label }}" {{ $institution->category == $label ? 'selected' : '' }}>{{ $key }}. {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Year of Establishment</label>
                            <input type="text" name="year_of_establishment" value="{{ old('year_of_establishment', $institution->year_of_establishment) }}" placeholder="e.g. 1962" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex flex-col md:flex-row gap-6 items-center">
                        <div class="shrink-0 relative group">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-white shadow-lg bg-white">
                                @if($institution->featured_image)
                                    <img src="{{ asset('storage/' . $institution->featured_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
                                        <i class="bi bi-image text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white rounded-2xl cursor-pointer" onclick="document.getElementById('featured_image_input').click()">
                                <i class="bi bi-camera text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2 text-center md:text-left">
                            <div>
                                <h3 class="text-lg font-black text-gray-900">Featured Identity Image</h3>
                                <p class="text-xs text-gray-400 font-medium">This image represents the institution across the platform.</p>
                            </div>
                            <input type="file" id="featured_image_input" name="featured_image" accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-[#000165] file:text-white cursor-pointer">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl shadow-blue-900/10 hover:scale-105 active:scale-95 transition-all">Save Identity</button>
                    </div>
                </form>
            </div>

            {{-- Tab: Contact --}}
            <div x-show="activeTab === 'contact'" x-cloak class="p-6 md:p-8 space-y-8 animate-in slide-in-from-bottom-4 duration-300">
                <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" class="space-y-8">
                    @csrf @method('PUT')
                    {{-- Hidden fields to prevent overwrite --}}
                    <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Website URL</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-globe"></i></span>
                                <input type="url" name="website" value="{{ $institution->website }}" placeholder="https://" class="w-full ps-11 pe-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" value="{{ $institution->phone }}" class="w-full ps-11 pe-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Full Address</label>
                            <textarea name="address" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-gray-700">{{ $institution->address }}</textarea>
                        </div>
                    </div>

                    <div class="p-6 bg-blue-50/30 rounded-2xl border border-blue-50">
                        <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center">
                            <i class="bi bi-share me-2"></i> Social Media Links
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php $socials = is_array($institution->social_links) ? $institution->social_links : json_decode($institution->social_links, true) ?? []; @endphp
                            @foreach(['facebook', 'instagram', 'linkedin', 'youtube'] as $social)
                                <div class="space-y-1">
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="bi bi-{{ $social }}"></i></span>
                                        <input type="url" name="social_links[{{ $social }}]" value="{{ $socials[$social] ?? '' }}" placeholder="{{ ucfirst($social) }} URL" class="w-full ps-11 pe-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-medium">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl shadow-blue-900/10 hover:scale-105 active:scale-95 transition-all">Save Details</button>
                    </div>
                </form>
            </div>

                {{-- Tab: Journey --}}
                <div x-show="activeTab === 'journey'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-500">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">
                        
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                <i class="bi bi-rocket-takeoff me-2 text-blue-600"></i> School Information / Journey
                            </label>
                            <div class="quill-editor" data-name="institutional_journey">
                                {!! old('institutional_journey', $institution->institutional_journey) !!}
                            </div>
                            <input type="hidden" name="institutional_journey" id="institutional_journey" value="{{ old('institutional_journey', $institution->institutional_journey) }}">
                        </div>

                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Visual Growth Graph</h4>
                            <div class="flex flex-col md:flex-row gap-6 items-center">
                                @if($institution->growth_graph)
                                    <div class="shrink-0">
                                        <img src="{{ asset('storage/' . $institution->growth_graph) }}" class="w-48 h-32 object-contain rounded-xl border border-white shadow-md bg-white">
                                    </div>
                                @endif
                                <div class="flex-1 w-full space-y-3">
                                    <div class="relative h-24 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center bg-white group hover:border-blue-400 transition-colors">
                                        <i class="bi bi-cloud-arrow-up text-2xl text-gray-300 group-hover:text-blue-500"></i>
                                        <span class="text-[10px] font-bold text-gray-400 mt-1">Upload Growth Image</span>
                                        <input type="file" name="growth_graph" class="absolute inset-0 opacity-0 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:scale-105 active:scale-95 transition-all">Save Journey</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Academic & Admissions --}}
                <div x-show="activeTab === 'academic'" x-cloak class="p-6 md:p-8 space-y-8 animate-in zoom-in duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">

                        <div class="space-y-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                    <i class="bi bi-calendar3 me-2 text-blue-600"></i> Academic Calendar
                                </label>
                                @php $academic_cal = $institution->sections->where('type', 'academic_calendar')->first(); @endphp
                                <div class="quill-editor" data-name="sections[academic_calendar]">
                                    {!! $academic_cal ? $academic_cal->content : '' !!}
                                </div>
                                <input type="hidden" name="sections[academic_calendar]" id="sections_academic_calendar" value="{{ $academic_cal ? $academic_cal->content : '' }}">
                            </div>

                            <div class="space-y-3 pt-4 border-t border-gray-50">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                    <i class="bi bi-info-square me-2 text-blue-600"></i> Admission Information
                                </label>
                                @php $admission_info = $institution->sections->where('type', 'admission_information')->first(); @endphp
                                <div class="quill-editor" data-name="sections[admission_information]">
                                    {!! $admission_info ? $admission_info->content : '' !!}
                                </div>
                                <input type="hidden" name="sections[admission_information]" id="sections_admission_information" value="{{ $admission_info ? $admission_info->content : '' }}">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-900">Save Academic Data</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Infrastructure --}}
                <div x-show="activeTab === 'infrastructure'" x-cloak class="p-6 md:p-8 space-y-8 animate-in slide-in-from-right-4 duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">

                        <div class="space-y-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                    <i class="bi bi-building-check me-2 text-blue-600"></i> Infrastructure Details
                                </label>
                                @php $infra = $institution->sections->where('type', 'infrastructure')->first(); @endphp
                                <div class="quill-editor" data-name="sections[infrastructure]">
                                    {!! $infra ? $infra->content : '' !!}
                                </div>
                                <input type="hidden" name="sections[infrastructure]" id="sections_infrastructure" value="{{ $infra ? $infra->content : '' }}">
                            </div>

                            <div class="space-y-3 pt-6 border-t border-gray-50">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                    <i class="bi bi-cpu me-2 text-blue-600"></i> Facilities
                                </label>
                                @php $facilities = $institution->sections->where('type', 'facilities')->first(); @endphp
                                <div class="quill-editor" data-name="sections[facilities]">
                                    {!! $facilities ? $facilities->content : '' !!}
                                </div>
                                <input type="hidden" name="sections[facilities]" id="sections_facilities" value="{{ $facilities ? $facilities->content : '' }}">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-800">Save Infrastructure</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Activities --}}
                <div x-show="activeTab === 'activities'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">

                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                <i class="bi bi-palette me-2 text-blue-600"></i> Co-curricular Activities
                            </label>
                            @php $activities = $institution->sections->where('type', 'activities')->first(); @endphp
                            <div class="quill-editor" data-name="sections[activities]">
                                {!! $activities ? $activities->content : '' !!}
                            </div>
                            <input type="hidden" name="sections[activities]" id="sections_activities" value="{{ $activities ? $activities->content : '' }}">
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-800">Save Activities</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Scholarships --}}
                <div x-show="activeTab === 'scholarships'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">

                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1 flex items-center">
                                <i class="bi bi-gift me-2 text-blue-600"></i> Available Scholarships
                            </label>
                            @php $scholarships = $institution->sections->where('type', 'scholarships')->first(); @endphp
                            <div class="quill-editor" data-name="sections[scholarships]">
                                {!! $scholarships ? $scholarships->content : '' !!}
                            </div>
                            <input type="hidden" name="sections[scholarships]" id="sections_scholarships" value="{{ $scholarships ? $scholarships->content : '' }}">
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-800">Save Scholarships</button>
                        </div>
                    </form>
                </div>

            {{-- Tab: Principal --}}
            <div x-show="activeTab === 'principal'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-500">
                <form action="{{ route('admin.institutions.save-principal', $institution->id) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl bg-gray-50 p-8 rounded-2xl border border-gray-100 space-y-8">
                    @csrf
                    <div class="flex items-center gap-6">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-2xl bg-white border-2 border-white shadow-lg overflow-hidden shrink-0">
                                @if($institution->principal && $institution->principal->photo)
                                    <img src="{{ asset('storage/' . $institution->principal->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-blue-200 bg-blue-50/50"><i class="bi bi-person-circle text-4xl"></i></div>
                                @endif
                                <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white cursor-pointer" onclick="document.getElementById('principal_photo_input').click()">
                                    <i class="bi bi-camera text-xl"></i>
                                </div>
                            </div>
                            <input type="file" name="photo" id="principal_photo_input" class="hidden" accept="image/*">
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900">Principal Profile</h3>
                            <p class="text-xs text-gray-400 font-medium">Head of the Institution Information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Full Name</label>
                            <input type="text" name="name" value="{{ $institution->principal ? $institution->principal->name : '' }}" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-gray-800">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Designation</label>
                            <input type="text" name="designation" value="{{ $institution->principal ? $institution->principal->designation : '' }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-gray-600">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Message / Brief Bio</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-gray-600 leading-relaxed text-sm">{{ $institution->principal ? $institution->principal->description : '' }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-800 transition-all">Save Profile</button>
                    </div>
                </form>
            </div>

            {{-- Tab: Results --}}
            <div x-show="activeTab === 'results'" x-cloak class="p-6 md:p-8 space-y-10 animate-in fade-in duration-300">
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                        <i class="bi bi-plus-circle me-2 text-blue-600"></i> Add Result Milestone
                    </h3>
                    <form action="{{ route('admin.institutions.save-result', $institution->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Title / Exam Name</label>
                                <input type="text" name="title" required placeholder="SSC Toppers 2024" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl font-black text-gray-800 text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Year</label>
                                <input type="text" name="year" placeholder="2023-24" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Medium</label>
                                <select name="medium" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs font-black">
                                    <option value="English">English</option>
                                    <option value="Marathi">Marathi</option>
                                    <option value="Semi-English">Semi-English</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Success Ratio (%)</label>
                                <input type="text" name="overall_result" placeholder="100%" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-black text-blue-600">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Record Photo (Optional)</label>
                                <input type="file" name="student_photo" class="w-full text-[10px] file:py-1.5 file:px-4 file:bg-gray-200 file:border-0 file:rounded-lg cursor-pointer">
                            </div>
                        </div>

                        <div class="p-5 bg-white rounded-xl border border-gray-100 flex flex-wrap gap-6 justify-center">
                            @foreach(['A', 'B', 'C'] as $g)
                                <div class="space-y-1 text-center">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Grade {{ $g }} (%)</label>
                                    <input type="number" name="grades[{{ $g }}]" step="0.01" class="w-24 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-center font-black text-gray-900 shadow-inner">
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg hover:bg-blue-700 transition-all">Add Entry</button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($institution->results as $res)
                        <div class="p-5 bg-white border border-gray-100 rounded-2xl flex gap-4 items-center group transition-all hover:border-blue-200 hover:shadow-md">
                            <div class="w-16 h-16 rounded-xl bg-gray-50 overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                                @if($res->student_photo)
                                    <img src="{{ asset('storage/' . $res->student_photo) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="bi bi-graph-up text-blue-200 text-2xl"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-black text-gray-900 truncate">{{ $res->title }}</h4>
                                    <form action="{{ route('admin.institutions.delete-sub-item', [$institution->id, 'result', $res->id]) }}" method="POST" onsubmit="return confirm('Delete result?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors"><i class="bi bi-trash text-sm"></i></button>
                                    </form>
                                </div>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg uppercase border border-blue-100">{{ $res->overall_result }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $res->year }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab: Awards --}}
            <div x-show="activeTab === 'awards'" x-cloak class="p-6 md:p-8 space-y-10 animate-in fade-in duration-300">
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">New Achievement / Recognition</h3>
                    <form action="{{ route('admin.institutions.save-award', $institution->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Award Title</label>
                                <input type="text" name="title" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-800 text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Award Image</label>
                                <input type="file" name="photo" class="w-full text-[10px] file:py-1.5 file:px-4 file:bg-gray-200 file:border-0 file:rounded-lg cursor-pointer">
                            </div>
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Short Description</label>
                                <textarea name="description" rows="2" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" class="px-10 py-3 bg-[#000165] text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg transition-all">Add Award</button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($institution->awards as $award)
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden group hover:border-blue-200 transition-all shadow-sm">
                            <div class="aspect-video relative overflow-hidden bg-gray-50">
                                @if($award->photo)
                                    <img src="{{ asset('storage/' . $award->photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-blue-100"><i class="bi bi-trophy text-4xl"></i></div>
                                @endif
                                <form action="{{ route('admin.institutions.delete-sub-item', [$institution->id, 'award', $award->id]) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf @method('DELETE')
                                    <button class="p-2 bg-white/90 backdrop-blur text-red-500 rounded-lg shadow-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                            <div class="p-4">
                                <h5 class="text-xs font-black text-gray-900 uppercase tracking-tight mb-2">{{ $award->title }}</h5>
                                <p class="text-[11px] text-gray-500 line-clamp-2 italic">{{ $award->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab: PTA --}}
            <div x-show="activeTab === 'pta'" x-cloak class="p-6 md:p-8 space-y-10 animate-in fade-in duration-300">
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Add PTA Council Member</h3>
                    <form action="{{ route('admin.institutions.save-pta', $institution->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Member Name</label>
                                <input type="text" name="name" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-800 text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Contact / Role</label>
                                <input type="text" name="mobile" placeholder="Mobile or Position" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl font-medium text-gray-700 text-sm">
                            </div>
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Member Photo</label>
                                <input type="file" name="photo" class="w-full text-[10px] file:py-1.5 file:px-4 file:bg-gray-200 file:border-0 file:rounded-lg cursor-pointer">
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit" class="px-10 py-3 bg-blue-600 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg transition-all">Register Member</button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($institution->ptaMembers as $member)
                        <div class="bg-white p-4 border border-gray-100 rounded-xl text-center group hover:border-blue-100 transition-all shadow-sm relative">
                            <form action="{{ route('admin.institutions.delete-sub-item', [$institution->id, 'pta', $member->id]) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf @method('DELETE')
                                <button class="text-red-300 hover:text-red-500"><i class="bi bi-x-circle"></i></button>
                            </form>
                            <div class="w-14 h-14 rounded-full mx-auto mb-3 border-2 border-gray-50 overflow-hidden shadow-sm">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-blue-100"><i class="bi bi-person"></i></div>
                                @endif
                            </div>
                            <h5 class="text-[11px] font-black text-gray-900 uppercase truncate">{{ $member->name }}</h5>
                            <p class="text-[9px] text-gray-400 font-bold tracking-wider truncate px-1">{{ $member->mobile ?? 'PTA Member' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab: Gallery --}}
            <div x-show="activeTab === 'gallery'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-300">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Institution Media Gallery</h3>
                    <label class="px-4 py-2 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-lg cursor-pointer hover:bg-blue-100 transition-all border border-blue-100">
                        <i class="bi bi-cloud-upload me-1.5"></i> Batch Upload
                        <form action="{{ route('admin.institutions.upload-gallery', $institution->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" name="images[]" multiple onchange="this.form.submit()">
                        </form>
                    </label>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach($institution->galleries as $gallery)
                        <div class="group relative aspect-square bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('admin.institutions.delete-sub-item', [$institution->id, 'gallery', $gallery->id]) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-white text-red-600 rounded-lg shadow-xl hover:scale-110 active:scale-90 transition-all"><i class="bi bi-trash text-sm"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab: SEO --}}
            <div x-show="activeTab === 'seo'" x-cloak class="p-6 md:p-8 space-y-8 animate-in zoom-in duration-300">
                <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST" class="max-w-xl bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-6">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug" value="{{ $institution->slug }}"><input type="hidden" name="category" value="{{ $institution->category }}">
                    
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">SEO Title (Tab Name)</label>
                        <input type="text" name="meta_title" value="{{ $institution->meta_title }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-gray-700 text-sm">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Meta Description (Search Snippet)</label>
                        <textarea name="meta_description" rows="3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-gray-600 leading-relaxed text-xs">{{ $institution->meta_description }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-[#000165] text-white font-black uppercase tracking-widest text-[10px] rounded-xl shadow-lg hover:bg-blue-800 transition-all">Save Config</button>
                    </div>
                </form>
            </div>

        </div> {{-- End Main Content Area --}}
    </div> {{-- End Grid --}}
</div> {{-- End x-data container --}}

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editors = document.querySelectorAll('.quill-editor');
        const quillInstances = {};

        editors.forEach(editor => {
            const fieldName = editor.getAttribute('data-name');
            // ID mapper for both types: 'institutional_journey' and 'sections[infra]'
            const inputId = fieldName.replace('[', '_').replace(']', '');
            const hiddenInput = document.getElementById(inputId) || document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);

            if (hiddenInput) {
                const quill = new Quill(editor, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'clean']
                        ]
                    }
                });

                if (hiddenInput.value) {
                    quill.root.innerHTML = hiddenInput.value;
                }

                quill.on('text-change', function() {
                    hiddenInput.value = (quill.root.innerHTML === '<p><br></p>') ? '' : quill.root.innerHTML;
                });

                quillInstances[fieldName] = quill;
            }
        });

        // Backup sync on submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                Object.keys(quillInstances).forEach(name => {
                    const inst = quillInstances[name];
                    const id = name.replace('[', '_').replace(']', '');
                    const input = document.getElementById(id) || document.getElementById(name) || document.querySelector(`[name="${name}"]`);
                    if (input) {
                        input.value = (inst.root.innerHTML === '<p><br></p>') ? '' : inst.root.innerHTML;
                    }
                });
            });
        });
    });
</script>
<style>
    [x-cloak] { display: none !important; }
    .sidebar-link.active { background: #000165; color: white; box-shadow: 0 4px 12px rgba(0, 1, 101, 0.2); transform: translateX(4px); }
    .ql-container { font-family: 'Outfit', sans-serif !important; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; }
    .ql-toolbar { border-top-left-radius: 12px; border-top-right-radius: 12px; background: #f9fafb !important; }
</style>
@endpush
@endsection
