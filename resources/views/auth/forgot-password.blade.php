<x-guest-layout>
    <div class="fixed inset-0 z-0 overflow-hidden">
        {{-- Animated Background Objects --}}
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-(--primary-color)/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-(--primary-color)/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full">
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2 uppercase">{{ __('Forgot Password?') }}</h2>
            <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">{{ __('Enter your email to recover access') }}</p>
        </div>

        <div class="mb-6 bg-blue-50/50 border border-blue-100 rounded-2xl p-4 text-[13px] text-blue-700 leading-relaxed font-medium">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Institutional Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-(--primary-color)">
                        <i class="bi bi-envelope-at text-gray-400"></i>
                    </div>
                    <input id="email" 
                        class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-(--primary-color)/10 focus:border-(--primary-color) focus:bg-white transition-all outline-none"
                        type="email" name="email" :value="old('email')" required autofocus 
                        placeholder="admin@vves.edu.in" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 px-2" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-(--primary-color)/20 text-xs font-black text-white bg-(--primary-color) hover:bg-(--primary-hover) focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-(--primary-color) transition-all transform active:scale-[0.98] tracking-[0.2em] uppercase">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>

            <div class="mt-8 text-center pt-2">
                <a class="text-[11px] font-bold text-gray-400 hover:text-(--primary-color) transition-colors uppercase tracking-widest" href="{{ route('login') }}">
                    <i class="bi bi-arrow-left mr-1"></i> Back to login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
