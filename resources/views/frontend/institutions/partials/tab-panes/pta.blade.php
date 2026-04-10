@if ($institution->ptaMembers->count() > 0)
    <div id="pane-pta" x-show="activeTab === 'pta'" x-transition.opacity.duration.400ms style="display: none;">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
            <h2 class="text-2xl font-bold text-[#1E234B]">PTA Members</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach ($institution->ptaMembers as $member)
                <div class="text-center bg-[#F8F9FA] p-5 rounded-2xl shadow-md border border-gray-100 hover:-translate-y-1 transition-transform">
                    <div class="w-20 h-20 mx-auto rounded-full overflow-hidden mb-3 border-4 border-white shadow-sm">
                        <img src="{{ $member->photo ? asset('storage/' . $member->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&size=100&background=1E234B&color=fff' }}"
                            class="w-full h-full object-cover" alt="{{ $member->name }}">
                    </div>
                    <h4 class="font-bold text-[#1E234B] text-sm mb-1 leading-tight">{{ $member->name }}</h4>
                    <p class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">{{ $member->mobile ?? 'PTA Member' }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif
