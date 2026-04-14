@extends('layouts.app')

@section('title', $activeSection->title)

@section('content')
    <x-breadcrumb-banner 
        :image="$activeSection->breadcrumb_image" 
        :title="$activeSection->title" 
        :breadcrumbs="$breadcrumbTrail"
        :note="$activeSection->breadcrumb_note"
    />

    @php
        $content = json_decode($activeSection->content, true);
        $blocks = $content['blocks'] ?? $content ?? [];
        $sidebarMode = $content['sidebarMode'] ?? 'default';
        $sidebarItems = $content['sidebarItems'] ?? [];
    @endphp

    <style>
        section>h2 {
            position: sticky;
            top: 0;
            background: #f9fafb;
            z-index: 10;
        }

        .scroll-mt-24 {
            scroll-margin-top: 6rem;
        }

        .sidebar-link.active {
            background-color: #013954;
            color: white;
        }
    </style>

    <section class="container px-4 py-10 mx-auto" x-data="{ activeSectionId: '' }">
        <div class="grid grid-cols-1 gap-8 {{ $sidebarMode === 'hidden' ? '' : 'md:grid-cols-4' }}">

            @if ($sidebarMode !== 'hidden')
                {{-- Sidebar --}}
                <aside class="space-y-2 md:sticky md:top-24 h-fit">
                    <h2 class="pb-2 mb-4 text-lg font-bold text-gray-900 border-b border-gray-200">
                        {{ $activeSection->title }}
                    </h2>

                    @if ($sidebarMode === 'custom' || $sidebarMode === 'inherit')
                        @php
                            $itemsToDisplay = $sidebarItems;
                            $navParent = $activeSection; // Default to current page
                            
                            if ($sidebarMode === 'inherit' && !empty($content['inheritedPageId'])) {
                                $sourcePage = \App\Models\Page::find($content['inheritedPageId']);
                                if ($sourcePage) {
                                    $navParent = $sourcePage; // Use the source page as the parent
                                    $sourceContent = json_decode($sourcePage->content, true);
                                    $itemsToDisplay = $sourceContent['sidebarItems'] ?? [];
                                }
                            }
                            
                            $isParentActive = request()->is(trim($navParent->slug, '/')) || request()->url() === url($navParent->slug);
                        @endphp

                        <ul class="space-y-1">
                            {{-- 1. Main Page Link (Always at top) --}}
                            <li>
                                <a href="{{ url($navParent->slug) }}"
                                   class="block px-4 py-3 text-sm font-extrabold transition-all duration-200 rounded-xl {{ $isParentActive ? 'bg-[#013954] text-white shadow-lg ring-2 ring-blue-100' : 'bg-gray-50 text-gray-700 hover:bg-white hover:text-[#013954] hover:shadow-md' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full {{ $isParentActive ? 'bg-blue-400 animate-pulse' : 'bg-gray-300' }}"></div>
                                            <span>{{ $navParent->title }}</span>
                                        </div>
                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    </div>
                                </a>
                            </li>

                            @if (!empty($itemsToDisplay))
                                @foreach ($itemsToDisplay as $item)
                                    @php
                                        $targetId = $item['targetId'] ?? '';
                                        $url = $item['type'] === 'section' ? '#section-' . $targetId : url($item['targetUrl'] ?? '#');
                                        $isPageLink = $item['type'] === 'page';
                                        
                                        // Initial active state for page links
                                        $isUrlActive = $isPageLink && (request()->is(trim($item['targetUrl'] ?? '', '/')) || request()->url() === url($item['targetUrl'] ?? ''));
                                    @endphp
                                    <li @if($item['type'] === 'section') 
                                            x-intersect:enter="activeSectionId = '{{ $targetId }}'"
                                            x-intersect:leave="if(activeSectionId === '{{ $targetId }}') activeSectionId = ''"
                                        @endif>
                                        <a href="{{ $url }}"
                                            @if($item['type'] === 'section') 
                                                @click.prevent="document.getElementById('section-{{ $targetId }}')?.scrollIntoView({behavior: 'smooth'})"
                                                :class="activeSectionId === '{{ $targetId }}' ? 'bg-[#013954] text-white shadow-lg ring-2 ring-blue-100' : 'bg-gray-50 text-gray-700 hover:bg-white hover:text-[#013954] hover:shadow-md'"
                                            @else
                                                class="block px-4 py-2.5 text-sm font-medium transition-all duration-200 rounded-xl {{ $isUrlActive ? 'bg-[#013954] text-white shadow-lg ring-2 ring-blue-100' : 'bg-gray-50 text-gray-700 hover:bg-white hover:text-[#013954] hover:shadow-md' }}"
                                            @endif
                                            class="block px-4 py-2.5 text-sm font-medium transition-all duration-200 rounded-xl">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    @if($item['type'] === 'section')
                                                        <div class="w-1.5 h-1.5 rounded-full" :class="activeSectionId === '{{ $targetId }}' ? 'bg-blue-300 animate-pulse' : 'bg-gray-300'"></div>
                                                    @else
                                                        <div class="w-1.5 h-1.5 rounded-full {{ $isUrlActive ? 'bg-blue-300 animate-pulse' : 'bg-gray-300' }}"></div>
                                                    @endif
                                                    <span>{{ $item['label'] }}</span>
                                                </div>
                                                @if($isPageLink)
                                                    <svg class="w-3.5 h-3.5 {{ $isUrlActive ? 'text-white' : 'opacity-30' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5" :class="activeSectionId === '{{ $targetId }}' ? 'text-white' : 'opacity-30'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7"></path></svg>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    @else
                        @include('frontend.pages.partials.menu', [
                            'menus' => $menus,
                            'activeSection' => $activeSection,
                        ])
                    @endif
                </aside>
            @endif

            {{-- ==================== MAIN CONTENT ==================== --}}
            <main
                class="p-4 space-y-6 bg-white shadow-md rounded-2xl {{ $sidebarMode === 'hidden' ? 'w-full' : 'md:col-span-3' }}">

                @if (!empty($blocks) && is_array($blocks))
                    @foreach ($blocks as $block)
                        <div @if($block['type'] === 'section') id="section-{{ $block['id'] }}" class="scroll-mt-24" @endif>
                            <x-page-block :block="$block" />
                        </div>
                    @endforeach
                @else
                    <p class="italic text-gray-400">No content found for this page.</p>
                @endif

                @if (!empty($activeSection->pdf_path))
                    <div class="mt-8">
                        <x-pdf-viewer :src="asset('storage/' . $activeSection->pdf_path)" />
                    </div>
                @endif
            </main>

        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
             // Hash handling for direct links
             if(window.location.hash) {
                 const target = document.querySelector(window.location.hash);
                 if(target) {
                     setTimeout(() => {
                         window.scrollTo({
                             top: target.offsetTop - 100,
                             behavior: 'smooth'
                         });
                     }, 500);
                 }
             }
        });
    </script>
@endsection