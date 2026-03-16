<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f8fafc]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-blue-50 rounded-full blur-3xl opacity-50 z-0"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-[600px] h-[600px] bg-indigo-50 rounded-full blur-3xl opacity-50 z-0"></div>

            <div class="z-10 w-full flex flex-col items-center">
                <div class="mb-8 transform transition-transform hover:scale-110 duration-500">
                    <a href="/">
                        <img src="{{ asset('storage/' . setting('college_logo')) }}"
                             alt="College Logo"
                             class="h-20 sm:h-24 w-auto object-contain drop-shadow-2xl">
                    </a>
                </div>

                <div class="w-full sm:max-w-md bg-white/80 backdrop-blur-xl border border-white p-8 sm:p-10 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] rounded-[2.5rem] overflow-hidden">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 text-center sm:max-w-md w-full">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em]">Validated Access Only</p>
                </div>
            </div>
        </div>
    </body>
</html>
