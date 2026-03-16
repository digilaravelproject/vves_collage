@php
    $model = $model ?? 'block';
    $compact = $compact ?? false;
@endphp
<div class="space-y-2">
    <label class="{{ $compact ? 'text-[10px] uppercase font-medium text-gray-500' : 'text-sm font-medium text-gray-600' }}">Embed URL (YouTube, etc.)</label>
    <input type="text" x-model="{{ $model }}.src" @input="pushHistory"
        class="w-full {{ $compact ? 'p-1.5 text-xs' : 'p-2' }} border rounded"
        placeholder="https://www.youtube.com/watch?v=...">
</div>
