@extends('layouts.admin.app')

@section('title', 'Edit Popup')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Edit Popup</h1>
        <a href="{{ route('admin.popups.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
            ← Back to List
        </a>
    </div>

    <div class="bg-white border border-gray-200 shadow-xl rounded-2xl overflow-hidden">
        <form action="{{ route('admin.popups.update', $popup) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Popup Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $popup->title) }}" 
                        class="w-full px-4 py-3 rounded-xl focus:ring-blue-500 focus:border-blue-500 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-200' }}" 
                        placeholder="e.g. Admissions Open 2026-27" required>
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Image Presence --}}
                @if($popup->image_path)
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Current Image</label>
                    <div class="relative w-40 h-40 group">
                        <img src="{{ asset($popup->image_path) }}" class="w-full h-full object-cover rounded-xl border">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition rounded-xl flex items-center justify-center">
                            <span class="text-white text-xs font-bold">New image will replace this</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Image Upload --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ $popup->image_path ? 'Change Image' : 'Popup Image' }}</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="bi bi-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500 font-semibold px-4 text-center">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-400">PNG, JPG or WEBP (Max 2MB)</p>
                            </div>
                            <input type="file" name="image" class="hidden" accept="image/*" />
                        </label>
                    </div>
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Button Name --}}
                <div>
                    <label for="button_name" class="block text-sm font-bold text-gray-700 mb-2">Button Name</label>
                    <input type="text" name="button_name" id="button_name" value="{{ old('button_name', $popup->button_name) }}" 
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="e.g. Enroll Now">
                </div>

                {{-- Button Link --}}
                <div>
                    <label for="button_link" class="block text-sm font-bold text-gray-700 mb-2">Button URL/Link</label>
                    <input type="text" name="button_link" id="button_link" value="{{ old('button_link', $popup->button_link) }}" 
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="https://example.com/admission">
                </div>

                {{-- Button Color --}}
                <div>
                    <label for="button_color" class="block text-sm font-bold text-gray-700 mb-2">Button Background Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="button_color" id="button_color" value="{{ old('button_color', $popup->button_color) }}" 
                            class="h-12 w-12 border-0 bg-transparent cursor-pointer rounded-lg">
                        <span class="text-sm text-gray-500 font-mono" x-data="{ color: '{{ old('button_color', $popup->button_color) }}' }" x-text="color"></span>
                    </div>
                </div>

                {{-- Is Active --}}
                <div class="flex items-end">
                    <label class="relative inline-flex items-center cursor-pointer mb-3">
                        <input type="checkbox" name="is_active" class="sr-only peer" {{ $popup->is_active ? 'checked' : '' }} value="1">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-5 after:h-5 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Active Status</span>
                    </label>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.popups.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Cancel</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-95">Update Popup</button>
            </div>
        </form>
    </div>
</div>
@endsection
