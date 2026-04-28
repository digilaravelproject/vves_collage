@php
    $model = $model ?? 'block';
@endphp

<div class="p-4 bg-white border border-gray-200 rounded-xl">
    {{-- Config: Columns --}}
    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Desktop Columns</label>
            <select x-model="{{ $model }}.columns_desktop" 
                    @change="pushHistory()"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option :value="2">2 Columns</option>
                <option :value="3">3 Columns</option>
                <option :value="4">4 Columns</option>
                <option :value="5">5 Columns</option>
                <option :value="6">6 Columns</option>
            </select>
        </div>
        <div class="flex-none pt-5">
            <button @click="addStaffProfile({{ $model }})"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                + Add Profile
            </button>
        </div>
    </div>

    {{-- Profiles Repeater --}}
    <div class="space-y-4">
        <template x-for="(profile, pIndex) in {{ $model }}.profiles" :key="profile.id">
            <div class="group relative p-4 border border-gray-100 rounded-xl bg-gray-50/30 hover:bg-gray-50 hover:border-blue-200 transition-all duration-200">
                
                {{-- Remove Button --}}
                <button @click="removeStaffProfile({{ $model }}, profile.id)"
                        class="absolute -top-2 -right-2 p-1.5 bg-red-100 text-red-600 rounded-full hover:bg-red-200 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm z-10"
                        title="Remove Profile">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                    
                    {{-- Photo Upload --}}
                    <div class="md:col-span-1">
                        <template x-if="profile.photo">
                            <div class="relative group/photo">
                                <img :src="profile.photo" class="w-full aspect-square object-cover rounded-lg border border-gray-200 shadow-sm">
                                <button @click="profile.photo = ''; pushHistory()"
                                        class="absolute top-1 right-1 p-1 bg-black/50 text-white rounded-md opacity-0 group-hover/photo:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="!profile.photo">
                            <div class="space-y-2">
                                <label class="flex flex-col items-center justify-center w-full aspect-square border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:bg-white hover:border-blue-300 transition-all">
                                    <input type="file" accept="image/*" class="hidden" @change="handleStaffProfileUpload($event, {{ $model }}, profile.id)">
                                    <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] text-gray-400 font-medium">Upload Photo</span>
                                </label>
                                <button type="button" 
                                        @click="openMediaLibrary({ blockId: {{ $model }}.id, type: 'staff_photo', profileId: profile.id })"
                                        class="w-full py-1.5 px-2 text-[9px] font-bold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Browse Library
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Data Fields --}}
                    <div class="md:col-span-3 space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Full Name</label>
                            <input type="text" x-model="profile.name" @input="pushHistory()"
                                   placeholder="e.g. Dr. John Doe"
                                   class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Designation</label>
                                <input type="text" x-model="profile.designation" @input="pushHistory()"
                                       placeholder="e.g. Principal"
                                       class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Qualification</label>
                                <input type="text" x-model="profile.qualification" @input="pushHistory()"
                                       placeholder="e.g. M.Sc, PhD"
                                       class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="!{{ $model }}.profiles || {{ $model }}.profiles.length === 0">
            <div class="py-10 text-center border-2 border-dashed border-gray-100 rounded-xl">
                <p class="text-sm text-gray-400">No profiles added yet. Click "Add Profile" to start.</p>
            </div>
        </template>
    </div>
</div>
