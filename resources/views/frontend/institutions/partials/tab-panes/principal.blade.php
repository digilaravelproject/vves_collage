@if ($institution->principal)
    <div id="pane-principal" x-show="activeTab === 'principal'" x-transition.opacity.duration.400ms style="display: none;">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
            <h2 class="text-2xl font-bold text-[#1E234B]">Principal Message</h2>
        </div>
        
        <div class="bg-white rounded-3xl p-6 md:p-10 flex flex-col md:flex-row gap-8 items-center md:items-start border border-gray-100 shadow-xl overflow-hidden relative group">
            {{-- Accent Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#F8F9FA] rounded-bl-full -z-10 transition-colors group-hover:bg-[#FFD700]/5"></div>
            
            <div class="shrink-0 w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden shadow-lg border-4 border-white bg-white">
                <img src="{{ $institution->principal->photo ? asset('storage/' . $institution->principal->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($institution->principal->name) . '&size=200' }}"
                    class="w-full h-full object-cover"
                    alt="{{ $institution->principal->name }}">
            </div>
            
            <div class="flex-1">
                <h3 class="text-xl font-bold text-[#1E234B] mb-1">
                    {{ $institution->principal->name }}
                </h3>
                <p class="text-[#1E234B] group-hover:text-[#FFD700] transition-colors font-bold text-sm mb-4 tracking-wide uppercase">
                    {{ $institution->principal->designation }}
                </p>
                <div class="text-gray-600 italic leading-relaxed text-sm format-quotes relative z-10 bg-white p-5 rounded-xl shadow-sm border border-gray-50">
                    <svg class="w-8 h-8 text-gray-200 absolute -top-3 -left-3 -z-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    "{!! nl2br(e($institution->principal->description)) !!}"
                </div>
            </div>
        </div>
    </div>
@endif
