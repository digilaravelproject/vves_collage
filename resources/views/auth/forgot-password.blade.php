<x-guest-layout>
    <div class="fixed inset-0 z-0 overflow-hidden">
        {{-- Animated Background Objects --}}
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full">
        {{-- Brand Icon --}}
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-linear-to-tr from-indigo-600 to-purple-600 rounded-3xl shadow-2xl shadow-indigo-200 flex items-center justify-center transform -rotate-6 hover:rotate-0 transition-transform duration-500 group">
                <i class="bi bi-shield-lock-fill text-white text-4xl group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-3 uppercase leading-none">{{ __('Access Recovery') }}</h2>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[0.3em]">{{ __('Identity Verification Required') }}</p>
        </div>

        <div class="mb-8 bg-gray-50/80 backdrop-blur-sm border border-gray-100 rounded-2xl p-5 text-[13px] text-gray-600 leading-relaxed font-medium shadow-sm">
            <div class="flex gap-3">
                <i class="bi bi-info-circle-fill text-indigo-500 text-lg"></i>
                <p>
                    {{ __('Forgot your password? No problem. Enter your institutional email address and we will dispatch a secure reset link to your inbox.') }}
                </p>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">Institutional Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <i class="bi bi-envelope-at text-gray-400 text-lg"></i>
                    </div>
                    <input id="email" 
                        class="block w-full pl-12 pr-5 py-4 bg-gray-50/50 border-2 border-transparent text-gray-900 text-sm rounded-2xl focus:ring-0 focus:border-indigo-500 focus:bg-white transition-all outline-none shadow-sm hover:bg-gray-100/50"
                        type="email" name="email" :value="old('email')" required autofocus 
                        placeholder="yourname@vves.edu.in" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 px-2" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-2xl shadow-xl shadow-indigo-200 text-xs font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-[0.98] tracking-[0.2em] uppercase group">
                    <span>{{ __('Send Recovery Link') }}</span>
                    <i class="bi bi-send-fill ml-2 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>
            </div>

            <div class="mt-10 text-center border-t border-gray-100 pt-8">
                <a class="inline-flex items-center text-[11px] font-bold text-gray-400 hover:text-indigo-600 transition-colors uppercase tracking-[0.2em]" href="{{ route('login') }}">
                    <i class="bi bi-arrow-left-circle-fill mr-2 text-base"></i> Back to login gateway
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
