@php
    $alumniData = is_array($institution->alumni_data)
        ? $institution->alumni_data
        : json_decode($institution->alumni_data, true) ?? [];
    
    $about = $alumniData['about'] ?? [];
    $registrationLink = $alumniData['registration_link'] ?? '';
    $students = $alumniData['students'] ?? [];
    $gallery = $alumniData['gallery'] ?? [];
    $testimonials = $alumniData['testimonials'] ?? [];
@endphp

<div id="pane-alumni" x-show="activeTab === 'alumni'" 
     x-transition:enter="transition ease-out duration-500" 
     x-transition:enter-start="opacity-0 scale-95" 
     x-transition:enter-end="opacity-100 scale-100" 
     class="space-y-16" style="display: none;">
    
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -z-10 w-96 h-96 bg-blue-50/50 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 left-0 -z-10 w-72 h-72 bg-amber-50/50 rounded-full blur-[100px]"></div>

    {{-- Header Section --}}
    <div class="relative">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-[#000165] font-black text-xs uppercase tracking-[0.3em]">
                    <span class="w-8 h-[2px] bg-[#000165]"></span>
                    Global Network
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-[#1E234B] tracking-tight leading-none">
                    ALUMNI <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#000165] to-[#000165]/70">NETWORK</span>
                </h2>
                <p class="text-gray-500 max-w-xl font-medium">Connecting generations of excellence. Our alumni are our greatest ambassadors and our pride.</p>
            </div>
            
            @if(!empty($registrationLink))
                <div class="shrink-0">
                    <a href="{{ $registrationLink }}" target="_blank" 
                       class="group relative inline-flex items-center gap-3 px-8 py-4 bg-[#000165] text-white rounded-2xl font-black uppercase tracking-widest text-sm overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-[#000165]/30 hover:-translate-y-1 active:scale-95">
                        <span class="relative z-10">Join Association</span>
                        <i class="bi bi-person-plus-fill relative z-10 text-lg"></i>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#000165] via-blue-900 to-[#000165] translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Alumni Association Section --}}
    @if(!empty($about['intro']) || !empty($about['purpose']) || !empty($about['engagement']))
        <section class="group relative bg-white/40 backdrop-blur-xl rounded-[48px] border border-white/60 shadow-[0_32px_64px_-16px_rgba(0,1,101,0.08)] overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -z-10 opacity-50 transition-transform duration-700 group-hover:scale-110"></div>
            
            <div class="p-8 md:p-16">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-16 items-start">
                    <div class="lg:col-span-3 space-y-8">
                        <div class="inline-flex items-center gap-3 px-4 py-2 bg-[#FFD700]/10 rounded-xl">
                            <i class="bi bi-info-circle-fill text-[#000165]"></i>
                            <span class="text-[10px] font-black text-[#000165] uppercase tracking-widest">About Association</span>
                        </div>
                        
                        <h3 class="text-3xl md:text-5xl font-black text-[#1E234B] leading-[1.1]">The Heart of Our Community</h3>
                        
                        @if(!empty($about['intro']))
                            <div class="relative pl-8 border-l-4 border-[#FFD700] py-2">
                                <p class="text-xl md:text-2xl font-semibold text-gray-700 italic leading-relaxed">
                                    "{!! nl2br(e($about['intro'])) !!}"
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="lg:col-span-2 space-y-10">
                        @if(!empty($about['purpose']))
                            <div class="bg-white/60 p-8 rounded-3xl border border-gray-100/50 shadow-sm transition-all duration-300 hover:shadow-xl hover:bg-white">
                                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-[#000165] mb-6 shadow-inner">
                                    <i class="bi bi-bullseye text-xl"></i>
                                </div>
                                <h4 class="text-lg font-black text-[#1E234B] mb-4 uppercase tracking-tight">Our Mission</h4>
                                <div class="text-gray-600 leading-relaxed font-medium">
                                    {!! nl2br(e($about['purpose'])) !!}
                                </div>
                            </div>
                        @endif

                        @if(!empty($about['engagement']))
                            <div class="bg-white/60 p-8 rounded-3xl border border-gray-100/50 shadow-sm transition-all duration-300 hover:shadow-xl hover:bg-white">
                                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 shadow-inner">
                                    <i class="bi bi-people text-xl"></i>
                                </div>
                                <h4 class="text-lg font-black text-[#1E234B] mb-4 uppercase tracking-tight">Engagement</h4>
                                <div class="text-gray-600 leading-relaxed font-medium">
                                    {!! nl2br(e($about['engagement'])) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Notable Alumni Students --}}
    @if(!empty($students))
        <section class="relative">
            <div class="flex items-end justify-between mb-12">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] block">Success Stories</span>
                    <h3 class="text-3xl md:text-4xl font-black text-[#1E234B] tracking-tight">NOTABLE <span class="text-[#000165]">ALUMNI</span></h3>
                </div>
                <div class="hidden md:flex gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-100"></div>
                    <div class="w-12 h-2 rounded-full bg-[#000165]"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($students as $student)
                    @php $student = (array) $student; @endphp
                    <div class="group bg-white rounded-[40px] p-8 border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_40px_80px_rgba(0,1,101,0.08)] hover:-translate-y-2 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-linear-to-bl from-blue-50/50 to-transparent rounded-bl-full -z-10 group-hover:scale-125 transition-transform duration-700"></div>
                        
                        <div class="flex items-center gap-6 mb-8">
                            <div class="relative shrink-0">
                                <div class="w-24 h-24 rounded-3xl overflow-hidden shadow-2xl rotate-[-4deg] group-hover:rotate-0 transition-all duration-500">
                                    <img src="{{ !empty($student['photo']) ? asset('storage/' . $student['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($student['name'] ?? 'Alumni') . '&size=160&background=000165&color=fff' }}" 
                                         class="w-full h-full object-cover" alt="{{ $student['name'] ?? 'Alumni' }}">
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#FFD700] rounded-2xl flex items-center justify-center text-[#000165] border-4 border-white shadow-lg">
                                    <i class="bi bi-patch-check-fill"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-[#1E234B] group-hover:text-[#000165] transition-colors duration-300 leading-tight">{{ $student['name'] ?? 'N/A' }}</h4>
                                <p class="text-xs font-black text-blue-500 uppercase tracking-widest mt-1">Class of {{ $student['batch'] ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-6 border-t border-gray-50">
                            @if(!empty($student['profession']))
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-[#000165] group-hover:bg-blue-50 transition-all duration-300">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Profession</span>
                                        <span class="text-sm font-bold text-gray-700 block truncate">{{ $student['profession'] }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($student['location']))
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-[#000165] group-hover:bg-blue-50 transition-all duration-300">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Current Location</span>
                                        <span class="text-sm font-bold text-gray-700 block truncate">{{ $student['location'] }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Testimonials Section --}}
    @if(!empty($testimonials))
        <section class="py-20 relative bg-[#1E234B] rounded-[64px] overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
            </div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-[100px]"></div>
            
            <div class="relative px-8 md:px-16">
                <div class="text-center mb-16">
                    <span class="text-[10px] font-black text-[#FFD700] uppercase tracking-[0.4em] mb-4 block">Testimonials</span>
                    <h3 class="text-4xl md:text-5xl font-black text-white tracking-tight uppercase">Hear from <span class="text-[#FFD700]">Them</span></h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    @foreach($testimonials as $testimonial)
                        @php $testimonial = (array) $testimonial; @endphp
                        <div class="relative bg-white/10 backdrop-blur-md p-10 rounded-[48px] border border-white/10 transition-all duration-500 hover:bg-white/15 hover:-translate-y-2 group">
                            <i class="bi bi-quote absolute top-8 right-10 text-6xl text-white/5 group-hover:text-[#FFD700]/10 transition-colors"></i>
                            
                            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                                <div class="shrink-0 relative">
                                    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white/10 p-1 shadow-2xl">
                                        <img src="{{ !empty($testimonial['photo']) ? asset('storage/' . $testimonial['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial['name'] ?? 'Alumni') . '&size=120&background=FFD700&color=000165' }}" 
                                             class="w-full h-full object-cover rounded-full" alt="{{ $testimonial['name'] ?? 'Alumni' }}">
                                    </div>
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-[#FFD700] rounded-full flex items-center justify-center text-[#000165] shadow-lg scale-0 group-hover:scale-100 transition-transform duration-500">
                                        <i class="bi bi-heart-fill text-xs"></i>
                                    </div>
                                </div>
                                <div class="text-center md:text-left min-w-0">
                                    <h5 class="text-xl font-black text-white mb-1 tracking-tight">{{ $testimonial['name'] ?? 'N/A' }}</h5>
                                    <span class="inline-block px-3 py-1 bg-[#FFD700] text-[#000165] rounded-full text-[9px] font-black uppercase tracking-widest mb-6">Proud Alumnus</span>
                                    <div class="text-blue-50/80 leading-relaxed font-medium italic text-lg line-clamp-4 group-hover:line-clamp-none transition-all duration-500">
                                        "{!! nl2br(e($testimonial['content'])) !!}"
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Meet Gallery Section --}}
    @if(!empty($gallery))
        <section class="space-y-12">
            <div class="flex items-center gap-6">
                <h3 class="text-3xl font-black text-[#1E234B] tracking-tight shrink-0 uppercase">NETWORK <span class="text-[#000165]">GALLERY</span></h3>
                <div class="h-[2px] bg-linear-to-r from-gray-100 to-transparent flex-1"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($gallery as $index => $photo)
                    @php $photo = (array) $photo; @endphp
                    @if(!empty($photo['photo']))
                        <div class="group relative aspect-square overflow-hidden rounded-[32px] cursor-pointer shadow-lg {{ $index % 5 == 0 ? 'md:col-span-2 md:row-span-2 aspect-auto' : '' }}">
                            <img src="{{ asset('storage/' . $photo['photo']) }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000" alt="{{ $photo['caption'] ?? 'Alumni Meet' }}">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-[#000165]/90 via-[#000165]/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-8">
                                <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    <div class="w-10 h-1 bg-[#FFD700] mb-4"></div>
                                    <p class="text-white text-sm font-bold tracking-wide uppercase leading-tight">
                                        {{ $photo['caption'] ?? 'Alumni Meet Moment' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif
</div>

