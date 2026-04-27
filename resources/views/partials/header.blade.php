@php
    use App\Models\Menu;

    // --- 1. HELPER FUNCTION FOR TITLE FORMATTING ---
    if (!function_exists('getMenuLabel')) {
        function getMenuLabel($title)
        {
            return ucwords(strtolower($title));
        }
    }

    // --- 2. Dynamic Fetch & Filter Menus ---
    $rawMenus = Menu::where('status', 1)->whereNull('parent_id')->with('childrenRecursive')->orderBy('order')->get();

    $menus = $rawMenus->filter(function ($value, $key) {
        return !str_starts_with((string) $value->order, '100');
    });

    // --- 3. Fetch Settings ---
    $backgroundAudio = setting('background_audio');
    $topBannerImage = setting('top_banner_image');

    // Fetch Social Media Links
    $email = setting('email');
    $phone = setting('phone');
    $facebook = setting('facebook_url');
    $twitter = setting('twitter_url');
    $instagram = setting('instagram_url');
    $youtube = setting('youtube_url');
    $linkedin = setting('linkedin_url');
    $libraryEnabled = setting('library_enabled');
    $collegeSongLyrics = setting('college_song_lyrics');

    // --- 4. Flatten Menus for Real-Time Search ---
    $searchableMenus = collect();
    $flattenMenus = function ($items) use (&$flattenMenus, &$searchableMenus) {
        foreach ($items as $item) {
            $searchableMenus->push([
                'title' => getMenuLabel($item->title),
                'link' => $item->link ?? '#',
            ]);
            if ($item->children->count()) {
                $flattenMenus($item->children);
            }
        }
    };
    $flattenMenus($menus);

    // --- 5. Notifications Marquee Logic ---
    $notifService = app(\App\Services\NotificationService::class);
    $marqueeNotifications = $notifService->getMarqueeNotifications();

    // --- 6. Helper Function for Standard Dropdowns (Desktop) ---
    if (!function_exists('renderDesktopRecursive')) {
        function renderDesktopRecursive($items, $level = 0)
        {
            if ($items->isEmpty()) {
                return '';
            }
            $paddingLeft = 14 + $level * 12;
            $wrapperClass = $level === 0 ? 'bg-gray-50 py-1 rounded-sm w-full' : 'mt-0.5 w-full';
            $html = '<ul class="space-y-0.5 ' . $wrapperClass . '">';
            foreach ($items as $item) {
                $hasChildren = $item->children->count() > 0;
                $title = getMenuLabel($item->title);
                $html .= '<li class="w-full">';
                $html .=
                    '<a href="' .
                    $item->link .
                    '" class="block text-xs font-normal text-gray-600 hover:text-theme hover:underline transition duration-150 wrap-break-word whitespace-normal" style="padding-left: ' .
                    $paddingLeft .
                    'px;">' .
                    $title .
                    '</a>';
                if ($hasChildren) {
                    $html .= renderDesktopRecursive($item->children, $level + 1);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
    }

    // --- 7. Mobile Recursive Function ---
    if (!function_exists('renderMobileRecursive')) {
        function renderMobileRecursive($items, $level = 0)
        {
            if ($items->isEmpty()) {
                return '';
            }
            $borderClass = $level >= 0 ? 'border-l-2 border-theme ml-4 opacity-70' : '';
            $bgClass = $level % 2 == 0 ? 'bg-theme-light' : 'bg-white';
            $html = '<div class="flex flex-col space-y-0.5 ' . $borderClass . ' ' . $bgClass . '">';
            foreach ($items as $item) {
                $hasChildren = $item->children->count() > 0;
                $title = getMenuLabel($item->title);
                $link = $item->link ?? '#';
                $html .= '<div x-data="{ open: false }" class="w-full">';
                $html .= '<div class="flex items-center justify-between w-full pr-4">';
                $html .=
                    '<a href="' .
                    $link .
                    '" class="flex-1 py-2.5 pl-3 text-sm font-medium text-theme hover:bg-theme-light rounded-l-md transition">' .
                    $title .
                    '</a>';
                if ($hasChildren) {
                    $html .=
                        '<button @click="open = !open" class="p-2.5 text-theme hover:bg-theme-light rounded-r-md transition">';
                    $html .=
                        '<svg :class="open ? \'rotate-180\' : \'\'" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>';
                    $html .= '</button>';
                }
                $html .= '</div>';
                if ($hasChildren) {
                    $html .= '<div x-show="open" x-cloak x-collapse class="pb-2">';
                    $html .= renderMobileRecursive($item->children, $level + 1);
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
            return $html;
        }
    }
@endphp


<style>
    /* ========================================================
       🎨 THEME COLORS (CHANGE THESE VARIABLES)
       Aapko colors change karne hain toh bas yahan update karein.
       ======================================================== */
    :root {
        --theme-color: var(--primary-color, #000165);
        /* Main Color */
        --theme-hover: var(--primary-hover, #00014d);
        /* Darker Hover Color */
    }

    /* --- Theme Utility Classes (Do not change below) --- */
    .text-theme {
        color: var(--theme-color) !important;
    }

    .hover\:text-theme:hover {
        color: var(--theme-color) !important;
    }

    .bg-theme {
        background-color: var(--theme-color) !important;
    }

    .hover\:bg-theme-dark:hover {
        background-color: var(--theme-hover) !important;
    }

    .border-theme {
        border-color: var(--theme-color) !important;
    }

    .border-t-theme {
        border-top-color: var(--theme-color) !important;
    }

    .border-l-theme {
        border-left-color: var(--theme-color) !important;
    }

    /* Auto-generates a light transparent version of the main color for hovers/dropdowns */
    .bg-theme-light {
        background-color: color-mix(in srgb, var(--theme-color) 8%, transparent) !important;
    }

    .hover\:bg-theme-light:hover {
        background-color: color-mix(in srgb, var(--theme-color) 12%, transparent) !important;
    }

    /* ======================================================== */

    /* Theme Setup & Base Typography */


    /* Unique Shield Shape */
    .shield-shape {
        clip-path: polygon(0 0, 100% 0, 100% 80%, 50% 100%, 0 80%);
    }

    /* Animation Classes */
    .animate-fadeInUp {
        animation: fadeInUp 0.3s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes music-bar {

        0%,
        100% {
            height: 20%;
        }

        50% {
            height: 100%;
        }
    }

    [x-cloak] {
        display: none !important;
    }

    /* Scrollbar */
    .thin-scrollbar::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    .thin-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }

    .thin-scrollbar::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 10px;
    }

    .thin-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #9ca3af;
    }

    /* Marquee Setup */
    .marquee-container {
        overflow: hidden;
        white-space: nowrap;
        width: 100%;
        position: relative;
    }

    .marquee-track {
        display: inline-flex;
        gap: 3rem;
        animation: marquee 60s linear infinite;
        will-change: transform;
    }

    .marquee-track:hover {
        animation-play-state: paused;
    }

    .marquee-track span {
        display: inline-block;
        white-space: nowrap;
    }

    .marquee-link {
        color: #fdf2f8;
        text-decoration: underline;
        font-weight: 500;
        transition: opacity 0.3s;
    }

    .marquee-link:hover {
        opacity: 0.8;
    }

    @keyframes marquee {
        0% {
            transform: translate3d(0, 0, 0);
        }

        100% {
            transform: translate3d(-50%, 0, 0);
        }
    }
</style>

<div class="relative w-full z-50" x-data="{ mobileMenuOpen: false }">




    <!-- ========================================== -->
    <!-- 2. TOP DARK BANNER (Announcements etc.)    -->
    <!-- ========================================== -->
    <div
        class="w-full bg-theme text-white flex items-center h-14 md:h-12 px-4 lg:px-8 text-xs lg:text-sm border-b border-white/10 shadow-sm relative z-40">

        <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between w-full h-full gap-2 md:gap-4 overflow-hidden py-1">

            <!-- Left Side: Welcome Text + Marquee + Music -->
            <div class="flex items-center gap-3 w-full md:w-auto flex-1 overflow-hidden">
                <!-- Cap Icon + Welcome -->
                <div class="flex items-center gap-2 whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                    </svg>
                    <span
                        class="font-bold text-sm md:text-xl lg:text-xl tracking-wider uppercase drop-shadow-sm">Welcome
                        To VVES</span>
                </div>

                <!-- Separator -->
                <span class="hidden lg:block w-px h-4 bg-white/30 shrink-0"></span>

                <!-- Dynamic Marquee -->
                <div class="marquee-container flex-1">
                    <div class="marquee-track items-center text-[13px]">
                        {{-- @if (count($marqueeNotifications))
                        @foreach ($marqueeNotifications as $n)
                        <span>{{ $n->icon ?: '🔔' }} {{ $n->title }} @if ($n->href)— <a href="{{ $n->href }}"
                                class="marquee-link">{{ $n->button_name ?: 'Click Here' }}</a>@endif</span>
                        @endforeach
                        <!-- Repeat for seamless loop -->
                        @foreach ($marqueeNotifications as $n)
                        <span>{{ $n->icon ?: '🔔' }} {{ $n->title }} @if ($n->href)— <a href="{{ $n->href }}"
                                class="marquee-link">{{ $n->button_name ?: 'Click Here' }}</a>@endif</span>
                        @endforeach
                        @else
                        <span>Welcome to the new academic year! — <a href="#" class="marquee-link">Explore
                                Campus</a></span>
                        @endif --}}
                    </div>
                </div>

                <!-- Music Player Component -->
                <div class="hidden xl:flex items-center gap-2 shrink-0 border-l border-white/20 pl-3" x-data="{
                        playing: false,
                        audio: null,
                        url: '{{ asset('storage/' . $backgroundAudio) }}',
                        toggleMusic() {
                            if (!this.audio) {
                                this.audio = new Audio(this.url);
                                this.audio.addEventListener('ended', () => this.playing = false);
                            }
                            if (this.playing) { this.audio.pause();
                                this.playing = false; } else { this.audio.play();
                                this.playing = true; }
                        }
                    }">
                    <button @click="toggleMusic()"
                        class="flex items-center gap-1.5 focus:outline-none hover:text-gray-200 transition-colors">
                        <svg x-show="!playing" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                        <svg x-show="playing" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                        <span class="text-[11px] font-medium uppercase tracking-wider">Song</span>
                        <div x-show="playing" x-cloak class="flex gap-px h-2.5 items-end ml-1">
                            <span class="w-px bg-white animate-[music-bar_0.5s_ease-in-out_infinite]"></span>
                            <span class="w-px bg-white animate-[music-bar_0.7s_ease-in-out_infinite]"></span>
                            <span class="w-px bg-white animate-[music-bar_0.4s_ease-in-out_infinite]"></span>
                        </div>
                    </button>
                    @if ($collegeSongLyrics)
                        <a href="{{ $collegeSongLyrics }}" target="_blank"
                            class="text-[10px] font-medium hover:underline text-white/80">(Lyrics)</a>
                    @endif
                </div>
            </div>

            <!-- Right Side: Top Links -->
            <div class="hidden md:flex items-center gap-3 shrink-0 text-[12px] font-medium opacity-90">
                <span class="w-px h-3 bg-white/40"></span>
                <a href="#" class="hover:text-gray-200 transition">Alumni</a>
                <span class="w-px h-3 bg-white/40"></span>
                <a href="#" class="hover:text-gray-200 transition">CSR</a>
            </div>

        </div>
    </div>


    <!-- ========================================== -->
    <!-- 3. MAIN WHITE NAVIGATION BAR               -->
    <!-- ========================================== -->
    <!-- Added more padding-left and reduced font sizes/gaps to prevent wrapping & cut-offs -->
    <div
        class="w-full bg-white shadow-md flex items-center justify-between h-20 lg:h-24 px-4 md:px-8 lg:px-12 relative z-30">

        <!-- Logo (Now Inline) -->
        <a href="{{ url('/') }}" class="flex items-center shrink-0 py-2">
            @if (setting('college_logo'))
                <img loading="lazy" decoding="async" src="{{ asset('storage/' . setting('college_logo')) }}" alt="VVES Logo"
                    class="h-14 md:h-16 lg:h-20 w-auto object-contain transition-transform duration-300 hover:scale-105">
            @else
                <div class="bg-theme p-2 rounded-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v7"></path>
                    </svg>
                </div>
            @endif
        </a>

        <!-- Center Desktop Menus -->
        <div class="hidden lg:flex items-center h-full flex-1 justify-end overflow-hidden px-6">
            <ul
                class="flex items-center h-full gap-3 xl:gap-5 whitespace-nowrap overflow-x-auto thin-scrollbar pb-1 pt-1">
                @foreach ($menus as $menu)
                    @php
                        $hasChildren = $menu->children->count() > 0;
                        $isMegaMenu =
                            $menu->children->count() >= 6 ||
                            ($menu->children->count() == 1 &&
                                $menu->children->first() &&
                                $menu->children->first()->children->count() >= 6);
                        $isStandardDropdown = $hasChildren && !$isMegaMenu;
                        $isActive = Request::is(trim(parse_url($menu->url, PHP_URL_PATH), '/'));
                    @endphp

                    <li x-data="{ openSub: false, activeTabIndex: 0 }"
                        class="h-full flex items-center shrink-0 {{ $isStandardDropdown ? 'relative' : 'static' }}">

                        <a href="{{ $menu->link }}" @if ($hasChildren) @mouseenter="openSub = true"
                        @mouseleave="openSub = false" @endif class="flex items-center gap-1 h-full text-[13px] xl:text-[14px] font-medium transition duration-200
                                      {{ $isActive ? 'text-theme' : 'text-gray-800 hover:text-theme' }}">

                            {{ getMenuLabel($menu->title) }}

                            @if ($hasChildren)
                                <svg class="w-3.5 h-3.5 mt-0.5 transition-transform duration-200 {{ $isActive ? 'text-theme' : 'text-gray-500' }}"
                                    :class="openSub ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            @endif
                        </a>

                        <!-- MEGA MENU STRUCTURE -->
                        @if ($isMegaMenu)
                            <div x-show="openSub" x-cloak @mouseenter="openSub = true" @mouseleave="openSub = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute left-0 top-full z-50 w-full text-gray-800">
                                <div
                                    class="mx-auto overflow-hidden bg-white shadow-xl max-w-[1250px] border-b border-b-gray-200 rounded-b-lg border-t-2 border-t-theme">
                                    <div class="flex">
                                        <!-- Level 2 Tabs -->
                                        <div
                                            class="w-1/4 bg-gray-50 border-r border-gray-200 py-2 max-h-120 overflow-y-auto thin-scrollbar">
                                            <ul class="space-y-0.5">
                                                @foreach ($menu->children as $index => $tabItem)
                                                    <li>
                                                        <a href="{{ $tabItem->link }}" @mouseenter="activeTabIndex = {{ $index }}"
                                                            :class="activeTabIndex === {{ $index }} ?
                                                                            'bg-white text-theme font-semibold shadow-sm border-l-4 border-l-theme' :
                                                                            'text-gray-700 hover:bg-gray-100 border-l-4 border-l-transparent'"
                                                            class="block transition-all duration-200 cursor-pointer px-4 py-2.5 text-sm">
                                                            {{ getMenuLabel($tabItem->title) }}
                                                            <span x-show="activeTabIndex === {{ $index }}"
                                                                class="float-right text-theme">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    stroke-width="2" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 5l7 7-7 7">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <!-- Level 3 Content -->
                                        <div class="w-3/4 p-6 bg-white max-h-120 overflow-y-auto thin-scrollbar">
                                            @foreach ($menu->children as $index => $tabItem)
                                                <div x-show="activeTabIndex === {{ $index }}" x-cloak
                                                    class="space-y-4 animate-fadeInUp">
                                                    <div class="grid grid-cols-3 gap-x-8 gap-y-6 min-w-0">
                                                        @foreach ($tabItem->children as $sub)
                                                            @if ($sub->children->count() > 0)
                                                                <div class="flex flex-col w-full whitespace-normal">
                                                                    <a href="{{ $sub->link }}"
                                                                        class="block mb-2 text-sm font-bold text-gray-800 border-b-2 border-gray-100 pb-1 hover:text-theme hover:border-gray-300 transition-colors">
                                                                        {{ getMenuLabel($sub->title) }}
                                                                    </a>
                                                                    <div
                                                                        class="max-h-80 w-full overflow-y-auto overflow-x-hidden thin-scrollbar pr-2">
                                                                        {!! renderDesktopRecursive($sub->children) !!}
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="py-1 whitespace-normal">
                                                                    <a href="{{ $sub->link }}"
                                                                        class="block text-sm font-normal text-gray-700 hover:text-theme hover:translate-x-1 transition-transform duration-200">
                                                                        {{ getMenuLabel($sub->title) }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STANDARD DROPDOWN -->
                        @elseif ($isStandardDropdown)
                            <div x-show="openSub" x-cloak @mouseenter="openSub = true" @mouseleave="openSub = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute left-0 top-full mt-0 text-gray-800 bg-white border-t-2 border-t-theme shadow-xl z-50 rounded-b-lg">
                                <div class="p-2 min-w-[220px]">
                                    <ul class="flex flex-col py-1">
                                        @foreach ($menu->children as $child)
                                            <li x-data="{ openChild: false }" @mouseenter="openChild = true"
                                                @mouseleave="openChild = false" class="relative">
                                                <a href="{{ $child->link }}"
                                                    class="flex items-center justify-between px-3 py-2 text-sm text-gray-700 hover:bg-theme-light hover:text-theme transition duration-150 rounded-md whitespace-normal">
                                                    <span>{{ getMenuLabel($child->title) }}</span>
                                                    @if ($child->children->count())
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    @endif
                                                </a>
                                                @if ($child->children->count())
                                                    <ul x-show="openChild" x-cloak x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 -translate-x-2"
                                                        x-transition:enter-end="opacity-100 translate-x-0"
                                                        class="absolute left-full top-0 ml-1 bg-white border border-gray-100 rounded-lg shadow-md min-w-[200px] z-50 p-1">
                                                        @foreach ($child->children as $subchild)
                                                            <li>
                                                                <a href="{{ $subchild->link }}"
                                                                    class="block px-4 py-1.5 text-sm text-gray-700 hover:bg-theme-light hover:text-theme transition duration-150 rounded-md whitespace-normal">
                                                                    {{ getMenuLabel($subchild->title) }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>



        <!-- Right Tools: Search, Hamburger, Apply Now -->
        <div class="flex items-center gap-3 lg:gap-5 shrink-0">

            <!-- Expanding Search Bar -->
            {{-- <div class="relative flex items-center" x-data="{
                    searchOpen: false, query: '', menus: {{ $searchableMenus->toJson() }}, results: [],
                    search() {
                        if (this.query.length < 2) { this.results = []; return; }
                        const q = this.query.toLowerCase();
                        this.results = this.menus.filter(m => m.title.toLowerCase().includes(q));
                    }
                }" @click.away="searchOpen = false; results = []">

                <button @click="searchOpen = !searchOpen"
                    class="text-gray-800 hover:text-theme transition-colors focus:outline-none">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Search Input Popover -->
                <div x-show="searchOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 top-full mt-4 w-64 md:w-80 bg-white rounded-lg shadow-xl border border-gray-100 p-2 z-50">
                    <input type="text" x-model="query" @input="search()" placeholder="Search site..."
                        x-ref="searchInput"
                        class="w-full px-4 py-2 bg-gray-50 border border-theme rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-theme">

                    <!-- Search Results -->
                    <div x-show="results.length > 0 && query.length >= 2"
                        class="mt-2 max-h-60 overflow-y-auto thin-scrollbar">
                        <ul class="flex flex-col">
                            <template x-for="result in results">
                                <li>
                                    <a :href="result.link"
                                        class="block px-3 py-2 text-sm text-gray-700 hover:bg-theme-light hover:text-theme rounded-md transition-colors">
                                        <span x-text="result.title"></span>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div x-show="query.length >= 2 && results.length === 0"
                        class="mt-2 p-2 text-sm text-center text-gray-500">
                        No results found.
                    </div>
                </div>
            </div> --}}

            <!-- Hamburger Button (Mobile) -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden flex items-center gap-2 text-gray-800 hover:text-theme transition focus:outline-none">

                <span class="text-xs font-bold uppercase tracking-wider">Menu</span>

                <svg x-show="!mobileMenuOpen" class="w-6 h-6 md:w-7 md:h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 md:w-7 md:h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Apply Now Button (Desktop) -->
            {{-- <a href="#"
                class="hidden lg:flex items-center gap-2 bg-theme hover:bg-theme-dark text-white px-5 xl:px-6 py-2 xl:py-2.5 rounded-full text-[13px] xl:text-[14px] font-medium transition-all shadow-md active:scale-95">
                Apply Now
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
            </a> --}}
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 4. MOBILE MENU DRAWER (SLIDE DOWN)         -->
    <!-- ========================================== -->
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="absolute top-full left-0 w-full bg-white shadow-2xl border-t border-gray-100 z-45 lg:hidden max-h-[80vh] overflow-y-auto thin-scrollbar">

        <!-- Mobile Apply Now Button -->
        {{-- <div class="p-4 border-b border-gray-100">
            <a href="#"
                class="flex justify-center items-center gap-2 w-full bg-theme text-white px-6 py-3 rounded-full text-sm font-medium transition-all shadow-sm">
                Apply Now
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
            </a>
        </div> --}}

        <!-- Mobile Menu Items -->
        <ul class="flex flex-col py-2 pb-6">
            @foreach ($menus as $menu)
                <li x-data="{ openSub: false }" class="border-b border-gray-50 last:border-0">
                    <div class="flex items-center justify-between w-full px-4 bg-white">
                        <a href="{{ $menu->link }}"
                            class="flex-1 py-3 text-[15px] font-bold text-theme hover:bg-gray-50 transition">
                            {{ getMenuLabel($menu->title) }}
                        </a>
                        @if ($menu->children->count())
                            <button @click="openSub = !openSub"
                                class="p-3 text-theme rounded-full transition bg-gray-50 hover:bg-theme-light">
                                <svg :class="openSub ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    @if ($menu->children->count())
                        <div x-show="openSub" x-cloak x-collapse class="bg-theme-light border-t border-gray-50 pb-2">
                            {!! renderMobileRecursive($menu->children) !!}
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        <!-- Socials & Counter (Mobile Footer) -->
        <div class="p-4 bg-gray-50 flex flex-col items-center gap-4 border-t border-gray-100">
            <!-- Social Icons mapped from settings -->
            <div class="flex gap-3">
                @if ($facebook)
                    <a href="{{ $facebook }}"
                        class="w-8 h-8 rounded-full bg-[#1877F2] text-white flex justify-center items-center"><svg
                            class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 3h-1.9v7A10 10 0 0 0 22 12" />
                        </svg></a>
                @endif
                @if ($instagram)
                    <a href="{{ $instagram }}"
                        class="w-8 h-8 rounded-full bg-[#E1306C] text-white flex justify-center items-center"><svg
                            class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.4.4.6.2 1 .4 1.4.8.4.4.7.8.8 1.4.2.5.3 1.2.4 2.4.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.4-.2.6-.4 1-.8 1.4-.4.4-.8.7-1.4.8-.5.2-1.2.3-2.4.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.4-.4-.6-.2-1-.4-1.4-.8-.4-.4-.7-.8-.8-1.4-.2-.5-.3-1.2-.4-2.4C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.4.2-.6.4-1 .8-1.4.4-.4.8-.7 1.4-.8.5-.2 1.2-.3 2.4-.4C8.4 2.2 8.8 2.2 12 2.2m0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.5.2-1.9.3-.5.2-.8.3-1.1.6-.3.3-.5.6-.6 1.1-.1.4-.3.9-.3 1.9-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.5.3 1.9.2.5.3.8.6 1.1.3.3.6.5 1.1.6.4.1.9.3 1.9.3 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1 0 1.5-.2 1.9-.3.5-.2.8-.3 1.1-.6.3-.3.5-.6.6-1.1.1-.4.3-.9.3-1.9.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c0-1-.2-1.5-.3-1.9-.2-.5-.3-.8-.6-1.1-.3-.3-.6-.5-1.1-.6-.4-.1-.9-.3-1.9-.3-1.2-.1-1.6-.1-4.7-.1m0 2.3a5.7 5.7 0 1 1 0 11.4 5.7 5.7 0 0 1 0-11.4m0 1.8a3.9 3.9 0 1 0 0 7.8 3.9 3.9 0 0 0 0-7.8M17.6 6a1.3 1.3 0 1 0 0 2.6 1.3 1.3 0 0 0 0-2.6z" />
                        </svg></a>
                @endif
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-theme">
                <span>TOTAL VISITORS:</span>
                <img src="https://hitwebcounter.com/counter/counter.php?page=21461883&style=0030&nbdigits=5&type=page&initCount=9999"
                    loading="lazy" Alt="Visitor Count" border="0" />
            </div>
        </div>
    </div>
</div>
