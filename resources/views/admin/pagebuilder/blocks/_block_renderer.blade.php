@php
    $model = $model ?? 'block';
    $section = $section ?? 'null';
    $compact = $compact ?? false;
    $depth = $depth ?? 0;
    $maxDepth = 5; // Prevent memory exhaustion while supporting nesting
@endphp

{{-- 1. HEADING / TEXT --}}
<template x-if="['heading', 'text'].includes({{ $model }}?.type)">
    @include('admin.pagebuilder.blocks.text', ['model' => $model, 'compact' => $compact])
</template>

{{-- 2. IMAGE --}}
<template x-if="{{ $model }}?.type === 'image'">
    @include('admin.pagebuilder.blocks.image', ['model' => $model, 'section' => $section, 'compact' => $compact])
</template>

{{-- 3. VIDEO --}}
<template x-if="{{ $model }}?.type === 'video'">
    @include('admin.pagebuilder.blocks.video', ['model' => $model, 'section' => $section, 'compact' => $compact])
</template>

{{-- 4. PDF --}}
<template x-if="{{ $model }}?.type === 'pdf'">
    @include('admin.pagebuilder.blocks.pdf', ['model' => $model, 'section' => $section, 'compact' => $compact])
</template>

{{-- 5. BUTTON --}}
<template x-if="{{ $model }}?.type === 'button'">
    @include('admin.pagebuilder.blocks.button', ['model' => $model, 'section' => $section, 'compact' => $compact])
</template>

{{-- 6. EMBED --}}
<template x-if="{{ $model }}?.type === 'embed'">
    @include('admin.pagebuilder.blocks.embed', ['model' => $model, 'compact' => $compact])
</template>

{{-- 7. DIVIDER --}}
<template x-if="{{ $model }}?.type === 'divider'">
    @include('admin.pagebuilder.blocks.divider')
</template>

{{-- 8. CODE --}}
<template x-if="{{ $model }}?.type === 'code'">
    @include('admin.pagebuilder.blocks.code', ['model' => $model, 'compact' => $compact])
</template>

{{-- 9. TABLE --}}
<template x-if="{{ $model }}?.type === 'table'">
    @include('admin.pagebuilder.blocks.table', ['model' => $model, 'compact' => $compact])
</template>

{{-- 10. SECTION --}}
<template x-if="{{ $model }}?.type === 'section'">
    @if($depth < $maxDepth)
        @include('admin.pagebuilder.blocks.section', ['model' => $model, 'index' => isset($index) ? $index : 'null', 'depth' => $depth + 1])
    @else
        <div class="p-4 border-2 border-red-200 bg-red-50 text-red-600 rounded text-xs">
            ⚠️ Nesting limit reached ({{ $maxDepth }}).
        </div>
    @endif
</template>

{{-- 11. LAYOUT GRID --}}
<template x-if="{{ $model }}?.type === 'layout_grid'">
    @if($depth < $maxDepth)
        @include('admin.pagebuilder.blocks.layout_grid', ['model' => $model, 'index' => isset($index) ? $index : 'null', 'depth' => $depth + 1])
    @else
        <div class="p-4 border-2 border-red-200 bg-red-50 text-red-600 rounded text-xs">
            ⚠️ Nesting limit reached ({{ $maxDepth }}).
        </div>
    @endif
</template>

{{-- 12. STAFF GRID --}}
<template x-if="{{ $model }}?.type === 'staff_grid'">
    @include('admin.pagebuilder.blocks.staff_grid', ['model' => $model, 'compact' => $compact])
</template>

{{-- 13. PHOTO GALLERY --}}
<template x-if="{{ $model }}?.type === 'photo_gallery'">
    @include('admin.pagebuilder.blocks.photo_gallery', ['model' => $model, 'compact' => $compact])
</template>
