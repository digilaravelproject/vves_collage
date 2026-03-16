<div class="flex flex-col flex-wrap justify-between gap-4 mb-6 sm:flex-row sm:items-center bg-white p-4 rounded-b-2xl shadow-md sticky top-0 z-100 border-b border-gray-100">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Vikas Page Builder</h1>
            <p class="text-xs text-gray-500 font-medium">Editing: <span class="text-blue-600">{{ $page->title }}</span></p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <div class="flex p-1 bg-gray-100 rounded-xl mr-2">
             <button @click="undo" class="p-2 hover:bg-white rounded-lg transition-all text-gray-600 hover:text-blue-600 hover:shadow-sm" title="Undo (Ctrl+Z)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
             </button>
             <button @click="redo" class="p-2 hover:bg-white rounded-lg transition-all text-gray-600 hover:text-blue-600 hover:shadow-sm" title="Redo (Ctrl+Y)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"></path></svg>
             </button>
        </div>

        <div class="h-8 w-px bg-gray-200 mx-1 hidden sm:block"></div>

        <button @click="showPageSettings = true" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-all active:scale-95" title="Page Settings">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Page Settings
        </button>

        <button @click="openPreview()" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-100 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            Live Preview
        </button>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-all">
                More
                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 p-1 overflow-hidden" x-transition>
                <button @click="exportJSON(); open=false" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Export Layout
                </button>
                <button @click="importJSONPrompt(); open=false" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Import Layout
                </button>
            </div>
        </div>

        <button @click="savePage" class="flex items-center gap-2 px-6 py-2 text-sm font-extrabold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition-all active:scale-95 group">
            <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
            Save Changes
        </button>
    </div>
</div>
