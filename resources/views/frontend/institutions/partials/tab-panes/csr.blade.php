<div x-show="activeTab === 'csr'" x-cloak class="animate-in fade-in slide-in-from-bottom-8 duration-1000">
    @if ($institution->csr_data)
        <div class="space-y-20 md:space-y-32">
            {{-- Luxury Introduction Section --}}
            @if (!empty($institution->csr_data['intro']))
                <section class="relative group">
                    {{-- Decorative Background Elements --}}
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-red-50 rounded-full blur-[100px] opacity-60 -z-10 animate-pulse"></div>
                    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-50 rounded-full blur-[100px] opacity-60 -z-10 animate-pulse" style="animation-delay: 2s"></div>

                    <div class="bg-white/80 backdrop-blur-xl rounded-[48px] p-8 md:p-20 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.05)] border border-white/50 relative overflow-hidden group-hover:shadow-[0_60px_120px_-30px_rgba(220,38,38,0.1)] transition-all duration-1000">
                        {{-- Top Accent Bar --}}
                        <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-red-600 via-blue-900 to-red-600"></div>
                        
                        <div class="relative z-10">
                            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                                <div class="space-y-4">
                                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-red-50 rounded-full border border-red-100">
                                        <span class="w-2 h-2 bg-red-600 rounded-full animate-ping"></span>
                                        <span class="text-[10px] font-black text-red-600 uppercase tracking-[0.3em]">Corporate Social Responsibility</span>
                                    </div>
                                    <h2 class="text-4xl md:text-6xl font-black text-[#1E234B] tracking-tighter leading-[0.9]">
                                        Fundraising <br>
                                        <span class="text-transparent bg-clip-text bg-linear-to-r from-red-600 to-red-400">Appeal.</span>
                                    </h2>
                                </div>
                                <div class="hidden lg:block">
                                    <div class="w-32 h-32 border-2 border-dashed border-gray-100 rounded-full flex items-center justify-center animate-spin-slow">
                                        <i class="bi bi-heart-fill text-4xl text-red-100"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                                <div class="lg:col-span-8">
                                    <div class="prose prose-xl prose-red max-w-none text-gray-600 font-medium leading-[1.6] space-y-6">
                                        {!! $institution->csr_data['intro'] !!}
                                    </div>
                                </div>
                                <div class="lg:col-span-4 bg-gray-50/50 rounded-3xl p-8 border border-gray-100">
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Ways to Contribute</h4>
                                    <div class="space-y-6">
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-red-600 shrink-0">
                                                <i class="bi bi-bank"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900">Direct Endowment</p>
                                                <p class="text-[10px] font-bold text-gray-500 uppercase mt-0.5">Corporate Funding</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-red-600 shrink-0">
                                                <i class="bi bi-laptop"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900">Equipment Donation</p>
                                                <p class="text-[10px] font-bold text-gray-500 uppercase mt-0.5">ICT & Lab Tools</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-red-600 shrink-0">
                                                <i class="bi bi-mortarboard"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900">Scholarship Grants</p>
                                                <p class="text-[10px] font-bold text-gray-500 uppercase mt-0.5">Student Support</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- Projects Showcase Section --}}
            @if (!empty($institution->csr_data['items']) && count($institution->csr_data['items']) > 0)
                <section>
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                        <div class="max-w-xl">
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em] mb-4 block">Transformation Goals</span>
                            <h3 class="text-3xl md:text-5xl font-black text-[#1E234B] tracking-tight uppercase">Ongoing & Future Projects</h3>
                        </div>
                        <div class="h-px bg-gray-100 flex-1 hidden md:block mb-4 mx-8"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16">
                        @foreach ($institution->csr_data['items'] as $item)
                            <div class="group relative bg-white rounded-[48px] border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.08)] transition-all duration-700 overflow-hidden flex flex-col hover:-translate-y-3">
                                @if (!empty($item['photo']))
                                    <div class="aspect-[16/9] overflow-hidden relative">
                                        <img src="{{ asset('storage/' . $item['photo']) }}" 
                                             alt="{{ $item['title'] ?? 'CSR Project' }}"
                                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-linear-to-t from-[#1E234B]/80 via-[#1E234B]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                        
                                        {{-- Image Caption --}}
                                        <div class="absolute bottom-6 left-8 right-8 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-700">
                                            <p class="text-white/70 text-[10px] font-black uppercase tracking-[0.2em]">Project Reference</p>
                                            <h4 class="text-white text-xl font-black">{{ $item['title'] ?? 'CSR Goal' }}</h4>
                                        </div>
                                    </div>
                                @endif
                                <div class="p-10 md:p-12 flex-grow space-y-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-1.5 bg-red-600 rounded-full group-hover:w-20 transition-all duration-700"></div>
                                        <h3 class="text-2xl font-black text-[#1E234B] group-hover:text-red-600 transition-colors uppercase tracking-tight">
                                            {{ $item['title'] ?? 'Untitled Project' }}
                                        </h3>
                                    </div>
                                    @if (!empty($item['description']))
                                        <div class="prose prose-sm prose-slate max-w-none text-gray-500 font-bold leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">
                                            {!! $item['description'] !!}
                                        </div>
                                    @endif
                                </div>
                                <div class="px-12 pb-12">
                                    <button class="inline-flex items-center gap-2 text-[10px] font-black text-gray-900 uppercase tracking-widest hover:text-red-600 transition-colors">
                                        Explore Impact <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- High Impact CTA --}}
            <section class="relative overflow-hidden rounded-[60px]">
                <div class="bg-[#000165] p-10 md:p-24 relative overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,1,101,0.4)]">
                    {{-- Abstract Shapes --}}
                    <div class="absolute top-0 left-0 w-full h-full opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <defs>
                                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#grid)" />
                        </svg>
                    </div>
                    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-red-600 rounded-full blur-[120px] opacity-20 animate-pulse"></div>

                    <div class="relative z-10 max-w-3xl mx-auto text-center space-y-10">
                        <div class="inline-block px-6 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                            <span class="text-[10px] font-black text-white uppercase tracking-[0.4em]">Get Involved</span>
                        </div>
                        <h3 class="text-4xl md:text-7xl font-black text-white tracking-tighter leading-tight">
                            Build the <span class="text-transparent bg-clip-text bg-linear-to-r from-red-400 to-red-200">Future</span> of Education.
                        </h3>
                        <p class="text-blue-100/80 font-bold text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                            Your contribution is more than a donation; it's an investment in the dreams of thousands of students. Join us in our mission.
                        </p>
                        @php 
                            $csrButtons = $institution->csr_data['buttons'] ?? []; 
                            $csrBtnTitle = $institution->csr_data['button_group_title'] ?? 'Get Involved';
                        @endphp

                        @if(!empty($csrButtons))
                            <div class="space-y-6 pt-6">
                                <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.4em]">{{ $csrBtnTitle }}</h4>
                                <div class="flex flex-wrap justify-center gap-6">
                                    @foreach($csrButtons as $btn)
                                        @php $btn = (array) $btn; @endphp
                                        @if(!empty($btn['label']) && !empty($btn['link']))
                                            <a href="{{ $btn['link'] }}" target="_blank"
                                               class="group px-10 py-5 bg-white text-[#000165] rounded-[24px] font-black uppercase tracking-[0.2em] hover:bg-red-600 hover:text-white transition-all duration-500 shadow-2xl hover:scale-105 active:scale-95 flex items-center gap-3">
                                                {{ $btn['label'] }} <i class="bi bi-arrow-right-short text-2xl transition-transform group-hover:translate-x-1"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="flex flex-wrap justify-center gap-6 pt-6">
                                <a href="mailto:{{ $institution->email ?? 'info@vves.org' }}" 
                                   class="group px-10 py-5 bg-white text-[#000165] rounded-[24px] font-black uppercase tracking-[0.2em] hover:bg-red-600 hover:text-white transition-all duration-500 shadow-2xl hover:scale-105 active:scale-95 flex items-center gap-3">
                                    Connect for CSR <i class="bi bi-envelope-heart-fill transition-transform group-hover:rotate-12"></i>
                                </a>
                                @if($institution->phone)
                                    <a href="tel:{{ $institution->phone }}" 
                                       class="px-10 py-5 bg-white/5 backdrop-blur-md border border-white/20 text-white rounded-[24px] font-black uppercase tracking-[0.2em] hover:bg-white/10 transition-all hover:scale-105 active:scale-95 flex items-center gap-3">
                                        Call Office <i class="bi bi-telephone-fill"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="py-32 text-center bg-gray-50/50 rounded-[60px] border-2 border-dashed border-gray-200 animate-in zoom-in duration-700">
            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl shadow-gray-200/50 rotate-6 hover:rotate-0 transition-transform">
                <i class="bi bi-heart text-5xl text-gray-200"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">CSR PORTAL</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-4 max-w-xs mx-auto leading-loose">Corporate Social Responsibility data is currently being prepared for this institution.</p>
        </div>
    @endif
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 12s linear infinite;
    }
</style>
