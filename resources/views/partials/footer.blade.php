@php
    use App\Models\Menu;
    $topMenus = Menu::where('status', 1)->whereNull('parent_id')->orderBy('order')->limit(8)->get();

    $collegeName = setting('college_name', config('app.name'));

    // Dedicated Footer Logo (fallback to dark banner if not set)
    $footerLogo = setting('footer_logo') ?: setting('top_banner_image_dark');

    $footerAbout = setting('footer_about', 'Committed to academic excellence, innovation, and holistic development.');

    $socials = [
        ['id' => 'facebook_url', 'icon' => 'bi-facebook', 'color' => '#1877F2'],
        ['id' => 'twitter_url', 'icon' => 'bi-twitter-x', 'color' => '#000000'],
        ['id' => 'instagram_url', 'icon' => 'bi-instagram', 'color' => '#E4405F'],
        ['id' => 'linkedin_url', 'icon' => 'bi-linkedin', 'color' => '#0077B5'],
        ['id' => 'youtube_url', 'icon' => 'bi-youtube', 'color' => '#FF0000'],
    ];

    // Map URL from Admin Settings
    $mapUrl = setting('map_embed_url');

    $footerLinksRaw = setting('footer_links');
    $footerLinks = $footerLinksRaw ? json_decode($footerLinksRaw, true) : [];

    $contactCentersRaw = setting('contact_centers');
    $contactCenters = $contactCentersRaw ? json_decode($contactCentersRaw, true) : [];
@endphp

