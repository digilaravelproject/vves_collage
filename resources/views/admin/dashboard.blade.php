@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">

        <!-- Role-Specific Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">
                @can('workflow.approve')
                    Checker Dashboard
                @else
                    Staff Dashboard
                @endcan
            </h1>
            <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }} 👋</p>
        </div>

        @if(auth()->user()->can('workflow.approve') || auth()->user()->hasRole('Super Admin'))
            <!-- Workflow Attention Section (Checker View) -->
            <div class="bg-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-200">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-black">Ready for Review?</h2>
                        <p class="text-indigo-100 text-lg font-medium opacity-90">There are <span class="bg-white text-indigo-600 px-2 py-0.5 rounded-lg font-bold">{{ $workflow['pendingToApprove'] }}</span> pending actions awaiting your approval.</p>
                    </div>
                    @if($workflow['pendingToApprove'] > 0)
                        <a href="{{ route('admin.workflow.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-all group">
                            Review Now
                            <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endif
                </div>
                <!-- Abstract Design Elements -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-indigo-400/20 rounded-full blur-3xl"></div>
            </div>
        @endif

        @if($workflow['myPendingCount'] > 0)
            <!-- Maker Pending Tracker -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-amber-900">Your Proposals are Pending</h3>
                        <p class="text-sm text-amber-700">You have {{ $workflow['myPendingCount'] }} submissions waiting for Checker review.</p>
                    </div>
                </div>
                <div class="text-[10px] font-bold uppercase tracking-widest text-amber-500 bg-white px-3 py-1 rounded-full border border-amber-100">
                    In Progress
                </div>
            </div>
        @endif

        <!-- Stats Cards (Filtered by Permissions) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @can('approve testimonials')
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between border border-gray-100 hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Pending Testimonials</p>
                        <h2 class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['pendingTestimonials'] }}</h2>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                        <i class="fas fa-comment-dots text-2xl"></i>
                    </div>
                </div>
            @endcan

            @can('view events')
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between border border-gray-100 hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Upcoming Events</p>
                        <h2 class="text-3xl font-bold text-green-600 mt-1">{{ $stats['upcomingEvents'] }}</h2>
                    </div>
                    <div class="p-3 bg-green-100 text-green-600 rounded-full">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            @endcan

            @can('view announcements')
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between border border-gray-100 hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Active Announcements</p>
                        <h2 class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['activeAnnouncements'] }}</h2>
                    </div>
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-full">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                </div>
            @endcan

            @can('view gallery')
                <div class="bg-white rounded-2xl shadow p-6 flex items-center justify-between border border-gray-100 hover:shadow-lg transition">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Gallery Images</p>
                        <h2 class="text-3xl font-bold text-(--primary-color) mt-1">{{ $stats['galleryImages'] }}</h2>
                    </div>
                    <div class="p-3 bg-(--primary-color)/10 text-(--primary-color) rounded-full">
                        <i class="fas fa-images text-2xl"></i>
                    </div>
                </div>
            @endcan
        </div>

        @if($workflow['myRecentSubmissions']->isNotEmpty())
            <!-- Recent My Submissions -->
            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center border-b p-4">
                    <h2 class="text-lg font-semibold text-gray-700">My Recent Submissions</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-gray-600 text-[11px] font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 text-left">Action</th>
                                <th class="px-6 py-4 text-left">Module</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700 font-medium">
                            @foreach($workflow['myRecentSubmissions'] as $submission)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 truncate max-w-xs">{{ $submission->action_type_label }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold">{{ class_basename($submission->model_type) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($submission->status === 'pending')
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200">Pending</span>
                                        @elseif($submission->status === 'approved')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-200">Approved</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold border border-rose-200">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-400 text-xs">{{ $submission->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @can('manage settings')
            <!-- Admin Charts (Placeholder) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Enrollment Trends -->
                <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Enrollment Trends</h2>
                        <span class="text-xs text-gray-400">Last 6 Months</span>
                    </div>
                    <div class="h-60 flex items-center justify-center text-gray-400 bg-gray-50 rounded-xl border border-dashed">
                        <span class="text-sm">[ Chart Data Available for Full Admin ]</span>
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">System Activity</h2>
                        <i class="bi bi-clock-history text-gray-400"></i>
                    </div>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 italic">Centralized activity logging under construction.</p>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection