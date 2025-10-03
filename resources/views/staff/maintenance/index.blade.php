@extends('layouts.app')

@section('title', 'Maintenance Requests')
@section('page-title', 'Maintenance Management')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Maintenance Requests
                        </h1>
                        <p class="text-lg text-gray-600">
                            Manage and track property maintenance issues
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-red-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">High Priority</h3>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $maintenanceRequests->where('priority', 'high')->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/25">
                            <i class="fas fa-clock text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-amber-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Pending</h3>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $maintenanceRequests->where('status', 'pending')->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <i class="fas fa-cog text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">In Progress</h3>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $maintenanceRequests->where('status', 'in_progress')->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25">
                            <i class="fas fa-check text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Requests</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $maintenanceRequests->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-list text-gray-600 text-sm"></i>
                        </div>
                        Active Maintenance Requests
                    </h3>
                </div>

                @if ($maintenanceRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200/50">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Apartment</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Issue</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Priority</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 divide-y divide-gray-200/50">
                                @foreach ($maintenanceRequests as $request)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            #{{ $request['id'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $request['apartment'] }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $request['issue'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($request['priority'] === 'high')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200/50">
                                                    <i class="fas fa-exclamation-triangle mr-1.5"></i>
                                                    High
                                                </span>
                                            @elseif($request['priority'] === 'medium')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-amber-100 to-orange-100 text-amber-700 border border-amber-200/50">
                                                    <i class="fas fa-minus mr-1.5"></i>
                                                    Medium
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200/50">
                                                    <i class="fas fa-arrow-down mr-1.5"></i>
                                                    Low
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($request['status'] === 'pending')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-amber-100 to-orange-100 text-amber-700 border border-amber-200/50">
                                                    <i class="fas fa-clock mr-1.5"></i>
                                                    Pending
                                                </span>
                                            @elseif($request['status'] === 'in_progress')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-200/50">
                                                    <i class="fas fa-cog mr-1.5"></i>
                                                    In Progress
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200/50">
                                                    <i class="fas fa-check mr-1.5"></i>
                                                    Completed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                @if ($request['status'] !== 'completed')
                                                    <form action="{{ route('staff.maintenance.complete', $request['id']) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="inline-flex items-center text-green-600 hover:text-green-700 transition-colors duration-200"
                                                            onclick="return confirm('Mark this request as completed?')">
                                                            <i class="fas fa-check mr-1.5"></i>
                                                            Complete
                                                        </button>
                                                    </form>
                                                @endif
                                                <button
                                                    class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-eye mr-1.5"></i>
                                                    View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tools text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No maintenance requests</h3>
                        <p class="text-gray-500">All maintenance tasks are up to date.</p>
                    </div>
                @endif
            </div>

            <!-- @if (session('success'))
                <div
                    class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200/50 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif -->
        </div>
    </div>

    @push('styles')
        <style>
            .glassmorphism {
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }
        </style>
    @endpush
@endsection
