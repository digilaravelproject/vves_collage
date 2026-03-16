@php
    $model = $model ?? 'block';
    $index = $index ?? 'null';
@endphp

<div class="overflow-hidden bg-white border rounded-lg shadow">
    <button @click="{{ $model }}.expanded = !{{ $model }}.expanded"
        class="flex items-center justify-between w-full px-4 py-2 transition bg-blue-100 hover:bg-blue-200">
        <input type="text" x-model="{{ $model }}.title" @input="pushHistory"
            class="flex-1 font-semibold text-gray-700 bg-transparent border-none outline-none" />
        <span x-text="{{ $model }}.expanded ? '▾' : '▸'"></span>
    </button>

    <div x-show="{{ $model }}.expanded" x-collapse class="p-2 bg-gray-50 sm:p-4">
        {{-- Sidebar Settings moved to Navigation Tab --}}

        <div :id="'section-drop-' + {{ $model }}.id"
            class="border-2 border-dashed border-gray-300 rounded p-4 min-h-[100px]"
            @dragover.prevent @drop="dropBlockToSection($event, {{ $model }})">
            <template x-if="!{{ $model }}.blocks || {{ $model }}.blocks.length === 0">
                <p class="text-sm text-center text-gray-400">Drag content blocks here...</p>
            </template>

            <div :id="'section-list-' + {{ $model }}.id">
                <template x-for="(sub, sIndex) in {{ $model }}.blocks" :key="sub.id">
                    <div class="relative p-3 mb-3 bg-white border rounded shadow-sm group"
                        :data-id="sub.id">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <div class="text-sm text-gray-700">
                                <span x-text="sub.type"></span>
                                <span class="text-xs text-gray-400"
                                    x-text="' — ' + sub.id.slice(0, 8)"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-1 text-base text-gray-400 cursor-grab">☰</span>
                                <button @click="moveSubUp({{ $model }}, sIndex)"
                                    class="px-2 py-1 text-xs bg-white border rounded">↑</button>
                                <button @click="moveSubDown({{ $model }}, sIndex)"
                                    class="px-2 py-1 text-xs bg-white border rounded">↓</button>
                                <button @click="duplicateSub({{ $model }}, sIndex)"
                                    class="px-2 py-1 text-xs bg-white border rounded">⧉</button>
                                <button @click="confirmRemoveSub({{ $model }}, sIndex)"
                                    class="px-2 py-1 text-xs text-red-600 bg-white border rounded">✖</button>
                            </div>
                        </div>

                        @include('admin.pagebuilder.blocks._block_renderer', ['model' => 'sub', 'section' => $model, 'depth' => $depth ?? 0])
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
