@extends('layouts.app')

@section('title', 'Maintenance Request Details')
@section('page-title', 'Maintenance Request #' . $maintenance['id'])

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="{{ route('tenant.maintenance.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Maintenance
                </a>
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Request #{{ $maintenance['id'] }}
                        </h1>
                        <p class="text-lg text-gray-600">
                            Submitted on {{ \Carbon\Carbon::parse($maintenance['submitted_date'])->format('F j, Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold
                            @if ($maintenance['status'] === 'completed') bg-green-100 text-green-800
                            @elseif($maintenance['status'] === 'in_progress') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $maintenance['status'])) }}
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold
                            @if ($maintenance['priority'] === 'high') bg-red-100 text-red-800
                            @elseif($maintenance['priority'] === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($maintenance['priority']) }} Priority
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                <div class="px-6 py-5 sm:px-8 border-b border-gray-200/60">
                    <h3 class="text-2xl font-semibold text-gray-900">Request Details</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Complete information about your maintenance request
                    </p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="space-y-6">

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Issue Summary</label>
                                <p class="text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                                    {{ $maintenance['issue'] }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ ucfirst($maintenance['category']) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Priority Level</label>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold
                                        @if ($maintenance['priority'] === 'high') bg-red-100 text-red-800 border border-red-200
                                        @elseif($maintenance['priority'] === 'medium') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @else bg-green-100 text-green-800 border border-green-200 @endif">
                                        {{ ucfirst($maintenance['priority']) }}
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        @if ($maintenance['priority'] === 'high')
                                            Urgent/Safety Issue
                                        @elseif($maintenance['priority'] === 'medium')
                                            Affects Daily Use
                                        @else
                                            Minor Issue
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Current Status</label>
                                <div class="flex items-center gap-2">
                                    @if ($maintenance['status'] === 'completed')
                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($maintenance['status'] === 'in_progress')
                                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $maintenance['status'])) }}</span>
                                    <span class="text-sm text-gray-600">
                                        @if ($maintenance['status'] === 'completed')
                                            - Request has been completed
                                        @elseif($maintenance['status'] === 'in_progress')
                                            - Currently being worked on
                                        @else
                                            - Waiting for assignment
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Submitted Date</label>
                            <p class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($maintenance['submitted_date'])->format('F j, Y \a\t g:i A') }}
                                <span class="text-gray-500">
                                    ({{ \Carbon\Carbon::parse($maintenance['submitted_date'])->diffForHumans() }})
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Detailed Description</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $maintenance['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                <div class="px-6 py-5 sm:px-8 border-b border-gray-200/60">
                    <h3 class="text-2xl font-semibold text-gray-900">Staff Updates</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Communication and notes from the maintenance team
                    </p>
                </div>
                <div class="p-6 sm:p-8">
                    @if ($maintenance['staff_notes'])
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-base font-semibold text-blue-900 mb-2">Staff Notes</h4>
                                    <div class="text-sm text-blue-800">
                                        <p class="whitespace-pre-wrap leading-relaxed">{{ $maintenance['staff_notes'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-2.96-.492L5 23l3.492-5.04A8.959 8.959 0 013 12C3 7.582 6.582 4 11 4s8 3.582 8 8z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">No staff updates yet</h3>
                            <p class="text-sm text-gray-600">
                                Staff will add updates and notes as they work on your request.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                <div class="px-6 py-5 sm:px-8 border-b border-gray-200/60">
                    <h3 class="text-2xl font-semibold text-gray-900">Request Timeline</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Track the progress of your maintenance request
                    </p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                        aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white shadow-lg shadow-green-500/30">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-600">
                                                    Request submitted on
                                                    <span class="font-semibold text-gray-900">
                                                        {{ \Carbon\Carbon::parse($maintenance['submitted_date'])->format('M j, Y \a\t g:i A') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="relative pb-8">
                                    @if ($maintenance['status'] !== 'completed')
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                            aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full
                                                @if ($maintenance['status'] === 'completed') bg-green-500 shadow-lg shadow-green-500/30
                                                @elseif($maintenance['status'] === 'in_progress') bg-blue-500 shadow-lg shadow-blue-500/30
                                                @else bg-yellow-500 shadow-lg shadow-yellow-500/30 @endif
                                                flex items-center justify-center ring-8 ring-white">
                                                @if ($maintenance['status'] === 'completed')
                                                    <svg class="h-5 w-5 text-white" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @elseif($maintenance['status'] === 'in_progress')
                                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 text-white" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-600">
                                                    Status: <span
                                                        class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $maintenance['status'])) }}</span>
                                                    @if ($maintenance['status'] === 'completed')
                                                        - Request completed
                                                    @elseif($maintenance['status'] === 'in_progress')
                                                        - Work in progress
                                                    @else
                                                        - Awaiting assignment
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            @if ($maintenance['status'] !== 'completed')
                                <li>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-600">
                                                    @if ($maintenance['status'] === 'pending')
                                                        Next: Staff assignment and scheduling
                                                    @elseif($maintenance['status'] === 'in_progress')
                                                        Next: Work completion and verification
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-3xl shadow-sm p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-base font-semibold text-blue-900 mb-2">
                            Need to Update Your Request?
                        </h3>
                        <p class="text-sm text-blue-800 leading-relaxed">
                            If you need to provide additional information or have questions about this request, please
                            contact the property management office at <strong>+63 993 551 0319</strong> and reference
                            request #{{ $maintenance['id'] }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