{{-- Premium Footer - Primary to Black Gradient --}}
<footer
    class="mt-16 bg-linear-to-b from-(--primary-color) to-[#010101] text-white relative font-sans selection:bg-white/20"
    x-data="{ activeSection: null }">

    {{-- Modern Wave Top Divider --}}
    <div
        class="absolute top-0 left-0 w-full overflow-hidden transform -translate-y-[99%] leading-none pointer-events-none">
        <svg aria-hidden="true" focusable="false" class="w-full h-12 md:h-16" preserveAspectRatio="none"
            viewBox="0 0 1440 56">
            <path fill="var(--primary-color, #000165)"
                d="M0,24 C240,48 480,0 720,16 C960,32 1200,56 1440,24 L1440,56 L0,56 Z"></path>
        </svg>
    </div>

    <div class="px-6 pt-12 pb-10 mx-auto max-w-7xl">
        {{-- Reduced gap on mobile (gap-8), larger on desktop (lg:gap-14) --}}
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-14">
            {{-- COLUMN 1: ABOUT & BRANDING (col-span-1) --}}
            <div class="space-y-6 md:space-y-8 lg:col-span-1" data-aos="fade-up">
                {{-- Motto / Slogan --}}
                <div
                    class="mb-8 text-center text-[18px] md:text-[22px] font-black text-white tracking-[0.3em] drop-shadow-[0_0_15px_rgba(255,215,0,0.5)] uppercase border-b border-white/20 pb-4">
                    || प्रज्वलितो ज्ञानमयोदीप: ||
                </div>

                {{-- Dynamic Footer Logo --}}
                @if ($footerLogo)
                    <div class="mb-4">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $footerLogo) }}"
                            alt="{{ $collegeName }}"
                            class="object-contain w-auto h-16 md:h-20 filter drop-shadow-[0_8px_20px_rgba(255,255,255,0.15)] hover:scale-105 transition-transform duration-500">
                    </div>
                @else
                    <div class="mb-4 text-[18px] font-bold tracking-tight uppercase text-white! drop-shadow-lg">
                        {{ $collegeName }}
                    </div>
                @endif

                <p class="text-[12.5px] leading-[1.8] text-white/80 font-medium max-w-sm">
                    {{ $footerAbout }}
                </p>

                {{-- Social Icons - Only show if settings exist --}}
                @php
                    $hasSocials = false;
                    foreach ($socials as $social) {
                        if (!empty(setting($social['id']))) {
                            $hasSocials = true;
                            break;
                        }
                    }
                @endphp

                @if ($hasSocials)
                    <div class="flex flex-wrap gap-2.5 pt-1">
                        @foreach ($socials as $social)
                            @if ($val = setting($social['id']))
                                <a href="{{ $val }}" target="_blank" rel="noopener"
                                    class="group relative inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/5 border border-white/10 overflow-hidden hover:bg-white/10 transition-all duration-300"
                                    aria-label="{{ ucfirst(str_replace('_url', '', $social['id'])) }}">
                                    <div class="absolute inset-x-0 bottom-0 h-0 group-hover:h-full transition-all duration-300 z-0"
                                        style="background-color: {{ $social['color'] }}"></div>
                                    <i
                                        class="bi {{ $social['icon'] }} relative z-10 text-[14px] text-white transition-transform group-hover:scale-110"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- COLUMN 2: NAVIGATION (Quick + Useful combined) --}}
            <div class="space-y-8 lg:col-span-1" data-aos="fade-up" data-aos-delay="100">
                {{-- Quick Links Section --}}
                <div class="space-y-4">
                    <h4 class="text-[11px] font-black tracking-[0.2em] text-[#FFD700] uppercase">Quick Links</h4>
                    <ul class="grid grid-cols-1 gap-x-4 gap-y-2.5 md:grid-cols-1">
                        @foreach ($topMenus as $item)
                            <li>
                                <a href="{{ $item->link }}"
                                    class="group flex items-center gap-2.5 text-[12px] text-white/70 hover:text-white transition-all font-semibold">
                                    <i
                                        class="bi bi-arrow-right-short text-lg text-[#FFD700]/50 group-hover:text-[#FFD700]"></i>
                                    <span class="group-hover:translate-x-1 transition-transform">{{ $item->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Useful Links Section --}}
                <div class="space-y-4">
                    <h4 class="text-[11px] font-black tracking-[0.2em] text-[#FFD700] uppercase">Useful Links</h4>
                    <ul class="grid grid-cols-1 gap-x-4 gap-y-2.5">
                        @forelse ($footerLinks as $fl)
                            @if (!empty($fl['title']))
                                <li>
                                    <a href="{{ $fl['url'] }}" target="_blank"
                                        class="group flex items-center gap-2.5 text-[12px] text-white/70 hover:text-white transition-all font-semibold">
                                        <i class="bi bi-link-45deg text-lg text-[#FFD700]/50 group-hover:text-[#FFD700]"></i>
                                        <span class="group-hover:translate-x-1 transition-transform">{{ $fl['title'] }}</span>
                                    </a>
                                </li>
                            @endif
                        @empty
                            <li class="text-[11px] text-white/30 italic">No links configured</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- COLUMN 3 & 4 (Span 2): CONTACT CENTERS & MAP --}}
            <div class="lg:col-span-2 space-y-8" data-aos="fade-up" data-aos-delay="200">
                <h4 class="text-[11px] font-black tracking-[0.2em] text-[#FFD700] uppercase">Contact Us</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
                    @forelse($contactCenters as $center)
                        <div class="space-y-5">
                            @if (!empty($center['name']))
                                <h5 class="text-white text-[13px] font-black tracking-tight leading-tight">
                                    {{ $center['name'] }}
                                </h5>
                            @endif

                            <div class="space-y-3.5 text-[12px]">
                                @if (!empty($center['address']))
                                    <div class="flex items-start gap-3 text-white/80">
                                        <i class="bi bi-geo-alt-fill text-[#FFD700] mt-0.5 text-[14px]"></i>
                                        <span class="leading-relaxed font-semibold">{{ $center['address'] }}</span>
                                    </div>
                                @endif

                                @if (!empty($center['phone']))
                                    <div class="flex items-center gap-3">
                                        <i class="bi bi-telephone-fill text-[#FFD700] text-[14px]"></i>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $center['phone']) }}"
                                            class="text-white font-bold hover:text-[#FFD700] transition-colors">{{ $center['phone'] }}</a>
                                    </div>
                                @endif

                                @if (!empty($center['email']))
                                    <div class="flex items-center gap-3">
                                        <i class="bi bi-envelope-at-fill text-[#FFD700] text-[14px]"></i>
                                        <a href="mailto:{{ $center['email'] }}"
                                            class="text-white font-bold hover:text-[#FFD700] transition-colors break-all">{{ $center['email'] }}</a>
                                    </div>
                                @endif

                                @if (!empty($center['website']))
                                    <div class="flex items-center gap-3">
                                        <i class="bi bi-globe text-[#FFD700] text-[14px]"></i>
                                        <a href="{{ $center['website'] }}" target="_blank"
                                            class="text-white font-bold hover:text-[#FFD700] transition-colors">Website</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-[12px] text-white/40">Contact centers not configured in settings.</p>
                    @endforelse
                </div>

                {{-- Integrated Map --}}
                <div class="relative group">
                    <div
                        class="overflow-hidden rounded-3xl h-48 md:h-56 ring-1 ring-white/10 bg-white/5 border border-white/10 shadow-2xl">
                        @if ($mapUrl)
                            <iframe src="{{ $mapUrl }}" width="100%" height="100%" style="border:0;" allowfullscreen=""
                                loading="lazy"
                                class="opacity-60 grayscale-[0.5] group-hover:opacity-100 group-hover:grayscale-0 transition-all duration-700"></iframe>

                            {{-- Directions Overlay --}}
                            <div
                                class="absolute top-4 right-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($collegeName) }}"
                                    target="_blank"
                                    class="bg-white text-black text-[10px] font-black px-4 py-2.5 rounded-xl flex items-center gap-2 shadow-2xl hover:scale-105 active:scale-95 transition-all">
                                    GET DIRECTIONS <i class="bi bi-map-fill"></i>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center justify-center w-full h-full text-xs text-white/30">Google Map
                                is
                                not configured</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    {{-- COPYRIGHT BAR (Pure Black/Dark) --}}
    <div class="bg-black/60 border-t border-white/10 backdrop-blur-xl relative z-10">
        {{-- Reduced bottom padding from 32 to 24 to bring the bottom a bit lower (more towards edge) while still
        avoiding buttons --}}
        <div class="px-6 py-5 pb-24 sm:pb-6 mx-auto max-w-7xl">
            <div
                class="flex flex-col items-center justify-between gap-6 text-[11px] text-white/60 md:flex-row font-bold tracking-wide uppercase">
                <div class="text-center md:text-left">
                    &copy; {{ date('Y') }} <span class="text-white font-black">{{ $collegeName }}</span>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-4 md:gap-6">
                    <a href="#" class="hover:text-white transition-all">Privacy Policy</a>
                    <span class="w-1.5 h-1.5 rounded-full bg-white/30 hidden sm:block"></span>
                    <a href="#" class="hover:text-white transition-all">Terms of Use</a>
                </div>
                <div class="text-center md:text-right flex flex-col sm:flex-row items-center gap-3">
                    <span class="text-white/40 font-medium normal-case tracking-normal">Crafted by</span>
                    <a href="https://digiemperor.com" target="_blank"
                        class="text-white font-black hover:text-white/80 hover:tracking-widest transition-all duration-500 bg-white/10 px-4 py-1.5 rounded-lg border border-white/10">DIGI
                        EMPORER</a>
                </div>
            </div>
        </div>
    </div>

    {{-- PREMIUM BACK TO TOP --}}
    <button type="button" aria-label="BackToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed z-1001 opacity-0 invisible translate-y-10 flex items-center justify-center w-10 h-10 text-black rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.4)] bottom-24 sm:bottom-10 right-6 bg-white hover:bg-(--primary-color) hover:text-white group transition-all duration-500 ease-out border-2 border-white/20"
        id="backToTopMain">
        <div
            class="absolute inset-0 rounded-2xl bg-(--primary-color) scale-0 group-hover:scale-100 transition-transform duration-500 -z-10">
        </div>
        <i class="bi bi-arrow-up-circle-fill text-lg group-hover:-translate-y-1 transition-transform"></i>
    </button>

    <script>
        (function () {
            var btn = document.getElementById('backToTopMain');
            if (!btn) return;
            window.addEventListener('scroll', function () {
                if (window.scrollY > 600) {
                    btn.classList.remove('opacity-0', 'invisible', 'translate-y-10');
                } else {
                    btn.classList.add('opacity-0', 'invisible', 'translate-y-10');
                }
            });
        })();
    </script>
</footer>
