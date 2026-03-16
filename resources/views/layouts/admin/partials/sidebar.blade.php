<aside
    class="fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 transform bg-white border-r border-gray-200 shadow-2xl w-72 lg:static lg:inset-auto lg:translate-x-0 lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    {{-- Logo/Header --}}
    <div class="flex items-center justify-between shrink-0 px-6 py-5 border-b border-gray-100">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="p-1.5 bg-indigo-50 rounded-xl group-hover:bg-indigo-100 transition-colors">
                <img src="{{ asset('storage/' . setting('college_logo')) }}" alt="logo" class="object-contain w-9 h-9">
            </div>
            <div class="overflow-hidden">
                <h1 class="text-base font-bold text-gray-900 leading-tight truncate">{{ setting('college_name') }}</h1>
                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest opacity-70">Admin Control</p>
            </div>
        </a>
        <button class="p-2 rounded-xl lg:hidden hover:bg-gray-100 text-gray-400 hover:text-gray-900 transition-all" @click="sidebarOpen = false">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-8 custom-scrollbar">

        {{-- Section: Overview --}}
        <div>
            <p class="px-3 mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">Dashboard</p>
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                {{ request()->routeIs('admin.dashboard') 
                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="text-lg bi bi-grid-1x2-fill {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                <span>Statistics Overview</span>
            </a>
        </div>

        {{-- Section: Website Management --}}
        <div>
            <p class="px-3 mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">Content Management</p>
            <div class="space-y-1">
                {{-- Dropdown: Homepage Elements --}}
                @php
                    $isHomeActive = request()->routeIs('admin.homepage*') ||
                        request()->routeIs('admin.notifications*') ||
                        request()->routeIs('admin.announcements*') ||
                        request()->routeIs('admin.event-items*') ||
                        request()->routeIs('admin.academic-calendar*') ||
                        request()->routeIs('admin.gallery-images*') ||
                        request()->routeIs('admin.testimonials*') ||
                        request()->routeIs('admin.why-choose-us*');
                @endphp
                
                <div x-data="{ homeMenuOpen: {{ $isHomeActive ? 'true' : 'false' }} }">
                    <button @click="homeMenuOpen = !homeMenuOpen" 
                            class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                            {{ $isHomeActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                        <span class="flex items-center gap-3">
                            <i class="text-lg bi bi-house-gear-fill {{ $isHomeActive ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                            Homepage Setup
                        </span>
                        <i class="bi bi-chevron-right text-xs transition-transform duration-300" :class="homeMenuOpen ? 'rotate-90' : ''"></i>
                    </button>

                    <div x-show="homeMenuOpen" 
                         x-collapse
                         class="mt-1 ml-4 pl-4 border-l-2 border-indigo-100 space-y-1">
                        
                        @php
                            $homeLinks = [
                                ['route' => 'admin.homepage.index', 'label' => 'Layout Manager', 'icon' => 'bi-layout-text-window-reverse'],
                                ['route' => 'admin.notifications.index', 'label' => 'News Ticker', 'icon' => 'bi-megaphone'],
                                ['route' => 'admin.announcements.index', 'label' => 'Announcements', 'icon' => 'bi-broadcast'],
                                ['route' => 'admin.event-items.index', 'label' => 'Event Manager', 'icon' => 'bi-calendar-event'],
                                ['route' => 'admin.academic-calendar.index', 'label' => 'Calendar PDF', 'icon' => 'bi-file-earmark-pdf'],
                                ['route' => 'admin.gallery-images.index', 'label' => 'Photo Gallery', 'icon' => 'bi-images'],
                                ['route' => 'admin.testimonials.index', 'label' => 'Success Stories', 'icon' => 'bi-chat-quote'],
                                ['route' => 'admin.why-choose-us.index', 'label' => 'Feature Cards', 'icon' => 'bi-patch-check'],
                            ];
                        @endphp

                        @foreach($homeLinks as $link)
                            @php $targetRoute = $link['route']; @endphp
                            <a href="{{ route($targetRoute) }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all
                                {{ request()->routeIs($targetRoute) 
                                    ? 'text-indigo-600 bg-indigo-50/50' 
                                    : 'text-gray-500 hover:text-indigo-600 hover:bg-gray-50' }}">
                                <i class="bi {{ $link['icon'] }} text-base"></i>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('admin.popups.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.popups*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-window-stack {{ request()->routeIs('admin.popups*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Admissions Popups
                </a>

                <a href="{{ route('admin.pagebuilder.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.pagebuilder*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-magic {{ request()->routeIs('admin.pagebuilder*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Page Designer
                </a>

                <a href="{{ route('admin.menus.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.menus*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-list-nested {{ request()->routeIs('admin.menus*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Navigation Menu
                </a>

                <a href="{{ route('admin.media.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.media*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-folder2-open {{ request()->routeIs('admin.media*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Asset Library
                </a>
            </div>
        </div>

        {{-- Section: Leads --}}
        <div>
            <p class="px-3 mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">User Inquiries</p>
            @php $isLeadsActive = request()->routeIs('admin.leads*'); @endphp
            <div x-data="{ leadsMenuOpen: {{ $isLeadsActive ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="leadsMenuOpen = !leadsMenuOpen" 
                        class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                        {{ $isLeadsActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <span class="flex items-center gap-3">
                        <i class="text-lg bi bi-person-badge-fill {{ $isLeadsActive ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                        Lead Hub
                    </span>
                    <i class="bi bi-chevron-right text-xs transition-transform duration-300" :class="leadsMenuOpen ? 'rotate-90' : ''"></i>
                </button>

                <div x-show="leadsMenuOpen" x-collapse class="mt-1 ml-4 pl-4 border-l-2 border-indigo-100 space-y-1">
                    <a href="{{ route('admin.leads.admissions') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all
                        {{ request()->routeIs('admin.leads.admissions') ? 'text-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="bi bi-mortarboard-fill text-base"></i>
                        Admission Leads
                    </a>
                    <a href="{{ route('admin.leads.enquiries') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold transition-all
                        {{ request()->routeIs('admin.leads.enquiries') ? 'text-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="bi bi-question-diamond-fill text-base"></i>
                        General Enquiries
                    </a>
                </div>
            </div>
        </div>

        {{-- Section: System --}}
        <div>
            <p class="px-3 mb-2 text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em]">Security & Ops</p>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.users*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-shield-shaded {{ request()->routeIs('admin.users*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Staff Accounts
                </a>
                <a href="{{ route('admin.roles-permissions.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.roles-permissions*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-key-fill {{ request()->routeIs('admin.roles-permissions*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Access Rights
                </a>
                <a href="{{ route('admin.website-settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all group
                    {{ request()->routeIs('admin.website-settings*') 
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' 
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i class="text-lg bi bi-sliders {{ request()->routeIs('admin.website-settings*') ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}"></i>
                    Global Settings
                </a>
            </div>
        </div>
    </nav>

    {{-- User Footer --}}
    <div class="shrink-0 p-4 m-4 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="relative">
                @if (auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" class="object-cover w-10 h-10 rounded-xl shadow-sm border-2 border-white">
                @else
                    <div class="flex items-center justify-center w-10 h-10 text-xs font-black text-white bg-linear-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm">
                        {{ auth()->user()->initials }}
                    </div>
                @endif
                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <div class="flex-1 overflow-hidden">
                <div class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</div>
                <div class="text-[10px] text-gray-400 font-bold uppercase truncate">System Admin</div>
            </div>
            <a href="{{ route('logout') }}"
                class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                title="Secure Sign Out">
                <i class="bi bi-power text-lg"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</aside>
