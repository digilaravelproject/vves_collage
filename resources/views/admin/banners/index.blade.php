@extends('layouts.admin.app')

@section('title', 'Homepage Banners')

@section('content')
    <div class="space-y-6"
        x-data="{
            showConfirmModal: false,
            confirmModalTitle: '',
            confirmModalMessage: '',
            formToSubmit: null,
            isDeleting: false
        }">

        {{-- Header --}}
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Homepage Banners</h1>
                <p class="text-sm text-gray-500">Manage individual slides for the homepage hero section.</p>
            </div>
            <a href="{{ route('admin.banners.create') }}"
                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition-all">
                <i class="bi bi-plus-circle me-2"></i>
                Add New Banner
            </a>
        </div>

        {{-- Success/Error Alerts --}}
        @if (session('success'))
            <div class="p-4 text-sm text-green-700 border border-green-200 rounded-xl bg-green-50 flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Main Table --}}
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Media</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Banner Text</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($banners as $banner)
                            <tr class="group hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-16 w-24 shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                                            @if ($banner->media_type === 'image')
                                                <img src="{{ asset('storage/' . $banner->media_path) }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="relative h-full w-full flex items-center justify-center bg-gray-900">
                                                    <i class="bi bi-play-fill text-white text-2xl"></i>
                                                    <span class="absolute bottom-1 right-1 text-[8px] text-white bg-black/50 px-1 rounded">VIDEO</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs space-y-1">
                                        <div class="font-bold text-gray-900 truncate">{{ $banner->title ?: 'No Title' }}</div>
                                        <div class="text-xs text-gray-500 line-clamp-2">{{ $banner->subtitle ?: 'No subtitle' }}</div>
                                        @if($banner->button_text)
                                            <div class="mt-2 text-[10px] inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-100">
                                                <i class="bi bi-link-45deg me-1"></i> {{ $banner->button_text }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" {{ $banner->is_active ? 'checked' : '' }}
                                            @click="
                                                fetch('{{ route('admin.banners.toggle-status', $banner) }}', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                }).then(res => res.json()).then(data => {
                                                    $dispatch('notify', { message: 'Status updated!', type: 'success' });
                                                })
                                            "
                                            class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-5 after:h-5 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"></div>
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-gray-600">
                                    {{ $banner->order }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" 
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                        <button @click="
                                            confirmModalTitle = 'Delete Banner';
                                            confirmModalMessage = 'Are you sure you want to delete this banner? This will permanently remove the media file.';
                                            formToSubmit = '{{ route('admin.banners.destroy', $banner) }}';
                                            showConfirmModal = true;
                                        " class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                                            <i class="bi bi-image text-4xl text-gray-300"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">No Banners Found</h3>
                                        <p class="text-sm text-gray-500 mt-1 max-w-sm">Create individual slides with unique text and media for your homepage.</p>
                                        <a href="{{ route('admin.banners.create') }}" class="mt-6 text-blue-600 font-semibold hover:underline">
                                            Add your first banner +
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Confirmation Modal --}}
        <div x-show="showConfirmModal" style="display: none;" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showConfirmModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="relative w-full max-w-md p-6 bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-red-100 text-red-600 rounded-2xl">
                        <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900" x-text="confirmModalTitle"></h3>
                        <p class="mt-2 text-sm text-gray-600 leading-relaxed" x-text="confirmModalMessage"></p>
                    </div>
                </div>
                <div class="mt-8 flex gap-3 flex-row-reverse">
                    <button @click="
                        isDeleting = true;
                        fetch(formToSubmit, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(() => window.location.reload());
                    " :disabled="isDeleting"
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 disabled:opacity-50 transition-all shadow-lg shadow-red-200">
                        <span x-show="!isDeleting">Yes, Delete It</span>
                        <span x-show="isDeleting">Deleting...</span>
                    </button>
                    <button @click="showConfirmModal = false" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        {{-- Toast Notify (Global) --}}
        <div x-data="{ show: false, message: '', type: 'success' }"
            @notify.window="show=true; message=$event.detail.message; type=$event.detail.type; setTimeout(()=>show=false, 3000)"
            x-show="show" x-transition class="fixed bottom-5 right-5 z-50 p-4 rounded-2xl shadow-2xl flex items-center gap-3 border"
            :class="type === 'success' ? 'bg-white border-green-100 text-green-700' : 'bg-white border-red-100 text-red-700'">
            <i :class="type === 'success' ? 'bi bi-check-circle-fill text-green-500' : 'bi bi-exclamation-circle-fill text-red-500'" class="text-xl"></i>
            <span class="font-bold" x-text="message"></span>
        </div>

    </div>
@endsection
