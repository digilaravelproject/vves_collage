@extends('layouts.admin.app')

@section('content')
    <div x-data="pageBuilder()" x-init="initAll()" class="relative min-h-screen p-2 bg-gray-50 sm:p-4">

        {{-- 1. HEADER --}}
        @include('admin.pagebuilder.partials._header')

        {{-- 2. MAIN GRID --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- 3. SIDEBAR --}}
            @include('admin.pagebuilder.partials._sidebar')

            {{-- 4. CONTENT AREA --}}
            <div class="lg:col-span-9 bg-white p-4 sm:p-6 rounded-lg shadow min-h-[60vh] relative" @dragover.prevent
                @drop="dropBlock($event)">
                <template x-if="blocks.length === 0">
                    <div class="flex flex-col items-center justify-center h-full pt-10">
                        <div class="p-8 border-2 border-dashed border-gray-200 rounded-2xl text-center">
                             <p class="text-xl font-medium text-gray-400">🚀 Ready to build something great?</p>
                             <p class="mt-2 text-sm text-gray-300">Drag blocks from the sidebar to start creating your page.</p>
                        </div>
                    </div>
                </template>

                <div id="rootBlocks">
                    <template x-for="(block, index) in blocks" :key="block.id">
                        <div class="relative p-4 mb-6 transition border border-gray-200 rounded-xl bg-gray-50/50 hover:shadow-lg hover:border-blue-200 group/root"
                             :data-id="block.id">

                            {{-- 5. BLOCK HEADER --}}
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="p-1 px-2 bg-blue-100 rounded text-blue-700 font-bold text-[10px] uppercase tracking-widest" x-text="block.type"></div>
                                    <span class="text-[10px] text-gray-400 font-mono" x-text="block.id.slice(0, 8)"></span>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5">
                                    <button @click="moveBlockUp(index)"
                                        class="p-1.5 text-xs bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition shadow-sm text-gray-500" title="Move Up">↑</button>
                                    <button @click="moveBlockDown(index)"
                                        class="p-1.5 text-xs bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition shadow-sm text-gray-500" title="Move Down">↓</button>
                                    <button @click="duplicateBlock(index)"
                                        class="p-1.5 text-xs bg-white border border-gray-200 rounded-lg hover:bg-blue-50 transition shadow-sm text-blue-500" title="Duplicate">⧉</button>
                                    <button @click="confirmRemove(block.id, index)"
                                        class="p-1.5 text-xs bg-white border border-gray-200 rounded-lg hover:bg-red-50 transition shadow-sm text-red-600" title="Delete">✖</button>
                                </div>
                            </div>

                            {{-- Render Block --}}
                            @include('admin.pagebuilder.blocks._block_renderer', ['model' => 'block', 'index' => 'index'])
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <form id="saveForm" method="POST" action="{{ route('admin.pagebuilder.builder.save', $page) }}">
            @csrf
            <input type="hidden" name="content" id="pageContent">
        </form>
        @include('admin.pagebuilder.partials._preview_modal')
        @include('admin.pagebuilder.partials._page_settings_modal')
    </div>

    <script type="application/json" id="pb-initial-content">{!! $page->content ?: '{"blocks":[]}' !!}</script>

    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    {{-- Scripts already loaded in admin layout: Alpine, Collapse, Tailwind, SweetAlert --}}
    {{-- <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script> --}} {{-- Keep this if version differs, otherwise use layout --}}
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- TABLE LINKS REMOVED --}}

    <script>
        // =================================================================
        //         TABLE MODULE REGISTRATION (REMOVED)
        // =================================================================

        // =================================================================
        //               MAIN ALPINE JS LOGIC
        // =================================================================

        function pageBuilder(savedContent = null) {
            return {
                availableBlocks: [{
                    type: 'section',
                    label: '📁 Section',
                    title: 'New Section',
                    blocks: [],
                    expanded: true
                },
                {
                    type: 'heading',
                    label: '🧱 Heading',
                    defaultContent: '<h2>Heading</h2>'
                },
                {
                    type: 'text',
                    label: '📝 Text',
                    defaultContent: '<p>Type something...</p>'
                },
                {
                    type: 'image',
                    label: '🖼️ Image',
                    src: ''
                },
                {
                    type: 'video',
                    label: '🎥 Video',
                    src: ''
                },
                {
                    type: 'pdf',
                    label: '📄 PDF',
                    src: ''
                },
                // --- NEW BLOCKS ---
                {
                    type: 'embed',
                    label: '▶️ YouTube/Embed',
                    src: ''
                },
                {
                    type: 'button',
                    label: '🔘 Button',
                    text: 'Click Here',
                    href: '#',
                    align: 'left',
                    target: '_self',
                    displayMode: 'default',
                    src: ''
                },
                {
                    type: 'divider',
                    label: '⎯⎯ Divider'
                },
                {
                    type: 'code',
                    label: '💻 Code Block',
                    defaultContent: ''
                },
                // START: NEW TABLE BLOCK DEFINITION
               {
    type: 'table',
    label: '📊 Table',
    // Ab hum Text string ki jagah Object use karenge { text: '', img: '' }
    data: [
        [
            { text: 'Header 1', img: '', href: '' },
            { text: 'Header 2', img: '', href: '' },
            { text: 'Header 3', img: '', href: '' }
        ],
        [
            { text: 'Cell 1', img: '', href: '' },
            { text: 'Cell 2', img: '', href: '' },
            { text: 'Cell 3', img: '', href: '' }
        ],
    ],
},
                    {
                            type: 'layout_grid',
                            label: 'Layout (Grid)',
                            title: 'Layout',
                            layout: '6-6',
                            columns: [
                                { span: 6, blocks: [] },
                                { span: 6, blocks: [] }
                            ]
                        }
                    // END: NEW Layout Grid BLOCK DEFINITION
                ],
                blocks: [],
                sidebarItems: [], // { id, type: 'section'|'page', label, target }
                activeSidebarTab: 'blocks', // 'blocks' or 'navigation'
                allPages: @json($allPages ?? []),
                sidebarMode: 'default', // 'menu' (standard), 'custom', 'hidden', 'inherit'
                inheritedPageId: '',
                id: '{{ $page->id }}',
                showPageSettings: false,
                quills: {},
                historyStack: [],
                redoStack: [],
                showPreview: false,
                previewLoading: false,
                previewUrl: '{{ route('admin.pagebuilder.preview', $page->slug) }}',

                refreshPreview() {
                    this.previewLoading = true;
                    const iframe = document.getElementById('previewIframe');
                    if (iframe) {
                        iframe.src = this.previewUrl + '?t=' + Date.now();
                    }
                },

                openPreview() {
                    this.showPreview = true;
                    this.refreshPreview();
                },

                // Sidebar Management
                addSidebarSectionLink() {
                    this.sidebarItems.push({
                        id: this._genId(),
                        type: 'section',
                        label: 'New Section Link',
                        targetId: '' // Will be populated by a dropdown of current sections
                    });
                    this.pushHistory();
                },
                addSidebarPageLink(pageId) {
                    const pg = this.allPages.find(p => p.id == pageId);
                    if (!pg) return;
                    this.sidebarItems.push({
                        id: this._genId(),
                        type: 'page',
                        label: pg.title,
                        targetUrl: pg.slug
                    });
                    this.pushHistory();
                },
                removeSidebarItem(index) {
                    this.sidebarItems.splice(index, 1);
                    this.pushHistory();
                },
                getAllSections() {
                    // Helper to get all section blocks from the current page
                    return this.blocks.filter(b => b.type === 'section');
                },

initAll() {
    try {
        // Robust initial content loading via script[type=application/json]
        const scriptEl = document.getElementById('pb-initial-content');
        let initial = null;
        if (scriptEl) {
            try {
                initial = JSON.parse(scriptEl.textContent || '');
            } catch (_) {
                initial = null;
            }
        }

        if (initial && initial.blocks && Array.isArray(initial.blocks)) {
            this.blocks = initial.blocks.map(b => ({
                ...b,
                id: b.id || this._genId()
            }));
            this.sidebarMode = initial.sidebarMode || 'default';
            this.sidebarItems = initial.sidebarItems || [];
            this.inheritedPageId = initial.inheritedPageId || '';
        } else if (Array.isArray(initial)) {
            this.blocks = initial.map(b => ({
                ...b,
                id: b.id || this._genId()
            }));
            this.sidebarMode = 'default';
            this.sidebarItems = [];
        } else {
            this.blocks = [];
            this.sidebarMode = 'default';
            this.sidebarItems = [];
        }

        // ============================================================
        // 🔥 FIX FOR OLD DATA (Migration Script - Recursive)
        // ============================================================
        const migrateTableData = (block) => {
            // 1. Table Data Fix
            if (block.type === 'table' && Array.isArray(block.data)) {
                block.data = block.data.map(row => {
                    return row.map(cell => {
                        // Agar cell String hai (Purana Data), toh usse Object bana do
                        if (typeof cell !== 'object' || cell === null) {
                            return { text: cell, img: '', href: '' };
                        }
                        // Ensure href exists even if cell is object
                        if (typeof cell.href === 'undefined') {
                            cell.href = '';
                        }
                        return cell;
                    });
                });
            }

            // 2. Recursive Fix for Nested Blocks (Sections)
            if (block.type === 'section' && Array.isArray(block.blocks)) {
                block.blocks.forEach(sub => migrateTableData(sub));
            }

            // 3. Recursive Fix for Layout Grid Blocks
            if (block.type === 'layout_grid' && Array.isArray(block.columns)) {
                block.columns.forEach(col => {
                    if (Array.isArray(col.blocks)) {
                        col.blocks.forEach(child => migrateTableData(child));
                    }
                });
            }
        };

        this.blocks.forEach(block => migrateTableData(block));

        // ============================================================
        // END FIX
        // ============================================================

        this.$nextTick(() => {
            this.initAllQuills();
            this.initSortables();
            this.pushHistory();
        });
    } catch (e) {
        console.error('initAll failed:', e);
        Swal.fire('Error', 'Failed to initialize page builder.', 'error');
    }
},
                initAll_old() {
                    try {
                        // Robust initial content loading via script[type=application/json]
                        const scriptEl = document.getElementById('pb-initial-content');
                        let initial = null;
                        if (scriptEl) {
                            try {
                                initial = JSON.parse(scriptEl.textContent || '');
                            } catch (_) {
                                initial = null;
                            }
                        }

                        if (initial && initial.blocks && Array.isArray(initial.blocks)) {
                            this.blocks = initial.blocks.map(b => ({
                                ...b,
                                id: b.id || this._genId()
                            }));
                        } else if (Array.isArray(initial)) {
                            this.blocks = initial.map(b => ({
                                ...b,
                                id: b.id || this._genId()
                            }));
                        } else {
                            this.blocks = [];
                        }

                        this.$nextTick(() => {
                            this.initAllQuills();
                            this.initSortables();
                            this.pushHistory();
                        });
                    } catch (e) {
                        console.error('initAll failed:', e);
                        Swal.fire('Error', 'Failed to initialize page builder.', 'error');
                    }
                },

                initAllQuills() {
                    try {
                        Object.keys(this.quills).forEach(id => {
                            const editorEl = document.getElementById('editor-' + id);
                            if (editorEl) editorEl.innerHTML = '';
                        });
                        this.quills = {};
                        this.blocks.forEach(b => this.initBlockQuills(b));
                    } catch (e) {
                        console.error('initAllQuills failed:', e);
                    }
                },

                initBlockQuills(block) {
                    try {
                        if (!block) return;
                        const types = ['text', 'heading']; // Quill editors only for these

                        if (types.includes(block.type)) {
                            const initialHtml = (block.contentHtml || block.content || block
                                .defaultContent || '<p></p>');
                            const initialDelta = block.contentDelta || null;
                            this.initQuill(block.id, initialHtml, initialDelta);
                        }

                        if (block.type === 'section' && Array.isArray(block.blocks)) {
                            block.blocks.forEach(sub => {
                                if (types.includes(sub.type)) {
                                    const subHtml = (sub.contentHtml || sub.content || sub
                                        .defaultContent || '<p></p>');
                                    const subDelta = sub.contentDelta || null;
                                    this.initQuill(sub.id, subHtml, subDelta);
                                }
                            });
                        }
                    } catch (e) {
                        console.error('initBlockQuills failed:', e);
                    }
                },

                _genId() {
                    return 'b_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 8);
                },

                // --- Drag & Drop/Reorder/Delete logic ---

                dragBlock(e, tpl) {
                    try {
                        e.dataTransfer.setData('blockTpl', JSON.stringify(tpl));
                    } catch (e) {
                        console.error('dragBlock failed:', e);
                    }
                },

                dropBlock(e) {
                    try {
                        const data = e.dataTransfer.getData('blockTpl');
                        if (!data) return;
                        const tpl = JSON.parse(data);
                        const newBlock = JSON.parse(JSON.stringify(tpl));
                        newBlock.id = this._genId();

                        // Set default content for text-based blocks
                        if (['text', 'heading'].includes(newBlock.type)) {
                            newBlock.content = newBlock.defaultContent || '<p></p>';
                        }
                        if (newBlock.type === 'code') {
                            newBlock.content = newBlock.defaultContent || '';
                        }
                        // START: New logic for table initialization
                        if (newBlock.type === 'table' && !Array.isArray(newBlock.data)) {
                            newBlock.data = [
                               // Row 1 (Headers)
        [
            { text: 'Header 1', img: '', href: '' },
            { text: 'Header 2', img: '', href: '' },
            { text: 'Header 3', img: '', href: '' }
        ],
        // Row 2 (Data)
        [
            { text: 'Cell 1,1', img: '', href: '' },
            { text: 'Cell 1,2', img: '', href: '' },
            { text: 'Cell 1,3', img: '', href: '' }
        ],
        // Row 3 (Data)
        [
            { text: 'Cell 2,1', img: '', href: '' },
            { text: 'Cell 2,2', img: '', href: '' },
            { text: 'Cell 2,3', img: '', href: '' }
        ],
                            ];
                        }
                        // END: New logic for table initialization
                        // Start Layout Grid feature
                        if (newBlock.type === 'layout_grid' && Array.isArray(newBlock.columns)) {
                            newBlock.columns = newBlock.columns.map(c => ({ span: c.span || 12, blocks: [] }));
                        }
                        // End Layout Grid feature

                        if (newBlock.type === 'section' && !Array.isArray(newBlock.blocks)) {
                            newBlock.blocks = [];
                            newBlock.expanded = true;
                            newBlock.title = newBlock.title || 'New Section';
                        }
                        this.blocks.push(newBlock);
                        this.$nextTick(() => {
                            this.initBlockQuills(newBlock); // Only inits Quill if needed
                            this.initSortables();
                            this.pushHistory();
                        });
                    } catch (e) {
                        console.error('dropBlock failed:', e);
                        Swal.fire('Error', 'Failed to drop block.', 'error');
                    }
                },

                dropBlockToSection(e, section) {
                    try {
                        e.stopPropagation();
                        const data = e.dataTransfer.getData('blockTpl');
                        if (!data) return;
                        const tpl = JSON.parse(data);
                        if (tpl.type === 'section') return;
                        const newBlock = JSON.parse(JSON.stringify(tpl));
                        newBlock.id = this._genId();

                        // Set default content for text-based blocks
                        if (['text', 'heading'].includes(newBlock.type)) {
                            newBlock.content = newBlock.defaultContent || '<p></p>';
                        }
                        if (newBlock.type === 'code') {
                            newBlock.content = newBlock.defaultContent || '';
                        }
                        // START: New logic for table initialization
                        if (newBlock.type === 'table' && !Array.isArray(newBlock.data)) {
                            newBlock.data = [
                               // Row 1 (Headers)
        [
            { text: 'Header 1', img: '', href: '' },
            { text: 'Header 2', img: '', href: '' },
            { text: 'Header 3', img: '', href: '' }
        ],
        // Row 2 (Data)
        [
            { text: 'Cell 1,1', img: '', href: '' },
            { text: 'Cell 1,2', img: '', href: '' },
            { text: 'Cell 1,3', img: '', href: '' }
        ],
        // Row 3 (Data)
        [
            { text: 'Cell 2,1', img: '', href: '' },
            { text: 'Cell 2,2', img: '', href: '' },
            { text: 'Cell 2,3', img: '', href: '' }
        ],
                            ];
                        }
                        // END: New logic for table initialization

                        if (!Array.isArray(section.blocks)) section.blocks = [];
                        section.blocks.push(newBlock);
                        this.$nextTick(() => {
                            this.initBlockQuills(newBlock); // Only inits Quill if needed
                            this.initSortables();
                            this.pushHistory();
                        });
                    } catch (e) {
                        console.error('dropBlockToSection failed:', e);
                        Swal.fire('Error', 'Failed to drop block into section.', 'error');
                    }
                },
                // ... (rest of the existing functions: initSortables, reorderByIds, etc.)

                // START: NEW TABLE MANIPULATION FUNCTIONS
addRow(block) {
    try {
        const numCols = block.data[0] ? block.data[0].length : 3;
        // String ki jagah object push karein
        const newRow = Array(numCols).fill(null).map(() => ({ text: 'New Cell', img: '', href: '' }));
        block.data.push(newRow);
        this.pushHistory();
    } catch (e) { console.error('addRow failed:', e); }
},
                removeRow(block) {
                    try {
                        if (block.data.length > 1) {
                            block.data.pop();
                            this.pushHistory();
                        } else {
                            Swal.fire('Cannot remove last row', 'A table must have at least one row.', 'info');
                        }
                    } catch (e) { console.error('removeRow failed:', e); }
                },
addCol(block) {
    try {
        // String ki jagah object push karein
        block.data.forEach(row => row.push({ text: 'New Cell', img: '', href: '' }));
        this.pushHistory();
    } catch (e) { console.error('addCol failed:', e); }
},
                removeCol(block) {
                    try {
                        if (block.data[0].length > 1) {
                            block.data.forEach(row => row.pop());
                            this.pushHistory();
                        } else {
                            Swal.fire('Cannot remove last column', 'A table must have at least one column.', 'info');
                        }
                    } catch (e) { console.error('removeCol failed:', e); }
                },
                // END: NEW TABLE MANIPULATION FUNCTIONS

changeGridLayout(block) {
    if (!block || !block.layout) return;
    const spans = ('' + block.layout).split('-').map(s => parseInt(s, 10) || 12);
    const oldColumns = Array.isArray(block.columns) ? block.columns : [];
    const newColumns = [];

    for (let i = 0; i < spans.length; i++) {
        if (oldColumns[i]) {
            // Agar column already exist karta hai, to uske blocks ko rakhein
            newColumns.push({ span: spans[i], blocks: oldColumns[i].blocks });
        } else {
            // Naya column, empty blocks ke saath
            newColumns.push({ span: spans[i], blocks: [] });
        }
    }

    // Agar columns kam ho gaye hain, to extra blocks ko aakhri column mein move karein
    if (oldColumns.length > newColumns.length) {
        for (let i = newColumns.length; i < oldColumns.length; i++) {
            if (Array.isArray(oldColumns[i].blocks) && oldColumns[i].blocks.length) {
                // Aakhri naye column mein blocks ko append karein
                newColumns[newColumns.length - 1].blocks.push(...oldColumns[i].blocks);
            }
        }
    }
    block.columns = newColumns;
    this.pushHistory();
    this.$nextTick(() => this.initSortables());
},
// Layout Grid

                initSortables() {
                    try {
                        const rootEl = document.getElementById('rootBlocks');
                        if (rootEl && !rootEl._sortable) {
                            rootEl._sortable = Sortable.create(rootEl, {
                                handle: '.group',
                                animation: 150,
                                draggable: '[data-id]',
                                dataIdAttr: 'data-id',
                                onEnd: (evt) => {
                                    const ids = Array.from(rootEl.querySelectorAll(
                                        ':scope > div[data-id]')).map(el => el
                                            .getAttribute('data-id'));
                                    this.reorderByIds(ids, 'root');
                                }
                            });
                        }
                        this.blocks.forEach(block => {
                            if (block.type === 'section') {
                                const secList = document.getElementById('section-list-' + block.id);
                                if (secList && !secList._sortable) {
                                    secList._sortable = Sortable.create(secList, {
                                        animation: 150,
                                        draggable: '[data-id]',
                                        onEnd: (evt) => {
                                            const ids = Array.from(secList
                                                .querySelectorAll(
                                                    ':scope > div[data-id]')).map(
                                                        el => el.getAttribute('data-id'));
                                            this.reorderSectionByIds(block, ids);
                                        }
                                    });
                                }
                            }
                            // ✅ NEW: LAYOUT_GRID COLUMN SORTABLES ADDITION
            if (block.type === 'layout_grid' && Array.isArray(block.columns)) {
                block.columns.forEach((col, colIndex) => {
                    // Unique ID: `column-list-BLOCK_ID-COL_INDEX`
                    const colList = document.getElementById(`column-list-${block.id}-${colIndex}`);

                    if (colList && !colList._sortable) {
                        colList._sortable = Sortable.create(colList, {
                            // Home page builder mein aapne 'shared-blocks' group use kiya tha.
                            // Page builder mein aap drag-and-drop ke liye section logic use karte hain.
                            // Yahan hum drag/drop ke liye "clone" functionality ko ignore kar rahe hain,
                            // sirf sorting ko enable kar rahe hain.
                            animation: 150,
                            draggable: '[data-id]',
                            dataIdAttr: 'data-id',

                            onEnd: (evt) => {
                                // 1. Updated order ke IDs ko fetch karein
                                const ids = Array.from(colList
                                    .querySelectorAll(':scope > div[data-id]'))
                                    .map(el => el.getAttribute('data-id'));

                                // 2. Data model mein blocks ko reorder karein
                                const map = {};
                                // Make sure 'col.blocks' array exists before mapping
                                (col.blocks || []).forEach(b => map[b.id] = b);
                                col.blocks = ids.map(id => map[id]).filter(Boolean);

                                // 3. History push karein aur Quills ko re-initialize karein
                                this.pushHistory();
                                this.$nextTick(() => this.initAllQuills());
                            },
                        });
                    }
                });
            }
                        });
                    } catch (e) {
                        console.error('initSortables failed:', e);
                    }
                },
                // ... (rest of the existing functions)
                reorderByIds(ids, scope = 'root') {
                    try {
                        if (!ids || !ids.length) return;
                        const map = {};
                        this.blocks.forEach(b => map[b.id] = b);
                        this.blocks = ids.map(id => map[id]).filter(Boolean);
                        this.pushHistory();
                        this.$nextTick(() => this.initAllQuills());
                    } catch (e) {
                        console.error('reorderByIds failed:', e);
                    }
                },

                reorderSectionByIds(section, ids) {
                    try {
                        if (!ids || !ids.length) return;
                        const map = {};
                        (section.blocks || []).forEach(b => map[b.id] = b);
                        section.blocks = ids.map(id => map[id]).filter(Boolean);
                        this.pushHistory();
                        this.$nextTick(() => this.initAllQuills());
                    } catch (e) {
                        console.error('reorderSectionByIds failed:', e);
                    }
                },
                // ... (moveBlockUp, moveBlockDown, duplicateBlock, confirmRemove, moveSubUp, moveSubDown, duplicateSub, confirmRemoveSub)
                moveBlockUp(index) {
                    try {
                        if (index <= 0) return;
                        const arr = this.blocks;
                        [arr[index - 1], arr[index]] = [arr[index], arr[index - 1]];
                        this.blocks = [...arr];
                        this.pushHistory();
                    } catch (e) {
                        console.error('moveBlockUp failed:', e);
                    }
                },
                moveBlockDown(index) {
                    try {
                        if (index >= this.blocks.length - 1) return;
                        const arr = this.blocks;
                        [arr[index + 1], arr[index]] = [arr[index], arr[index + 1]];
                        this.blocks = [...arr];
                        this.pushHistory();
                    } catch (e) {
                        console.error('moveBlockDown failed:', e);
                    }
                },
                duplicateBlock(index) {
                    try {
                        const b = JSON.parse(JSON.stringify(this.blocks[index]));
                        b.id = this._genId();
                        if (b.type === 'section' && Array.isArray(b.blocks)) {
                            b.blocks = b.blocks.map(sb => ({
                                ...sb,
                                id: this._genId()
                            }));
                        }
                        this.blocks.splice(index + 1, 0, b);
                        this.$nextTick(() => {
                            this.initBlockQuills(b);
                            this.initSortables();
                            this.pushHistory();
                        });
                    } catch (e) {
                        console.error('duplicateBlock failed:', e);
                    }
                },
                confirmRemove(blockId, index) {
                    try {
                        Swal.fire({
                            title: 'Delete?',
                            text: 'Remove this block?',
                            icon: 'warning',
                            showCancelButton: true
                        }).then(res => {
                            if (res.isConfirmed) {
                                this.blocks.splice(index, 1);
                                this.pushHistory();
                            }
                        });
                    } catch (e) {
                        console.error('confirmRemove failed:', e);
                    }
                },
                moveSubUp(section, sIndex) {
                    try {
                        if (sIndex <= 0) return;
                        const arr = section.blocks;
                        [arr[sIndex - 1], arr[sIndex]] = [arr[sIndex], arr[sIndex - 1]];
                        section.blocks = [...arr];
                        this.pushHistory();
                    } catch (e) {
                        console.error('moveSubUp failed:', e);
                    }
                },
                moveSubDown(section, sIndex) {
                    try {
                        if (sIndex >= section.blocks.length - 1) return;
                        const arr = section.blocks;
                        [arr[sIndex + 1], arr[sIndex]] = [arr[sIndex], arr[sIndex + 1]];
                        section.blocks = [...arr];
                        this.pushHistory();
                    } catch (e) {
                        console.error('moveSubDown failed:', e);
                    }
                },
                duplicateSub(section, sIndex) {
                    try {
                        const sb = JSON.parse(JSON.stringify(section.blocks[sIndex]));
                        sb.id = this._genId();
                        section.blocks.splice(sIndex + 1, 0, sb);
                        this.$nextTick(() => {
                            this.initBlockQuills(sb);
                            this.initSortables();
                            this.pushHistory();
                        });
                    } catch (e) {
                        console.error('duplicateSub failed:', e);
                    }
                },
                confirmRemoveSub(section, index) {
                    try {
                        Swal.fire({
                            title: 'Delete?',
                            text: 'Remove sub-block?',
                            icon: 'warning',
                            showCancelButton: true
                        }).then(res => {
                            if (res.isConfirmed) {
                                section.blocks.splice(index, 1);
                                this.pushHistory();
                            }
                        });
                    } catch (e) {
                        console.error('confirmRemoveSub failed:', e);
                    }
                },
                // ... (rest of the existing Quill, File Upload, History functions)
                initQuill(blockId, initialHtml = '', initialDelta = null) {
                    if (this.quills[blockId]) return;
                    const toolbarSelector = '#toolbar-' + blockId;
                    const editorSelector = '#editor-' + blockId;

                    const attemptInit = () => {
                        const ed = document.querySelector(editorSelector);
                        const tbEl = document.querySelector(toolbarSelector);
                        if (!ed || !tbEl) return setTimeout(attemptInit, 50);

                        try {
                            if (this.quills[blockId]) delete this.quills[blockId];

                            const quill = new Quill(editorSelector, {
                                theme: 'snow',
                                modules: {
                                    toolbar: toolbarSelector
                                },
                                placeholder: 'Type here...'
                            });

                            if (initialDelta && typeof initialDelta === 'object') {
                                try {
                                    quill.setContents(initialDelta);
                                } catch (_) {
                                    quill.root.innerHTML = initialHtml || '<p></p>';
                                }
                            } else if (initialHtml) {
                                quill.root.innerHTML = initialHtml;
                            }
                            this.quills[blockId] = quill;

                            let debounceTimer;
                            quill.on('text-change', (delta, oldDelta, source) => {
                                if (source !== 'user') return;
                                clearTimeout(debounceTimer);
                                debounceTimer = setTimeout(() => {
                                    // This is the fix: only call pushHistory,
                                    // which will then call updateQuillContent for *all* editors.
                                    this.pushHistory();
                                }, 400);
                            });
                        } catch (e) {
                            console.error(`Quill initialization for ${blockId} failed:`, e);
                        }
                    };

                    attemptInit();
                },

                updateQuillContent(blockId, html, delta = null) {
                    try {
                        const findAndUpdate = (arr) => {
                            for (let b of arr) {
                                if (b.id === blockId) {
                                    b.content = html; // legacy field support
                                    b.contentHtml = html;
                                    if (delta) b.contentDelta = delta;
                                    return true;
                                }
                                if (b.type === 'section' && Array.isArray(b.blocks)) {
                                    if (findAndUpdate(b.blocks)) return true;
                                }
                            }
                            return false;
                        };
                        findAndUpdate(this.blocks);
                        // 🚫 This is the root bug fix: DO NOT trigger reactivity here.
                        // this.blocks = [...this.blocks];
                    } catch (e) {
                        console.error('updateQuillContent failed:', e);
                    }
                },

                openLinkDialog(blockId) {
                    try {
                        const quill = this.quills[blockId];
                        if (!quill) return Swal.fire('Error', 'Editor not ready', 'error');
                        const range = quill.getSelection();
                        if (!range || range.length === 0) {
                            return Swal.fire('Select text', 'Please select the text you want to link.',
                                'info');
                        }

                        Swal.fire({
                            title: 'Insert Link',
                            html: `<input id="swal-link-url" class="swal2-input" placeholder="https://example.com" style="width: 100%;">
                                                                                               <select id="swal-link-target" class="swal2-select" style="width: 100%; margin-top: 10px;">
                                                                                                    <option value="_blank" selected>New Tab (_blank)</option>
                                                                                                    <option value="_self">Same Tab (_self)</option>
                                                                                               </select>`,
                            preConfirm: () => ({
                                url: document.getElementById('swal-link-url').value,
                                target: document.getElementById('swal-link-target').value ||
                                    '_blank'
                            }),
                            showCancelButton: true
                        }).then(res => {
                            if (res.isConfirmed && res.value.url) {
                                quill.format('link', res.value.url);
                                // Set target attribute after link is created
                                quill.formatText(range.index, range.length, 'link', {
                                    href: res.value.url,
                                    target: res.value.target
                                });

                                // Manually update the DOM as Quill doesn't natively support 'target'
                                setTimeout(() => {
                                    const anchors = quill.root.querySelectorAll('a[href="' + res.value.url + '"]');
                                    anchors.forEach(a => {
                                        if (!a.hasAttribute('target')) {
                                            a.setAttribute('target', res.value.target);
                                        }
                                    });
                                    this.updateQuillContent(blockId, quill.root.innerHTML, quill.getContents());
                                    this.pushHistory();
                                }, 100);
                            }
                        });
                    } catch (e) {
                        console.error('openLinkDialog failed:', e);
                    }
                },

                async handleFileUpload(e, blockId, type, section = null) {
                    try {
                        const file = e.target.files[0];
                        if (!file) return;

                        const formData = new FormData();
                        formData.append("file", file);

                        Swal.fire({
                            title: 'Uploading...',
                            text: 'Please wait.',
                            didOpen: () => { Swal.showLoading() },
                            allowOutsideClick: false
                        });

                        const res = await fetch(
                            '{{ route('admin.pagebuilder.builder.upload', $page) }}', {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: formData,
                        });

                        const data = await res.json();

                        if (data.success) {
                            const url = data.url;
                            const update = (arr) => {
                                for (let b of arr) {
                                    if (b.id === blockId) {
                                        b.src = url;
                                        return true;
                                    }
                                    if (b.type === "section" && Array.isArray(b.blocks)) {
                                        if (update(b.blocks)) return true;
                                    }
                                }
                                return false;
                            };

                            update(this.blocks);
                            this.blocks = [...this.blocks]; // This is OK here, only for media change

                            Swal.fire({
                                icon: "success",
                                title: "✅ File Uploaded",
                                text: `${data.filename}`,
                                timer: 1400,
                                showConfirmButton: false,
                            });

                            this.pushHistory();
                        } else {
                            Swal.fire("Error", data.message || "Upload failed.", "error");
                        }
                    } catch (err) {
                        console.error('handleFileUpload failed:', err);
                        Swal.fire("Error", "An error occurred during upload.", "error");
                    } finally {
                        // Clear the file input so the same file can be re-uploaded if needed
                        e.target.value = null;
                    }
                },
                // table handle
                async handleTableUpload(e, block, rowIndex, colIndex) {
    try {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append("file", file);

        Swal.fire({
            title: 'Uploading...',
            text: 'Please wait.',
            didOpen: () => { Swal.showLoading() },
            allowOutsideClick: false
        });

        // 3. Server Request (AJAX)
        // Note: Yahan wahi route use karein jo handleFileUpload me hai
        const res = await fetch('{{ route('admin.pagebuilder.builder.upload', $page) }}', {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData,
        });

        const data = await res.json();

        // 4. Success Handling - Update Specific Cell
        if (data.success) {
            // Check karein agar cell abhi string hai ya object
            let currentCell = block.data[rowIndex][colIndex];

            // Agar purana data sirf text tha, usse object banao
            if (typeof currentCell !== 'object') {
                currentCell = { text: currentCell, img: '' };
            }

            // Image URL set karein
            currentCell.img = data.url;

            // Wapis data array me assign karein (Update)
            block.data[rowIndex][colIndex] = currentCell;

            Swal.fire({
                icon: "success",
                title: "✅ Photo Added",
                timer: 1000,
                showConfirmButton: false,
            });

            // UI Refresh Trigger
            this.blocks = [...this.blocks];
            this.pushHistory();

        } else {
            Swal.fire("Error", data.message || "Upload failed.", "error");
        }

    } catch (err) {
        console.error('handleTableUpload failed:', err);
        Swal.fire("Error", "An error occurred.", "error");
    } finally {
        e.target.value = null;
    }
},
                removeMedia(blockId) {
                    try {
                        const update = (arr) => {
                            for (let b of arr) {
                                if (b.id === blockId) {
                                    delete b.src;
                                    return true;
                                }
                                if (b.type === 'section' && Array.isArray(b.blocks)) {
                                    if (update(b.blocks)) return true;
                                }
                            }
                            return false;
                        };
                        update(this.blocks);
                        this.blocks = [...this.blocks]; // OK here
                        this.pushHistory();
                    } catch (e) {
                        console.error('removeMedia failed:', e);
                    }
                },
                removeMediaFromSub(section, subId) {
                    try {
                        const idx = (section.blocks || []).findIndex(s => s.id === subId);
                        if (idx !== -1) {
                            delete section.blocks[idx].src;
                            this.blocks = [...this.blocks]; // OK here
                            this.pushHistory();
                        }
                    } catch (e) {
                        console.error('removeMediaFromSub failed:', e);
                    }
                },
                getMediaStyle(block) {
                    // This function seems unused for root blocks, but left for completeness
                    try {
                        const w = block.width || 600;
                        const h = block.height || 300;
                        return `max-width: 100%; width:${w}px; height:${h}px; object-fit:contain;`;
                    } catch (e) {
                        console.error('getMediaStyle failed:', e);
                        return 'max-width: 100%;';
                    }
                },
                savePage() {
                    try {
                        // Sync all Quill content *before* saving
                        Object.keys(this.quills).forEach(id => {
                            const q = this.quills[id];
                            if (q) this.updateQuillContent(id, q.root.innerHTML, q
                                .getContents());
                        });

                        const payload = {
                            blocks: this.blocks,
                            sidebarMode: this.sidebarMode,
                            sidebarItems: this.sidebarItems,
                            inheritedPageId: this.inheritedPageId
                        };
                        document.getElementById('pageContent').value = JSON.stringify(payload);

                        // Show saving indicator
                        Swal.fire({
                            title: '💾 Saving...',
                            text: 'Please wait.',
                            didOpen: () => { Swal.showLoading() },
                            allowOutsideClick: false
                        });

                        // Use fetch to submit the form for better UX
                        const form = document.getElementById('saveForm');
                        fetch(form.action, {
                            method: form.method,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams(new FormData(form))
                        })
                            .then(res => {
                                if (!res.ok) {
                                    // If server returns an error, show it
                                    return res.json().then(err => { throw new Error(err.message || 'Save failed') });
                                }
                                return res.json();
                            })
                            .then(data => {
                                // Check for a success flag from the controller
                                if (data.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "✅ Page Saved!",
                                        timer: 1500,
                                        showConfirmButton: false,
                                    });
                                    // Reset history stacks as the current state is now saved
                                    this.historyStack = [JSON.stringify(this.blocks)];
                                    this.redoStack = [];
                                } else {
                                    throw new Error(data.message || 'Save operation failed.');
                                }
                            })
                            .catch(e => {
                                console.error('savePage failed:', e);
                                Swal.fire('Error', e.message || 'Failed to save the page.', 'error');
                            });

                        // Original submit logic (commented out in favor of fetch)
                        // document.getElementById('saveForm').submit();

                    } catch (e) {
                        console.error('savePage failed:', e);
                        Swal.fire('Error', 'Failed to prepare for saving.', 'error');
                    }
                },
                exportJSON() {
                    try {
                        // Sync all Quill content *before* exporting
                        Object.keys(this.quills).forEach(id => {
                            const q = this.quills[id];
                            if (q) this.updateQuillContent(id, q.root.innerHTML, q
                                .getContents());
                        });

                        const payload = {
                            blocks: this.blocks,
                            sidebarMode: this.sidebarMode,
                            sidebarItems: this.sidebarItems
                        };
                        const blob = new Blob([JSON.stringify(payload, null, 2)], {
                            type: 'application/json'
                        });
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = '{{ \Illuminate\Support\Str::slug($page->title ?: 'page') }}-layout.json';
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        URL.revokeObjectURL(url);
                    } catch (e) {
                        console.error('exportJSON failed:', e);
                        Swal.fire('Error', 'Failed to export JSON.', 'error');
                    }
                },
                importJSONPrompt() {
                    try {
                        Swal.fire({
                            title: 'Import JSON',
                            html: `<input type="file" id="jsonFile" accept="application/json" class="swal2-file" style="width: 100%;">`,
                            showCancelButton: true,
                            preConfirm: () => {
                                return new Promise((resolve) => {
                                    const f = document.getElementById('jsonFile')
                                        .files[0];
                                    if (!f) return resolve(null);
                                    const reader = new FileReader();
                                    reader.onload = () => resolve(reader.result);
                                    reader.readAsText(f);
                                });
                            }
                        }).then(res => {
                            if (res.isConfirmed && res.value) {
                                try {
                                    const parsed = JSON.parse(res.value);
                                    if (parsed.blocks) {
                                        this.blocks = parsed.blocks.map(b => ({
                                            ...b,
                                            id: b.id || this._genId()
                                        }));
                                    } else {
                                        this.blocks = Array.isArray(parsed) ? parsed.map(b => ({
                                            ...b,
                                            id: b.id || this._genId()
                                        })) : [];
                                    }
                                    this.$nextTick(() => {
                                        this.quills = {}; // Clear old instances
                                        this.initAllQuills(); // Re-init all
                                        this.initSortables();
                                        this.pushHistory();
                                    });
                                    Swal.fire('Imported', 'JSON imported successfully',
                                        'success');
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid JSON', 'error');
                                }
                            }
                        });
                    } catch (e) {
                        console.error('importJSONPrompt failed:', e);
                    }
                },
                pushHistory() {
                    try {
                        // Sync all Quill content *before* making snapshot
                        Object.keys(this.quills).forEach(id => {
                            const q = this.quills[id];
                            if (q) this.updateQuillContent(id, q.root.innerHTML, q
                                .getContents());
                        });

                        const snapshot = JSON.stringify(this.blocks);
                        if (this.historyStack.length > 0 && this.historyStack[this.historyStack.length -
                            1] === snapshot) return;

                        this.historyStack.push(snapshot);
                        if (this.historyStack.length > 50) this.historyStack.shift();
                        this.redoStack = [];
                    } catch (e) {
                        console.error('pushHistory failed:', e);
                    }
                },

                undo() {
                    try {
                        if (this.historyStack.length <= 1) return Swal.fire('Nothing to undo', '', 'info');
                        const cur = this.historyStack.pop();
                        this.redoStack.push(cur);

                        const prev = this.historyStack[this.historyStack.length - 1];
                        if (prev) {
                            try {
                                this.blocks = JSON.parse(prev);
                            } catch (e) { }

                            this.quills = {}; // Clear old instances
                            this.$nextTick(() => {
                                this.initAllQuills(); // Re-init all
                                this.initSortables();
                            });
                        }
                    } catch (e) {
                        console.error('undo failed:', e);
                    }
                },
                redo() {
                    try {
                        if (!this.redoStack.length) return Swal.fire('Nothing to redo', '', 'info');
                        const next = this.redoStack.pop();
                        try {
                            this.blocks = JSON.parse(next);
                        } catch (e) { }
                        this.historyStack.push(next);

                        this.quills = {}; // Clear old instances
                        this.$nextTick(() => {
                            this.initAllQuills(); // Re-init all
                            this.initSortables();
                        });
                    } catch (e) {
                        console.error('redo failed:', e);
                    }
                },
            };
        }
    </script>


    <style>
        /* 1. Base Quill Editor Styles */
        .ql-editor {
            min-height: 120px;
            font-size: 1rem;
        }

        .quill-editor {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
        }

        /* 2. Responsive Quill Toolbar */
        .ql-toolbar.ql-snow {
            border-radius: 0.375rem 0.375rem 0 0;
            border-bottom: 0;
            padding: 4px;
            /* Tighter padding for mobile */
        }

        .ql-toolbar.ql-snow .ql-formats {
            margin-right: 8px;
            /* Tighter margin */
        }

        .ql-snow .ql-picker-label {
            font-size: 12px;
            /* Smaller picker labels */
        }

        .ql-snow .ql-picker {
            margin-top: 2px;
        }

        .ql-snow .ql-stroke {
            stroke-width: 1.5px;
        }

        /* 3. SweetAlert Mobile Fixes */
        .swal2-input,
        .swal2-select,
        .swal2-file {
            width: 100% !important;
            /* Force full width */
            box-sizing: border-box;
            /* Ensure padding is included */
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .swal2-popup {
            width: 90vw !important;
            /* Use viewport width */
            max-width: 480px;
            /* Set a reasonable max-width */
        }
    </style>
@endsection
