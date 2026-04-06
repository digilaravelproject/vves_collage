@php
    // $loop variable ko PageBlock.php class se pass karna hoga
    // (Aapko PageBlock.php me public $loop; add karna pad sakta hai)
    $loop = $loop ?? null;

    // Default view path
    $includePath = null;

    // Baki sab blocks ke liye unka view path set karein
    $includePath = match ($type) {
        'intro' => 'components.home-page-blocks.intro',
        'sectionLinks' => 'components.home-page-blocks.section-links',
        // 'latestUpdates' => 'components.home-page-blocks.latest-updates',
        'announcements' => 'components.home-page-blocks.announcements',
        'events' => 'components.home-page-blocks.events',
        'academic_calendar' => 'components.home-page-blocks.academic-calendar',
        // 'image_text' => 'components.home-page-blocks.image-text', // Yeh 'intro' jaisa lag raha hai, iska naam 'image_text' hai
        'gallery' => 'components.home-page-blocks.gallery',
        'testimonials' => 'components.home-page-blocks.testimonials',
        'why_choose_us' => 'components.home-page-blocks.why-choose-us',
        'divider' => 'components.home-page-blocks.divider',
        'layout_grid' => 'components.home-page-blocks.layout-grid',
        'social-connects' => 'components.home-page-blocks.social-connects',
        'instagram_profiles' => 'components.home-page-blocks.instagram-profiles',
        'instagram_feed' => 'components.home-page-blocks.instagram-feed',
        'board_of_advisors' => 'components.home-page-blocks.board-of-advisors',
        'institutions' => 'components.home-page-blocks.institutions',
        default => null
    };

@endphp

@if ($includePath)
    @if ($type === 'divider')
        {{-- Divider ko wrapper/padding nahi chahiye --}}
        @include($includePath)

    @elseif ($type === 'layout_grid')
        {{-- Layout Grid ka wrapper alag hai (yeh recursive hai) --}}
        @include($includePath, [
            'block' => $block,
            'loop' => $loop // $loop ko nested blocks me pass karein
        ])
    @else
        <section class="w-full py-2 md:py-3 {{ $loop && $loop->even ? 'bg-[#F8F9FA]' : 'bg-white' }}">
            <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
                @include($includePath, [
                    'block' => $block, 
                    'items' => $items, 
                    'title' => $title, 
                    'description' => $description, 
                ])
            </div>
        </section>
    @endif
@endif
