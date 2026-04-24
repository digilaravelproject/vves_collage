<div class="block-container min-h-[50px] space-y-4" :data-sortable-container="`{{ $parentPath }}`">

    {{-- Loop through blocks (Level 1) --}}
    <template x-for="(block, blockIndex) in {{ $blocks }}" :key="block.id">
        <div class="relative p-4 transition border rounded-lg bg-gray-50 hover:shadow-md group" :data-id="block.id">

            {{-- 🧰 Block Controls (Level 1) --}}
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-700 cursor-grab"
                        x-text="availableBlocks.find(b => b.type === block.type)?.label || block.type"></span>
                </div>

                <div class="flex flex-wrap items-center gap-2 max-sm:w-full max-sm:justify-end">
                    {{-- Yeh buttons ab 'setup.blade.php' mein define kiye gaye functions ko call karenge --}}
                    <button @click="moveBlockUp('{{ $parentPath }}', blockIndex)" :disabled="blockIndex === 0"
                        class="px-2 py-1 text-sm bg-white border rounded disabled:opacity-50">↑</button>

                    <button @click="moveBlockDown('{{ $parentPath }}', blockIndex)"
                        :disabled="blockIndex === {{ $blocks }}.length - 1"
                        class="px-2 py-1 text-sm bg-white border rounded disabled:opacity-50">↓</button>

                    <button @click="duplicateBlock('{{ $parentPath }}', blockIndex)"
                        class="px-2 py-1 text-sm bg-white border rounded">⧉</button>

                    <button @click="confirmRemove('{{ $parentPath }}', blockIndex)"
                        class="px-2 py-1 text-sm text-red-600 bg-white border rounded">✖</button>
                </div>
            </div>

            <hr class="mb-4">

            {{-- =================================== --}}
            {{-- 🧩 BLOCK-SPECIFIC SETTINGS (Level 1) --}}
            {{-- =================================== --}}

            {{-- ⭐️ ENHANCED: 'intro' block --}}
            <template x-if="block.type === 'intro'">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Layout</label>
                            <select x-model="block.layout" @change="pushHistoryDebounced"
                                class="relative z-10 w-full p-2 border rounded cursor-text">
                                <option value="left">Image Left</option>
                                <option value="right">Image Right</option>
                                <option value="top">Image Top</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-600">Image</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="block.image" @input="pushHistoryDebounced"
                                    class="relative z-10 flex-1 p-2 border rounded cursor-text"
                                    placeholder="https://...">
                                <button type="button" @click="$refs.introFile.click()"
                                    class="px-3 bg-blue-50 text-blue-600 border border-blue-200 rounded hover:bg-blue-100 transition-colors">
                                    <i class="bi bi-upload"></i>
                                </button>
                                <input type="file" x-ref="introFile" class="hidden" @change="
                                    const file = $event.target.files[0];
                                    if(file){
                                        const formData = new FormData();
                                        formData.append('file', file);
                                        fetch('{{ route('admin.homepage.upload') }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                            body: formData
                                        }).then(r => r.json()).then(data => {
                                            if(data.success) { block.image = data.url; pushHistory(); }
                                            else { alert('Upload failed'); }
                                        }).catch(e => alert('Upload error'));
                                    }
                                ">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Heading</label>
                            <input type="text" x-model="block.heading" @input="pushHistoryDebounced"
                                class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="Block Heading">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Content</label>
                            <textarea x-model="block.text" @input="pushHistoryDebounced"
                                class="relative z-10 w-full p-2 border rounded cursor-text">
                            </textarea>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Button Text</label>
                            <input type="text" x-model="block.buttonText" @input="pushHistoryDebounced"
                                class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="Learn More">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Button Link</label>
                            <input type="text" x-model="block.buttonLink" @input="pushHistoryDebounced"
                                class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="/about-us">
                        </div>
                    </div>
                </div>
            </template>

            {{-- Gallery Block Settings --}}
            <template x-if="block.type === 'social-connects'">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>
                </div>
            </template>
            {{-- ⭐️ ENHANCED: 'sectionLinks' block --}}
            <template x-if="block.type === 'sectionLinks'">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Title</label>
                        <input type="text" x-model="block.title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="Section Title">
                    </div>

                    {{-- NEW: Repeater for links --}}
                    <div class="p-3 space-y-3 border rounded bg-gray-100/50">
                        <label class="text-sm font-medium text-gray-600">Links</label>
                        <template x-for="(link, linkIndex) in block.links" :key="linkIndex">
                            <div class="grid grid-cols-1 gap-2 p-2 bg-white border rounded sm:grid-cols-2 sm:gap-4">
                                <input type="text" x-model="link.text" @input="pushHistoryDebounced"
                                    class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                    placeholder="Link Text">
                                <div class="flex gap-2">
                                    <input type="text" x-model="link.url" @input="pushHistoryDebounced"
                                        class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                        placeholder="Link URL (e.g., /page)">
                                    <button @click="block.links.splice(linkIndex, 1); pushHistory();"
                                        class="px-2 text-red-500 bg-white border rounded hover:bg-red-50">✖</button>
                                </div>
                            </div>
                        </template>
                        <button @click="block.links.push({ text: 'New Link', url: '#' }); pushHistory();"
                            class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                            + Add Link
                        </button>
                    </div>
                </div>
            </template>

            {{-- 'latestUpdates' block --}}
            {{-- <template x-if="block.type === 'latestUpdates'">
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Title</label>
                        <input type="text" x-model="block.title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="Latest Updates">
                    </div>
                </div>
            </template> --}}

            {{-- Events Block Settings --}}
            <template x-if="block.type === 'events'">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Description</label>
                        <textarea x-model="block.section_description" @input="pushHistoryDebounced" rows="3"
                            class="relative z-10 w-full p-2 border rounded cursor-text"></textarea>
                    </div>
                </div>
            </template>
            {{-- Academic Calendar Block Settings --}}
            <template x-if="block.type === 'academic_calendar'">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Item Count</label>
                        <input type="number" min="1" x-model.number="block.item_count" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>
                </div>
            </template>
            {{-- Testimonials Block Settings --}}
            <template x-if="block.type === 'testimonials'">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Description</label>
                        <textarea x-model="block.section_description" @input="pushHistoryDebounced" rows="3"
                            class="relative z-10 w-full p-2 border rounded cursor-text"></textarea>
                    </div>
                </div>
            </template>

            {{-- Instagram Profiles Block Settings --}}
            <template x-if="block.type === 'instagram_profiles'">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>

                    {{-- Repeater for Instagram Profiles --}}
                    <div
                        class="p-4 space-y-3 border-2 border-dashed border-pink-200 rounded-lg bg-linear-to-br from-pink-50/50 to-purple-50/50">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">📸</span>
                            <label class="text-sm font-bold text-gray-700">Instagram Accounts</label>
                            <span class="ml-auto text-xs text-gray-400 bg-white px-2 py-0.5 rounded-full border"
                                x-text="block.profiles.length + ' account(s)'"></span>
                        </div>

                        <template x-for="(profile, idx) in block.profiles" :key="idx">
                            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm space-y-3 relative">
                                {{-- Card number badge --}}
                                <span
                                    class="absolute -top-2 -left-2 w-6 h-6 bg-pink-500 text-white text-xs font-bold rounded-full flex items-center justify-center shadow"
                                    x-text="idx + 1"></span>

                                <div>
                                    <label class="text-xs text-gray-500 font-semibold">Display Name</label>
                                    <input type="text"
                                        x-model="block.columns[colIndex].blocks[childIndex].profiles[idx].name"
                                        @input="pushHistoryDebounced"
                                        class="w-full p-2 text-sm border border-gray-200 rounded-lg focus:border-pink-400 focus:ring-1 focus:ring-pink-200 outline-none transition"
                                        placeholder="e.g., VVES Official">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 font-semibold">Instagram Link / URL</label>
                                    <input type="text"
                                        x-model="block.columns[colIndex].blocks[childIndex].profiles[idx].link"
                                        @input="pushHistoryDebounced"
                                        class="w-full p-2 text-sm border border-gray-200 rounded-lg focus:border-pink-400 focus:ring-1 focus:ring-pink-200 outline-none transition"
                                        placeholder="https://instagram.com/username">
                                    <p class="text-xs text-gray-400 mt-1">
                                        Preview: <span class="text-pink-600 font-semibold" x-text="(() => {
                                            let l = (profile.link || '').replace(/\/+$/, '').trim();
                                            if(l.includes('instagram.com')) { let p = l.split('instagram.com/')[1] || ''; return '@' + p.split('/')[0].split('?')[0]; }
                                            if(!l.includes('/') && !l.includes('.')) return '@' + l.replace(/^@/,'');
                                            return '—';
                                        })()"></span>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 font-semibold">Profile Photo / Logo</label>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="profile.image" @input="pushHistoryDebounced"
                                            class="flex-1 p-2 text-sm border border-gray-200 rounded-lg focus:border-pink-400 focus:ring-1 focus:ring-pink-200 outline-none transition"
                                            placeholder="https://...">
                                        <button type="button" @click="$refs.instaFile.click()"
                                            class="px-3 bg-pink-50 text-pink-600 border border-pink-200 rounded-lg hover:bg-pink-100 transition-colors">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                        <input type="file" x-ref="instaFile" class="hidden" @change="
                                            const file = $event.target.files[0];
                                            if(file){
                                                const formData = new FormData();
                                                formData.append('file', file);
                                                fetch('{{ route('admin.homepage.upload') }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                    body: formData
                                                }).then(r => r.json()).then(data => {
                                                    if(data.success) { profile.image = data.url; pushHistory(); }
                                                    else { alert('Upload failed'); }
                                                }).catch(e => alert('Upload error'));
                                            }
                                        ">
                                    </div>
                                </div>
                                <div class="flex justify-end pt-1">
                                    <button @click="block.profiles.splice(idx, 1); pushHistory();"
                                        class="text-xs text-red-500 font-semibold px-3 py-1 hover:bg-red-50 rounded-lg transition">✖
                                        Remove</button>
                                </div>
                            </div>
                        </template>

                        <button @click="block.profiles.push({ name: '', link: '', image: '' }); pushHistory();"
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-linear-to-r from-pink-500 to-purple-600 rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all shadow-sm flex items-center justify-center gap-2">
                            <span>+</span> Add Instagram Account
                        </button>
                    </div>
                </div>
            </template>
            {{-- Instagram Feed (Embeds) --}}
            <template x-if="block.type === 'instagram_feed'">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text"
                            placeholder="e.g., Our Instagram Feed">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Section Description</label>
                        <textarea x-model="block.section_description" @input="pushHistoryDebounced" rows="2"
                            class="relative z-10 w-full p-2 border rounded cursor-text"
                            placeholder="Optional description..."></textarea>
                    </div>
                    <div class="p-2 px-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-3">
                        <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
                        <p class="text-xs text-blue-700">
                            This block automatically pulls active posts from the <strong>Instagram Feed</strong>
                            management page.
                            <a href="/admin/instagram-feeds" target="_blank" class="font-bold underline ml-1">Manage
                                Posts →</a>
                        </p>
                    </div>
                </div>
            </template>
            {{-- Why Choose Us Block Settings --}}
            <template x-if="block.type === 'why_choose_us'">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Section Title</label>
                        <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                            class="relative z-10 w-full p-2 border rounded cursor-text">
                    </div>
                    <label class="text-sm font-medium text-gray-600">Section Description</label>
                    <textarea x-model="block.section_description" @input="pushHistoryDebounced" rows="3"
                        class="relative z-10 w-full p-2 border rounded cursor-text"></textarea>
                </div>
        </div>
    </template>

    {{-- Image Grid Block Settings --}}
    <template x-if="block.type === 'image_grid'">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Section Title</label>
                    <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                        class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="e.g., Our Features">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Columns (Desktop)</label>
                    <select x-model.number="block.columns_count" @change="pushHistoryDebounced"
                        class="relative z-10 w-full p-2 border rounded cursor-text">
                        <option value="1">1 Column</option>
                        <option value="2">2 Columns</option>
                        <option value="3">3 Columns</option>
                        <option value="4">4 Columns</option>
                    </select>
                </div>
            </div>

            <div class="p-3 space-y-3 border rounded bg-gray-100/50">
                <div class="flex items-center justify-between">
                    <label class="text-sm text-gray-600 font-bold">Grid Items</label>
                    <button
                        @click="block.items.push({ title: '', caption: '', image: '', button_text: '', button_url: '#' }); pushHistory();"
                        class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                        + Add Item
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <template x-for="(item, idx) in block.items" :key="idx">
                        <div class="p-4 bg-white border rounded shadow-sm relative space-y-3">
                            <button @click="block.items.splice(idx, 1); pushHistory();"
                                class="absolute top-2 right-2 text-red-500 hover:text-red-700">✖</button>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-3">
                                    <div class="relative aspect-video bg-gray-100 rounded border border-dashed flex items-center justify-center overflow-hidden cursor-pointer group/img"
                                        @click="$refs.gridItemFile.click()">
                                        <template x-if="item.image">
                                            <img :src="item.image" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="text-center">
                                                <i class="bi bi-image text-2xl text-gray-300"></i>
                                                <p class="text-[10px] text-gray-400">Upload Image</p>
                                            </div>
                                        </template>
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity">
                                            <i class="bi bi-camera text-white"></i>
                                        </div>
                                        <input type="file" x-ref="gridItemFile" class="hidden" @change="
                                                    const file = $event.target.files[0];
                                                    if(file){
                                                        const formData = new FormData();
                                                        formData.append('file', file);
                                                        fetch('{{ route('admin.homepage.upload') }}', {
                                                            method: 'POST',
                                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                            body: formData
                                                        }).then(r => r.json()).then(data => {
                                                            if(data.success) { item.image = data.url; pushHistory(); }
                                                            else { alert('Upload failed'); }
                                                        }).catch(e => alert('Upload error'));
                                                    }
                                                ">
                                    </div>
                                </div>
                                <div class="md:col-span-9 space-y-2">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <input type="text" x-model="item.title" @input="pushHistoryDebounced"
                                            class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                            placeholder="Title">
                                        <input type="text" x-model="item.button_text" @input="pushHistoryDebounced"
                                            class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                            placeholder="Button Text">
                                    </div>
                                    <textarea x-model="item.caption" @input="pushHistoryDebounced" rows="1"
                                        class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                        placeholder="Caption/Description"></textarea>
                                    <input type="text" x-model="item.button_url" @input="pushHistoryDebounced"
                                        class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                        placeholder="Button Link (URL)">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>

    {{-- Institutions Block Settings --}}
    <template x-if="block.type === 'institutions'">
        <div class="grid grid-cols-1 gap-3">
            <div>
                <label class="text-sm font-medium text-gray-600">Section Title (Optional)</label>
                <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                    class="relative z-10 w-full p-2 border rounded cursor-text"
                    placeholder="e.g., Our Educational Centers">
            </div>
        </div>
    </template>

    {{-- Board of Advisors Block Settings (Level 1) --}}
    <template x-if="block.type === 'board_of_advisors'">
        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Section Title</label>
                <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                    class="relative z-10 w-full p-2 border rounded cursor-text">
            </div>
            {{-- Repeater for Advisors --}}
            <div class="p-3 space-y-3 border rounded bg-gray-100/50">
                <label class="text-sm font-medium text-gray-600">Advisors / Directors</label>
                <template x-for="(item, idx) in block.items" :key="idx">
                    <div class="grid grid-cols-1 gap-4 p-4 mb-2 bg-white border rounded group relative">
                        <button @click="block.items.splice(idx, 1); pushHistory();"
                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">✖</button>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            {{-- Image Preview/Upload --}}
                            <div class="relative aspect-square bg-gray-100 rounded border border-dashed flex items-center justify-center overflow-hidden group/img-upload cursor-pointer"
                                @click="$refs.advisorFile.click()">
                                <template x-if="item.photo">
                                    <img :src="item.photo"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover/img-upload:scale-105">
                                </template>
                                <template x-if="!item.photo">
                                    <div class="flex flex-col items-center gap-1">
                                        <i class="bi bi-person-bounding-box text-2xl text-gray-300"></i>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Click to
                                            Upload</span>
                                    </div>
                                </template>

                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover/img-upload:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="bi bi-camera-fill text-white text-xl"></i>
                                </div>

                                <input type="file" x-ref="advisorFile" class="hidden" @change="
                                                    const file = $event.target.files[0];
                                                    if(file){
                                                        const formData = new FormData();
                                                        formData.append('file', file);
                                                        fetch('{{ route('admin.homepage.upload') }}', {
                                                            method: 'POST',
                                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                            body: formData
                                                        }).then(r => r.json()).then(data => {
                                                            if(data.success) {
                                                                item.photo = data.url;
                                                                pushHistory();
                                                            } else {
                                                                alert('Upload failed: ' + (data.message || 'Unknown error'));
                                                            }
                                                        }).catch(e => {
                                                            console.error(e);
                                                            alert('Upload error. Check console.');
                                                        });
                                                    }
                                                ">
                            </div>
                            <div class="md:col-span-9 space-y-2">
                                <input type="text" x-model="item.name" @input="pushHistoryDebounced"
                                    class="w-full p-2 text-sm border rounded focus:border-blue-400 outline-none"
                                    placeholder="Name">
                                <textarea x-model="item.description" @input="pushHistoryDebounced" rows="2"
                                    class="w-full p-2 text-sm border rounded focus:border-blue-400 outline-none"
                                    placeholder="Short Bio"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
                <button @click="block.items.push({ name: 'New Advisor', photo: '', description: '' }); pushHistory();"
                    class="w-full py-2 text-sm text-blue-600 border-2 border-dashed border-blue-200 rounded hover:bg-blue-50 transition-colors">
                    + Add Member
                </button>
            </div>
        </div>
    </template>

    {{-- 'divider' block --}}
    <template x-if="block.type === 'divider'">
        <hr class="my-4 border-gray-300 border-dashed">
    </template>
    <template x-if="block.type === 'announcements'">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-gray-600">Section Title</label>
                <input type="text" x-model="block.section_title" @input="pushHistoryDebounced"
                    class="relative z-10 w-full p-2 border rounded cursor-text">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Display Count</label>
                <input type="number" min="1" x-model.number="block.display_count" @input="pushHistoryDebounced"
                    class="relative z-10 w-full p-2 border rounded cursor-text">
            </div>
        </div>
    </template>

    {{-- =================================== --}}
    {{-- ⭐️ 'layout_grid' BLOCK (Level 1) ⭐️ --}}
    {{-- =================================== --}}
    <template x-if="block.type === 'layout_grid'">
        <div class="space-y-4">
            {{-- 1️⃣ Grid Layout Selector --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Title</label>
                <input type="text" x-model="block.title" @input="pushHistoryDebounced"
                    class="relative z-10 w-full p-2 border rounded cursor-text" placeholder="Layout Title">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Grid Layout</label>
                <select x-model="block.layout" @change="changeGridLayout(block)"
                    class="w-full p-2 bg-white border rounded">
                    <optgroup label="Equal Columns">
                        <option value="12">1 Column (100%)</option>
                        <option value="6-6">2 Columns (50% / 50%)</option>
                        <option value="4-4-4">3 Columns (33% * 3)</option>
                        <option value="3-3-3-3">4 Columns (25% * 4)</option>
                        <option value="2.4-2.4-2.4-2.4-2.4">5 Columns (20% * 5)</option>
                        <option value="2-2-2-2-2-2">6 Columns (16% * 6)</option>
                    </optgroup>
                    <optgroup label="Asymmetrical (Horizontal)">
                        <option value="4-8">2 Columns (33% / 66%)</option>
                        <option value="8-4">2 Columns (66% / 33%)</option>
                        <option value="3-9">2 Columns (25% / 75%)</option>
                        <option value="9-3">2 Columns (75% / 25%)</option>
                    </optgroup>
                </select>
            </div>

            {{-- 2️⃣ Recursive Column Rendering (Level 2) --}}
            <div class="flex flex-wrap -mx-2 gap-y-4 pt-2">
                <template x-for="(col, colIndex) in block.columns" :key="colIndex">
                    <div :class="['px-2 w-full', col.span == 12 ? '' : (col.span == 6 ? 'lg:w-1/2' : (col.span == 4 ?
                            'lg:w-1/3' : (col.span == 8 ? 'lg:w-2/3' : (col.span == 3 ? 'lg:w-1/4' : (col
                                .span == 9 ? 'lg:w-3/4' : (col.span == 2.4 ? 'lg:w-1/5' : (col.span ==
                                    2 ? 'lg:w-1/6' : (col.span == 10 ? 'lg:w-5/6' : ''))))))))]">
                        <div
                            class="p-4 border border-blue-400 border-dashed rounded-lg bg-blue-50/50 h-full flex flex-col">
                            <span class="block mb-2 text-xs font-medium text-blue-700"
                                x-text="`Column ${colIndex + 1} (${col.span === 2.4 ? '1/5' : col.span + '/12'})`"></span>

                            {{-- =================================================================== --}}
                            {{-- ✅ LEVEL 3: RECURSIVE DROP AREA (Column Content) --}}
                            {{-- =================================================================== --}}


                            <div class="block-container min-h-[100px] pb-10 space-y-4 flex-1"
                                :data-sortable-container="`{{ $parentPath }}[${blockIndex}].columns[${colIndex}].blocks`">

                                <template x-if="!col.blocks || col.blocks.length === 0">
                                    <div class="flex flex-col items-center justify-center h-full py-4 text-center">
                                        <span class="text-xs text-blue-300 font-medium">Empty Drop Zone</span>
                                    </div>
                                </template>

                                {{-- Loop through blocks (Level 3) --}}
                                <template x-for="(childBlock, childIndex) in col.blocks" :key="childBlock.id">
                                    <div class="relative p-4 transition border rounded-lg bg-gray-50 hover:shadow-md group"
                                        :data-id="childBlock.id">
                                        <div
                                            class="absolute inset-0 bg-linear-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-10 pointer-events-none transition-opacity">
                                        </div>

                                        {{-- 🧰 Block Controls (Level 3) --}}
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-700 cursor-grab"
                                                    x-text="availableBlocks.find(b => b.type === childBlock.type)?.label || childBlock.type"></span>
                                            </div>

                                            <div
                                                class="flex flex-wrap items-center gap-2 max-sm:w-full max-sm:justify-end">
                                                <button
                                                    @click="moveBlockUp(`{{ $parentPath }}[${blockIndex}].columns[${colIndex}].blocks`, childIndex)"
                                                    :disabled="childIndex === 0"
                                                    class="px-2 py-1 text-sm bg-white border rounded disabled:opacity-50">↑</button>

                                                <button
                                                    @click="moveBlockDown(`{{ $parentPath }}[${blockIndex}].columns[${colIndex}].blocks`, childIndex)"
                                                    :disabled="childIndex === col.blocks.length - 1"
                                                    class="px-2 py-1 text-sm bg-white border rounded disabled:opacity-50">↓</button>

                                                <button
                                                    @click="duplicateBlock(`{{ $parentPath }}[${blockIndex}].columns[${colIndex}].blocks`, childIndex)"
                                                    class="px-2 py-1 text-sm bg-white border rounded">⧉</button>

                                                <button
                                                    @click="confirmRemove(`{{ $parentPath }}[${blockIndex}].columns[${colIndex}].blocks`, childIndex)"
                                                    class="px-2 py-1 text-sm text-red-600 bg-white border rounded">✖</button>
                                            </div>
                                        </div>

                                        <hr class="mb-4">

                                        {{-- =================================== --}}
                                        {{-- 🧩 BLOCK-SPECIFIC SETTINGS (Level 3) --}}
                                        {{-- =================================== --}}

                                        {{-- ⭐️ ENHANCED: 'intro' block --}}

                                        <template x-if="childBlock.type === 'intro'">
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                    <div>
                                                        <label class="text-sm font-medium text-gray-600">Layout</label>
                                                        <select
                                                            x-model="block.columns[colIndex].blocks[childIndex].layout"
                                                            @change="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 border rounded cursor-text">
                                                            <option value="left">Image Left</option>
                                                            <option value="right">Image Right</option>
                                                            <option value="top">Image Top</option>
                                                        </select>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="text-sm font-medium text-gray-600">Image</label>
                                                        <div class="flex gap-2">
                                                            <input type="text" x-model="childBlock.image"
                                                                @input="pushHistoryDebounced"
                                                                class="relative z-10 flex-1 p-2 border rounded cursor-text"
                                                                placeholder="https://...">
                                                            <button type="button" @click="$refs.introFileChild.click()"
                                                                class="px-3 bg-blue-50 text-blue-600 border border-blue-200 rounded hover:bg-blue-100 transition-colors">
                                                                <i class="bi bi-upload"></i>
                                                            </button>
                                                            <input type="file" x-ref="introFileChild" class="hidden"
                                                                @change="
                                                                         const file = $event.target.files[0];
                                                                         if(file){
                                                                             const formData = new FormData();
                                                                             formData.append('file', file);
                                                                             fetch('{{ route('admin.homepage.upload') }}', {
                                                                                 method: 'POST',
                                                                                 headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                                                 body: formData
                                                                             }).then(r => r.json()).then(data => {
                                                                                 if(data.success) { childBlock.image = data.url; pushHistory(); }
                                                                                 else { alert('Upload failed'); }
                                                                             }).catch(e => alert('Upload error'));
                                                                         }
                                                                     ">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="text-sm font-medium text-gray-600">Heading</label>
                                                        <input type="text"
                                                            x-model="block.columns[colIndex].blocks[childIndex].heading"
                                                            @input="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 border rounded cursor-text"
                                                            placeholder="Block Heading">
                                                    </div>
                                                    <div>
                                                        <label class="text-sm font-medium text-gray-600">Content</label>
                                                        <textarea
                                                            x-model="block.columns[colIndex].blocks[childIndex].text"
                                                            @input="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 border rounded cursor-text">
                                                                    </textarea>
                                                    </div>
                                                    <div>
                                                        <label class="text-sm font-medium text-gray-600">Button
                                                            Text</label>
                                                        <input type="text"
                                                            x-model="block.columns[colIndex].blocks[childIndex].buttonText"
                                                            @input="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 border rounded cursor-text"
                                                            placeholder="Learn More">
                                                    </div>
                                                    <div>
                                                        <label class="text-sm font-medium text-gray-600">Button
                                                            Link</label>
                                                        <input type="text"
                                                            x-model="block.columns[colIndex].blocks[childIndex].buttonLink"
                                                            @input="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 border rounded cursor-text"
                                                            placeholder="/about-us">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        {{-- ⭐️ ENHANCED: 'Gallery Block Settings' block --}}
                                        <template x-if="childBlock.type === 'gallery'">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="childBlock.type === 'social-connects'">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                            </div>
                                        </template>
                                        {{-- ⭐️ ENHANCED: 'Testimonials Block Settings' --}}
                                        <template x-if="childBlock.type === 'testimonials'">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Description</label>
                                                    <textarea
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_description"
                                                        @input="pushHistoryDebounced" rows="3"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text"></textarea>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- ⭐️ ENHANCED: Instagram Profiles --}}
                                        <template x-if="childBlock.type === 'instagram_profiles'">
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-sm font-semibold text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                                <div
                                                    class="p-3 space-y-3 border-2 border-dashed border-pink-200 rounded-lg bg-linear-to-br from-pink-50/50 to-purple-50/50">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span>📸</span>
                                                        <label class="text-sm font-bold text-gray-700">Accounts</label>
                                                        <span
                                                            class="ml-auto text-xs text-gray-400 bg-white px-2 py-0.5 rounded-full border"
                                                            x-text="childBlock.profiles.length + ' account(s)'"></span>
                                                    </div>
                                                    <template x-for="(profile, idx) in childBlock.profiles" :key="idx">
                                                        <div
                                                            class="p-2 bg-white border border-gray-200 rounded-lg shadow-sm space-y-2 relative">
                                                            <span
                                                                class="absolute -top-2 -left-2 w-5 h-5 bg-pink-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow"
                                                                x-text="idx + 1"></span>
                                                            <input type="text"
                                                                x-model="block.columns[colIndex].blocks[childIndex].profiles[idx].name"
                                                                @input="pushHistoryDebounced"
                                                                class="relative z-10 w-full p-1 text-sm border border-gray-200 rounded-lg cursor-text"
                                                                placeholder="Display Name">
                                                            <input type="text"
                                                                x-model="block.columns[colIndex].blocks[childIndex].profiles[idx].link"
                                                                @input="pushHistoryDebounced"
                                                                class="relative z-10 w-full p-1 text-sm border border-gray-200 rounded-lg cursor-text"
                                                                placeholder="Instagram Link">
                                                            <p class="text-xs text-gray-400">
                                                                Preview: <span class="text-pink-600 font-semibold"
                                                                    x-text="(() => {
                                                                            let l = (profile.link || '').replace(/\/+$/, '').trim();
                                                                            if(l.includes('instagram.com')) { let p = l.split('instagram.com/')[1] || ''; return '@' + p.split('/')[0].split('?')[0]; }
                                                                            if(!l.includes('/') && !l.includes('.')) return '@' + l.replace(/^@/,'');
                                                                            return '—';
                                                                        })()"></span>
                                                            </p>
                                                            <input type="text"
                                                                x-model="block.columns[colIndex].blocks[childIndex].profiles[idx].image"
                                                                @input="pushHistoryDebounced"
                                                                class="relative z-10 w-full p-1 text-sm border border-gray-200 rounded-lg cursor-text"
                                                                placeholder="Photo URL (optional)">
                                                            <div class="flex justify-end">
                                                                <button
                                                                    @click="block.columns[colIndex].blocks[childIndex].profiles.splice(idx, 1); pushHistory();"
                                                                    class="text-[10px] text-red-500 font-semibold hover:bg-red-50 px-2 py-0.5 rounded">✖
                                                                    Remove</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button
                                                        @click="block.columns[colIndex].blocks[childIndex].profiles.push({ name: '', link: '', image: '' }); pushHistory();"
                                                        class="w-full px-3 py-2 text-xs font-semibold text-white bg-linear-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center gap-1">
                                                        + Add Account
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        {{-- Instagram Feed (Inside Grid) --}}
                                        <template x-if="childBlock.type === 'instagram_feed'">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="text-xs font-bold text-gray-600 uppercase">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 text-sm border rounded cursor-text">
                                                </div>
                                                <div
                                                    class="p-2 bg-blue-50 border border-blue-100 rounded text-[10px] text-blue-700 italic">
                                                    Pulls active posts from Instagram Feed manager.
                                                </div>
                                            </div>
                                        </template>
                                        {{-- ⭐️ ENHANCED: '' announcment block --}}
                                        <template x-if="childBlock.type === 'announcements'">
                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Display
                                                        Count</label>
                                                    <input type="number" min="1"
                                                        x-model.number="block.columns[colIndex].blocks[childIndex].display_count"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                            </div>
                                        </template>
                                        {{-- ⭐️ ENHANCED: Academic Calendar Block Settings --}}
                                        <template x-if="childBlock.type === 'academic_calendar'">
                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                <div class="sm:col-span-2">
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Item
                                                        Count</label>
                                                    <input type="number" min="1"
                                                        x-model.number="block.columns[colIndex].blocks[childIndex].item_count"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                            </div>
                                        </template>
                                        {{-- ⭐️ ENHANCED: 'Events' block --}}

                                        <template x-if="childBlock.type === 'events'">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Description</label>
                                                    <textarea
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_description"
                                                        @input="pushHistoryDebounced" rows="3"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text"></textarea>
                                                </div>
                                            </div>
                                        </template>
                                        {{-- ⭐️ ENHANCED: 'sectionLinks' block --}}
                                        <template x-if="childBlock.type === 'sectionLinks'">
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text"
                                                        placeholder="Section Title">
                                                </div>
                                                {{-- NEW: Repeater for links (Level 3) --}}
                                                <div class="p-3 space-y-3 border rounded bg-gray-100/50">
                                                    <label class="text-sm font-medium text-gray-600">Links</label>
                                                    <template
                                                        x-for="(link, linkIndex) in block.columns[colIndex].blocks[childIndex].links"
                                                        :key="linkIndex">
                                                        <div
                                                            class="grid grid-cols-1 gap-2 p-2 bg-white border rounded sm:grid-cols-2 sm:gap-4">
                                                            <input type="text"
                                                                x-model="block.columns[colIndex].blocks[childIndex].links[linkIndex].text"
                                                                @input="pushHistoryDebounced"
                                                                class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                                                placeholder="Link Text">
                                                            <div class="flex gap-2">
                                                                <input type="text"
                                                                    x-model="block.columns[colIndex].blocks[childIndex].links[linkIndex].url"
                                                                    @input="pushHistoryDebounced"
                                                                    class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                                                    placeholder="Link URL (e.g., /page)">
                                                                <button
                                                                    @click="block.columns[colIndex].blocks[childIndex].links.splice(linkIndex, 1); pushHistory();"
                                                                    class="px-2 text-red-500 bg-white border rounded hover:bg-red-50">✖</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button
                                                        @click="block.columns[colIndex].blocks[childIndex].links.push({ text: 'New Link', url: '#' }); pushHistory();"
                                                        class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                                        + Add Link
                                                    </button>
                                                </div>
                                            </div>
                                        </template>


                                        {{-- ⭐️ Board of Advisors Block Settings (Level 3) --}}
                                        <template x-if="childBlock.type === 'board_of_advisors'">
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="text-xs font-bold text-gray-600 uppercase">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 text-sm border rounded cursor-text">
                                                </div>
                                                <div
                                                    class="p-2 space-y-2 border-2 border-dashed border-blue-100 rounded-lg bg-blue-50/30">
                                                    <template x-for="(item, idx) in childBlock.items" :key="idx">
                                                        <div
                                                            class="p-2 bg-white border border-gray-200 rounded shadow-sm relative flex gap-2">
                                                            <button
                                                                @click="block.columns[colIndex].blocks[childIndex].items.splice(idx, 1); pushHistory();"
                                                                class="absolute top-1 right-1 text-red-500 text-xs">✖</button>

                                                            <div class="w-10 h-10 shrink-0 bg-gray-100 border rounded cursor-pointer flex items-center justify-center overflow-hidden group/child-img relative"
                                                                @click="$refs.advisorFileChild.click()">
                                                                <template x-if="item.photo">
                                                                    <img :src="item.photo"
                                                                        class="w-full h-full object-cover">
                                                                </template>
                                                                <template x-if="!item.photo">
                                                                    <i class="bi bi-camera text-gray-400"></i>
                                                                </template>

                                                                <div
                                                                    class="absolute inset-0 bg-black/20 opacity-0 group-hover/child-img:opacity-100 transition-opacity flex items-center justify-center">
                                                                    <i class="bi bi-upload text-white text-xs"></i>
                                                                </div>

                                                                <input type="file" x-ref="advisorFileChild"
                                                                    class="hidden" @change="
                                                                                const file = $event.target.files[0];
                                                                                if(file){
                                                                                    const formData = new FormData();
                                                                                    formData.append('file', file);
                                                                                    fetch('{{ route('admin.homepage.upload') }}', {
                                                                                        method: 'POST',
                                                                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                                                        body: formData
                                                                                    }).then(r => r.json()).then(data => {
                                                                                        if(data.success) {
                                                                                            item.photo = data.url;
                                                                                            pushHistory();
                                                                                        } else {
                                                                                            alert('Upload failed');
                                                                                        }
                                                                                    }).catch(e => alert('Upload error'));
                                                                                }
                                                                            ">
                                                            </div>
                                                            <div class="flex-1 space-y-1">
                                                                <input type="text" x-model="item.name"
                                                                    @input="pushHistoryDebounced"
                                                                    class="w-full p-1 text-[10px] border rounded"
                                                                    placeholder="Name">
                                                                <textarea x-model="item.description"
                                                                    @input="pushHistoryDebounced" rows="1"
                                                                    class="w-full p-1 text-[10px] border rounded"
                                                                    placeholder="Bio"></textarea>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button
                                                        @click="block.columns[colIndex].blocks[childIndex].items.push({ name: '', photo: '', description: '' }); pushHistory();"
                                                        class="w-full py-1 text-[10px] font-bold text-blue-500 border border-blue-200 rounded hover:bg-blue-50">
                                                        + Add Member
                                                    </button>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Institutions Block Settings (Nested) --}}
                                        <template x-if="childBlock.type === 'institutions'">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="text-xs font-bold text-gray-600 uppercase">Section
                                                        Title (Optional)</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 text-sm border rounded cursor-text"
                                                        placeholder="e.g., Our Educational Centers">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Why Choose Us Block Settings --}}
                                        <template x-if="childBlock.type === 'why_choose_us'">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Title</label>
                                                    <input type="text"
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_title"
                                                        @input="pushHistoryDebounced"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text">
                                                </div>
                                                <div>
                                                    <label class="text-sm font-medium text-gray-600">Section
                                                        Description</label>
                                                    <textarea
                                                        x-model="block.columns[colIndex].blocks[childIndex].section_description"
                                                        @input="pushHistoryDebounced" rows="3"
                                                        class="relative z-10 w-full p-2 border rounded cursor-text"></textarea>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Image Grid Block Settings (Nested) --}}
                                        <template x-if="childBlock.type === 'image_grid'">
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="text-xs font-bold text-gray-600 uppercase">Section
                                                            Title</label>
                                                        <input type="text" x-model="childBlock.section_title"
                                                            @input="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 text-sm border rounded cursor-text">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-gray-600 uppercase">Columns
                                                            (Desktop)</label>
                                                        <select x-model.number="childBlock.columns_count"
                                                            @change="pushHistoryDebounced"
                                                            class="relative z-10 w-full p-2 text-sm border rounded cursor-text">
                                                            <option value="1">1 Column</option>
                                                            <option value="2">2 Columns</option>
                                                            <option value="3">3 Columns</option>
                                                            <option value="4">4 Columns</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div
                                                    class="p-2 space-y-2 border-2 border-dashed border-blue-100 rounded-lg bg-blue-50/30">
                                                    <div class="flex items-center justify-between">
                                                        <label
                                                            class="text-[10px] font-bold text-gray-600 uppercase">Grid
                                                            Items</label>
                                                        <button
                                                            @click="childBlock.items.push({ title: '', caption: '', image: '', button_text: '', button_url: '#' }); pushHistory();"
                                                            class="px-2 py-0.5 text-[10px] text-white bg-blue-500 rounded hover:bg-blue-600">
                                                            + Add
                                                        </button>
                                                    </div>
                                                    <template x-for="(item, idx) in childBlock.items" :key="idx">
                                                        <div
                                                            class="p-2 bg-white border border-gray-200 rounded shadow-sm relative space-y-2">
                                                            <button
                                                                @click="childBlock.items.splice(idx, 1); pushHistory();"
                                                                class="absolute top-1 right-1 text-red-500 text-xs">✖</button>

                                                            <div class="flex gap-2">
                                                                <div class="w-16 h-12 shrink-0 bg-gray-100 border rounded cursor-pointer flex items-center justify-center overflow-hidden group/child-item-img relative"
                                                                    @click="$refs.gridItemFileChild.click()">
                                                                    <template x-if="item.image">
                                                                        <img :src="item.image"
                                                                            class="w-full h-full object-cover">
                                                                    </template>
                                                                    <template x-if="!item.image">
                                                                        <i class="bi bi-image text-gray-400"></i>
                                                                    </template>
                                                                    <div
                                                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover/child-item-img:opacity-100 transition-opacity flex items-center justify-center">
                                                                        <i
                                                                            class="bi bi-camera text-white text-[10px]"></i>
                                                                    </div>
                                                                    <input type="file" x-ref="gridItemFileChild"
                                                                        class="hidden" @change="
                                                                                 const file = $event.target.files[0];
                                                                                 if(file){
                                                                                     const formData = new FormData();
                                                                                     formData.append('file', file);
                                                                                     fetch('{{ route('admin.homepage.upload') }}', {
                                                                                         method: 'POST',
                                                                                         headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                                                         body: formData
                                                                                     }).then(r => r.json()).then(data => {
                                                                                         if(data.success) { item.image = data.url; pushHistory(); }
                                                                                         else { alert('Upload failed'); }
                                                                                     }).catch(e => alert('Upload error'));
                                                                                 }
                                                                             ">
                                                                </div>
                                                                <div class="flex-1 space-y-1">
                                                                    <input type="text" x-model="item.title"
                                                                        @input="pushHistoryDebounced"
                                                                        class="w-full p-1 text-[10px] border rounded"
                                                                        placeholder="Title">
                                                                    <input type="text" x-model="item.button_text"
                                                                        @input="pushHistoryDebounced"
                                                                        class="w-full p-1 text-[10px] border rounded"
                                                                        placeholder="Button Text">
                                                                </div>
                                                            </div>
                                                            <textarea x-model="item.caption"
                                                                @input="pushHistoryDebounced" rows="1"
                                                                class="w-full p-1 text-[10px] border rounded"
                                                                placeholder="Caption"></textarea>
                                                            <input type="text" x-model="item.button_url"
                                                                @input="pushHistoryDebounced"
                                                                class="w-full p-1 text-[10px] border rounded"
                                                                placeholder="Button Link">
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 'divider' block --}}
                                        <template x-if="childBlock.type === 'divider'">
                                            <hr class="my-4 border-gray-300 border-dashed">
                                        </template>

                                    </div>
                                </template>
                            </div>
                            {{-- ============================================= --}}
                            {{-- ✅ END OF LEVEL 3 RECURSIVE DROP AREA --}}
                            {{-- ============================================= --}}

                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

</div>
</template>
</div>