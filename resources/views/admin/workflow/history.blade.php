@extends('layouts.admin.app')

@section('title', 'Activity History')

@section('content')
<div class="container-fluid px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Activity History</h1>
            <p class="text-slate-500 mt-1">Audit trail of all processed change requests (Approved & Rejected).</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-r-lg shadow-sm" role="alert">
        <div class="flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Institution</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Maker</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Approver/Checker</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actioned Date</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($historyActions as $action)
                    <tr class="hover:bg-slate-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-800">{{ class_basename($action->model_type) }}</span>
                                <span class="text-xs text-slate-400">ID: {{ $action->model_id ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600 font-medium">
                                {{ $action->institution->name ?? 'Global' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $actionBadge = match($action->action) {
                                    'CREATE' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'UPDATE' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'DELETE' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $actionBadge }}">
                                {{ $action->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-[10px] font-bold mr-3 border border-indigo-100">
                                    {{ strtoupper(substr($action->maker->name, 0, 2)) }}
                                </div>
                                <span class="text-sm text-slate-600 font-medium">{{ $action->maker->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusBadge = match($action->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $statusBadge }}">
                                {{ $action->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($action->checker)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-[10px] font-bold mr-3 border border-slate-200">
                                    {{ strtoupper(substr($action->checker->name, 0, 2)) }}
                                </div>
                                <span class="text-sm text-slate-600 font-medium">{{ $action->checker->name }}</span>
                            </div>
                            @else
                            <span class="text-xs text-slate-400 italic">Auto-Bypassed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $action->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.workflow.show', $action) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm transition-colors">
                                View Logs
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900">No History Records Found</h3>
                                <p class="text-slate-500 mt-1">Activity logs will appear here once requests are processed.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($historyActions->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $historyActions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
