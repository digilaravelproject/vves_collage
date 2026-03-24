<x-guest-layout>
    <div class="fixed inset-0 z-0 overflow-hidden">
        {{-- Animated Background Objects --}}
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-(--primary-color)/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-(--primary-color)/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full">
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2 uppercase">{{ __('Account Setup') }}</h2>
            <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">{{ __('Create new administrator') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 px-1">Full Name</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-(--primary-color)">
                        <i class="bi bi-person-fill text-gray-400"></i>
                    </div>
                    <input id="name" 
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-(--primary-color)/10 focus:border-(--primary-color) focus:bg-white transition-all outline-none"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        placeholder="Dr. John Doe" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1 px-2" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 px-1">Institutional Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-(--primary-color)">
                        <i class="bi bi-envelope-at text-gray-400"></i>
                    </div>
                    <input id="email" 
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-(--primary-color)/10 focus:border-(--primary-color) focus:bg-white transition-all outline-none"
                        type="email" name="email" :value="old('email')" required autocomplete="username"
                        placeholder="admin@vves.edu.in" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 px-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 px-1">Secret Key</label>
                <div class="relative group" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-(--primary-color)">
                        <i class="bi bi-shield-lock-fill text-gray-400"></i>
                    </div>
                    <input id="password" 
                        :type="show ? 'text' : 'password'"
                        class="block w-full pl-11 pr-12 py-3 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-(--primary-color)/10 focus:border-(--primary-color) focus:bg-white transition-all outline-none"
                        name="password" required autocomplete="new-password"
                        placeholder="••••••••" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-(--primary-color)">
                        <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 px-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 px-1">Confirm Secret</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-(--primary-color)">
                        <i class="bi bi-shield-check text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" 
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-(--primary-color)/10 focus:border-(--primary-color) focus:bg-white transition-all outline-none"
                        type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 px-2" />
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-(--primary-color)/20 text-xs font-black text-white bg-(--primary-color) hover:bg-(--primary-hover) focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-(--primary-color) transition-all transform active:scale-[0.98] tracking-[0.2em] uppercase">
                    {{ __('Create Account') }}
                </button>
            </div>

            <div class="mt-6 text-center pt-2">
                <a class="text-[11px] font-bold text-gray-500 hover:text-(--primary-color) transition-colors uppercase tracking-widest" href="{{ route('login') }}">
                    {{ __('Already registered?') }} <span class="text-(--primary-color)">Sign In</span>
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
