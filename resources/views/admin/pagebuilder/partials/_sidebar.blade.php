<div class="self-start p-0 bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-3 h-fit lg:sticky lg:top-28 flex flex-col max-h-[calc(100vh-160px)] overflow-hidden z-20">
    <!-- Tab Headers -->
    <div class="flex border-b border-gray-100 bg-gray-50/50">
        <button @click="activeSidebarTab = 'blocks'"
            :class="activeSidebarTab === 'blocks' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'"
            class="flex-1 py-3 text-xs font-bold uppercase tracking-wider transition-all">
            Blocks
        </button>
        <button @click="activeSidebarTab = 'navigation'"
            :class="activeSidebarTab === 'navigation' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'"
            class="flex-1 py-3 text-xs font-bold uppercase tracking-wider transition-all">
            Navigation
        </button>
    </div>

    <!-- Blocks Tab Content -->
    <div x-show="activeSidebarTab === 'blocks'" class="p-5 overflow-y-auto flex-1 custom-scrollbar">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <h2 class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">Available Components</h2>
        </div>

        {{-- Search Input for Blocks --}}
        <div class="mb-5 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" x-model="blockSearchTerm" placeholder="Search blocks..." 
                   class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400 font-medium">
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-1">
            <template x-for="tpl in filteredBlocks" :key="tpl.type">
                <div draggable="true" @dragstart="dragBlock($event, tpl)"
                    class="flex items-center gap-3 p-3 text-sm font-medium text-gray-700 transition-all border border-gray-100 rounded-xl cursor-grab hover:bg-blue-50 hover:border-blue-100 hover:shadow-sm group active:scale-95 bg-gray-50/50">
                    <div class="p-1.5 bg-white rounded-lg shadow-sm border border-gray-100 group-hover:text-blue-600 transition-colors">
                        <span x-text="tpl.label.split(' ')[0]"></span>
                    </div>
                    <span x-text="tpl.label.split(' ').slice(1).join(' ')"></span>
                </div>
            </template>
        </div>

        <template x-if="filteredBlocks.length === 0">
            <div class="py-12 text-center bg-gray-50/50 border border-dashed border-gray-200 rounded-2xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No matching components</p>
            </div>
        </template>

        <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
            <h3 class="text-xs font-bold text-blue-800 uppercase mb-2">Pro Tip 💡</h3>
            <p class="text-[11px] text-blue-600 leading-relaxed font-medium">Drag and drop these components onto the canvas to build your layout.</p>
        </div>
    </div>

    <!-- Navigation Tab Content -->
    <div x-show="activeSidebarTab === 'navigation'" class="p-5 overflow-y-auto flex-1 custom-scrollbar" x-cloak>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                <h2 class="text-[11px] font-bold text-gray-800 uppercase tracking-wider">Sidebar Items</h2>
            </div>
        </div>

        {{-- Search Input for Navigation --}}
        <div class="mb-5 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" x-model="navSearchTerm" placeholder="Search items..." 
                   class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400 font-medium">
        </div>

        <!-- Sidebar Items List -->        <template x-if="sidebarMode === 'inherit'">
            <div class="p-6 text-center border-2 border-dashed border-blue-200 bg-blue-50/30 rounded-2xl">
                <div class="mb-3 inline-flex p-3 bg-blue-100 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h4 class="text-xs font-bold text-blue-900 mb-1">Inherited Links</h4>
                <p class="text-[10px] text-blue-600 px-2 leading-relaxed">
                    This sidebar is inheriting links from: 
                    <span class="font-bold text-blue-800" x-text="allPages.find(p => p.id == inheritedPageId)?.title || 'Unselected Page'"></span>.
                </p>
                <button @click="showPageSettings = true" class="mt-4 px-4 py-1.5 text-[10px] font-bold text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-100 transition-all">Change Source</button>
            </div>
        </template>

        <div x-show="sidebarMode === 'custom' || sidebarMode === 'default'">
            <div class="space-y-3 mb-6">
                <template x-for="(item, index) in filteredNavItems" :key="item.id">
                    {{-- Existing Item Template --}}
                    <div class="p-3 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition group">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-gray-100 text-gray-500" x-text="item.type"></span>
                            <button @click="removeSidebarItem(index)" class="text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <input type="text" x-model="item.label" placeholder="Link Label" 
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none mb-2">
                        
                        <div class="space-y-2">
                            <template x-if="item.type === 'section'">
                                <select x-model="item.targetId" 
                                        @change="if(!item.label || item.label === 'New Section Link') item.label = $event.target.options[$event.target.selectedIndex].text"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
                                    <option value="">Select a Section...</option>
                                    <template x-for="section in getAllSections()" :key="section.id">
                                        <option :value="section.id" x-text="section.title || 'Untitled Section'"></option>
                                    </template>
                                </select>
                            </template>

                            <template x-if="item.type === 'page'">
                                <select x-model="item.targetUrl" @change="if(!item.label) item.label = $event.target.options[$event.target.selectedIndex].text"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
                                    <option value="">Select a Page...</option>
                                    <template x-for="pg in allPages" :key="pg.id">
                                        <option :value="pg.slug" x-text="pg.title"></option>
                                    </template>
                                </select>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="sidebarItems.length === 0" class="py-8 text-center bg-gray-50/50 border border-dashed border-gray-200 rounded-xl">
                    <p class="text-[10px] text-gray-400 font-medium px-4">No sidebar items added yet. Use the buttons below to start.</p>
                </div>
            </div>

            <!-- Add Actions -->
            <div class="mt-4 p-4 bg-gray-50 border border-gray-100 rounded-2xl">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-3 tracking-widest text-center">Add Navigation</h4>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="addSidebarSectionLink()" 
                            class="flex flex-col items-center justify-center gap-2 p-3 text-[10px] font-bold text-blue-600 bg-white border border-blue-100 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm group">
                        <div class="p-2 bg-blue-50 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        Section
                    </button>
                    <button @click="Swal.fire({
                        title: 'Add Page Link',
                        input: 'select',
                        inputOptions: Object.fromEntries(allPages.filter(p => !p.slug || p.slug !== '{{ $page->slug }}').map(p => [p.id, p.title])),
                        inputPlaceholder: 'Select a page',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        confirmButtonText: 'Add Link'
                    }).then(res => { if(res.isConfirmed && res.value) addSidebarPageLink(res.value) })" 
                            class="flex flex-col items-center justify-center gap-2 p-3 text-[10px] font-bold text-purple-600 bg-white border border-purple-100 rounded-xl hover:bg-purple-50 hover:border-purple-200 transition-all shadow-sm group">
                        <div class="p-2 bg-purple-50 rounded-lg group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        Page
                    </button>
                </div>
            </div>
        </div>

        <div x-show="sidebarMode === 'hidden'" class="p-6 text-center border border-dashed border-amber-200 bg-amber-50/30 rounded-2xl">
            <h4 class="text-xs font-bold text-amber-900 mb-1">Sidebar Hidden</h4>
            <p class="text-[10px] text-amber-600">The sidebar layout is disabled for this page.</p>
            <button @click="showPageSettings = true" class="mt-4 px-4 py-1.5 text-[10px] font-bold text-amber-600 border border-amber-200 rounded-lg hover:bg-amber-100 transition-all">Change Mode</button>
        </div>

        <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
            <h3 class="text-[10px] font-bold text-amber-800 uppercase mb-1.5">Note</h3>
            <p class="text-[10px] text-amber-600 leading-relaxed">Ensure "Dynamic Sections" is chosen in **Page Settings** for these links to appear on the live site.</p>
        </div>
    </div>
</div>
