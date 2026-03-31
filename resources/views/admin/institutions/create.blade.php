@extends('layouts.admin.app')
@section('title', 'Add Institution')

@section('content')
    <div class="max-w-4xl p-4 mx-auto sm:p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Add New Institution</h1>
            <a href="{{ route('admin.institutions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
                <i class="bi bi-arrow-left me-2"></i> Back to List
            </a>
        </div>

        {{-- ✅ Validation Errors Summary --}}
        @if ($errors->any())
            <div class="p-6 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-sm mb-6">
                <div class="flex items-center mb-3">
                    <i class="bi bi-exclamation-octagon-fill text-red-500 text-xl me-3"></i>
                    <h3 class="text-red-800 font-bold">Please correct the following errors:</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1 ml-9">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-hidden bg-white border border-gray-200 shadow-xl rounded-3xl" x-data="{ 
            name: '{{ old('name') }}', 
            slug: '{{ old('slug') }}',
            generateSlug() {
                this.slug = this.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            }
        }">
            <form action="{{ route('admin.institutions.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-bold text-gray-700">Institution Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" x-model="name" @input="generateSlug()" required placeholder="e.g. Vikas High School"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200">
                    </div>

                    <div class="space-y-2">
                        <label for="slug" class="block text-sm font-bold text-gray-700">URL Slug <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" id="slug" x-model="slug" required placeholder="e.g. vikas-high-school"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200">
                        <p class="text-[10px] text-gray-400 font-medium italic">Auto-generated from name. Must be unique.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-bold text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select name="category" id="category" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200">
                            <option value="">Select a category</option>
                            @foreach ($categories as $code => $label)
                                <option value="{{ $code }}" {{ old('category') == $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="year_of_establishment" class="block text-sm font-bold text-gray-700">Year of Establishment</label>
                        <input type="text" name="year_of_establishment" id="year_of_establishment" value="{{ old('year_of_establishment') }}" placeholder="e.g. 1985"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="featured_image" class="block text-sm font-bold text-gray-700">Featured Image</label>
                        <div class="relative group">
                            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                class="w-full px-4 py-3 bg-gray-50 border border-dashed border-gray-300 rounded-2xl focus:ring-4 focus:ring-blue-500/10 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-all">
                        </div>
                        <p class="text-[11px] text-gray-400 font-medium">JPG, PNG or WEBP. Recommended size: 1200x800px. Max: 5MB.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-2xl border border-gray-100" x-data="{ active: true }">
                    <div class="relative inline-block w-12 h-6 cursor-pointer" @click="active = !active">
                        <input type="checkbox" name="status" id="status" class="sr-only" :checked="active">
                        <div class="w-full h-full rounded-full transition-colors duration-300 shadow-inner" :class="active ? 'bg-blue-600' : 'bg-gray-300'"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 shadow-md transform" :class="active ? 'translate-x-6' : 'translate-x-0'"></div>
                    </div>
                    <label for="status" class="text-sm font-bold text-gray-700 cursor-pointer">Active in Campus Listing</label>
                </div>

                <div class="pt-8 space-y-4">
                    <button type="submit"
                        class="w-full flex items-center justify-center p-4 text-lg font-bold text-white bg-blue-600 rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                        Register Institution
                    </button>
                    <p class="text-center text-xs text-gray-400 font-medium">You will be redirected to add comprehensive details (Principal, Results, Gallery, etc.) after this step.</p>
                </div>
            </form>
        </div>
    </div>
@endsection
