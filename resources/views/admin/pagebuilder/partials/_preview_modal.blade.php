<div x-show="showPreview" 
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="showPreview = false"
     style="display: none;">
    <div class="relative w-full h-full max-w-6xl transition-all transform bg-white rounded-xl shadow-2xl flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Live Preview</h3>
                <p class="text-sm text-gray-500">Previewing: {{ $page->title }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="refreshPreview()" class="p-2 transition rounded-full hover:bg-gray-200 text-gray-600" title="Refresh">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
                <button @click="showPreview = false" class="p-2 transition rounded-full hover:bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Iframe Container --}}
        <div class="flex-1 bg-gray-200 relative">
             <div x-show="previewLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <iframe id="previewIframe" :src="previewUrl" class="w-full h-full border-none shadow-inner" @load="previewLoading = false"></iframe>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-3 bg-gray-50 border-t flex justify-end items-center gap-4">
            <span class="text-xs text-gray-400 italic">Note: Save your changes before previewing for up-to-date results.</span>
            <button @click="savePage().then(() => refreshPreview())" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Save & Refresh</button>
            <button @click="showPreview = false" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Close</button>
        </div>
    </div>
</div>
