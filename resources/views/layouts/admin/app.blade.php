<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ✅ Admin Panel Title --}}
    <title>@yield('title', 'Admin Panel') - {{ setting('college_name', config('app.name')) }}</title>

    {{-- ✅ Favicon --}}
    <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}" type="image/x-icon">

    {{-- ✅ IMPORTANT: Tell Google to NOT index this admin panel --}}
    <meta name="robots" content="noindex, nofollow">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ✅ Icons (Bootstrap Icons & Font Awesome) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/@fortawesome/fontawesome-free/css/all.min.css">

    <style>
        /* --- Branding Variables --- */
        :root {
            --primary-color: #000165;
            --primary-hover: #00014d;
        }

        /* --- Basic UI Enhancements --- */
        [x-cloak] {
            display: none !important;
        }

        .card-shadow {
            box-shadow: 0 6px 18px rgba(8, 15, 52, 0.08);
        }

        .glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(6px);
        }
    </style>

    {{-- ✅ Custom styles from child views --}}
    @stack('styles')
</head>

<body class="min-h-screen text-gray-800 bg-gray-50" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        {{-- ✅ Sidebar --}}
        @include('layouts.admin.partials.sidebar')

        {{-- ✅ Main Area --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- ✅ Topbar --}}
            @include('layouts.admin.partials.topbar')

            {{-- ✅ Main Content --}}
            <main class="flex-1 p-6 overflow-auto">
                <div class="mx-auto max-w-7xl">
                    {{-- ✅ Permission Error --}}
                    @if (session('permission_error'))
                        <div class="mb-6 flex items-center p-4 text-sm text-red-800 border-l-4 border-red-500 bg-red-50 rounded shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-3 text-lg"></i>
                            <div>
                                <span class="font-bold">Access Denied:</span> {{ session('permission_error') }}
                            </div>
                        </div>
                    @endif

                    {{-- ✅ Success Message --}}
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                            class="mb-6 flex items-center justify-between p-4 text-sm text-green-800 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
                            <div class="flex items-center">
                                <i class="bi bi-check-circle-fill me-3 text-lg"></i>
                                <div>
                                    <span class="font-bold">Success!</span> {{ session('success') }}
                                </div>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    {{-- ✅ Error Message --}}
                    @if (session('error'))
                        <div class="mb-6 flex items-center p-4 text-sm text-red-800 border-l-4 border-red-500 bg-red-50 rounded shadow-sm" role="alert">
                            <i class="bi bi-x-circle-fill me-3 text-lg"></i>
                            <div>
                                <span class="font-bold">Error:</span> {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @can('view admin dashboard')
                        @yield('content')
                    @else
                        <div class="p-12 text-center bg-white border border-gray-200 rounded-3xl shadow-xl">
                            <div class="inline-flex items-center justify-center w-20 h-20 mb-6 bg-red-100 rounded-full text-red-600">
                                <i class="bi bi-lock-fill text-4xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Restricted Access</h2>
                            <p class="text-gray-500 max-w-sm mx-auto">You don't have the required permissions to access this administrative module.</p>
                            <a href="{{ route('homepage') }}" class="mt-8 inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                                <i class="bi bi-arrow-left me-2"></i> Return to Homepage
                            </a>
                        </div>
                    @endcan
                </div>
            </main>

            {{-- ✅ Footer --}}
            <footer class="p-4 text-sm text-gray-500 bg-white border-t">
                <div class="mx-auto text-center max-w-7xl">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </footer>

        </div>
    </div>

    {{-- ✅ Extra scripts (SweetAlert, etc.) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    {{-- ✅ Scripts pushed from child views --}}
    @stack('scripts')

</body>

</html>
