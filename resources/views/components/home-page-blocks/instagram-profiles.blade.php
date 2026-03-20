@php
    $profiles = $block['profiles'] ?? [];
    $sectionTitle = $block['section_title'] ?? 'Follow Our Journey';

    // Extract Instagram username from URL
    if (!function_exists('extractInstagramUsername')) {
        function extractInstagramUsername($link) {
            $link = trim($link ?? '', '/ ');
            if (str_contains($link, 'instagram.com')) {
                $path = parse_url($link, PHP_URL_PATH);
                $path = trim($path ?? '', '/');
                $segments = explode('/', $path);
                return $segments[0] ?? '';
            }
            if (!str_contains($link, '/') && !str_contains($link, '.')) {
                return ltrim($link, '@');
            }
            return '';
        }
    }
@endphp

<div class="instagram-profiles-section py-12 overflow-hidden">
    @if($sectionTitle)
        <div class="mb-8 sm:mb-12 text-center relative">
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
                <span class="text-5xl sm:text-7xl lg:text-9xl font-black uppercase tracking-widest sm:tracking-tighter">INSTAGRAM</span>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl flex items-center justify-center gap-3 relative z-10 font-black tracking-tight text-gray-800">
                <span class="p-2 sm:p-3 bg-white shadow-md rounded-xl sm:rounded-2xl rotate-3 group-hover:rotate-0 transition-transform duration-500 text-xl sm:text-2xl">📸</span> 
                {{ $sectionTitle }}
            </h2>
            <div class="w-16 h-1 bg-linear-to-r from-yellow-400 via-pink-500 to-purple-600 mx-auto rounded-full mt-4 shadow-md shadow-pink-100"></div>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 px-6 max-w-[1400px] mx-auto">
        @foreach($profiles as $profile)
            @php
                $username = extractInstagramUsername($profile['link'] ?? '');
            @endphp
            <div class="insta-card-perspective group h-full">
                <div class="insta-card-inner relative h-full bg-white/70 backdrop-blur-xl rounded-[2.5rem] p-7 border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-700 hover:shadow-[0_20px_50px_rgba(236,72,153,0.15)] overflow-hidden flex flex-col items-center">
                    
                    {{-- Premium Background Glow --}}
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-linear-to-br from-pink-500/10 to-purple-600/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-linear-to-tr from-yellow-400/10 to-pink-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>

                    {{-- Floating Icon --}}
                    <div class="absolute top-6 right-8 opacity-20 group-hover:opacity-100 transition-all duration-500 group-hover:-translate-y-2 group-hover:rotate-12">
                        <svg class="w-6 h-6 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>

                    {{-- Avatar --}}
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-full p-[3px] bg-linear-to-tr from-yellow-400 via-pink-500 to-purple-600 animate-spin-slow shadow-xl">
                            <div class="w-full h-full rounded-full border-4 border-white overflow-hidden bg-white shadow-inner">
                                @if(!empty($profile['image']))
                                    <img src="{{ $profile['image'] }}" alt="{{ $profile['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                        <span class="text-2xl">👤</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full p-1 shadow-lg border border-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204... (truncated)"></path></svg>
                        </div>
                    </div>

                    {{-- Text Info --}}
                    <div class="text-center z-10 w-full mb-8">
                        <h3 class="text-xl font-black text-gray-800 tracking-tight leading-tight group-hover:text-pink-600 transition-colors duration-300">
                            {{ $profile['name'] }}
                        </h3>
                        @if($username)
                            <p class="text-sm font-bold bg-linear-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                                {{ '@' . $username }}
                            </p>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-auto w-full flex flex-col gap-3 z-10">
                        <a href="{{ $profile['link'] }}" target="_blank"
                           class="group/btn relative overflow-hidden flex items-center justify-center gap-2 px-6 py-3 bg-linear-to-r from-pink-500 to-purple-600 text-white rounded-[1.2rem] text-sm font-black transition-all duration-300 hover:shadow-[0_10px_20px_rgba(236,72,153,0.3)] hover:-translate-y-0.5 active:scale-95">
                           <span class="relative z-10 flex items-center gap-2">
                               Follow Now
                               <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                           </span>
                           <div class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                        </a>
                        
                        <a href="{{ $profile['link'] }}" target="_blank"
                           class="flex items-center justify-center px-6 py-3 bg-gray-100/50 hover:bg-white text-gray-700 rounded-[1.2rem] text-xs font-bold border border-gray-200/50 transition-all duration-300">
                            Visit Profile
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .insta-card-perspective {
        perspective: 2000px;
    }

    .insta-card-inner {
        transform-style: preserve-3d;
        will-change: transform;
    }

    .insta-card-perspective:hover .insta-card-inner {
        transform: rotateX(8deg) rotateY(-8deg) translateZ(10px);
    }

    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }

    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @media (max-width: 640px) {
        .insta-card-perspective:hover .insta-card-inner {
            transform: none;
        }
    }
</style>
