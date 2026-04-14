@extends('layouts.app')
@section('title', 'Our Institutions')

@section('content')
    <style>
        :root {
            /* Premium Standard Theme Colors */
            --theme-navy: #1E234B;
            --theme-yellow: #FFD700;
            --theme-bg: #F8F9FA;
            --card-radius: 16px;
            --transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Force Syne Font and Global Typography Reset */
        * {
            font-family: 'Syne', sans-serif !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 700;
            letter-spacing: -0.01em;
            text-transform: none !important;
        }

        /* Hide horizontal scrollbar for the categories */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom Scroll Indicators */
        .scroll-container-wrapper {
            position: relative;
            padding: 0 10px;
        }

        .scroll-container-wrapper::after,
        .scroll-container-wrapper::before {
            content: '';
            position: absolute;
            top: 5px;
            bottom: 5px;
            width: 50px;
            z-index: 30;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        .scroll-container-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #ffffff 10%, rgba(255, 255, 255, 0));
            opacity: 0;
        }

        .scroll-container-wrapper::after {
            right: 0;
            background: linear-gradient(to left, #ffffff 10%, rgba(255, 255, 255, 0));
            opacity: 0;
        }

        .has-scroll-left.scroll-container-wrapper::before {
            opacity: 1;
        }

        .has-scroll-right.scroll-container-wrapper::after {
            opacity: 1;
        }

        #category-scroll {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        #category-scroll::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        /* Selection prevention during drag */
        .drag-active {
            cursor: grabbing !important;
            user-select: none;
        }

        .drag-active a {
            pointer-events: none;
        }
    </style>

    {{--
    =======================================================
    PREMIUM HERO SECTION
    =======================================================
    --}}
    {{--
    =======================================================
    PREMIUM HERO BANNER (Thin breadcrumb style with image)
    =======================================================
    --}}
    <x-breadcrumb-banner 
        :title="request('category') ? request('category') : 'Our Institute'" 
        :breadcrumbs="$breadcrumbTrail"
        note="Nurturing young minds through holistic education"
    />

    {{--
    =======================================================
    CATEGORY FILTERS (Commented out but styled for future)
    =======================================================
    --}}
    {{-- <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="scroll-container-wrapper max-w-5xl mx-auto" id="scroll-wrapper">
            <div id="category-scroll"
                class="flex items-center justify-start gap-3 overflow-x-auto whitespace-nowrap py-4 px-6 bg-white rounded-full shadow-md border border-gray-100 cursor-grab select-none active:cursor-grabbing">
                <a href="{{ route('institutions.index') }}"
                    class="inline-block shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 {{ !request('category') ? 'bg-[#1E234B] text-white shadow-md' : 'bg-[#F8F9FA] text-gray-600 hover:bg-gray-100' }}">
                    All Centers
                </a>
                @foreach($categories as $key => $label)
                <a href="{{ route('institutions.index', ['category' => $label]) }}"
                    class="inline-block shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 {{ request('category') == $label ? 'bg-[#1E234B] text-white shadow-md' : 'bg-[#F8F9FA] text-gray-600 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div> --}}

    {{--
    =======================================================
    MAIN GRID SECTION
    =======================================================
    --}}
    <section class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 mb-8 font-sans">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
            @forelse($institutions as $inst)

                {{-- Premium Card - Standardized Black Text & Polished Shadow --}}
                <div class="flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 group/card overflow-hidden"
                    data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

                    {{-- Fixed Aspect Ratio Image Wrapper --}}
                    <div class="relative aspect-4/3 overflow-hidden bg-gray-50 shrink-0 border-b border-gray-100">

                        {{-- Category Badge --}}
                        <span
                            class="absolute top-4 right-4 z-20 bg-[#FFD700] px-4 py-1.5 rounded-full text-[10px] font-black uppercase text-black tracking-wider shadow-md">
                            {{ $inst->category_label }}
                        </span>

                        @if($inst->featured_image)
                            <img src="{{ asset('storage/' . $inst->featured_image) }}"
                                class="w-full h-full object-cover transform group-hover/card:scale-110 transition-transform duration-1000 ease-in-out"
                                alt="{{ $inst->name }}" loading="lazy">
                        @else
                            {{-- Fallback Graphic --}}
                            <div class="w-full h-full flex flex-col items-center justify-center text-center p-6 gap-3 bg-gray-100">
                                <svg class="w-14 h-14 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v1H9V7zm5 0h1v1h-1V7zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1zm-3 4H2v5h12v-5zm3 0h1v1h-1v-1z">
                                    </path>
                                </svg>
                                <span class="text-xs font-black text-gray-300 uppercase tracking-widest leading-tight">
                                    {{ $inst->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Card Content - ALL TEXT BLACK --}}
                    <div class="p-7 flex flex-col grow bg-[#FDFDFD]">

                        {{-- Title --}}
                        <h3
                            class="text-xl font-black text-black mb-6 group-hover/card:text-[#000165] transition-colors duration-300 leading-tight">
                            {{ $inst->name }}
                        </h3>

                        {{-- Details List (Curriculum & Location) --}}
                        <div class="mt-auto space-y-5 mb-8">
                            {{-- Curriculum --}}
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 shadow-sm flex items-center justify-center text-black shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72l5 2.73 5-2.73v3.72z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-gray-400">Curriculum</span>
                                    <span
                                        class="text-sm font-bold text-black leading-none mt-1.5">{{ $inst->curriculum ?? 'Not Specified' }}</span>
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 shadow-sm flex items-center justify-center text-black shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Location</span>
                                    <span
                                        class="text-sm font-bold text-black leading-none mt-1.5">{{ $inst->city ?? 'Campus' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Explore Button --}}
                        <a href="{{ route('institutions.show', $inst->slug) }}"
                            class="mt-auto flex items-center justify-center gap-3 w-full px-6 py-2 text-sm font-bold text-[#000165] bg-white border-2 border-[#000165] rounded-full transition-all duration-300 hover:bg-[#FFD700] hover:text-[#000165] hover:border-[#000165] hover:scale-[1.02] active:scale-[0.98] shadow-lg group/btn">
                            Explore Center
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none"
                                stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                {{-- Standardized Empty State --}}
                <div class="col-span-full">
                    <div class="bg-[#F8F9FA] rounded-[24px] p-16 text-center border border-dashed border-gray-200 shadow-sm">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-400 uppercase tracking-tight">No centers found</h3>
                        <p class="text-gray-400 mt-2 text-sm font-medium">Try exploring another category.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const slider = document.getElementById('category-scroll');
                const wrapper = document.getElementById('scroll-wrapper');
                if (!slider || !wrapper) return;

                let isDown = false;
                let startX;
                let scrollLeft;
                let isDragging = false;

                const updateIndicators = () => {
                    const scrollWidth = slider.scrollWidth;
                    const clientWidth = slider.clientWidth;
                    const currentScroll = slider.scrollLeft;

                    const canScroll = scrollWidth > clientWidth + 5;

                    if (!canScroll) {
                        wrapper.classList.remove('has-scroll-left', 'has-scroll-right');
                        return;
                    }

                    const atLeft = currentScroll <= 15;
                    const atRight = currentScroll >= (scrollWidth - clientWidth - 15);

                    if (atLeft) wrapper.classList.remove('has-scroll-left');
                    else wrapper.classList.add('has-scroll-left');

                    if (atRight) wrapper.classList.remove('has-scroll-right');
                    else wrapper.classList.add('has-scroll-right');
                };

                // CRITICAL FIX: Prevent browser from trying to "drag" the link as an image/URL.
                slider.querySelectorAll('a').forEach(link => {
                    link.addEventListener('dragstart', (e) => e.preventDefault());
                });

                // 1. Mouse Drag Events
                slider.addEventListener('mousedown', (e) => {
                    isDown = true;
                    isDragging = false;
                    slider.classList.add('drag-active');

                    startX = e.pageX - slider.offsetLeft;
                    scrollLeft = slider.scrollLeft;
                });

                slider.addEventListener('mouseleave', () => {
                    isDown = false;
                    slider.classList.remove('drag-active');
                });

                slider.addEventListener('mouseup', () => {
                    isDown = false;
                    slider.classList.remove('drag-active');
                });

                slider.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();

                    const x = e.pageX - slider.offsetLeft;
                    const walk = (x - startX) * 2;

                    if (Math.abs(walk) > 5) {
                        isDragging = true;
                    }

                    slider.scrollLeft = scrollLeft - walk;
                    updateIndicators();
                });

                // 2. Wheel Support
                slider.addEventListener('wheel', (e) => {
                    if (e.deltaY !== 0) {
                        e.preventDefault();
                        slider.scrollLeft += e.deltaY;
                        updateIndicators();
                    }
                }, { passive: false });

                // 3. Click Interception
                slider.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        e.stopPropagation();
                        isDragging = false;
                    }
                }, true);

                // 4. Initial and Dynamic Updates
                slider.addEventListener('scroll', updateIndicators);
                window.addEventListener('resize', updateIndicators);

                setTimeout(updateIndicators, 200);

                slider.addEventListener('touchmove', updateIndicators, { passive: true });
            });
        </script>
    @endpush
@endsection
