@php
    $model = $model ?? 'block';
@endphp

<div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-50 rounded-xl">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800">Photo Gallery</h3>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Bulk Upload Enabled</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <label class="text-[11px] font-bold text-gray-500 uppercase">Images per line:</label>
            <select x-model="{{ $model }}.columns_desktop" 
                    class="bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-lg focus:ring-blue-500 block p-1.5 px-3">
                <option value="2">2 Columns</option>
                <option value="3">3 Columns</option>
                <option value="4">4 Columns</option>
                <option value="5">5 Columns</option>
                <option value="6">6 Columns</option>
            </select>
        </div>
    </div>

    {{-- Bulk Action --}}
    <div class="mb-6 p-6 border-2 border-dashed border-blue-100 rounded-2xl bg-blue-50/30 text-center group hover:border-blue-300 transition-colors">
        <input type="file" multiple accept="image/*" class="hidden" x-ref="bulkInput" 
               @change="handleBulkGalleryUpload($event, {{ $model }}.id)">
        
        <div class="flex flex-col items-center cursor-pointer" @click="$refs.bulkInput.click()">
            <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center mb-3 shadow-lg group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <p class="text-xs font-bold text-blue-800">Click to Add Many Photos</p>
            <p class="text-[10px] text-blue-500 mt-1">Select multiple images at once to upload in bulk</p>
        </div>

        <div class="mt-4 pt-4 border-t border-blue-100 flex items-center justify-center gap-4">
            <span class="text-[10px] text-blue-400 font-bold uppercase">OR</span>
            <button type="button" 
                    @click="openMediaLibrary({ blockId: {{ $model }}.id, type: 'gallery' })"
                    class="flex items-center gap-2 px-4 py-1.5 bg-white border border-blue-200 text-blue-600 text-xs font-bold rounded-full hover:bg-blue-50 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Open Media Library
            </button>
        </div>
    </div>

    {{-- Image List (Repeater) --}}
    <div class="space-y-3">
        <template x-for="(img, imgIndex) in ({{ $model }}.images || [])" :key="img.id">
            <div class="flex items-start gap-4 p-3 bg-gray-50 border border-gray-100 rounded-xl group/item hover:bg-white hover:shadow-sm transition-all border-l-4 border-l-blue-500">
                {{-- Preview --}}
                <div class="relative w-24 h-24 shrink-0 overflow-hidden rounded-lg bg-gray-200 border border-gray-100">
                    <img :src="img.src" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/item:opacity-100 transition-opacity flex items-center justify-center">
                        <button @click="if(confirm('Change this image?')) $refs.singleInput.click()" class="p-1.5 bg-white rounded-full text-blue-600 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Caption & Actions --}}
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between">
                         <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Caption</span>
                         <button @click="{{ $model }}.images.splice(imgIndex, 1); pushHistory();" 
                                 class="text-gray-300 hover:text-red-500 transition-colors">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                         </button>
                    </div>
                    <textarea x-model="img.caption" rows="2" 
                              class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-white transition-shadow"
                              placeholder="Write a caption for this photo..."></textarea>
                </div>
            </div>
        </template>

        <template x-if="!{{ $model }}.images || {{ $model }}.images.length === 0">
            <div class="py-10 text-center bg-gray-50/50 border border-dashed border-gray-200 rounded-xl">
                <div class="mb-2 inline-flex p-2 bg-gray-100 text-gray-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-xs font-medium text-gray-400">No photos yet. Click the blue button above to add some!</p>
            </div>
        </template>
    </div>
</div>
