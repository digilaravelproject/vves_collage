@php
    use App\Models\Menu;
    $topMenus = Menu::where('status', 1)->whereNull('parent_id')->orderBy('order')->limit(8)->get();

    $collegeName = setting('college_name', config('app.name'));

    // Dedicated Footer Logo (fallback to dark banner if not set)
    $footerLogo = setting('footer_logo') ?: setting('top_banner_image_dark');

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
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4 lg:gap-14">

            {{-- COLUMN 1: ABOUT & BRANDING --}}
            <div class="space-y-6 md:space-y-8" data-aos="fade-up">
                {{-- Dynamic Footer Logo --}}
                @if ($footerLogo)
                    <div class="mb-4">
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $footerLogo) }}" alt="{{ $collegeName }}" 
                             class="object-contain w-auto h-16 md:h-24 filter drop-shadow-[0_8px_20px_rgba(255,255,255,0.15)] hover:scale-105 transition-transform duration-500">
                    </div>
                @else
                    <div class="mb-4 text-[20px] md:text-[22px] font-bold tracking-tight uppercase text-white! drop-shadow-lg">
                        {{ $collegeName }}
                    </div>
                @endif

                <p class="text-[13px] leading-[1.8] text-white/90 font-medium max-w-sm">
                    {{ $footerAbout }}
                </p>

                {{-- Premium Social Icons --}}
                @php
                    $socials = [
                        ['id' => 'facebook_url', 'icon' => 'bi-facebook', 'color' => '#1877F2'],
                        ['id' => 'twitter_url', 'icon' => 'bi-twitter-x', 'color' => '#000000'],
                        ['id' => 'instagram_url', 'icon' => 'bi-instagram', 'color' => '#E4405F'],
                        ['id' => 'linkedin_url', 'icon' => 'bi-linkedin', 'color' => '#0077B5'],
                        ['id' => 'youtube_url', 'icon' => 'bi-youtube', 'color' => '#FF0000'],
                    ];
                @endphp
                
                <div class="flex flex-wrap gap-3 pt-1">
                    @foreach($socials as $social)
                        @if ($val = setting($social['id']))
                            <a href="{{ $val }}" target="_blank" rel="noopener" 
                               class="group relative inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 overflow-hidden hover:bg-white/20 transition-all duration-300"
                               aria-label="{{ ucfirst(str_replace('_url', '', $social['id'])) }}">
                                {{-- Hover Background Color Layer --}}
                                <div class="absolute inset-x-0 bottom-0 h-0 group-hover:h-full transition-all duration-300 z-0" style="background-color: {{ $social['color'] }}"></div>
                                <i class="bi {{ $social['icon'] }} relative z-10 text-base text-white group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- COLUMN 2: QUICK NAVIGATION (Accordion on Mobile) --}}
            <div class="space-y-4 md:space-y-6" data-aos="fade-up" data-aos-delay="100">
                <button type="button" @click="activeSection = (activeSection === 'quick' ? null : 'quick')" 
                        class="flex items-center justify-between w-full md:cursor-default group text-left border-b border-white/10 pb-3 md:border-none md:pb-0">
                    <h4 class="text-[13px] font-bold tracking-wide text-white! uppercase relative inline-block">
                        Quick Links
                        <span class="absolute left-0 -bottom-2 w-10 h-0.5 bg-linear-to-r from-white/70 to-transparent rounded hidden md:block"></span>
                    </h4>
                    <span class="md:hidden text-white transition-transform duration-300" :class="activeSection === 'quick' ? 'rotate-180' : ''">
                        <i class="bi bi-chevron-down text-base"></i>
                    </span>
                </button>
                
                <ul class="space-y-3 pt-1 md:block" :class="activeSection === 'quick' ? 'block animate-fade-in' : 'hidden'">
                    @forelse ($topMenus as $item)
                        <li>
                            <a href="{{ $item->link }}" class="group flex items-center gap-3 text-[12.5px] text-white/80 hover:text-white transition-all duration-300 font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full border-2 border-white/30 group-hover:bg-white group-hover:border-white transition-all"></span>
                                <span class="group-hover:translate-x-1 transition-transform">{{ $item->title }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-[12px] text-white/50 font-medium">No links</li>
                    @endforelse
                </ul>
            </div>

            {{-- COLUMN 3: RESOURCES (Accordion on Mobile) --}}
            <div class="space-y-4 md:space-y-6" data-aos="fade-up" data-aos-delay="200">
                <button type="button" @click="activeSection = (activeSection === 'useful' ? null : 'useful')" 
                        class="flex items-center justify-between w-full md:cursor-default group text-left border-b border-white/10 pb-3 md:border-none md:pb-0">
                    <h4 class="text-[13px] font-bold tracking-wide text-white! uppercase relative inline-block">
                        Useful Links
                        <span class="absolute left-0 -bottom-2 w-10 h-0.5 bg-linear-to-r from-white/70 to-transparent rounded hidden md:block"></span>
                    </h4>
                    <span class="md:hidden text-white transition-transform duration-300" :class="activeSection === 'useful' ? 'rotate-180' : ''">
                        <i class="bi bi-chevron-down text-base"></i>
                    </span>
                </button>

                <ul class="space-y-3 pt-1 md:block" :class="activeSection === 'useful' ? 'block animate-fade-in' : 'hidden'">
                    @forelse ($footerLinks as $fl)
                        @if (!empty($fl['title']))
                            <li>
                                <a href="{{ $fl['url'] }}" target="_blank" rel="noopener" 
                                   class="group flex items-center gap-3 text-[12.5px] text-white/80 hover:text-white transition-all duration-300 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full border-2 border-white/30 group-hover:bg-white group-hover:border-white transition-all"></span>
                                    <span class="group-hover:translate-x-1 transition-transform">{{ $fl['title'] }}</span>
                                </a>
                            </li>
                        @endif
                    @empty
                        <li class="text-[12px] text-white/50 font-medium">Coming soon</li>
                    @endforelse
                </ul>
            </div>

            {{-- COLUMN 4: CONTACT & MAP (Accordion on Mobile) --}}
            <div class="space-y-4 md:space-y-6" data-aos="fade-up" data-aos-delay="300">
                <button type="button" @click="activeSection = (activeSection === 'contact' ? null : 'contact')" 
                        class="flex items-center justify-between w-full md:cursor-default group text-left border-b border-white/10 pb-3 md:border-none md:pb-0">
                    <h4 class="text-[13px] font-bold tracking-wide text-white! uppercase relative inline-block">
                        Contact Us
                        <span class="absolute left-0 -bottom-2 w-10 h-0.5 bg-linear-to-r from-white/70 to-transparent rounded hidden md:block"></span>
                    </h4>
                    <span class="md:hidden text-white transition-transform duration-300" :class="activeSection === 'contact' ? 'rotate-180' : ''">
                        <i class="bi bi-chevron-down text-base"></i>
                    </span>
                </button>

                <div class="space-y-6 pt-1 md:block" :class="activeSection === 'contact' ? 'block animate-fade-in' : 'hidden'">
                    <div class="space-y-4 text-[12.5px]">
                        @if ($address)
                            <div class="flex items-start gap-3 group">
                                <div class="w-8 h-8 shrink-0 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all">
                                    <i class="bi bi-geo-alt-fill text-sm"></i>
                                </div>
                                <span class="leading-relaxed font-semibold text-white">{{ $address }}</span>
                            </div>
                        @endif
                        @if ($phone)
                            <div class="flex items-center gap-3 group">
                                <div class="w-8 h-8 shrink-0 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all">
                                    <i class="bi bi-telephone-fill text-sm"></i>
                                </div>
                                <a class="font-bold text-white transition-colors" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
                            </div>
                        @endif
                        @if ($email)
                            <div class="flex items-center gap-3 group">
                                <div class="w-8 h-8 shrink-0 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all">
                                    <i class="bi bi-envelope-at-fill text-sm"></i>
                                </div>
                                <a class="font-bold text-white transition-colors break-all" href="mailto:{{ $email }}">{{ $email }}</a>
                            </div>
                        @endif
                    </div>

                    {{-- Premium Map Integration --}}
                    <div class="mt-6 relative overflow-hidden rounded-3xl ring-1 ring-white/20 shadow-2xl h-40 md:h-44 bg-white/5 group border border-white/10">
                        @if($mapUrl)
                            <iframe src="{{ $mapUrl }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade" title="Map vves" 
                                    class="transition-transform duration-700 blur-[0.2px] group-hover:blur-0 group-hover:scale-110 opacity-80 group-hover:opacity-100"></iframe>
                            
                            {{-- Directions Overlay --}}
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 backdrop-blur-[1px]">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" rel="noopener" 
                                   class="bg-white text-black text-[10px] font-black px-4 py-2.5 rounded-xl flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-2xl">
                                    DIRECTIONS <i class="bi bi-map-fill"></i>
                                </a>
                            </div>
                        @else
                            <div class="flex items-center justify-center w-full h-full text-xs text-white/40 font-medium">Map not configured</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- COPYRIGHT BAR (Pure Black/Dark) --}}
    <div class="bg-black/60 border-t border-white/10 backdrop-blur-xl relative z-10">
        {{-- Reduced bottom padding from 32 to 24 to bring the bottom a bit lower (more towards edge) while still avoiding buttons --}}
        <div class="px-6 py-5 pb-24 sm:pb-6 mx-auto max-w-7xl">
            <div class="flex flex-col items-center justify-between gap-6 text-[11px] text-white/60 md:flex-row font-bold tracking-wide uppercase">
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
                    <a href="https://digiemperor.com" target="_blank" class="text-white font-black hover:text-white/80 hover:tracking-widest transition-all duration-500 bg-white/10 px-4 py-1.5 rounded-lg border border-white/10">DIGI EMPORER</a>
                </div>
            </div>
        </div>
    </div>

    {{-- PREMIUM BACK TO TOP --}}
    <button type="button" aria-label="BackToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed z-1001 opacity-0 invisible translate-y-10 flex items-center justify-center w-10 h-10 text-black rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.4)] bottom-24 sm:bottom-10 right-6 bg-white hover:bg-(--primary-color) hover:text-white group transition-all duration-500 ease-out border-2 border-white/20"
        id="backToTopMain">
        <div class="absolute inset-0 rounded-2xl bg-(--primary-color) scale-0 group-hover:scale-100 transition-transform duration-500 -z-10"></div>
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

