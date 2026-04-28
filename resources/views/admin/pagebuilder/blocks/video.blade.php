@php
    $model = $model ?? 'block';
    $section = $section ?? 'null';
    $compact = $compact ?? false;
@endphp
<div class="text-center">
    <template x-if="{{ $model }}.src">
        <div class="relative">
            <video :src="{{ $model }}.src" controls class="max-w-full mx-auto rounded-lg shadow-md"></video>
             <div class="flex justify-center gap-2 mt-2">
                <button @click="{{ $section !== 'null' ? "removeMediaFromSub($section, $model.id)" : "removeMedia($model.id)" }}"
                    class="px-2 py-1 text-sm bg-red-100 rounded text-red-600">Remove</button>
            </div>
        </div>
    </template>
    <template x-if="!{{ $model }}.src">
        <div class="space-y-2">
            <label class="block cursor-pointer">
                <input type="file" accept="video/*"
                    @change="handleFileUpload($event, {{ $model }}.id, 'video', {{ $section }})" class="hidden" />
                <div class="p-4 border border-gray-300 border-dashed rounded-lg hover:bg-blue-50 transition">
                    <p class="text-sm text-gray-500">🎬 {{ $compact ? 'Upload Video' : 'Click to upload video' }}</p>
                </div>
            </label>
            <button type="button" 
                    @click="openMediaLibrary({ blockId: {{ $model }}.id, type: 'video', field: 'src' })"
                    class="w-full py-2 px-3 text-xs font-bold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Browse Library
            </button>
        </div>
    </template>
</div>
