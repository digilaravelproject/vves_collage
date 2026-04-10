<section class="bg-[#F8F9FA] py-8 md:py-12 border-t border-gray-200">
    <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 md:mb-10 text-center" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1E234B] mb-2">
                Other Institutes
            </h2>
            <div class="w-16 h-1 bg-[#FFD700] rounded-full mx-auto mb-6"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
            @foreach ($otherInstitutions as $inst)
                {{-- Clean Card Design matching 'Institutions Grid' standard --}}
                <div class="flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 group/card overflow-hidden"
                    data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                    {{-- Fixed Aspect Ratio Image Wrapper --}}
                    <div class="relative aspect-4/3 overflow-hidden bg-[#F8F9FA] shrink-0 border-b border-gray-50">
                        {{-- Category Badge --}}
                        <span
                            class="absolute top-4 right-4 z-20 bg-[#FFD700] px-3 py-1 rounded-full text-[10px] font-bold uppercase text-gray-900 tracking-wider shadow-sm">
                            {{ $inst->category_label }}
                        </span>

                        @if ($inst->featured_image)
                            <img src="{{ asset('storage/' . $inst->featured_image) }}"
                                class="w-full h-full object-cover transform group-hover/card:scale-105 transition-transform duration-700 ease-in-out"
                                alt="{{ $inst->name }}">
                        @else
                            {{-- Fallback Graphic --}}
                            <div
                                class="w-full h-full flex flex-col items-center justify-center text-center p-6 gap-3 bg-gray-100">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v1H9V7zm5 0h1v1h-1V7zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1zm-3 4H2v5h12v-5zm3 0h1v1h-1v-1z">
                                    </path>
                                </svg>
                                <span
                                    class="text-sm sm:text-base font-bold text-gray-400 uppercase tracking-widest leading-tight">
                                    {{ $inst->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Card Content --}}
                    <div class="p-6 flex flex-col grow bg-[#F8F9FA]">
                        <h3
                            class="text-xl font-bold text-[#1E234B] mb-5 group-hover/card:text-[#FFD700] transition-colors duration-300 leading-tight">
                            {{ $inst->name }}
                        </h3>

                        <div class="mt-auto space-y-4 mb-6">
                            {{-- Curriculum --}}
                            <div class="flex items-center gap-3 text-gray-600">
                                <div
                                    class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-[#1E234B] shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72l5 2.73 5-2.73v3.72z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Curriculum</span>
                                    <span
                                        class="text-sm font-semibold text-gray-800 leading-none mt-0.5">{{ $inst->curriculum ?? 'Not Specified' }}</span>
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="flex items-center gap-3 text-gray-600">
                                <div
                                    class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-[#1E234B] shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Location</span>
                                    <span
                                        class="text-sm font-semibold text-gray-800 leading-none mt-0.5">{{ $inst->city ?? 'Campus' }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('institutions.show', $inst->slug) }}"
                            class="mt-auto flex items-center justify-center gap-2 w-full px-6 py-3 text-sm font-bold text-[#1E234B] bg-white border border-gray-200 rounded-full transition-all duration-300 hover:border-[#1E234B] hover:bg-[#1E234B] hover:text-white group/btn">
                            Explore Center
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1"
                                fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
