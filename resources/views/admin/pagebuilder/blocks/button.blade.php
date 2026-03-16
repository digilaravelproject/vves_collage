@php
    $model = $model ?? 'block';
    $section = $section ?? 'null';
    $compact = $compact ?? false;
@endphp

<div class="space-y-3">
    <div class="grid grid-cols-1 gap-3 {{ $compact ? '' : 'sm:grid-cols-2' }}">
        <div>
            <label class="{{ $compact ? 'text-[10px] font-medium text-gray-500 uppercase' : 'text-sm font-medium text-gray-600' }}">
                {{ $compact ? 'Text' : 'Button Text' }}
            </label>
            <input type="text" x-model="{{ $model }}.text" @input="pushHistory"
                class="w-full {{ $compact ? 'p-1.5 text-sm' : 'p-2' }} border rounded" placeholder="Click Here">
        </div>

        <div>
            <label class="{{ $compact ? 'text-[10px] font-medium text-gray-500 uppercase' : 'text-sm font-medium text-gray-600' }}">
                {{ $compact ? 'Link' : 'Button Link (URL)' }}
            </label>
            <input type="text" x-model="{{ $model }}.href" @input="pushHistory"
                class="w-full {{ $compact ? 'p-1.5 text-sm' : 'p-2' }} border rounded" placeholder="https://...">
        </div>

        @if(!$compact)
        <div>
            <label class="text-sm font-medium text-gray-600">Alignment</label>
            <select x-model="{{ $model }}.align" @change="pushHistory"
                class="w-full p-2 border rounded bg-white">
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Target</label>
            <select x-model="{{ $model }}.target" @change="pushHistory"
                class="w-full p-2 border rounded bg-white">
                <option value="_self">Same Tab (_self)</option>
                <option value="_blank">New Tab (_blank)</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Display Mode</label>
            <select x-model="{{ $model }}.displayMode" @change="pushHistory"
                class="w-full p-2 border rounded bg-white">
                <option value="default">Default</option>
                <option value="inline">Inline</option>
            </select>
        </div>
        @else
        <div class="flex items-center justify-between gap-2">
            <select x-model="{{ $model }}.align" @change="pushHistory" class="text-xs p-1 border rounded bg-white">
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
            </select>
            <button @click="{{ $model }}.expanded = !{{ $model }}.expanded" class="text-[10px] text-blue-500 underline">Settings</button>
        </div>
        @endif
    </div>

    @if(!$compact)
    <div class="p-3 border border-gray-200 rounded-lg bg-gray-50">
        <h4 class="mb-2 text-sm font-medium text-gray-700">Optional Image/Icon</h4>
        <div class="text-center">
            <template x-if="{{ $model }}.src">
                <div>
                    <img :src="{{ $model }}.src"
                        class="max-w-[100px] max-h-[100px] mx-auto rounded" />
                    <div class="flex justify-center gap-2 mt-2">
                        <button @click="{{ $section !== 'null' ? "removeMediaFromSub($section, $model.id)" : "removeMedia($model.id)" }}"
                            class="px-2 py-1 text-xs bg-red-100 rounded">Remove Image</button>
                    </div>
                </div>
            </template>
            <template x-if="!{{ $model }}.src">
                <label class="block cursor-pointer">
                    <input type="file" accept="image/*"
                        @change="handleFileUpload($event, {{ $model }}.id, 'image', {{ $section }})"
                        class="hidden" />
                    <div class="p-4 border border-gray-300 border-dashed rounded-lg hover:bg-blue-100 transition">
                        <p class="text-sm text-gray-500">🖼️ Click to upload button image/icon</p>
                    </div>
                </label>
            </template>
        </div>
    </div>
    @else
    <div x-show="{{ $model }}.expanded" class="p-2 bg-gray-50 border rounded space-y-2">
        <div>
            <label class="text-[10px]">Target</label>
            <select x-model="{{ $model }}.target" @change="pushHistory" class="w-full text-xs p-1 border rounded">
                <option value="_self">Same Tab</option>
                <option value="_blank">New Tab</option>
            </select>
        </div>
        <div>
            <label class="text-[10px]">Image/Icon</label>
            <template x-if="{{ $model }}.src">
                <div class="flex items-center gap-2">
                    <img :src="{{ $model }}.src" class="w-8 h-8 rounded border" />
                    <button @click="{{ $section !== 'null' ? "removeMediaFromSub($section, $model.id)" : "removeMedia($model.id)" }}" class="text-[9px] text-red-500">Remove</button>
                </div>
            </template>
            <template x-if="!{{ $model }}.src">
                <input type="file" accept="image/*" @change="handleFileUpload($event, {{ $model }}.id, 'image', {{ $section }})" class="text-[9px]" />
            </template>
        </div>
    </div>
    @endif
</div>
