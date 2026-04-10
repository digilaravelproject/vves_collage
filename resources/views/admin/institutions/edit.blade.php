@extends('layouts.admin.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        .ql-editor {
            min-height: 200px;
            font-size: 16px;
            font-family: 'Outfit', sans-serif;
        }

        .ql-toolbar.ql-snow {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        .ql-container.ql-snow {
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
            border-color: #e5e7eb;
            max-width: 100%;
        }

        .sidebar-link.active {
            background: #000165;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 1, 101, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="px-2 py-4 sm:p-6 lg:p-8 space-y-6" x-data="{ activeTab: 'general' }">
        {{-- Header Section --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all">
            <div>
                <nav class="flex mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    <a href="{{ route('admin.institutions.index') }}" class="hover:text-blue-600">Institutions</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900">Edit Profile</span>
                </nav>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $institution->name }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.institutions.index') }}"
                    class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all border border-gray-100">
                    <i class="bi bi-arrow-left me-1.5"></i> Back
                </a>
                <form action="{{ route('admin.institutions.toggle-status', $institution->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold rounded-xl transition-all shadow-sm {{ $institution->status ? 'bg-green-50 text-green-700 border border-green-100 hover:bg-green-100' : 'bg-red-50 text-red-700 border border-red-100 hover:bg-red-100' }}">
                        <i class="bi bi-{{ $institution->status ? 'check-circle' : 'slash-circle' }} me-1.5"></i>
                        {{ $institution->status ? 'Active' : 'Disabled' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-3 space-y-2 lg:sticky lg:top-6 overflow-x-auto">
                <div
                    class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm flex lg:flex-col gap-1 min-w-max lg:min-w-0">
                    <p class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Identity & Setup</p>
                    <button @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-info-circle me-3 text-base"></i> General Info
                    </button>
                    <button @click="activeTab = 'contact'"
                        :class="activeTab === 'contact' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-telephone me-3 text-base"></i> Contact & Social
                    </button>

                    <button @click="activeTab = 'about'"
                        :class="activeTab === 'about' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-journal-text me-3 text-base"></i> About School
                    </button>

                    <button @click="activeTab = 'academic'"
                        :class="activeTab === 'academic' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-calendar-event me-3 text-base"></i> Academic Calendar
                    </button>

                    <button @click="activeTab = 'results_awards'"
                        :class="activeTab === 'results_awards' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-star me-3 text-base"></i> Results & Awards
                    </button>


                    <button @click="activeTab = 'activities'"
                        :class="activeTab === 'activities' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-grid-3x3-gap me-3 text-base"></i> Activities & Facilities
                    </button>

                    <button @click="activeTab = 'gallery'"
                        :class="activeTab === 'gallery' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-images me-3 text-base"></i> Gallery
                    </button>
                    <button @click="activeTab = 'seo'"
                        :class="activeTab === 'seo' ? 'sidebar-link active' : 'text-gray-600 hover:bg-gray-50'"
                        class="whitespace-nowrap flex items-center px-4 py-3 text-xs font-bold rounded-xl transition-all duration-300">
                        <i class="bi bi-search me-3 text-base"></i> SEO
                    </button>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="lg:col-span-9 bg-white rounded-2xl border border-gray-100 shadow-xl min-h-[700px] overflow-hidden">

                {{-- Tab: General --}}
                <div x-show="activeTab === 'general'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-500">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Institution
                                    Name</label>
                                <input type="text" name="name" value="{{ old('name', $institution->name) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-800">
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Slug
                                    (URL)</label>
                                <input type="text" name="slug" value="{{ old('slug', $institution->slug) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-gray-500">
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Tagline
                                    / Mission Statement</label>
                                <input type="text" name="tagline" value="{{ old('tagline', $institution->tagline) }}"
                                    placeholder="e.g. Empowering Minds, Shaping Futures"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-800 text-lg">
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Category
                                    / Group</label>
                                <select name="category" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                                    @foreach ($categories as $key => $label)
                                        <option value="{{ $label }}" {{ $institution->category == $label ? 'selected' : '' }}>
                                            {{ $key }}. {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Year
                                    of Establishment</label>
                                <input type="text" name="year_of_establishment"
                                    value="{{ old('year_of_establishment', $institution->year_of_establishment) }}"
                                    placeholder="e.g. 1962"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Curriculum
                                    (e.g. CBSE, ICSE)</label>
                                <input type="text" name="curriculum"
                                    value="{{ old('curriculum', $institution->curriculum) }}"
                                    placeholder="ICSE / CBSE / SSC"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">City
                                    /
                                    Location</label>
                                <input type="text" name="city" value="{{ old('city', $institution->city) }}"
                                    placeholder="Mumbai / Navi Mumbai"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">ISO
                                    Certification Number</label>
                                <input type="text" name="iso_certification"
                                    value="{{ old('iso_certification', $institution->iso_certification) }}"
                                    placeholder="e.g. ISO 9001:2015"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 p-5 bg-gray-50/80 rounded-2xl border border-gray-100"
                            x-data="{ active: {{ $institution->status ? 'true' : 'false' }} }">
                            <div class="relative inline-block w-12 h-6 cursor-pointer" @click="active = !active">
                                <input type="hidden" name="status_toggle_present" value="1">
                                <input type="checkbox" name="status" id="status_edit" value="1" class="sr-only"
                                    :checked="active">
                                <div class="w-full h-full rounded-full transition-colors duration-300 shadow-inner"
                                    :class="active ? 'bg-[#000165]' : 'bg-gray-300'"></div>
                                <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 shadow-md transform"
                                    :class="active ? 'translate-x-6' : 'translate-x-0'"></div>
                            </div>
                            <div>
                                <label for="status_edit" class="text-sm font-black text-gray-800 cursor-pointer">Live
                                    Visibility</label>
                                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Enable or
                                    disable
                                    this campus on the public portal</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex flex-col items-center text-center">
                                <div class="shrink-0 relative group mb-4">
                                    <div
                                        class="w-32 h-24 rounded-2xl overflow-hidden border-2 border-white shadow-lg bg-white">
                                        @if ($institution->featured_image)
                                            <img src="{{ asset('storage/' . $institution->featured_image) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
                                                <i class="bi bi-image text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white rounded-2xl cursor-pointer"
                                        onclick="document.getElementById('featured_image_input').click()">
                                        <i class="bi bi-camera text-xl"></i>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-sm font-black text-gray-900">Featured Identity Image</h3>
                                    <p class="text-[10px] text-gray-400 font-medium">Main thumbnail for listing pages</p>
                                    <input type="file" id="featured_image_input" name="featured_image" accept="image/*"
                                        class="hidden">
                                    <button type="button" @click="document.getElementById('featured_image_input').click()"
                                        class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-gray-300">Choose
                                        File</button>
                                </div>
                            </div>

                            <div
                                class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex flex-col items-center text-center">
                                <div class="shrink-0 relative group mb-4 w-full">
                                    <div
                                        class="w-full h-24 rounded-2xl overflow-hidden border-2 border-white shadow-lg bg-white">
                                        @if ($institution->breadcrumb_image)
                                            <img src="{{ asset('storage/' . $institution->breadcrumb_image) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex flex-col items-center justify-center bg-gray-100 text-gray-400">
                                                <i class="bi bi-image text-2xl mb-1"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">Default
                                                    Banner
                                                    Applied</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white rounded-2xl cursor-pointer"
                                        onclick="document.getElementById('breadcrumb_image_input').click()">
                                        <i class="bi bi-camera text-xl"></i>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-sm font-black text-gray-900">Breadcrumb Image (Banner)</h3>
                                    <p class="text-[10px] text-gray-400 font-medium">Hero banner at the top of institute
                                        page</p>
                                    <input type="file" id="breadcrumb_image_input" name="breadcrumb_image" accept="image/*"
                                        class="hidden">
                                    <button type="button" @click="document.getElementById('breadcrumb_image_input').click()"
                                        class="px-4 py-1.5 bg-gray-200 text-gray-700 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-gray-300">Choose
                                        File</button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl shadow-blue-900/10 hover:scale-105 active:scale-95 transition-all">Save
                                Identity</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Contact --}}
                <div x-show="activeTab === 'contact'" x-cloak
                    class="p-6 md:p-8 space-y-8 animate-in slide-in-from-bottom-4 duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields to prevent overwrite --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug"
                            value="{{ $institution->slug }}"><input type="hidden" name="category"
                            value="{{ $institution->category }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Website
                                    URL</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                            class="bi bi-globe"></i></span>
                                    <input type="url" name="website" value="{{ $institution->website }}"
                                        placeholder="https://"
                                        class="w-full ps-11 pe-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Google
                                    Maps Link (URL)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                            class="bi bi-geo-alt"></i></span>
                                    <input type="url" name="google_maps_link"
                                        value="{{ $institution->google_maps_link }}"
                                        placeholder="https://maps.app.goo.gl/..."
                                        class="w-full ps-11 pe-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Phone
                                    Number</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                            class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" value="{{ $institution->phone }}"
                                        class="w-full ps-11 pe-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="md:col-span-2 space-y-1.5">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Full
                                    Address</label>
                                <textarea name="address" rows="2"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-gray-700">{{ $institution->address }}</textarea>
                            </div>
                        </div>

                        <div class="p-6 bg-blue-50/30 rounded-2xl border border-blue-50">
                            <h3
                                class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center">
                                <i class="bi bi-share me-2"></i> Social Media Links
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php $socials = is_array($institution->social_links) ? $institution->social_links : json_decode($institution->social_links, true) ?? []; @endphp
                                @foreach (['facebook', 'instagram', 'linkedin', 'youtube'] as $social)
                                    <div class="space-y-1">
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                                    class="bi bi-{{ $social }}"></i></span>
                                            <input type="url" name="social_links[{{ $social }}]"
                                                value="{{ $socials[$social] ?? '' }}" placeholder="{{ ucfirst($social) }} URL"
                                                class="w-full ps-11 pe-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-medium">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl shadow-blue-900/10 hover:scale-105 active:scale-95 transition-all">Save
                                Details</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: About School (Dynamic Sections) --}}
                <div x-show="activeTab === 'about'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-500"
                    x-data="{
                            sections: {{ $institution->about_sections ? json_encode($institution->about_sections) : '[]' }},
                            addSection() {
                                this.sections.push({ title: '', content: '' });
                            },
                            removeSection(index) {
                                this.sections.splice(index, 1);
                            }
                        }">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug"
                            value="{{ $institution->slug }}"><input type="hidden" name="category"
                            value="{{ $institution->category }}">

                        {{-- Dynamic Sections Repeater --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                                    <label
                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Expandable
                                        About Sections</label>
                                </div>
                                <button type="button" @click="addSection(); reinitQuill();"
                                    class="px-5 py-2.5 bg-[#000165] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-blue-900/10">
                                    <i class="bi bi-plus-lg me-1.5"></i> Add New Section
                                </button>
                            </div>

                            <div class="space-y-6">
                                <template x-for="(section, index) in sections" :key="index">
                                    <div
                                        class="p-4 md:p-6 bg-white rounded-2xl border-2 border-gray-100 relative group animate-in slide-in-from-top-4 duration-500 hover:border-blue-100 transition-colors shadow-sm overflow-hidden">

                                        {{-- Section Item Header --}}
                                        <div
                                            class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-50">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 md:w-10 md:h-10 bg-[#FFD700] text-[#000165] flex items-center justify-center rounded-xl text-xs md:text-sm font-black shadow-sm"
                                                    x-text="index + 1"></div>
                                                <div>
                                                    <p
                                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">
                                                        Section Item</p>
                                                    <p class="text-[11px] font-bold text-[#000165] mt-1"
                                                        x-text="section.title || 'Untitled Section'"></p>
                                                </div>
                                            </div>

                                            <button type="button" @click="removeSection(index)"
                                                class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl transition-all hover:bg-red-500 hover:text-white hover:scale-110 shadow-sm border border-red-100">
                                                <i class="bi bi-trash-fill text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 gap-6">
                                            <div class="space-y-1.5">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Section
                                                    Title / Name</label>
                                                <input type="text" :name="'about_sections[' + index + '][title]'"
                                                    x-model="section.title" placeholder="e.g. From the Desk of HM"
                                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-black text-gray-800 transition-all">
                                            </div>

                                            <div class="space-y-1.5 overflow-hidden">
                                                <label
                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Description
                                                    Content (Page Editor)</label>
                                                <div
                                                    class="w-full max-w-full overflow-hidden border border-gray-100 rounded-xl">
                                                    <div class="quill-dynamic" :id="'quill-about-' + index" :data-target="'input-quill-about-' + index"
                                                        style="height: 300px; max-width: 100%;">
                                                    </div>
                                                </div>
                                                <input type="hidden" :name="'about_sections[' + index + '][content]'"
                                                    :id="'input-quill-about-' + index" x-model="section.content">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="sections.length === 0"
                                    class="py-16 text-center border-3 border-dashed border-gray-100 rounded-3xl bg-gray-50/30">
                                    <div
                                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                                        <i class="bi bi-journal-plus text-3xl text-gray-200"></i>
                                    </div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Your
                                        institution profile is currently simplified.<br>Add sections like Admission Process,
                                        Facilities, etc.</p>
                                </div>
                            </div>
                        </div>


                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:scale-105 active:scale-95 transition-all">Save
                                About Information</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Academic & Admissions --}}
                <div x-show="activeTab === 'academic'" x-cloak class="p-6 md:p-8 space-y-8 animate-in zoom-in duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug"
                            value="{{ $institution->slug }}"><input type="hidden" name="category"
                            value="{{ $institution->category }}">

                        <div class="space-y-8">
                            {{-- PDF DIARY SECTION --}}
                            <div class="p-6 bg-[#000165]/5 rounded-2xl border border-[#000165]/10 flex flex-col gap-6">
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                    <div
                                        class="shrink-0 w-16 h-16 rounded-xl bg-white border border-gray-100 shadow-sm flex items-center justify-center text-red-500 text-3xl">
                                        <i class="bi bi-file-pdf"></i>
                                    </div>
                                    <div class="flex-1 space-y-2 text-center md:text-left">
                                        <div>
                                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Academic
                                                Calendar (PDF)</h3>
                                            <p class="text-[10px] text-gray-400 font-medium">Upload the school's academic
                                                diary
                                                / calendar PDF</p>
                                        </div>
                                        <input type="file" name="academic_diary_pdf" accept="application/pdf"
                                            class="w-full text-[10px] text-gray-400 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:bg-[#000165] file:text-white cursor-pointer">
                                    </div>
                                </div>

                                @if ($institution->academic_diary_pdf)
                                    <div class="mt-4 border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white p-2">
                                        <div class="flex items-center justify-between px-2 pb-2">
                                            <span class="text-[10px] font-black tracking-widest uppercase text-gray-500">Live
                                                Preview</span>
                                            <a href="{{ url('pdf-viewer/storage/' . $institution->academic_diary_pdf) }}"
                                                target="_blank" class="text-[10px] font-bold text-blue-600 hover:underline"><i
                                                    class="bi bi-box-arrow-up-right me-1"></i>Open in New Tab</a>
                                        </div>
                                        <x-pdf-viewer :src="asset('storage/' . $institution->academic_diary_pdf)" />
                                    </div>
                                @endif
                            </div>


                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-900">Save
                                Academic Data</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Activities & Facilities (Dynamic Repeater) --}}
                <div x-show="activeTab === 'activities'" x-cloak
                    class="p-6 md:p-8 space-y-8 animate-in fade-in duration-300" x-data="{
                            blocks: {{ $institution->activities_facilities_blocks ? json_encode($institution->activities_facilities_blocks) : '[{title: \'Academic Activities\', content: \'\'}, {title: \'Other Activities\', content: \'\'}, {title: \'Facilities Offered\', content: \'\'}]' }},
                            addBlock() {
                                this.blocks.push({title: '', content: ''});
                                window.reinitQuill();
                            },
                            removeBlock(index) {
                                if(confirm('Remove this activity/facility block?')) {
                                    this.blocks.splice(index, 1);
                                }
                            }
                        }">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug"
                            value="{{ $institution->slug }}"><input type="hidden" name="category"
                            value="{{ $institution->category }}">

                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-6 bg-amber-500 rounded-full"></div>
                                    <h3 class="text-xs font-black text-[#000165] uppercase tracking-widest flex items-center">
                                        Activities & Facilities
                                    </h3>
                                </div>
                                <button type="button" @click="addBlock()"
                                    class="px-5 py-2.5 bg-[#000165] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-blue-900/10">
                                    <i class="bi bi-plus-lg me-1.5"></i> Add New Block
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-8">
                                <template x-for="(block, index) in blocks" :key="index">
                                    <div class="p-6 bg-white rounded-2xl border-2 border-gray-100 relative group animate-in slide-in-from-top-4 duration-500 hover:border-amber-100 transition-colors shadow-sm overflow-hidden">
                                        
                                        <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-50">
                                            <div class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-black text-xs" x-text="index + 1"></span>
                                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Activity / Facility Block</span>
                                            </div>
                                            <button type="button" @click="removeBlock(index)"
                                                class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg transition-all hover:bg-red-500 hover:text-white hover:scale-110 shadow-sm border border-red-100">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 gap-6">
                                            <div class="space-y-1.5">
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Block Title</label>
                                                <input type="text" :name="'activities_facilities_blocks[' + index + '][title]'"
                                                    x-model="block.title" placeholder="e.g. Academic Activities"
                                                    class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 font-black text-gray-800 transition-all text-sm">
                                            </div>

                                            <div class="space-y-1.5 overflow-hidden">
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Description Content</label>
                                                <div class="w-full max-w-full overflow-hidden border border-gray-100 rounded-xl">
                                                    <div class="quill-dynamic" :id="'quill-act-' + index" :data-target="'input-quill-act-' + index"
                                                        style="height: 250px; max-width: 100%;">
                                                    </div>
                                                </div>
                                                <input type="hidden" :name="'activities_facilities_blocks[' + index + '][content]'"
                                                    :id="'input-quill-act-' + index" x-model="block.content">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                class="px-8 py-4 bg-[#000165] text-white font-black uppercase tracking-widest rounded-xl shadow-xl hover:scale-105 active:scale-95 transition-all">Save Activities & Facilities</button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Results & Awards (Unified Dynamic) --}}
                <div x-show="activeTab === 'results_awards'" x-cloak
                    class="p-6 md:p-8 space-y-8 animate-in fade-in duration-500" x-data="{
                            sections: {{ $institution->results_awards ? json_encode($institution->results_awards) : '[]' }}.map(s => ({
                                ...s,
                                items: (s.items || []).map(i => ({
                                    ...i,
                                    students: i.students || []
                                }))
                            })),
                            addSection() {
                                this.sections.push({ title: '', items: [] });
                            },
                            removeSection(index) {
                                if (confirm('Are you sure you want to remove this entire section?')) {
                                    this.sections.splice(index, 1);
                                }
                            },
                            addItem(sectionIndex) {
                                this.sections[sectionIndex].items.push({
                                    type: 'result',
                                    title: '',
                                    year: '',
                                    medium: 'English',
                                    overall_result: '',
                                    summary: '',
                                    photo: null,
                                    students: []
                                });
                            },
                            removeItem(sectionIndex, itemIndex) {
                                this.sections[sectionIndex].items.splice(itemIndex, 1);
                            },
                            addStudent(sectionIndex, itemIndex) {
                                this.sections[sectionIndex].items[itemIndex].students.push({
                                    name: '',
                                    class: '',
                                    percentage: '',
                                    photo: null
                                });
                            },
                            removeStudent(sectionIndex, itemIndex, studentIndex) {
                                this.sections[sectionIndex].items[itemIndex].students.splice(studentIndex, 1);
                            }
                        }">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf @method('PUT')
                        {{-- Hidden fields --}}
                        <input type="hidden" name="name" value="{{ $institution->name }}">
                        <input type="hidden" name="slug" value="{{ $institution->slug }}">
                        <input type="hidden" name="category" value="{{ $institution->category }}">

                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                                <i class="bi bi-star-fill text-yellow-400 text-2xl"></i>
                                Results & Awards Management
                            </h2>
                            <button type="button" @click="addSection()"
                                class="px-6 py-3 bg-[#000165] text-white rounded-xl text-xs font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-blue-900/10">
                                <i class="bi bi-plus-circle-fill me-2"></i> Create New Section
                            </button>
                        </div>

                        <div class="space-y-10">
                            <template x-for="(section, sIdx) in sections" :key="sIdx">
                                <div
                                    class="p-8 bg-gray-50/50 rounded-3xl border-2 border-dashed border-gray-200 relative group/section transition-all hover:border-blue-200 hover:bg-white shadow-sm">

                                    {{-- Section Header --}}
                                    <div
                                        class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 pb-6 border-b border-gray-100">
                                        <div class="flex-1 space-y-1.5">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Section
                                                Title (e.g., SSC Board Results, Sports Achievements)</label>
                                            <div class="flex items-center gap-4">
                                                <input type="text" x-model="section.title"
                                                    :name="'results_awards[' + sIdx + '][title]'"
                                                    placeholder="Enter Section Name..."
                                                    class="flex-1 px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 font-black text-lg text-[#000165]">
                                                <button type="button" @click="removeSection(sIdx)"
                                                    class="p-4 bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                    <i class="bi bi-trash3-fill text-xl"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Section Items (Repeater) --}}
                                    <div class="space-y-6">
                                        <template x-for="(item, iIdx) in section.items" :key="iIdx">
                                            <div
                                                class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm relative group/item animate-in zoom-in-95 duration-300">

                                                <div
                                                    class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                                                    <div class="flex items-center gap-4">
                                                        <select x-model="item.type"
                                                            :name="'results_awards[' + sIdx + '][items][' + iIdx + '][type]'"
                                                            class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-xs font-black uppercase tracking-widest cursor-pointer border-none focus:ring-0">
                                                            <option value="result">Result / Achievement</option>
                                                            <option value="award">Award / Recognition</option>
                                                        </select>
                                                        <span class="text-[10px] font-black text-gray-300 uppercase">Item
                                                            #<span x-text="iIdx + 1"></span></span>
                                                    </div>
                                                    <button type="button" @click="removeItem(sIdx, iIdx)"
                                                        class="text-gray-300 hover:text-red-500 transition-colors">
                                                        <i class="bi bi-x-circle-fill text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                                                    {{-- Left: Image Upload --}}
                                                    <div class="md:col-span-3 space-y-4">
                                                        <label
                                                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Reference
                                                            Photo</label>
                                                        <div
                                                            class="relative group/photo aspect-square rounded-2xl overflow-hidden border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center">
                                                            <template x-if="item.photo">
                                                                <img :src="typeof item.photo === 'string' ? '/storage/' + item
                                                                        .photo : URL.createObjectURL(item.photo)"
                                                                    class="w-full h-full object-cover">
                                                            </template>
                                                            <template x-if="!item.photo">
                                                                <div class="text-center p-4">
                                                                    <i class="bi bi-camera text-3xl text-gray-200"></i>
                                                                    <p
                                                                        class="text-[8px] font-black text-gray-300 uppercase mt-2">
                                                                        Upload Photo</p>
                                                                </div>
                                                            </template>
                                                            <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover/photo:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                                                @click="document.getElementById('itemPhoto_' + sIdx + '_' + iIdx).click()">
                                                                <i class="bi bi-plus-lg text-white text-2xl"></i>
                                                            </div>
                                                        </div>
                                                        <input type="file"
                                                            :name="'results_awards[' + sIdx + '][items][' + iIdx + '][photo]'"
                                                            :id="'itemPhoto_' + sIdx + '_' + iIdx" class="hidden"
                                                            accept="image/*" @change="item.photo = $event.target.files[0]">

                                                        {{-- Hidden input to preserve existing photo path --}}
                                                        <input type="hidden" :name="'results_awards[' + sIdx + '][items][' + iIdx +
                                                                    '][existing_photo]'" :value="typeof item.photo === 'string' ? item.photo : (item
                                                                    .existing_photo || '')">

                                                        <template x-if="item.photo">
                                                            <button type="button"
                                                                @click="item.photo = null; if($refs['itemPhoto'+sIdx+'_'+iIdx]) $refs['itemPhoto'+sIdx+'_'+iIdx].value = ''"
                                                                class="w-full py-1.5 text-[9px] font-black text-red-500 uppercase hover:text-red-600 transition-colors">
                                                                Remove Photo
                                                            </button>
                                                        </template>
                                                    </div>

                                                    {{-- Right: Content --}}
                                                    <div class="md:col-span-9 space-y-6">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                            <div class="space-y-1.5 md:col-span-2">
                                                                <label
                                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Title
                                                                    / Label</label>
                                                                <input type="text" x-model="item.title" :name="'results_awards[' + sIdx + '][items][' + iIdx +
                                                                            '][title]'"
                                                                    placeholder="e.g. SSC Toppers, National Sports Meet..."
                                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-900">
                                                            </div>

                                                            {{-- Conditional Fields for Result --}}
                                                            <template x-if="item.type === 'result'">
                                                                <div class="contents">
                                                                    <div class="space-y-1.5">
                                                                        <label
                                                                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Year</label>
                                                                        <input type="text" x-model="item.year" :name="'results_awards[' + sIdx + '][items][' +
                                                                                    iIdx + '][year]'" placeholder="2023-24"
                                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700">
                                                                    </div>
                                                                    <div class="space-y-1.5">
                                                                        <label
                                                                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Medium</label>
                                                                        <select x-model="item.medium" :name="'results_awards[' + sIdx + '][items][' +
                                                                                    iIdx + '][medium]'"
                                                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold text-gray-700">
                                                                            <option value="English">English</option>
                                                                            <option value="Marathi">Marathi</option>
                                                                            <option value="Semi-English">Semi-English
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="space-y-1.5 md:col-span-2 p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50">
                                                                        <label
                                                                            class="block text-[10px] font-black text-blue-600 uppercase tracking-widest ps-1">Overall
                                                                            Percentage / Performance Summary</label>
                                                                        <input type="text" x-model="item.overall_result"
                                                                            :name="'results_awards[' + sIdx + '][items][' +
                                                                                    iIdx + '][overall_result]'"
                                                                            placeholder="e.g. 100% Passing, Gold Medalist, Distinctions..."
                                                                            class="w-full px-4 py-3 bg-white border border-blue-100 rounded-xl font-black text-blue-800 text-lg shadow-sm focus:ring-blue-500">
                                                                        <p
                                                                            class="text-[9px] text-blue-400 font-bold mt-1.5 ps-1">
                                                                            Leave empty if you only want to highlight
                                                                            individual students below.</p>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <div class="space-y-1.5 md:col-span-2">
                                                                <label
                                                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Summary
                                                                    / Description</label>
                                                                <textarea x-model="item.summary" :name="'results_awards[' + sIdx + '][items][' + iIdx +
                                                                        '][summary]'" rows="3"
                                                                    placeholder="Brief details about this achievement..."
                                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-medium text-gray-600"></textarea>
                                                            </div>
                                                        </div>

                                                        {{-- Nested Students (Only if Result) --}}
                                                        <template x-if="item.type === 'result'">
                                                            <div class="pt-6 border-t border-gray-100">
                                                                <div class="flex items-center justify-between mb-4">
                                                                    <h5
                                                                        class="text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                                                        Toppers / Students List</h5>
                                                                    <button type="button" @click="addStudent(sIdx, iIdx)"
                                                                        class="text-[9px] font-black text-white bg-blue-600 px-3 py-1.5 rounded-lg uppercase tracking-widest hover:bg-blue-700 transition-colors">
                                                                        + Add Student
                                                                    </button>
                                                                </div>

                                                                <div class="grid grid-cols-1 gap-4">
                                                                    <template x-for="(student, stIdx) in item.students"
                                                                        :key="stIdx">
                                                                        <div
                                                                            class="flex items-center gap-4 bg-gray-50 p-3 rounded-2xl border border-gray-100 group/student">
                                                                            <div class="shrink-0 relative group/stphoto">
                                                                                <div
                                                                                    class="w-12 h-12 rounded-xl overflow-hidden bg-white border border-gray-200">
                                                                                    <template x-if="student.photo">
                                                                                        <img :src="typeof student
                                                                                                .photo === 'string' ?
                                                                                                '/storage/' + student
                                                                                                .photo : URL
                                                                                                .createObjectURL(student
                                                                                                    .photo)"
                                                                                            class="w-full h-full object-cover">
                                                                                    </template>
                                                                                    <template x-if="!student.photo">
                                                                                        <div
                                                                                            class="w-full h-full flex items-center justify-center text-gray-200">
                                                                                            <i
                                                                                                class="bi bi-person-fill text-xl"></i>
                                                                                        </div>
                                                                                    </template>
                                                                                </div>
                                                                                <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover/stphoto:opacity-100 transition-opacity flex items-center justify-center cursor-pointer rounded-xl"
                                                                                    @click="document.getElementById('stPhoto_'+sIdx+'_'+iIdx+'_'+stIdx).click()">
                                                                                    <i
                                                                                        class="bi bi-camera text-white text-xs"></i>
                                                                                </div>
                                                                                <input type="file" :name="'results_awards[' + sIdx +
                                                                                            '][items][' + iIdx +
                                                                                            '][students][' + stIdx +
                                                                                            '][photo]'" :id="'stPhoto_' + sIdx + '_' + iIdx +
                                                                                            '_' + stIdx" class="hidden"
                                                                                    accept="image/*"
                                                                                    @change="student.photo = $event.target.files[0]">

                                                                                {{-- Hidden input to preserve existing
                                                                                student photo path --}}
                                                                                <input type="hidden" :name="'results_awards[' + sIdx +
                                                                                            '][items][' + iIdx +
                                                                                            '][students][' + stIdx +
                                                                                            '][existing_photo]'" :value="typeof student
                                                                                            .photo === 'string' ? student
                                                                                            .photo : (student
                                                                                                .existing_photo || '')">
                                                                            </div>

                                                                            <input type="text" x-model="student.name" :name="'results_awards[' + sIdx + '][items][' +
                                                                                        iIdx + '][students][' + stIdx +
                                                                                        '][name]'"
                                                                                placeholder="Student Name"
                                                                                class="flex-1 bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold">

                                                                            <input type="text" x-model="student.class"
                                                                                :name="'results_awards[' + sIdx + '][items][' +
                                                                                        iIdx + '][students][' + stIdx +
                                                                                        '][class]'" placeholder="Class"
                                                                                class="w-24 bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-center">

                                                                            <input type="text" x-model="student.percentage"
                                                                                :name="'results_awards[' + sIdx + '][items][' +
                                                                                        iIdx + '][students][' + stIdx +
                                                                                        '][percentage]'" placeholder="%"
                                                                                class="w-16 bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-black text-center text-blue-600">

                                                                            <button type="button"
                                                                                @click="removeStudent(sIdx, iIdx, stIdx)"
                                                                                class="text-gray-300 hover:text-red-500 transition-colors">
                                                                                <i class="bi bi-dash-circle-fill"></i>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <div x-show="section.items.length === 0"
                                            class="py-12 border-2 border-dashed border-gray-200 rounded-3xl text-center bg-white/50">
                                            <p class="text-xs font-bold text-gray-400">Empty Section</p>
                                            <button type="button" @click="addItem(sIdx)"
                                                class="mt-2 text-xs font-black text-blue-600 uppercase hover:underline">+
                                                Add Your First Item</button>
                                        </div>

                                        <div class="flex justify-center pt-4">
                                            <button type="button" @click="addItem(sIdx)"
                                                class="px-5 py-2.5 bg-white border border-gray-200 text-[#000165] rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
                                                <i class="bi bi-plus-lg me-1.5"></i> Add Award / Result Card
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="sections.length === 0"
                            class="py-24 text-center border-4 border-dashed border-gray-100 rounded-[40px]">
                            <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <i class="bi bi-collection-play text-4xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-black text-gray-900">No Content Sections Created</h3>
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mt-2 max-w-xs mx-auto">
                                Create sections to organize board results, sports, medals, and school recognitions
                                separately.</p>
                        </div>

                        <div class="pt-10 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                class="px-10 py-4 bg-[#000165] text-white font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-blue-900/20 hover:scale-105 active:scale-95 transition-all">
                                Update Results & Awards
                            </button>
                        </div>
                    </form>
                </div>





                {{-- Tab: Gallery --}}
                <div x-show="activeTab === 'gallery'" x-cloak class="p-6 md:p-8 space-y-8 animate-in fade-in duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Institution Media
                            Gallery</h3>
                        <label
                            class="px-4 py-2 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-lg cursor-pointer hover:bg-blue-100 transition-all border border-blue-100">
                            <i class="bi bi-cloud-upload me-1.5"></i> Batch Upload
                            <form action="{{ route('admin.institutions.upload-gallery', $institution->id) }}" method="POST"
                                enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" name="images[]" multiple onchange="this.form.submit()">
                            </form>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach ($institution->galleries as $gallery)
                            <div
                                class="group relative aspect-square bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                                <img src="{{ asset('storage/' . $gallery->image_path) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form
                                        action="{{ route('admin.institutions.delete-sub-item', [$institution->id, 'gallery', $gallery->id]) }}"
                                        method="POST" onsubmit="return confirm('Delete this image?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 bg-white text-red-600 rounded-lg shadow-xl hover:scale-110 active:scale-90 transition-all"><i
                                                class="bi bi-trash text-sm"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tab: SEO --}}
                <div x-show="activeTab === 'seo'" x-cloak class="p-6 md:p-8 space-y-8 animate-in zoom-in duration-300">
                    <form action="{{ route('admin.institutions.update', $institution->id) }}" method="POST"
                        class="max-w-xl bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-6">
                        @csrf @method('PUT')
                        <input type="hidden" name="name" value="{{ $institution->name }}"><input type="hidden" name="slug"
                            value="{{ $institution->slug }}"><input type="hidden" name="category"
                            value="{{ $institution->category }}">

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">SEO
                                Title (Tab Name)</label>
                            <input type="text" name="meta_title" value="{{ $institution->meta_title }}"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-gray-700 text-sm">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ps-1">Meta
                                Description (Search Snippet)</label>
                            <textarea name="meta_description" rows="3"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-gray-600 leading-relaxed text-xs">{{ $institution->meta_description }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-200 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-[#000165] text-white font-black uppercase tracking-widest text-[10px] rounded-xl shadow-lg hover:bg-blue-800 transition-all">Save
                                Config</button>
                        </div>
                    </form>
                </div>

            </div> {{-- End Main Content Area --}}
        </div> {{-- End Grid --}}
    </div> {{-- End x-data container --}}

    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- Static Editors ---
                const editors = document.querySelectorAll('.quill-editor');
                const quillInstances = {};

                editors.forEach(editor => {
                    const fieldName = editor.getAttribute('data-name');
                    const inputId = fieldName.replace('[', '_').replace(']', '');
                    const hiddenInput = document.getElementById(inputId) || document.getElementById(
                        fieldName) || document.querySelector(`[name="${fieldName}"]`);

                    if (hiddenInput) {
                        const quill = new Quill(editor, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    [{
                                        'header': [1, 2, 3, false]
                                    }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{
                                        'list': 'ordered'
                                    }, {
                                        'list': 'bullet'
                                    }],
                                    ['link', 'image', 'clean']
                                ]
                            }
                        });

                        if (hiddenInput.value) {
                            quill.root.innerHTML = hiddenInput.value;
                        }

                        quill.on('text-change', function () {
                            hiddenInput.value = (quill.root.innerHTML === '<p><br></p>') ? '' : quill
                                .root.innerHTML;
                        });

                        quillInstances[fieldName] = quill;
                    }
                });

                // --- Dynamic Editors (About Sections) ---
                const initDynamicQuill = () => {
                    document.querySelectorAll('.quill-dynamic').forEach((el, idx) => {
                        if (el.children.length > 0) return; // Already initialized

                        const inputId = el.getAttribute('data-target');
                        const hiddenInput = document.getElementById(inputId);

                        if (hiddenInput) {
                            const quill = new Quill(el, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline'],
                                        [{
                                            'list': 'ordered'
                                        }, {
                                            'list': 'bullet'
                                        }],
                                        ['link', 'image', 'clean']
                                    ]
                                }
                            });

                            if (hiddenInput.value) {
                                quill.root.innerHTML = hiddenInput.value;
                            }

                            quill.on('text-change', function () {
                                hiddenInput.value = (quill.root.innerHTML === '<p><br></p>') ? '' :
                                    quill.root.innerHTML;
                                hiddenInput.dispatchEvent(new Event('input'));
                            });
                        }
                    });
                };

                // Initialize existing dynamic editors
                setTimeout(initDynamicQuill, 300);

                // Listen for new sections added by Alpine
                document.addEventListener('alpine:initialized', () => {
                    // No direct hook, so we use a MutationObserver or just a small timeout after button click
                });

                // Simpler approach for Alpine: check for new editors every second or when add button is clicked
                window.reinitQuill = () => setTimeout(initDynamicQuill, 100);

                // Backup sync on submit
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function () {
                        // Sync all quill-dynamic instances as well
                        document.querySelectorAll('.quill-dynamic').forEach(el => {
                            const quill = Quill.find(el);
                            if (quill) {
                                const inputId = el.getAttribute('data-target');
                                const input = document.getElementById(inputId);
                                if (input) input.value = quill.root.innerHTML;
                            }
                        });
                    });
                });
            });
        </script>
        <style>
            [x-cloak] {
                display: none !important;
            }

            .sidebar-link.active {
                background: #000165;
                color: white;
                box-shadow: 0 4px 12px rgba(0, 1, 101, 0.2);
                transform: translateX(4px);
            }

            .ql-container {
                font-family: 'Outfit', sans-serif !important;
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
            }

            .ql-toolbar {
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
                background: #f9fafb !important;
                display: flex;
                flex-wrap: wrap;
                width: 100%;
                border-color: #f3f4f6 !important;
            }

            .ql-container {
                border-bottom-left-radius: 12px;
                border-bottom-right-radius: 12px;
                border-color: #f3f4f6 !important;
            }
        </style>
    @endpush
@endsection
