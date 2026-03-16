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
        <label class="block mt-2 cursor-pointer">
            <input type="file" accept="video/*"
                @change="handleFileUpload($event, {{ $model }}.id, 'video', {{ $section }})" class="hidden" />
            <div class="p-4 border border-gray-300 border-dashed rounded-lg hover:bg-blue-50 transition">
                <p class="text-sm text-gray-500">🎬 {{ $compact ? 'Upload Video' : 'Click to upload video' }}</p>
            </div>
        </label>
    </template>
</div>
