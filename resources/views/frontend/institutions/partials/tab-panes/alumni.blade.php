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

<div id="pane-alumni" x-show="activeTab === 'alumni'" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
    style="display: none;">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8 md:mb-12">
        <div class="w-1.5 h-8 bg-[#000165] rounded-full"></div>
        <h2 class="text-2xl md:text-4xl font-black text-[#1E234B] tracking-tight uppercase">Alumni Network</h2>
    </div>

    <div class="space-y-12 md:space-y-20">
        {{-- Alumni Association Section --}}
        @if(!empty($about['intro']) || !empty($about['purpose']) || !empty($about['engagement']) || !empty($registrationLink))
            <section class="relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50 -z-10"></div>
                <div class="bg-white rounded-[40px] border border-gray-100 shadow-2xl overflow-hidden relative">
                    <div class="p-8 md:p-12">
                        <div class="flex flex-wrap items-center gap-4 mb-8">
                            <span
                                class="px-5 py-2 bg-[#000165] text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">Association</span>
                            @if(!empty($registrationLink))
                                <a href="{{ $registrationLink }}" target="_blank"
                                    class="px-5 py-2 bg-[#FFD700] text-[#000165] rounded-full text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-transform shadow-md">
                                    Register Now <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <div>
                                <h3 class="text-3xl md:text-4xl font-black text-[#1E234B] mb-6 leading-tight">VVES College
                                    Alumni Association</h3>
                                @if(!empty($about['intro']))
                                    <div
                                        class="prose prose-blue max-w-none text-gray-600 font-syne text-lg italic leading-relaxed mb-8">
                                        "{!! nl2br(e($about['intro'])) !!}"
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-8">
                                @if(!empty($about['purpose']))
                                    <div>
                                        <h4
                                            class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-[#FFD700] rounded-full"></span> Our Primary Goals
                                        </h4>
                                        <div class="text-gray-600 leading-relaxed text-sm md:text-base">
                                            {!! nl2br(e($about['purpose'])) !!}
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($about['engagement']))
                                    <div>
                                        <h4
                                            class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-[#FFD700] rounded-full"></span> Alumni Engagement
                                        </h4>
                                        <div class="text-gray-600 leading-relaxed text-sm md:text-base">
                                            {!! nl2br(e($about['engagement'])) !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Notable Alumni Students --}}
        @if(!empty($students))
            <section>
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] mb-2 block">Our
                            Pride</span>
                        <h3 class="text-2xl md:text-3xl font-black text-[#1E234B] uppercase tracking-tight">Notable Students
                        </h3>
                    </div>
                    <div class="h-px bg-gray-100 flex-1 mx-8 hidden md:block"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($students as $student)
                        @php $student = (array) $student; @endphp
                        <div
                            class="group relative bg-white rounded-[32px] p-8 border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                            <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                                <i class="bi bi-quote text-6xl text-[#000165]"></i>
                            </div>

                            <div class="relative z-10">
                                <div
                                    class="w-20 h-20 rounded-2xl overflow-hidden mb-6 border-2 border-white shadow-xl rotate-3 group-hover:rotate-0 transition-transform duration-500">
                                    <img src="{{ !empty($student['photo']) ? asset('storage/' . $student['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($student['name'] ?? 'Alumni') . '&size=160&background=000165&color=fff' }}"
                                        class="w-full h-full object-cover" alt="{{ $student['name'] ?? 'Alumni' }}">
                                </div>

                                <h4 class="text-xl font-black text-[#1E234B] mb-1 group-hover:text-[#000165] transition-colors">
                                    {{ $student['name'] ?? 'N/A' }}</h4>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if(!empty($student['batch']))
                                        <span
                                            class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[9px] font-black uppercase tracking-widest">Batch
                                            {{ $student['batch'] }}</span>
                                    @endif
                                    @if(!empty($student['section']))
                                        <span
                                            class="px-2 py-0.5 bg-gray-50 text-gray-500 rounded text-[9px] font-black uppercase tracking-widest">{{ $student['section'] }}</span>
                                    @endif
                                </div>

                                <div class="space-y-3 pt-4 border-t border-gray-50">
                                    @if(!empty($student['profession']))
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-[#1E234B] text-xs">
                                                <i class="bi bi-briefcase"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-600">{{ $student['profession'] }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($student['location']))
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-[#1E234B] text-xs">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-600">{{ $student['location'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Testimonials Section --}}
        @if(!empty($testimonials))
            <section class="py-12 bg-gray-50/50 rounded-[60px] px-8 md:px-12 border border-gray-100">
                <div class="text-center mb-16">
                    <span class="text-[10px] font-black text-[#FFD700] uppercase tracking-[0.3em] mb-3 block">Voices of
                        success</span>
                    <h3 class="text-3xl md:text-4xl font-black text-[#1E234B] uppercase tracking-tight">Alumni Testimonials
                    </h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    @foreach($testimonials as $testimonial)
                        @php $testimonial = (array) $testimonial; @endphp
                        <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-50 relative group">
                            <div
                                class="absolute -top-6 -right-6 w-12 h-12 bg-[#FFD700] rounded-full flex items-center justify-center text-[#000165] text-xl shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-500 scale-0 group-hover:scale-100">
                                <i class="bi bi-chat-heart-fill"></i>
                            </div>

                            <div class="flex items-start gap-6">
                                <div
                                    class="shrink-0 w-16 h-16 rounded-full overflow-hidden border-2 border-[#000165]/10 p-1 shadow-inner">
                                    <img src="{{ !empty($testimonial['photo']) ? asset('storage/' . $testimonial['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial['name'] ?? 'Alumni') . '&size=120&background=F8F9FA&color=000165' }}"
                                        class="w-full h-full object-cover rounded-full"
                                        alt="{{ $testimonial['name'] ?? 'Alumni' }}">
                                </div>
                                <div class="min-w-0">
                                    <h5 class="text-lg font-black text-[#1E234B] mb-1">{{ $testimonial['name'] ?? 'N/A' }}</h5>
                                    <p class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-4">VVES Alumni</p>
                                    <div class="text-gray-600 leading-relaxed font-syne italic text-sm md:text-base">
                                        "{!! nl2br(e($testimonial['content'])) !!}"
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Meet Gallery Section --}}
        @if(!empty($gallery))
            <section>
                <div class="flex items-center gap-6 mb-12">
                    <h3 class="text-2xl md:text-3xl font-black text-[#1E234B] uppercase tracking-tight shrink-0">Alumni Meet
                        Gallery</h3>
                    <div class="h-px bg-linear-to-r from-gray-100 to-transparent flex-1"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($gallery as $photo)
                        @php $photo = (array) $photo; @endphp
                        @if(!empty($photo['photo']))
                            <div class="group relative aspect-square overflow-hidden rounded-[32px] cursor-pointer shadow-lg">
                                <img src="{{ asset('storage/' . $photo['photo']) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    alt="{{ $photo['caption'] ?? 'Alumni Meet' }}">
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                                    @if(!empty($photo['caption']))
                                        <p class="text-white text-xs font-bold tracking-wide uppercase">{{ $photo['caption'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>