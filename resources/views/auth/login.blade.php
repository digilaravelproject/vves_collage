<x-guest-layout>
    <div class="fixed inset-0 z-0 overflow-hidden">
        {{-- Animated Background Objects --}}
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-indigo-600/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Portal Access</h2>
            <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Administrator Sign In</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Institutional Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <i class="bi bi-envelope-at text-gray-400"></i>
                    </div>
                    <input id="email" 
                        class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 focus:bg-white transition-all outline-none"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="admin@vves.edu.in" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 px-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2 px-1">
                    <label for="password" class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">Secret Key</label>
                    @if (Route::has('password.request'))
                        <a class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-wider" href="{{ route('password.request') }}">
                            Recover?
                        </a>
                    @endif
                </div>
                <div class="relative group" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-600">
                        <i class="bi bi-shield-lock-fill text-gray-400"></i>
                    </div>
                    <input id="password" 
                        :type="show ? 'text' : 'password'"
                        class="block w-full pl-11 pr-12 py-3.5 bg-gray-50/50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 focus:bg-white transition-all outline-none"
                        name="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-indigo-600">
                        <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 px-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center px-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-indigo-500/10 focus:ring-2 transition-all cursor-pointer" name="remember">
                    <span class="ml-2 text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors">Keep my session active</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-indigo-200 text-xs font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-[0.98] tracking-[0.2em] uppercase">
                    Authenticate
                </button>
            </div>
            
            <div class="mt-8 text-center pt-4">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">
                    &copy; {{ date('Y') }} {{ setting('college_name', 'VVES College') }}
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
