@extends('layouts.admin.app')

@section('title', 'Review Change')

@section('content')
<div class="container-fluid px-4 py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('admin.workflow.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Workflow</a></li>
                    <li class="flex items-center space-x-2 font-medium text-slate-400">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                        <span class="text-slate-600">Review Request #{{ $pendingAction->id }}</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-slate-800">Review Request</h1>
        </div>
        <div class="flex gap-4">
            <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-6 py-2.5 bg-white border border-rose-600 text-rose-600 font-semibold rounded-xl hover:bg-rose-50 transition-all duration-300">
                Reject Change
            </button>
            <form action="{{ route('admin.workflow.approve', $pendingAction) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve and apply these changes live?')">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all duration-300">
                    Approve & Publish
                </button>
            </form>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 mb-8 rounded-r-lg shadow-sm">
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Comparison) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 class="font-bold text-slate-800">Proposed Changes</h3>
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full uppercase">{{ $pendingAction->action }}</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($proposedData as $key => $value)
                            @if(!is_array($value) && !in_array($key, ['_token', '_method', 'id']))
                            <div class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ str_replace('_', ' ', $key) }}</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                        <span class="text-xs font-bold text-slate-400 block mb-1 uppercase">Current</span>
                                        <span class="text-sm text-slate-600 italic">
                                            @if($pendingAction->action === 'CREATE')
                                                <span class="text-slate-400 cursor-not-allowed">[Does not exist]</span>
                                            @else
                                                {{ is_string($currentData[$key] ?? null) ? $currentData[$key] : '[Complex Data/Null]' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                        <span class="text-xs font-bold text-emerald-400 block mb-1 uppercase">Proposed</span>
                                        <span class="text-sm text-emerald-900 font-semibold uppercase">
                                            {{ is_string($value) ? $value : '[Complex Data]' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800">History & Logs</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-8 border-l-2 border-slate-100 space-y-8">
                        @foreach($pendingAction->logs as $log)
                        <div class="relative">
                            <div class="absolute -left-[41px] top-0 h-4 w-4 rounded-full border-4 border-white {{ $log->action === 'approved' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-slate-800 uppercase">{{ $log->user->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 italic">"{{ $log->notes }}"</p>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Initial Action -->
                        <div class="relative">
                            <div class="absolute -left-[41px] top-0 h-4 w-4 rounded-full border-4 border-white bg-amber-500"></div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-slate-800 uppercase">{{ $pendingAction->maker->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $pendingAction->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600">Submitted request for <strong>{{ $pendingAction->action }}</strong> on {{ class_basename($pendingAction->model_type) }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Maker Information</h4>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-indigo-100">
                            {{ strtoupper(substr($pendingAction->maker->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $pendingAction->maker->name }}</p>
                            <p class="text-xs text-slate-500">{{ $pendingAction->maker->roles->pluck('name')->first() ?? 'Staff' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-400 font-medium">Status</span>
                            <span class="font-bold text-amber-600 uppercase">{{ $pendingAction->status }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-400 font-medium">Model Type</span>
                            <span class="text-slate-700 font-semibold">{{ class_basename($pendingAction->model_type) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 font-medium">Target ID</span>
                            <span class="text-slate-700 font-semibold">#{{ $pendingAction->model_id ?? 'New' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 h-24 w-24 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m3.332-4.706C11.054 10.053 9.465 8.358 8.1 6m4 0v2m0 0v2m0-2h2m-2 0H10m11 4a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h4 class="font-bold uppercase tracking-wider text-xs">Security Protocol</h4>
                    </div>
                    <p class="text-xs text-indigo-100 leading-relaxed mb-4">
                        Approving this request will immediately overwrite production data. Ensure all fields (names, slugs, and JSON payloads) are accurate.
                    </p>
                    <div class="text-[10px] font-mono text-indigo-400 bg-black/20 p-2 rounded">
                        Audit Log: {{ now()->format('Y-m-d H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Reject Request</h2>
            <p class="text-slate-500 mb-6">Please provide a reason for rejecting this change so the Maker can correct it.</p>
            <form action="{{ route('admin.workflow.reject', $pendingAction) }}" method="POST">
                @csrf
                <textarea name="notes" required rows="4" class="w-full px-4 py-3 rounded-2xl border-slate-200 focus:ring-rose-500 focus:border-rose-500 placeholder:text-slate-300" placeholder="Type clarification for rejection..."></textarea>
                <div class="mt-8 flex gap-4">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-200">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
