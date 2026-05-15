@props(['src'])

@php
    $finalSrc = $src;
    if (str_contains($finalSrc, '.pdf')) {
        $finalSrc .= (str_contains($finalSrc, '?') ? '&' : '?') . 'view_embedded=true';
    }
@endphp

<iframe src="{{ $finalSrc }}" class="w-full h-[600px] border rounded-lg shadow-inner" frameborder="0"
    loading="lazy"></iframe>