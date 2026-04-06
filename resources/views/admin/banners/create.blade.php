@extends('layouts.admin.app')

@section('title', 'Create Banner')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Banner</h1>
                <p class="text-sm text-gray-500">Add a new slide to the homepage hero slider.</p>
            </div>
            <a href="{{ route('admin.banners.index') }}" class="flex items-center text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors">
                <i class="bi bi-arrow-left me-2"></i> Back to list
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-2xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Side: Main Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Banner Heading (Title)</label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                placeholder="e.g. Empowering Future Leaders">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Banner Subheading (Subtitle)</label>
                            <textarea name="subtitle" rows="3" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                placeholder="Short description for this slide...">{{ old('subtitle') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="button_text" value="{{ old('button_text') }}" 
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                    placeholder="e.g. Learn More">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Link (URL)</label>
                                <input type="url" name="button_link" value="{{ old('button_link') }}" 
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                    placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Media & Settings --}}
                <div class="space-y-6">
                    {{-- Media Upload --}}
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <label class="block text-sm font-bold text-gray-700">Banner Media</label>
                        <div x-data="{ fileName: '' }" class="relative">
                            <input type="file" name="media" id="media" class="hidden" required
                                @change="fileName = $event.target.files[0].name">
                            <label for="media" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 hover:border-blue-400 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                                    <p class="text-xs font-bold text-gray-500 group-hover:text-blue-600" x-text="fileName || 'Click to upload image/video'"></p>
                                    <p class="text-[10px] text-gray-400 mt-1">MP4, JPG, PNG (Max 50MB)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Visibility</h4>
                                <p class="text-xs text-gray-500">Should this slide be visible?</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-5 after:h-5 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center p-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        Save Banner
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
