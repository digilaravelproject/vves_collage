<div class="self-start p-5 bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-3 h-fit lg:sticky lg:top-24">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Components</h2>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-1">
        <template x-for="tpl in availableBlocks" :key="tpl.type">
            <div draggable="true" @dragstart="dragBlock($event, tpl)"
                class="flex items-center gap-3 p-3 text-sm font-medium text-gray-700 transition-all border border-gray-100 rounded-xl cursor-grab hover:bg-blue-50 hover:border-blue-100 hover:shadow-sm group active:scale-95 bg-gray-50/50">
                <div class="p-1.5 bg-white rounded-lg shadow-sm border border-gray-100 group-hover:text-blue-600 transition-colors">
                    <span x-text="tpl.label.split(' ')[0]"></span>
                </div>
                <span x-text="tpl.label.split(' ').slice(1).join(' ')"></span>
            </div>
        </template>
    </div>

    <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
        <h3 class="text-xs font-bold text-blue-800 uppercase mb-2">Pro Tip 💡</h3>
        <p class="text-[11px] text-blue-600 leading-relaxed font-medium">Drag and drop these components onto the canvas to build your layout. You can also reorder them anytime!</p>
    </div>
</div>
