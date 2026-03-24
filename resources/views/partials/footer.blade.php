@php
    use App\Models\Menu;
    $topMenus = Menu::where('status', 1)
        ->whereNull('parent_id')
        ->orderBy('order')
        ->limit(8)
        ->get();

    $collegeName = setting('college_name', config('app.name'));

    // Dark Banner Image
    $footerBanner = setting('top_banner_image_dark');

    $address = setting('address');
    $email = setting('email');
    $phone = setting('phone');
    $facebook = setting('facebook_url');
    $twitter = setting('twitter_url');
    $instagram = setting('instagram_url');
    $youtube = setting('youtube_url');
    $linkedin = setting('linkedin_url');
    $footerAbout = setting('footer_about', 'Committed to academic excellence, innovation, and holistic development.');

    // Map URL from Admin Settings
    $mapUrl = setting('map_embed_url');

    $footerLinksRaw = setting('footer_links');
    $footerLinks = $footerLinksRaw ? json_decode($footerLinksRaw, true) : [];
@endphp

{{-- Background Updated to Primary Theme --}}
<footer class="mt-12 bg-(--primary-color) text-white relative font-roboto">

    {{-- SVG Wave Top Divider --}}
    <div class="absolute top-0 left-0 w-full overflow-hidden transform -translate-y-full leading-none">
        <svg aria-hidden="true" focusable="false" class="w-full h-8 sm:h-12" preserveAspectRatio="none" viewBox="0 0 1440 56">
            <path fill="var(--primary-color, #000165)" d="M0,24 C240,48 480,0 720,16 C960,32 1200,56 1440,24 L1440,56 L0,56 Z"></path>
        </svg>
    </div>

    <div class="px-6 pt-12 pb-10 mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4 md:gap-12">

            {{-- COLUMN 1: ABOUT & SOCIALS --}}
            <div class="space-y-6">
                {{-- Banner/Logo --}}
                @if ($footerBanner)
                    <div class="mb-4">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $footerBanner) }}" alt="{{ $collegeName }}" class="object-contain w-auto h-20 drop-shadow-md">
                    </div>
                @else
                    <div class="mb-4 text-2xl font-black tracking-wider uppercase text-white drop-shadow-md">
                        {{ $collegeName }}
                    </div>
                @endif

                <p class="text-[14px] leading-relaxed text-white/80 font-medium">
                    {{ $footerAbout }}
                </p>

                {{-- Social Icons --}}
                @if ($facebook || $twitter || $instagram || $youtube || $linkedin)
                    <div class="flex flex-wrap gap-3 pt-2">
                        @if ($facebook)
                            <a href="{{ setting('facebook_url') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-linear-to-tr hover:from-[#1877F2] hover:to-[#5195ee] hover:-translate-y-1 transition-all duration-300 border border-white/20 hover:border-transparent" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 3h-1.9v7A10 10 0 0 0 22 12" /></svg>
                            </a>
                        @endif
                        @if ($twitter)
                            <a href="{{ $twitter }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-[#1DA1F2] hover:-translate-y-1 transition-all duration-300 border border-white/20 hover:border-transparent" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.25 4.25 0 0 0 1.86-2.35 8.52 8.52 0 0 1-2.7 1.03 4.24 4.24 0 0 0-7.22 3.87A12.04 12.04 0 0 1 3.1 4.9a4.22 4.22 0 0 0 1.31 5.66 4.2 4.2 0 0 1-1.92-.53v.05a4.24 4.24 0 0 0 3.4 4.16 4.25 4.25 0 0 1-1.91.07 4.24 4.24 0 0 0 3.95 2.93A8.5 8.5 0 0 1 2 19.54a12.02 12.02 0 0 0 6.51 1.91c7.82 0 12.1-6.48 12.1-12.1l-.01-.55A8.64 8.64 0 0 0 22.46 6z" /></svg>
                            </a>
                        @endif
                        @if ($instagram)
                            <a href="{{ $instagram }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:-translate-y-1 transition-all duration-300 border border-white/20 hover:border-transparent" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.4.4.6.2 1 .4 1.4.8.4.4.7.8.8 1.4.2.5.3 1.2.4 2.4.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.4-.2.6-.4 1-.8 1.4-.4.4-.8.7-1.4.8-.5.2-1.2.3-2.4.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.4-.4-.6-.2-1-.4-1.4-.8-.4-.4-.7-.8-.8-1.4-.2-.5-.3-1.2-.4-2.4C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.4.2-.6.4-1 .8-1.4.4-.4.8-.7 1.4-.8.5-.2 1.2-.3 2.4-.4C8.4 2.2 8.8 2.2 12 2.2m0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.5.2-1.9.3-.5.2-.8.3-1.1.6-.3.3-.5.6-.6 1.1-.1.4-.3.9-.3 1.9-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.5.3 1.9.2.5.3.8.6 1.1.3.3.6.5 1.1.6.4.1.9.3 1.9.3 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1 0 1.5-.2 1.9-.3.5-.2.8-.3 1.1-.6.3-.3.5-.6.6-1.1.1-.4.3-.9.3-1.9.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c0-1-.2-1.5-.3-1.9-.2-.5-.3-.8-.6-1.1-.3-.3-.6-.5-1.1-.6-.4-.1-.9-.3-1.9-.3-1.2-.1-1.6-.1-4.7-.1m0 2.3a5.7 5.7 0 1 1 0 11.4 5.7 5.7 0 0 1 0-11.4m0 1.8a3.9 3.9 0 1 0 0 7.8 3.9 3.9 0 0 0 0-7.8M17.6 6a1.3 1.3 0 1 0 0 2.6 1.3 1.3 0 0 0 0-2.6z" /></svg>
                            </a>
                        @endif
                        @if ($linkedin)
                            <a href="{{ $linkedin }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-[#0077B5] hover:-translate-y-1 transition-all duration-300 border border-white/20 hover:border-transparent" aria-label="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 6.5a2.19 2.19 0 1 1-4.38 0 2.19 2.19 0 0 1 4.38 0M2.88 8.82h3.82v12.3H2.88zM9.08 8.82h3.66v1.68h.05c.51-.96 1.76-1.98 3.62-1.98 3.88 0 4.6 2.55 4.6 5.86v6.74h-3.82v-5.98c0-1.43-.03-3.28-2-3.28-2 0-2.3 1.56-2.3 3.17v6.09H9.08z" /></svg>
                            </a>
                        @endif
                        @if ($youtube)
                            <a href="{{ $youtube }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-[#FF0000] hover:-translate-y-1 transition-all duration-300 border border-white/20 hover:border-transparent" aria-label="YouTube">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6a3 3 0 0 0-2.1 2.1C0 8.1 0 12 0 12s0 3.9.6 5.8a3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1C24 15.9 24 12 24 12s0-3.9-.5-5.8zM9.5 15.5v-7l6 3.5-6 3.5z" /></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- COLUMN 2: QUICK LINKS --}}
            <div>
                <h4 class="mb-6 text-base font-bold tracking-wider text-white uppercase relative inline-block">
                    Quick Links
                    <span class="absolute left-0 -bottom-2 w-1/2 h-0.5 bg-white/50 rounded"></span>
                </h4>
                <ul class="space-y-3 mt-4">
                    @forelse ($topMenus as $item)
                        <li>
                            <a href="{{ $item->link }}" class="group flex items-center gap-2 text-[14px] text-white/80 transition-all duration-300 hover:text-white hover:translate-x-2 font-medium">
                                <span class="text-white/40 group-hover:text-white transition-colors">▹</span>
                                <span>{{ $item->title }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="flex items-center gap-2 text-[14px] text-white/50 italic">
                            <span>No links available</span>
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- COLUMN 3: USEFUL LINKS --}}
            <div>
                <h4 class="mb-6 text-base font-bold tracking-wider text-white uppercase relative inline-block">
                    Useful Links
                    <span class="absolute left-0 -bottom-2 w-1/2 h-0.5 bg-white/50 rounded"></span>
                </h4>
                <ul class="space-y-3 mt-4">
                    @forelse ($footerLinks as $fl)
                        @if (!empty($fl['title']) && !empty($fl['url']))
                            <li>
                                <a href="{{ $fl['url'] }}" target="_blank" rel="noopener" class="group flex items-center gap-2 text-[14px] text-white/80 transition-all duration-300 hover:text-white hover:translate-x-2 font-medium">
                                    <span class="text-white/40 group-hover:text-white transition-colors">▹</span>
                                    <span>{{ $fl['title'] }}</span>
                                </a>
                            </li>
                        @endif
                    @empty
                        <li class="flex items-center gap-2 text-[14px] text-white/50 italic">
                            <span>No useful links added</span>
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- COLUMN 4: CONTACT & LOCATE --}}
            <div>
                <h4 class="mb-6 text-base font-bold tracking-wider text-white uppercase relative inline-block">
                    Contact & Locate
                    <span class="absolute left-0 -bottom-2 w-1/2 h-0.5 bg-white/50 rounded"></span>
                </h4>

                @if ($address || $email || $phone)
                    <div class="space-y-4 text-[14px] text-white/80 mt-4 mb-6">
                        @if ($address)
                            <div class="flex items-start gap-3">
                                <span class="p-1.5 rounded-md bg-white/10 shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                <span class="leading-relaxed">{{ $address }}</span>
                            </div>
                        @endif
                        @if ($phone)
                            <div class="flex items-center gap-3">
                                <span class="p-1.5 rounded-md bg-white/10 shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </span>
                                <a class="hover:text-white hover:underline transition-colors" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                            </div>
                        @endif
                        @if ($email)
                            <div class="flex items-center gap-3">
                                <span class="p-1.5 rounded-md bg-white/10 shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                <a class="hover:text-white hover:underline transition-colors break-all" href="mailto:{{ $email }}">{{ $email }}</a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Map Container --}}
                <div class="relative overflow-hidden rounded-xl shadow-lg ring-1 ring-white/20 h-36 bg-white/5 group">
                    @if($mapUrl)
                        <iframe src="{{ $mapUrl }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Map to {{ $collegeName }}" class="transition-transform duration-500 group-hover:scale-105"></iframe>

                        {{-- Hover Overlay Directions Link --}}
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-[2px]">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" rel="noopener" class="bg-(--primary-color) text-white text-xs font-bold px-4 py-2 rounded-full inline-flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                Get Directions <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                        </div>
                    @else
                        <div class="flex items-center justify-center w-full h-full text-xs text-white/40 italic">
                            Map not configured
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- COPYRIGHT BAR (Darker Color) --}}
    <div class="bg-(--primary-hover) border-t border-white/10">
        <div class="px-6 py-5 mx-auto max-w-7xl">
            <div class="flex flex-col items-center justify-between gap-4 text-[13px] text-white/70 md:flex-row font-medium tracking-wide">
                <div class="text-center md:text-left">
                    &copy; {{ date('Y') }} <span class="text-white">{{ $collegeName }}</span>. All rights reserved.
                </div>
                <div class="text-center md:text-right">
                    Developed by <a href="https://digiemperor.com" class="text-white hover:text-blue-200 transition-colors hover:underline">Digi Emporirer</a>
                </div>
            </div>
        </div>
    </div>

    {{-- BACK TO TOP BUTTON --}}
    <button type="button" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed z-1001 opacity-0 invisible translate-y-4 p-3.5 text-white border border-white/20 rounded-full shadow-2xl bottom-6 right-6 bg-(--primary-hover) hover:bg-white hover:text-(--primary-color) hover:-translate-y-1 hover:scale-110 transition-all duration-300 ease-out"
        id="backToTop">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <script>
        (function () {
            var btn = document.getElementById('backToTop');
            if (!btn) return;
            window.addEventListener('scroll', function () {
                if (window.scrollY > 400) {
                    btn.classList.remove('opacity-0', 'invisible', 'translate-y-4');
                } else {
                    btn.classList.add('opacity-0', 'invisible', 'translate-y-4');
                }
            });
        })();
    </script>
</footer>
