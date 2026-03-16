@extends('layouts.app')

@section('title', $activeSection->title)

@section('content')
    <style>
        section>h2 {
            position: sticky;
            top: 0;
            background: #f9fafb;
            z-index: 10;
        }
    </style>
    <section class="container px-4 py-10 mx-auto">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-4">

            {{-- Render the menu --}}
            <aside class="space-y-2 md:sticky md:top-24 h-fit">
                <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">
                    {{ $topParent->title ?? $activeSection->title }}
                </h2>

                @include('frontend.pages.partials.menu', ['menus' => $menus, 'activeSection' => $activeSection])

            </aside>

            {{-- ==================== MAIN CONTENT ==================== --}}
            <main class="p-4 space-y-6 bg-white shadow-md rounded-2xl md:col-span-3">
                @php
                    $content = json_decode($activeSection->content, true);
                    $blocks = $content['blocks'] ?? $content ?? [];
                @endphp

                @if (!empty($blocks) && is_array($blocks))
                    @foreach ($blocks as $block)
                        <x-page-block :block="$block" />
                    @endforeach
                @else
                    <p class="italic text-gray-400">No content found for this page.</p>
                @endif

                @if (!empty($activeSection->pdf_path))
                    <div class="mt-8">
                        <x-pdf-viewer :src="asset('storage/' . $activeSection->pdf_path)" />
                    </div>
                @endif
            </main>

        </div>
    </section>
@endsection