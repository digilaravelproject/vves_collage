<x-guest-layout>
    <div class="fixed inset-0 z-0 overflow-hidden">
        {{-- Animated Background Objects --}}
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-purple-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full">
        {{-- Brand Icon --}}
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-linear-to-tr from-amber-500 to-orange-600 rounded-3xl shadow-2xl shadow-orange-200 flex items-center justify-center transform rotate-6 hover:rotate-0 transition-transform duration-500 group">
                <i class="bi bi-key-fill text-white text-4xl group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-3 uppercase leading-none">{{ __('Update Secret') }}</h2>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[0.3em]">{{ __('Establish New Security Credentials') }}</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">Institutional Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <i class="bi bi-envelope-at text-gray-400 text-lg"></i>
                    </div>
                    <input id="email" 
                        class="block w-full pl-12 pr-5 py-4 bg-gray-50/50 border-2 border-transparent text-gray-900 text-sm rounded-2xl focus:ring-0 focus:border-indigo-500 focus:bg-white transition-all outline-none shadow-sm"
                        type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 px-2" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">New Secret Key</label>
                <div class="relative group" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <i class="bi bi-shield-lock-fill text-gray-400 text-lg"></i>
                    </div>
                    <input id="password" 
                        :type="show ? 'text' : 'password'"
                        class="block w-full pl-12 pr-12 py-4 bg-gray-50/50 border-2 border-transparent text-gray-900 text-sm rounded-2xl focus:ring-0 focus:border-indigo-500 focus:bg-white transition-all outline-none shadow-sm"
                        name="password" required autocomplete="new-password"
                        placeholder="••••••••" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-indigo-600 transition-colors">
                        <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 px-2" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">Confirm New Secret</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <i class="bi bi-shield-check text-gray-400 text-lg"></i>
                    </div>
                    <input id="password_confirmation" 
                        class="block w-full pl-12 pr-5 py-4 bg-gray-50/50 border-2 border-transparent text-gray-900 text-sm rounded-2xl focus:ring-0 focus:border-indigo-500 focus:bg-white transition-all outline-none shadow-sm"
                        type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 px-2" />
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-2xl shadow-xl shadow-indigo-200 text-xs font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-[0.98] tracking-[0.2em] uppercase group">
                    <span>{{ __('Finalize Security Update') }}</span>
                    <i class="bi bi-check-circle-fill ml-2 group-hover:scale-110 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
