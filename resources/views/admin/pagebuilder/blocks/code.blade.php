@php
    $model = $model ?? 'block';
    $compact = $compact ?? false;
@endphp
<div>
    <label class="{{ $compact ? 'text-[10px] uppercase font-medium text-gray-500' : 'text-sm font-medium text-gray-600' }}">Code</label>
    <textarea x-model="{{ $model }}.content" @input="pushHistory"
        class="w-full p-2 font-mono border rounded {{ $compact ? 'text-xs' : '' }}" rows="{{ $compact ? '4' : '6' }}"
        placeholder="<script>..."></textarea>
</div>
