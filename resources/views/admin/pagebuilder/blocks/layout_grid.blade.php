@php
    $model = $model ?? 'block';
    $index = $index ?? 'null';
@endphp

<div class="p-3 bg-white border-2 border-gray-100 rounded-xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Grid System</span>
            <select x-model="{{ $model }}.layout" @change="changeGridLayout({{ $model }})"
                class="bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5 shadow-sm">
                <option value="12">1 Column (12)</option>
                <option value="6-6">2 Columns (6-6)</option>
                <option value="4-4-4">3 Columns (4-4-4)</option>
                <option value="3-3-3-3">4 Columns (3-3-3-3)</option>
                <option value="4-8">2 Columns (4-8)</option>
                <option value="8-4">2 Columns (8-4)</option>
            </select>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <template x-for="(column, colIndex) in {{ $model }}.columns" :key="colIndex">
            <div :class="'px-2 mb-4 w-full md:w-' + (column.span === 12 ? 'full' : column.span + '/12')">
                <div :id="'column-list-' + {{ $model }}.id + '-' + colIndex"
                    class="h-full p-3 transition border-2 border-gray-100 border-dashed rounded-xl bg-gray-50/50 hover:bg-blue-50/30 min-h-[120px]"
                    @dragover.prevent @drop="dropBlockToColumn($event, {{ $model }}, colIndex)">

                    <template x-if="!column.blocks || column.blocks.length === 0">
                        <div class="flex flex-col items-center justify-center h-full py-4 text-center">
                            <span class="text-[10px] text-gray-400 font-medium">Empty Column</span>
                        </div>
                    </template>

                    <div :id="'column-list-' + {{ $model }}.id + '-' + colIndex" class="space-y-3">
                        <template x-for="(childBlock, cbIndex) in (column.blocks || [])" :key="childBlock.id">
                            <div class="relative p-3 transition bg-white border border-gray-200 shadow-sm rounded-xl group/child hover:border-blue-300"
                                :data-id="childBlock.id">
                                <div class="flex items-center justify-between gap-1 mb-2">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter" x-text="childBlock.type"></span>
                                    <div class="flex items-center gap-1 opacity-0 group-hover/child:opacity-100 transition-opacity">
                                        <button @click="moveChildUp({{ $model }}, colIndex, cbIndex)"
                                            class="p-1 hover:bg-gray-100 rounded text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        <button @click="moveChildDown({{ $model }}, colIndex, cbIndex)"
                                            class="p-1 hover:bg-gray-100 rounded text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <button @click="duplicateChild({{ $model }}, colIndex, cbIndex)"
                                            class="p-1 hover:bg-gray-100 rounded text-blue-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                        </button>
                                        <button @click="confirmRemoveChild({{ $model }}, colIndex, cbIndex)"
                                            class="p-1 hover:bg-red-50 rounded text-red-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                @include('admin.pagebuilder.blocks._block_renderer', ['model' => 'childBlock', 'section' => 'null', 'compact' => true, 'depth' => $depth ?? 0])
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
