@extends('layouts.app')
@section('title', $institution->meta_title ?: $institution->name)
@section('meta_description', $institution->meta_description ?: Str::limit(strip_tags($institution->institutional_journey), 160))

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
    * { font-family: 'Syne', sans-serif !important; }
    h1, h2, h3, h4, h5, h6 { font-weight: 700; letter-spacing: -0.01em; text-transform: none !important; }

    /* Scroll wrappers for organized slider */
    /*.no-scrollbar::-webkit-scrollbar { display: none; }*/
    /*.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }*/

    /* 🌊 Premium Thin Scrollbar for Tab Slider */
    #category-scroll::-webkit-scrollbar {
        height: 4px;
    }
    #category-scroll::-webkit-scrollbar-track {
        background: transparent;
        margin: 0 20px;
    }
    #category-scroll::-webkit-scrollbar-thumb {
        background: #000165;
        border-radius: 10px;
        transition: background 0.3s;
    }
    #category-scroll:hover::-webkit-scrollbar-thumb {
        background: #000165;
    }

    .slider-track {
        @apply bg-white rounded-4xl p-2 relative overflow-visible shadow-sm border border-gray-100;
        min-height: 70px;
        display: flex;
        align-items: center;
    }

    .scroll-container-wrapper {
        position: relative;
        width: 100%;
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .scroll-container-wrapper::after, .scroll-container-wrapper::before {
        content: ''; position: absolute; top: 0; bottom: 0; width: 80px; z-index: 20; pointer-events: none; transition: all 0.4s ease;
    }
    .scroll-container-wrapper::before {
        left: 0;
        background: linear-gradient(to right, rgba(255,255,255,0.9) 0%, transparent 100%);
        opacity: 0; transform: translateX(-10px);
    }
    .scroll-container-wrapper::after {
        right: 0;
        background: linear-gradient(to left, rgba(255,255,255,0.9) 0%, transparent 100%);
        opacity: 0; transform: translateX(10px);
    }
    .has-scroll-left.scroll-container-wrapper::before { opacity: 1; transform: translateX(0); }
    .has-scroll-right.scroll-container-wrapper::after { opacity: 1; transform: translateX(0); }

    .drag-active { cursor: grabbing !important; user-select: none; }

    .staff-card-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 100%;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .staff-avatar-placeholder {
        background: linear-gradient(135deg, #f8f9fa 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    /* Custom prose for Syne */
    .prose * { font-family: 'Syne', sans-serif !important; color: #4b5563; }
    .prose strong { color: var(--theme-navy); font-weight: 700; }
</style>

@php
    // Determine the first available tab
    $firstTab = '';
    if(!empty(trim(strip_tags($institution->institutional_journey)))) $firstTab = 'about';
    elseif($institution->growth_graph) $firstTab = 'growth';
    elseif($institution->principal) $firstTab = 'principal';
    elseif($institution->sections->count() > 0) $firstTab = 'sec_'.$institution->sections->first()->id;
    elseif($institution->results->count() > 0) $firstTab = 'results';
    elseif($institution->ptaMembers->count() > 0) $firstTab = 'pta';
    elseif($institution->awards->count() > 0) $firstTab = 'awards';
    elseif($institution->staffs->count() > 0) $firstTab = 'staffs';
@endphp

<div class="w-full bg-white font-sans" x-data="{ activeTab: '{{ $firstTab }}' }">

    {{--
        =======================================================
        TOP HERO BANNER (Fixed Height & Static Image)
        =======================================================
    --}}
    <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 mt-4 md:mt-6 relative z-10">
        {{-- Reduced height for 'Thin' design --}}
        <div class="relative w-full h-[200px] md:h-[260px] rounded-3xl overflow-hidden shadow-sm group">

            {{-- Background Image --}}
            <img src="{{ asset('storage/breadcrum.jpeg') }}"
                 alt="{{ $institution->name }} Banner"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                 onerror="this.src='https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop'">

            {{-- Premium Navy Gradient Overlay - REDUCED --}}
            <div class="absolute inset-0 bg-linear-to-r from-[#000165]/30 via-[#000165]/20 to-transparent"></div>

            {{-- Content inside Banner --}}
            <div class="absolute inset-0 w-full p-6 md:p-10 flex flex-col justify-center">

                {{-- Breadcrumb --}}
                <nav class="flex text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-white/80 mb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li class="opacity-40">/</li>
                        <li><a href="{{ route('institutions.list') }}" class="hover:text-white transition-colors">Our Institute</a></li>
                        <li class="opacity-40">/</li>
                        <li class="text-[#FFD700] truncate max-w-[150px] sm:max-w-none">{{ $institution->name }}</li>
                    </ol>
                </nav>

                {{-- Banner Title Area --}}
                <div class="border-l-4 border-[#FFD700] pl-6 max-w-4xl">
                    <h1 class="text-3xl md:text-5xl font-black text-white! leading-tight mb-2 tracking-tighter">{{ $institution->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="bg-[#FFD700] text-[#000165] px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest">{{ $institution->category_label }}</span>
                        @if($institution->year_of_establishment)
                            <span class="text-white font-black text-[10px] md:text-[11px] border-l border-white/30 pl-3 uppercase tracking-widest">Est. {{ $institution->year_of_establishment }}</span>
                        @endif
                        @if($institution->city)
                            <span class="text-white/80 font-bold text-[10px] md:text-[11px] border-l border-white/30 pl-3 uppercase tracking-widest"><i class="bi bi-geo-alt-fill me-1"></i>{{ $institution->city }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--
        =======================================================
        MAIN CONTENT AREA
        =======================================================
    --}}
    <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

            {{-- Left Column (Tabs & Details) --}}
            <div class="lg:col-span-8">

                {{-- Horizontal Slider Tabs --}}
                @if($firstTab !== '')
                    <div class="slider-track mb-8 group">
                        <div id="scroll-wrapper" class="scroll-container-wrapper">
                            {{-- Unified Navigation Arrows --}}
                            <button onclick="scrollCategories(-250)" id="btn-left" class="absolute left-2 top-1/2 -translate-y-1/2 z-30 w-10 h-10 bg-white border border-gray-100 rounded-full shadow-[0_4px_15px_rgba(0,0,0,0.1)] items-center justify-center text-[#1E234B] hover:bg-[#000165] hover:text-white transition-all transform hover:scale-110 hidden md:flex opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto">
                                <i class="bi bi-chevron-left text-lg"></i>
                            </button>
                            <button onclick="scrollCategories(250)" id="btn-right" class="absolute right-2 top-1/2 -translate-y-1/2 z-30 w-10 h-10 bg-white border border-gray-100 rounded-full shadow-[0_4px_15px_rgba(0,0,0,0.1)] items-center justify-center text-[#1E234B] hover:bg-[#000165] hover:text-white transition-all transform hover:scale-110 hidden md:flex opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto">
                                <i class="bi bi-chevron-right text-lg"></i>
                            </button>

                            <div id="category-scroll" class="flex items-center justify-start gap-4 overflow-x-auto whitespace-nowrap py-1 px-4 cursor-grab select-none active:cursor-grabbing pb-3">

                            @if(!empty(trim(strip_tags($institution->institutional_journey))))
                                <button @click="activeTab = 'about'"
                                    :class="activeTab === 'about' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                    class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                    <i class="bi bi-info-circle me-2"></i>About School
                                </button>
                            @endif

                            @foreach($institution->sections as $sec)
                                @if(!empty(trim(strip_tags($sec->content))))
                                    <button @click="activeTab = 'sec_{{ $sec->id }}'"
                                        :class="activeTab === 'sec_{{ $sec->id }}' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                        class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                        <i class="bi bi-file-text me-2"></i>{{ str_replace('_', ' ', $sec->type) }}
                                    </button>
                                @endif
                            @endforeach

                            @if($institution->growth_graph)
                                <button @click="activeTab = 'growth'"
                                    :class="activeTab === 'growth' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                    class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                    <i class="bi bi-graph-up me-2"></i>Growth Graph
                                </button>
                            @endif

                            @if($institution->principal)
                                <button @click="activeTab = 'principal'"
                                    :class="activeTab === 'principal' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                    class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                    <i class="bi bi-person-badge me-2"></i>Principal
                                </button>
                            @endif

                            @if($institution->results->count() > 0)
                                <button @click="activeTab = 'results'"
                                    :class="activeTab === 'results' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                    class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                    <i class="bi bi-trophy me-2"></i>Awards & Results
                                </button>
                            @endif

                            @if($institution->ptaMembers->count() > 0)
                                <button @click="activeTab = 'pta'"
                                    :class="activeTab === 'pta' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                    class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                    <i class="bi bi-people me-2"></i>PTA Members
                                </button>
                            @endif

                            @if($institution->staffs->count() > 0)
                                <button @click="activeTab = 'staffs'"
                                    :class="activeTab === 'staffs' ? 'bg-[#000165] text-white shadow-[#000165]/20 shadow-lg border-[#000165] scale-105' : 'bg-white border-[#000165]/20 text-gray-500 hover:border-[#000165]/40 hover:text-[#000165]'"
                                    class="shrink-0 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 border-2 outline-none">
                                    <i class="bi bi-person-video2 me-2"></i>Staff Directory
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="h-4"></div>

                    {{-- Tab Content Panes --}}

                    <div class="min-h-[400px]">

                        {{-- About Tab --}}
                        @if(!empty(trim(strip_tags($institution->institutional_journey))))
                            <div id="pane-about" x-show="activeTab === 'about'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                    <h2 class="text-2xl font-bold text-[#1E234B]">About School</h2>
                                </div>
                                <div class="prose max-w-none text-gray-600 leading-relaxed text-[15px] bg-[#F8F9FA] p-6 md:p-8 rounded-2xl shadow-sm border border-gray-50">
                                    {!! $institution->institutional_journey !!}
                                </div>
                            </div>
                        @endif

                        {{-- Dynamic Sections Tabs --}}
                        @foreach($institution->sections as $sec)
                            @if(!empty(trim(strip_tags($sec->content))))
                                <div id="pane-sec-{{ $sec->id }}" x-show="activeTab === 'sec_{{ $sec->id }}'" x-transition.opacity.duration.400ms style="display: none;">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                        <h2 class="text-2xl font-bold text-[#1E234B]">{{ ucwords(str_replace('_', ' ', $sec->type)) }}</h2>
                                    </div>
                                    <div class="prose max-w-none text-gray-600 leading-relaxed text-[15px] bg-[#F8F9FA] p-6 md:p-8 rounded-2xl shadow-sm border border-gray-50">
                                        {!! $sec->content !!}
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- Growth Graph Tab --}}
                        @if($institution->growth_graph)
                            <div id="pane-growth" x-show="activeTab === 'growth'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                    <h2 class="text-2xl font-bold text-[#1E234B]">Growth Graph</h2>
                                </div>
                                <div class="bg-[#F8F9FA] p-4 rounded-2xl shadow-sm border border-gray-100">
                                    <img src="{{ asset('storage/' . $institution->growth_graph) }}" class="w-full h-auto rounded-xl" alt="Growth Graph">
                                </div>
                            </div>
                        @endif

                        {{-- Principal Tab --}}
                        @if($institution->principal)
                            <div id="pane-principal" x-show="activeTab === 'principal'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                    <h2 class="text-2xl font-bold text-[#1E234B]">Principal Message</h2>
                                </div>
                                <div class="bg-white rounded-3xl p-6 md:p-10 flex flex-col md:flex-row gap-8 items-center md:items-start border border-gray-100 shadow-xl overflow-hidden relative">
                                    {{-- Accent Background --}}
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#F8F9FA] rounded-bl-full -z-10"></div>
                                    <div class="shrink-0 w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden shadow-lg border-4 border-white bg-white">
                                        <img src="{{ $institution->principal->photo ? asset('storage/' . $institution->principal->photo) : 'https://ui-avatars.com/api/?name='.urlencode($institution->principal->name).'&size=200' }}" class="w-full h-full object-cover" alt="{{ $institution->principal->name }}">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-[#1E234B] mb-1">{{ $institution->principal->name }}</h3>
                                        <p class="text-[#FFD700] font-bold text-sm mb-4 tracking-wide uppercase">{{ $institution->principal->designation }}</p>
                                        <div class="text-gray-600 italic leading-relaxed text-sm format-quotes relative z-10 bg-white p-5 rounded-xl shadow-sm border border-gray-50">
                                            <svg class="w-8 h-8 text-gray-200 absolute -top-3 -left-3 -z-10" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                                            "{!! nl2br(e($institution->principal->description)) !!}"
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Results Tab --}}
                        @if($institution->results->count() > 0)
                            <div id="pane-results" x-show="activeTab === 'results'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                                    @foreach($institution->results as $res)
                                         <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-md hover:shadow-xl transition-all duration-500 group/item">
                                             <div class="flex items-center gap-4 mb-4">
                                                 @if($res->student_photo)
                                                     <img src="{{ asset('storage/' . $res->student_photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md" alt="{{ $res->title }}">
                                                 @else
                                                     <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 text-xl border-2 border-white shadow-sm"><i class="bi bi-person-fill text-2xl"></i></div>
                                                 @endif
                                                 <div>
                                                     <h4 class="font-black text-black text-lg leading-tight">{{ $res->title }}</h4>
                                                     <span class="inline-block px-2 py-0.5 mt-1 bg-blue-50 text-blue-700 border border-blue-100 rounded text-[9px] font-black uppercase tracking-widest">{{ $res->medium }} &bull; {{ $res->year }}</span>
                                                 </div>
                                             </div>
                                             <div class="bg-gray-50 rounded-xl p-3 mb-3 flex justify-between items-center text-sm border border-gray-100">
                                                 <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Overall Result</span>
                                                 <span class="font-black text-black text-lg">{{ $res->overall_result }}</span>
                                             </div>
                                             @if($res->grades)
                                                 @php $grades = is_array($res->grades) ? $res->grades : json_decode($res->grades, true); @endphp
                                                 <div class="grid grid-cols-3 gap-2">
                                                     @foreach(['A', 'B', 'C'] as $g)
                                                         <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-2">
                                                             <div class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Grade {{ $g }}</div>
                                                             <div class="font-black text-black text-base">{{ $grades[$g] ?? '0' }}%</div>
                                                         </div>
                                                     @endforeach
                                                 </div>
                                             @endif
                                         </div>
                                     @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Awards Tab --}}
                        @if($institution->awards->count() > 0)
                            <div id="pane-awards" x-show="activeTab === 'awards'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                    <h2 class="text-2xl font-bold text-[#1E234B]">Awards & Recognition</h2>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    @foreach($institution->awards as $award)
                                        <div class="bg-[#F8F9FA] rounded-2xl overflow-hidden border border-gray-100 shadow-md group">
                                            @if($award->photo)
                                                <div class="aspect-video overflow-hidden">
                                                    <img src="{{ asset('storage/' . $award->photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $award->title }}">
                                                </div>
                                            @endif
                                            <div class="p-6">
                                                <h4 class="font-bold text-[#1E234B] text-lg mb-2">{{ $award->title }}</h4>
                                                <p class="text-sm text-gray-600 leading-relaxed">{{ $award->description }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- PTA Members Tab --}}
                        @if($institution->ptaMembers->count() > 0)
                            <div id="pane-pta" x-show="activeTab === 'pta'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                    <h2 class="text-2xl font-bold text-[#1E234B]">PTA Members</h2>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                                    @foreach($institution->ptaMembers as $member)
                                        <div class="text-center bg-[#F8F9FA] p-5 rounded-2xl shadow-md border border-gray-100 hover:-translate-y-1 transition-transform">
                                            <div class="w-20 h-20 mx-auto rounded-full overflow-hidden mb-3 border-4 border-white shadow-sm">
                                                <img src="{{ $member->photo ? asset('storage/' . $member->photo) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&size=100&background=1E234B&color=fff' }}" class="w-full h-full object-cover" alt="{{ $member->name }}">
                                            </div>
                                            <h4 class="font-bold text-[#1E234B] text-sm mb-1 leading-tight">{{ $member->name }}</h4>
                                            <p class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">{{ $member->mobile ?? 'PTA Member' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Staff Directory Tab --}}
                        @if($institution->staffs->count() > 0)
                            <div id="pane-staffs" x-show="activeTab === 'staffs'" x-transition.opacity.duration.400ms style="display: none;">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-6 bg-[#FFD700] rounded-sm"></div>
                                        <h2 class="text-2xl font-bold text-[#1E234B]">Staff Directory</h2>
                                    </div>
                                    <div class="bg-[#F8F9FA] px-4 py-2 rounded-xl border border-gray-100 flex items-center gap-3 shadow-sm">
                                        <svg class="w-4 h-4 text-[#1E234B]" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                        <span class="text-xs font-bold text-[#1E234B] uppercase tracking-widest">{{ $institution->staffs->count() }} members</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($institution->staffs as $staff)
                                        <div class="flex flex-col items-center bg-white border border-gray-100 rounded-3xl p-6 text-center shadow-lg hover:shadow-2xl transition-all duration-500 group border-b-4 border-b-transparent hover:border-b-[#FFD700]">
                                            {{-- Profile Image --}}
                                            <div class="relative mb-5">
                                                <div class="staff-card-img {{ !$staff->photo ? 'staff-avatar-placeholder' : '' }}">
                                                    @if($staff->photo)
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
                                                    @if($staff->subject)
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

                    </div>
                @else
                    <div class="py-12 text-center text-gray-400 bg-[#F8F9FA] rounded-2xl border border-dashed border-gray-200">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="currentColor" viewBox="0 0 24 24"><path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                        <p class="text-sm font-medium uppercase tracking-widest">Details are currently being updated.</p>
                    </div>
                @endif
            </div>

            {{--
                =======================================================
                RIGHT SIDEBAR
                =======================================================
            --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24">
                    <div class="bg-[#F8F9FA] rounded-3xl border border-gray-100 p-8 shadow-md relative overflow-hidden">

                        <div class="relative z-10 flex flex-col gap-6">
                            <div class="flex items-center gap-4 border-b border-gray-200 pb-6 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-[#1E234B] text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                </div>
                                <h3 class="font-bold text-[#1E234B] leading-tight uppercase tracking-tight text-base text-left">{{ $institution->name }}</h3>
                            </div>

                            <ul class="space-y-5">
                                @if($institution->website)
                                    <li>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Website</span>
                                        <a href="{{ $institution->website }}" target="_blank" class="flex items-center gap-3 text-sm font-bold text-[#1E234B] hover:text-[#FFD700] transition-colors">
                                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                            {{ str_replace(['http://', 'https://'], '', $institution->website) }}
                                        </a>
                                    </li>
                                @endif
                                @if($institution->phone)
                                    <li>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Phone</span>
                                        <a href="tel:{{ $institution->phone }}" class="flex items-center gap-3 text-sm font-bold text-[#1E234B] hover:text-[#FFD700] transition-colors">
                                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $institution->phone }}
                                        </a>
                                    </li>
                                @endif
                                @if($institution->address)
                                    <li>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Address</span>
                                        <div class="flex items-start gap-3 text-sm font-medium text-gray-600 leading-relaxed">
                                            <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span>{{ $institution->address }}</span>
                                        </div>
                                    </li>
                                @endif
                            </ul>

                            @if($institution->social_links)
                                @php
                                    $socials = is_array($institution->social_links) ? $institution->social_links : json_decode($institution->social_links, true);
                                    $hasSocials = collect($socials)->filter()->count() > 0;
                                @endphp
                                @if($hasSocials)
                                <div class="pt-5 border-t border-gray-200 mt-2">
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Social Connect</span>
                                    <div class="flex items-center gap-3">
                                        @foreach(['facebook', 'instagram', 'linkedin', 'youtube'] as $network)
                                            @if(isset($socials[$network]) && $socials[$network])
                                                <a href="{{ $socials[$network] }}" target="_blank" class="w-10 h-10 rounded-full bg-white border border-gray-100 text-[#1E234B] flex items-center justify-center hover:bg-[#1E234B] hover:-translate-y-1 hover:text-[#FFD700] transition-all shadow-sm">
                                                    <i class="bi bi-{{ $network }} text-lg"></i>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--
    =======================================================
    FULL WIDTH IMAGE GALLERY
    =======================================================
--}}
@if($institution->galleries->count() > 0)
    <section class="border-t border-gray-100 bg-white py-8 md:py-12 relative z-10">
        <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center gap-3 mb-8 md:mb-10">
                <div class="w-1.5 h-8 bg-[#FFD700] rounded-sm"></div>
                <h2 class="text-3xl md:text-4xl font-bold text-[#1E234B] tracking-tight text-center">Campus Gallery</h2>
                <div class="w-1.5 h-8 bg-[#FFD700] rounded-sm"></div>
            </div>

            <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
                @foreach($institution->galleries as $img)
                    <div class="break-inside-avoid relative group rounded-2xl overflow-hidden bg-[#F8F9FA] shadow-sm">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-auto object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700 transform cursor-pointer rounded-2xl" alt="Gallery Image" onclick="openLightbox('{{ asset('storage/' . $img->image_path) }}')">
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors pointer-events-none rounded-2xl"></div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Lightbox --}}
        <div id="lightbox" class="fixed inset-0 z-50 bg-[#1E234B]/95 hidden items-center justify-center p-4 backdrop-blur-md" onclick="this.classList.add('hidden'); this.classList.remove('flex');">
            <img id="lightbox-img" src="" class="max-w-full max-h-[90vh] object-contain rounded drop-shadow-2xl scale-95 transition-transform" alt="Zoomed">
            <button class="absolute top-6 right-6 text-[#1E234B] bg-white hover:bg-[#FFD700] rounded-full w-12 h-12 flex items-center justify-center text-xl transition-colors shadow-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <script>
            function openLightbox(src) {
                document.getElementById('lightbox-img').src = src;
                const lb = document.getElementById('lightbox');
                lb.classList.remove('hidden');
                lb.classList.add('flex');
                setTimeout(() => document.getElementById('lightbox-img').classList.replace('scale-95', 'scale-100'), 50);
            }
        </script>
    </section>
@endif

{{--
    =======================================================
    OTHER INSTITUTES (Mapped to New Premium Standard)
    =======================================================
--}}
@if($otherInstitutions && $otherInstitutions->count() > 0)
    <section class="bg-[#F8F9FA] py-8 md:py-12 border-t border-gray-200">
        <div class="max-w-[1500px] w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 md:mb-10 text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1E234B] mb-2">
                    Other Institutes
                </h2>
                <div class="w-16 h-1 bg-[#FFD700] rounded-full mx-auto mb-6"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                @foreach($otherInstitutions as $inst)
                    {{-- Clean Card Design matching 'Institutions Grid' standard --}}
                    <div class="flex flex-col h-full bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 group/card overflow-hidden"
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        {{-- Fixed Aspect Ratio Image Wrapper --}}
                        <div class="relative aspect-4/3 overflow-hidden bg-[#F8F9FA] shrink-0 border-b border-gray-50">
                            {{-- Category Badge --}}
                            <span class="absolute top-4 right-4 z-20 bg-[#FFD700] px-3 py-1 rounded-full text-[10px] font-bold uppercase text-gray-900 tracking-wider shadow-sm">
                                {{ $inst->category_label }}
                            </span>

                            @if($inst->featured_image)
                                <img src="{{ asset('storage/' . $inst->featured_image) }}"
                                     class="w-full h-full object-cover transform group-hover/card:scale-105 transition-transform duration-700 ease-in-out"
                                     alt="{{ $inst->name }}">
                            @else
                                {{-- Fallback Graphic --}}
                                <div class="w-full h-full flex flex-col items-center justify-center text-center p-6 gap-3 bg-gray-100">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v1H9V7zm5 0h1v1h-1V7zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1zm-3 4H2v5h12v-5zm3 0h1v1h-1v-1z"></path>
                                    </svg>
                                    <span class="text-sm sm:text-base font-bold text-gray-400 uppercase tracking-widest leading-tight">
                                        {{ $inst->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Content --}}
                        <div class="p-6 flex flex-col grow bg-[#F8F9FA]">
                            <h3 class="text-xl font-bold text-[#1E234B] mb-5 group-hover/card:text-[#FFD700] transition-colors duration-300 leading-tight">
                                {{ $inst->name }}
                            </h3>

                            <div class="mt-auto space-y-4 mb-6">
                                {{-- Curriculum --}}
                                <div class="flex items-center gap-3 text-gray-600">
                                    <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-[#1E234B] shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72l5 2.73 5-2.73v3.72z"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Curriculum</span>
                                        <span class="text-sm font-semibold text-gray-800 leading-none mt-0.5">{{ $inst->curriculum ?? 'Not Specified' }}</span>
                                    </div>
                                </div>

                                {{-- Location --}}
                                <div class="flex items-center gap-3 text-gray-600">
                                    <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-[#1E234B] shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Location</span>
                                        <span class="text-sm font-semibold text-gray-800 leading-none mt-0.5">{{ $inst->city ?? 'Campus' }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('institutions.show', $inst->slug) }}"
                               class="mt-auto flex items-center justify-center gap-2 w-full px-6 py-3 text-sm font-bold text-[#1E234B] bg-white border border-gray-200 rounded-full transition-all duration-300 hover:border-[#1E234B] hover:bg-[#1E234B] hover:text-white group/btn">
                                Explore Center
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@push('scripts')
<script>
    function scrollCategories(amount) {
        document.getElementById('category-scroll').scrollBy({ left: amount, behavior: 'smooth' });
    }

    document.addEventListener('DOMContentLoaded', function() {
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

             const btnLeft = document.getElementById('btn-left');
             const btnRight = document.getElementById('btn-right');

             const canScroll = scrollWidth > clientWidth + 5;

             if (!canScroll) {
                 if (btnLeft) btnLeft.style.setProperty('display', 'none', 'important');
                 if (btnRight) btnRight.style.setProperty('display', 'none', 'important');
                 return;
             }

             const atLeft = currentScroll <= 15;
             const atRight = currentScroll >= (scrollWidth - clientWidth - 15);

             if (btnLeft) btnLeft.style.setProperty('display', atLeft ? 'none' : 'flex', atLeft ? 'important' : '');
             if (btnRight) btnRight.style.setProperty('display', atRight ? 'none' : 'flex', atRight ? 'important' : '');

             // Edge-fade toggling
             if (atLeft) wrapper.classList.remove('has-scroll-left');
             else wrapper.classList.add('has-scroll-left');

             if (atRight) wrapper.classList.remove('has-scroll-right');
             else wrapper.classList.add('has-scroll-right');
        };

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            isDragging = false;
            slider.classList.add('drag-active');
            startX = e.clientX;
            scrollLeft = slider.scrollLeft;
        });

        window.addEventListener('mouseup', () => {
            if (!isDown) return;
            isDown = false;
            slider.classList.remove('drag-active');
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            const x = e.clientX;
            const walk = (x - startX) * 2;
            if (Math.abs(walk) > 5) isDragging = true;
            slider.scrollLeft = scrollLeft - walk;
            updateIndicators();
        });

        slider.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                slider.scrollLeft += e.deltaY;
                updateIndicators();
            }
        }, { passive: false });

        slider.addEventListener('click', (e) => {
            if (isDragging) {
                e.preventDefault();
                e.stopPropagation();
                isDragging = false;
            }
        }, true);

        slider.addEventListener('scroll', updateIndicators);
        window.addEventListener('resize', updateIndicators);
        setTimeout(updateIndicators, 200);
        slider.addEventListener('touchmove', updateIndicators, { passive: true });
    });
</script>
@endpush
@endsection
