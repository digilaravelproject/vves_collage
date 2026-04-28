@extends('layouts.admin.app')
@section('title', 'Institutions')

@section('content')
    <div class="p-4 sm:p-6 space-y-6" x-data="{ showConfirmModal: false, confirmModalTitle: '', confirmModalMessage: '', formToSubmit: null }">
        
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <h1 class="text-3xl font-bold text-gray-900">Institutions Management</h1>
            <a href="{{ route('admin.institutions.create') }}"
                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                <i class="bi bi-plus-circle me-2 font-bold"></i>
                Add New Institution
            </a>
        </div>


        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Institution Name</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($institutions as $item)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($item->featured_image)
                                            <img src="{{ asset('storage/' . $item->featured_image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100">
                                                <i class="bi bi-building text-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <div class="font-semibold text-gray-900">{{ $item->name }}</div>
                                                @if($item->hasPendingChanges())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 animate-pulse uppercase tracking-tight">
                                                        <i class="bi bi-clock-history me-1"></i> Pending
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 font-medium">Est. {{ $item->year_of_establishment ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                        {{ $item->category_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.institutions.toggle-status', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center">
                                            <span class="px-3 py-1 text-xs font-medium rounded-full cursor-pointer transition-colors duration-200 {{ $item->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $item->status ? 'Active' : 'Disabled' }}
                                            </span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.institutions.edit', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Edit">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                        <form action="{{ route('admin.institutions.destroy', $item->id) }}" method="POST"
                                            id="delete-form-{{ $item->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" @click="
                                                confirmModalTitle = 'Delete Institution';
                                                confirmModalMessage = 'Are you sure you want to delete \'{{ addslashes($item->name) }}\'? All related data like results, principal info, and gallery will be permanently removed.';
                                                formToSubmit = document.getElementById('delete-form-{{ $item->id }}');
                                                showConfirmModal = true;
                                            " class="text-red-500 hover:text-red-700 transition-colors duration-200" title="Delete">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="bi bi-building-slash text-5xl text-gray-200"></i>
                                        <p class="text-lg font-medium">No Institutions Found</p>
                                        <p class="text-sm text-gray-400">Start by adding your first college or school.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($institutions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $institutions->links() }}
                </div>
            @endif
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="showConfirmModal" style="display: none;" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div @click="showConfirmModal = false; formToSubmit = null;" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div x-show="showConfirmModal" x-transition class="relative z-50 w-full max-w-md p-6 overflow-hidden bg-white shadow-2xl rounded-3xl">
                <div class="sm:flex sm:items-start text-center sm:text-left">
                    <div class="flex items-center justify-center shrink-0 w-12 h-12 mx-auto bg-red-50 rounded-full sm:mx-0">
                        <i class="text-xl text-red-600 bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="mt-4 sm:ml-4 sm:mt-0">
                        <h3 class="text-xl font-bold text-gray-900" x-text="confirmModalTitle"></h3>
                        <p class="mt-2 text-sm text-gray-500" x-text="confirmModalMessage"></p>
                    </div>
                </div>
                <div class="mt-8 flex flex-col sm:flex-row-reverse gap-3">
                    <button @click="formToSubmit.submit(); showConfirmModal = false;" type="button"
                        class="w-full px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 focus:outline-none transition-all duration-200">
                        Confirm Delete
                    </button>
                    <button @click="showConfirmModal = false; formToSubmit = null;" type="button"
                        class="w-full px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
