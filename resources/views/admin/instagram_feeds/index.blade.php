@extends('layouts.admin.app')
@section('title', 'Instagram Feed Management')

@section('content')
<div class="p-4 sm:p-6 space-y-6" x-data="{ showEditModal: false, editItem: {id: null, embed_code: '', sort_order: 0, status: true} }">

    {{-- Header --}}
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <h1 class="text-3xl font-bold text-gray-900">Instagram Feed</h1>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 text-sm text-green-700 border border-green-200 rounded-lg bg-green-50" x-transition>
            <div class="flex items-center">
                <i class="bi bi-check-circle-fill text-lg me-3"></i>
                <div><span class="font-medium">Success:</span> {{ session('success') }}</div>
            </div>
            <button @click="show = false" class="ml-3 text-green-700/70 hover:text-green-900">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Add New Feed Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-800">Add New Post</h2>
                </div>
                <form action="{{ route('admin.instagram-feeds.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Instagram Embed Code</label>
                        <textarea name="embed_code" rows="6"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Paste the <blockquote> and <script> code from Instagram here..." required></textarea>
                        <p class="mt-1 text-xs text-gray-400 italic">Go to the Instagram post -> ... -> Embed -> Copy Embed Code</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0"
                            class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <button type="submit" class="w-full py-3 px-4 bg-(--primary-color) hover:bg-(--primary-hover) text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                        <i class="bi bi-plus-circle"></i> Add to Feed
                    </button>
                </form>
            </div>
        </div>

        {{-- Existing Feeds List --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800">Active Feed Items</h2>
                    <span class="px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-500">{{ $items->total() }} post(s)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Preview / Code</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-400 font-mono line-clamp-2 max-w-xs bg-gray-50 p-2 rounded">
                                            {{ Str::limit($item->embed_code, 150) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="fetch('{{ route('admin.instagram-feeds.toggle-status', $item) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => location.reload())"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border transition-colors {{ $item->status ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                            {{ $item->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-600">
                                        {{ $item->sort_order }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="editItem = {id: {{ $item->id }}, embed_code: `{{ addslashes($item->embed_code) }}`, sort_order: {{ $item->sort_order }}, status: {{ $item->status ? 'true' : 'false' }}}; showEditModal = true"
                                            class="text-amber-500 hover:text-amber-700 transition-colors p-1" title="Edit">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <form action="{{ route('admin.instagram-feeds.destroy', $item) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-1" onclick="return confirm('Delete this post?')" title="Delete">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-300 mb-2"><i class="bi bi-instagram text-4xl"></i></div>
                                        <p class="text-gray-500 font-medium">No Instagram posts added yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($items->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden" @click.away="showEditModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Edit Feed Item</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form :action="`/admin/instagram-feeds/${editItem.id}`" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Instagram Embed Code</label>
                    <textarea name="embed_code" rows="6" x-model="editItem.embed_code"
                        class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" x-model="editItem.sort_order"
                            class="w-full px-4 py-2 rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" class="sr-only peer" :checked="editItem.status">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-(--primary-color)"></div>
                            <span class="ml-3 text-sm font-bold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3 px-4 bg-(--primary-color) hover:bg-(--primary-hover) text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-100">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
