<!-- Media Library Modal -->
<div x-show="showMediaLibrary" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     x-cloak
     @keydown.escape.window="showMediaLibrary = false">
    
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Backdrop -->
        <div x-show="showMediaLibrary" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Content -->
        <div x-show="showMediaLibrary" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Media Library</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Select or manage your assets</p>
                    </div>
                </div>
                <button @click="showMediaLibrary = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors rounded-xl hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                
                <!-- Loading State -->
                <template x-if="mediaLibraryLoading">
                    <div class="flex flex-col items-center justify-center py-20">
                        <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Fetching your assets...</p>
                    </div>
                </template>

                <!-- Grid -->
                <template x-if="!mediaLibraryLoading">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        <template x-for="item in media" :key="item.path">
                            <div @click="selectMedia(item)" 
                                 class="group relative aspect-square bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden cursor-pointer hover:border-blue-500 hover:ring-4 hover:ring-blue-50 transition-all">
                                
                                <template x-if="item.mime.startsWith('image/')">
                                    <img :src="item.url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </template>

                                <template x-if="item.mime.startsWith('video/')">
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-900">
                                        <svg class="w-10 h-10 text-white opacity-40" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.5 11.5l3 2v-7l-3 2v3z"></path></svg>
                                        <span class="text-[9px] text-white/50 mt-2 font-mono" x-text="item.name"></span>
                                    </div>
                                </template>

                                <!-- Selection Overlay -->
                                <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                
                                <!-- File Info Tag -->
                                <div class="absolute bottom-2 left-2 right-2 p-2 bg-white/90 backdrop-blur-sm rounded-lg border border-gray-100 shadow-sm transform translate-y-8 group-hover:translate-y-0 transition-transform">
                                    <p class="text-[10px] font-black text-gray-800 truncate" x-text="item.name"></p>
                                    <p class="text-[8px] text-gray-500 font-bold uppercase tracking-tighter" x-text="(item.size / 1024).toFixed(1) + ' KB'"></p>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <template x-if="media.length === 0">
                            <div class="col-span-full py-20 text-center">
                                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h4 class="text-gray-900 font-bold uppercase tracking-tight">No Media Found</h4>
                                <p class="text-xs text-gray-400 mt-2">Upload some files first using the block upload fields.</p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                    Showing <span x-text="media.length" class="text-blue-600"></span> assets
                </div>
                <button @click="showMediaLibrary = false" class="px-6 py-2 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-colors">
                    Close Library
                </button>
            </div>
        </div>
    </div>
</div>
