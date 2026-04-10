@if ($institution->staffs->count() > 0)
    <div id="pane-staffs" x-show="activeTab === 'staffs'" x-transition.opacity.duration.400ms style="display: none;">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                <h2 class="text-2xl font-bold text-[#1E234B]">Staff Directory</h2>
            </div>
            <div class="bg-[#F8F9FA] px-4 py-2 rounded-xl border border-gray-100 flex items-center gap-3 shadow-sm">
                <svg class="w-4 h-4 text-[#1E234B]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                </svg>
                <span class="text-xs font-bold text-[#1E234B] uppercase tracking-widest">{{ $institution->staffs->count() }} members</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($institution->staffs as $staff)
                <div class="flex flex-col items-center bg-white border border-gray-100 rounded-3xl p-6 text-center shadow-lg hover:shadow-2xl transition-all duration-500 group border-b-4 border-b-transparent hover:border-b-[#FFD700]">
                    {{-- Profile Image --}}
                    <div class="relative mb-5">
                        <div class="staff-card-img {{ !$staff->photo ? 'staff-avatar-placeholder' : '' }}">
                            @if ($staff->photo)
                                <img src="{{ asset('storage/' . $staff->photo) }}" class="w-full h-full object-cover">
                            @else
                                <i class="bi bi-person-fill text-5xl"></i>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="space-y-1 mb-6">
                        <h4 class="text-xl font-black text-black group-hover:text-[#000165] transition-colors leading-tight">{{ $staff->name }}</h4>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $staff->section }}</span>
                            @if ($staff->subject)
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span class="text-[9px] font-black text-[#000165] uppercase tracking-widest">{{ $staff->subject }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="w-full grid grid-cols-2 gap-3 pt-5 border-t border-gray-50 mt-auto">
                        <div class="text-center">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Qualification</p>
                            <p class="text-[10px] font-black text-black uppercase truncate px-1" title="{{ $staff->qualification }}">{{ $staff->qualification ?: '-' }}</p>
                        </div>
                        <div class="text-center border-l border-gray-100">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Experience</p>
                            <p class="text-[10px] font-black text-black uppercase">{{ $staff->experience ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
