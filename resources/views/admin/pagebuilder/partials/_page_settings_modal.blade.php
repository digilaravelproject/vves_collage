<div x-show="showPageSettings" 
     class="fixed inset-0 z-[99999] overflow-y-auto" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div @click="showPageSettings = false" class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true"></div>

        <div class="relative inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Page Configuration</h3>
                </div>
                <button @click="showPageSettings = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                {{-- Sidebar Mode --}}
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider">Sidebar Navigation Mode</label>
                    <p class="text-xs text-gray-500 mb-3">Choose how the sidebar navigation should be generated for this page.</p>
                    
                    <div class="grid grid-cols-1 gap-3">
                        <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                               :class="sidebarMode === 'default' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200'">
                            <input type="radio" name="sidebarMode" value="default" x-model="sidebarMode" class="hidden">
                            <div class="flex-1">
                                <span class="block text-sm font-bold" :class="sidebarMode === 'default' ? 'text-blue-700' : 'text-gray-900'">Standard Menu</span>
                                <span class="block text-xs text-gray-500">Show the main menu assigned to this page.</span>
                            </div>
                            <div x-show="sidebarMode === 'default'" class="text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                               :class="sidebarMode === 'custom' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200'">
                            <input type="radio" name="sidebarMode" value="custom" x-model="sidebarMode" @change="pushHistory()" class="hidden">
                            <div class="flex-1">
                                <span class="block text-sm font-bold" :class="sidebarMode === 'custom' ? 'text-blue-700' : 'text-gray-900'">Dynamic List</span>
                                <span class="block text-xs text-gray-500">Show a custom list of links (managed in the "Navigation" tab).</span>
                            </div>
                            <div x-show="sidebarMode === 'custom'" class="text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                               :class="sidebarMode === 'hidden' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200'">
                            <input type="radio" name="sidebarMode" value="hidden" x-model="sidebarMode" @change="pushHistory()" class="hidden">
                            <div class="flex-1">
                                <span class="block text-sm font-bold" :class="sidebarMode === 'hidden' ? 'text-blue-700' : 'text-gray-900'">Hidden (Full Width)</span>
                                <span class="block text-xs text-gray-500">Hide the sidebar entirely for this page.</span>
                            </div>
                            <div x-show="sidebarMode === 'hidden'" class="text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                               :class="sidebarMode === 'inherit' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-gray-200'">
                            <input type="radio" name="sidebarMode" value="inherit" x-model="sidebarMode" @change="pushHistory()" class="hidden">
                            <div class="flex-1">
                                <span class="block text-sm font-bold" :class="sidebarMode === 'inherit' ? 'text-blue-700' : 'text-gray-900'">Inherit from Page</span>
                                <span class="block text-xs text-gray-500">Use navigation links from another page.</span>
                            </div>
                            <div x-show="sidebarMode === 'inherit'" class="text-blue-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>
                    </div>

                    {{-- Inherit Dropdown --}}
                    <div x-show="sidebarMode === 'inherit'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="p-4 bg-gray-50 border border-gray-200 rounded-2xl space-y-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Select Source Page</label>
                        <select x-model="inheritedPageId" 
                                @change="pushHistory()"
                                class="w-full px-4 py-2 text-sm border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select a Page --</option>
                            <template x-for="pg in allPages.filter(p => p.id != id)" :key="pg.id">
                                <option :value="pg.id" x-text="pg.title"></option>
                            </template>
                        </select>
                        <p class="text-[10px] text-gray-400">This page will display exact same navigation items as the selected page.</p>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex gap-3">
                    <div class="text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-blue-800 font-medium leading-relaxed">
                            <strong>Tip:</strong> Use the <strong>"Navigation"</strong> tab in the left panel to manage your "Dynamic List" links!
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex justify-end gap-3 rounded-b-3xl">
                <button @click="showPageSettings = false" class="px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-200 rounded-xl transition-all">Done</button>
            </div>
        </div>
    </div>
</div>
