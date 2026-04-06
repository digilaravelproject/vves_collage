@extends('layouts.admin.app')

@section('title', 'Edit Banner')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Banner Slide</h1>
                <p class="text-sm text-gray-500">Update text or media for this homepage slide.</p>
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

        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Side: Main Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Banner Heading (Title)</label>
                            <input type="text" name="title" value="{{ old('title', $banner->title) }}" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                placeholder="e.g. Empowering Future Leaders">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Banner Subheading (Subtitle)</label>
                            <textarea name="subtitle" rows="3" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                placeholder="Short description for this slide...">{{ old('subtitle', $banner->subtitle) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" 
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                    placeholder="e.g. Learn More">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Button Link (URL)</label>
                                <input type="url" name="button_link" value="{{ old('button_link', $banner->button_link) }}" 
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium"
                                    placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Media & Settings --}}
                <div class="space-y-6">
                    
                    {{-- Current Media Preview --}}
                    <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                        <label class="block text-sm font-bold text-gray-700">Current Media</label>
                        <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-gray-100 border border-gray-200">
                            @if ($banner->media_type === 'image')
                                <img src="{{ asset('storage/' . $banner->media_path) }}" class="h-full w-full object-cover">
                            @else
                                <video src="{{ asset('storage/' . $banner->media_path) }}" class="h-full w-full object-cover" muted loop autoplay></video>
                            @endif
                        </div>
                    </div>

                    {{-- Media Upload --}}
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <label class="block text-sm font-bold text-gray-700">Change Media (Optional)</label>
                        <div x-data="{ fileName: '' }" class="relative">
                            <input type="file" name="media" id="media" class="hidden"
                                @change="fileName = $event.target.files[0].name">
                            <label for="media" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 hover:border-blue-400 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                    <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 group-hover:text-blue-500 mb-1"></i>
                                    <p class="text-[10px] font-bold text-gray-500 group-hover:text-blue-600" x-text="fileName || 'Replace image/video'"></p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Visibility</h4>
                                <p class="text-xs text-gray-500">Toggle this slide on/off</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-5 after:h-5 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center p-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        Update Banner
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
