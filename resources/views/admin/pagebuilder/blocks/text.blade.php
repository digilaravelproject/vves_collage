@php
    $model = $model ?? 'block';
    $compact = $compact ?? false;
@endphp

<div :class="{{ $compact ? 'false' : 'true' }} ? 'space-y-2' : 'space-y-2'">
    <div :id="'toolbar-' + {{ $model }}.id"
        class="flex flex-wrap items-center gap-1 p-1 mb-2 bg-white rounded shadow-sm {{ $compact ? '' : 'sm:gap-2 sm:p-2' }}">
        <select class="ql-header">
            <option value="1"></option>
            <option value="2"></option>
            <option value="3"></option>
            <option selected></option>
        </select>
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-strike"></button>
        <button class="ql-code"></button>
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-blockquote"></button>
        <select class="ql-color"></select>
        <select class="ql-align"></select>
        <button class="ql-link"></button>
        <button @click.prevent="openLinkDialog({{ $model }}.id)" class="ql-btn-link">🔗</button>
    </div>

    <div :id="'editor-' + {{ $model }}.id" class="bg-white border rounded quill-editor"
        style="{{ $compact ? 'min-height:80px;' : 'min-height:100px;' }}"></div>
</div>
