@extends('layouts.app')

@section('title', 'Maintenance Requests')
@section('page-title', 'Maintenance Requests')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Maintenance Requests
                        </h1>
                        <p class="text-lg text-gray-600">
                            Track and manage your apartment maintenance requests
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('tenant.maintenance.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-lg shadow-blue-500/25 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            New Request
                        </a>
                    </div>
                </div>
            </div>

            @if (count($maintenanceRequests) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i class="fas fa-clipboard-list text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Total Requests</h3>
                        <p class="text-3xl font-semibold text-gray-900">{{ count($maintenanceRequests) }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">In Progress</h3>
                        <p class="text-3xl font-semibold text-gray-900">
                            {{ collect($maintenanceRequests)->where('status', 'in_progress')->count() }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Completed</h3>
                        <p class="text-3xl font-semibold text-gray-900">
                            {{ collect($maintenanceRequests)->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">
                                <i class="fas fa-tools mr-3 text-gray-400"></i>Your Requests
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">All maintenance requests and their current status</p>
                        </div>
                    </div>

                    @if (count($maintenanceRequests) > 0)
                        <div class="space-y-4">
                            @foreach ($maintenanceRequests as $request)
                                <div
                                    class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 hover:shadow-lg transition-all duration-300 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="flex-shrink-0">
                                                    @if ($request['status'] === 'completed')
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                                                            <i class="fas fa-check text-white text-xl"></i>
                                                        </div>
                                                    @elseif($request['status'] === 'in_progress')
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                                            <i class="fas fa-clock text-white text-xl"></i>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/30">
                                                            <i class="fas fa-exclamation-circle text-white text-xl"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-3">
                                                        <h3 class="text-lg font-bold text-gray-900">{{ $request['issue'] }}
                                                        </h3>
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-xl whitespace-nowrap
                                                            @if ($request['priority'] === 'high') bg-red-500/90 text-white ring-1 ring-red-400/50
                                                            @elseif($request['priority'] === 'medium') bg-orange-500/90 text-white ring-1 ring-orange-400/50
                                                            @else bg-green-500/90 text-white ring-1 ring-green-400/50 @endif">
                                                            {{ ucfirst($request['priority']) }} Priority
                                                        </span>
                                                    </div>

                                                    <div
                                                        class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                            <span>Submitted
                                                                {{ \Carbon\Carbon::parse($request['submitted'])->format('M d, Y') }}</span>
                                                        </div>

                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                                            @if ($request['status'] === 'completed') bg-green-100 text-green-700
                                                            @elseif($request['status'] === 'in_progress') bg-blue-100 text-blue-700
                                                            @else bg-gray-100 text-gray-700 @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $request['status'])) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-shrink-0">
                                                <button onclick="openModal({{ $request['id'] }})"
                                                    class="inline-flex items-center px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
                                                    <i class="fas fa-eye mr-2"></i>View Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tools text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No maintenance requests</h3>
                            <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                                You haven't submitted any maintenance requests yet.
                            </p>
                            <a href="{{ route('tenant.maintenance.create') }}"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <i class="fas fa-plus mr-2"></i>Submit First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/50 rounded-2xl shadow-sm p-6 mt-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-base font-semibold text-blue-900 mb-2">
                            Maintenance Request Guidelines
                        </h3>
                        <p class="text-sm text-blue-800 leading-relaxed">
                            For emergency maintenance issues (water leaks, electrical problems, security concerns), please
                            contact the property management office immediately. Non-emergency requests are typically
                            addressed within 24-48 hours during business days.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="maintenanceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div
                    class="sticky top-0 bg-white/95 backdrop-blur-xl border-b border-gray-200/50 px-6 py-5 sm:px-8 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-tools text-white"></i>
                                </div>
                                <span id="modalTitle">Request Details</span>
                            </h2>
                            <p class="text-sm text-gray-500 mt-1 ml-13" id="modalSubtitle"></p>
                        </div>
                        <button onclick="closeModal()"
                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-times text-gray-500"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div id="modalContent" class="p-6 sm:p-8">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store maintenance data in JavaScript
        const maintenanceData = @json($maintenanceRequests);

        function openModal(id) {
            const request = maintenanceData.find(r => r.id === id);
            if (!request) return;

            const modal = document.getElementById('maintenanceModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');
            const modalContent = document.getElementById('modalContent');

            // Update modal header
            modalTitle.textContent = `Request #${request.id}`;
            modalSubtitle.textContent = `Submitted on ${formatDate(request.submitted)}`;

            // Generate modal content
            modalContent.innerHTML = generateModalContent(request);

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('maintenanceModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('maintenanceModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function generateModalContent(request) {
            return `
                <!-- Status Badges -->
                <div class="flex items-center gap-2 mb-6">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold backdrop-blur-xl
                        ${request.status === 'completed' ? 'bg-green-500/90 text-white ring-1 ring-green-400/50' :
                          request.status === 'in_progress' ? 'bg-blue-500/90 text-white ring-1 ring-blue-400/50' :
                          'bg-yellow-500/90 text-white ring-1 ring-yellow-400/50'}">
                        <i class="fas ${request.status === 'completed' ? 'fa-check-circle' : request.status === 'in_progress' ? 'fa-clock' : 'fa-exclamation-circle'} mr-1"></i>
                        ${request.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                    </span>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold backdrop-blur-xl
                        ${request.priority === 'high' ? 'bg-red-500/90 text-white ring-1 ring-red-400/50' :
                          request.priority === 'medium' ? 'bg-orange-500/90 text-white ring-1 ring-orange-400/50' :
                          'bg-green-500/90 text-white ring-1 ring-green-400/50'}">
                        <i class="fas fa-flag mr-1"></i>${request.priority.charAt(0).toUpperCase() + request.priority.slice(1)} Priority
                    </span>
                </div>

                <!-- Request Details -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-blue-200/50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        Request Details
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-exclamation-circle mr-1 text-blue-500"></i>Issue Summary
                                </label>
                                <p class="text-sm text-gray-700 bg-white border border-blue-200/30 rounded-xl px-4 py-3">
                                    ${request.issue}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-1 text-purple-500"></i>Category
                                </label>
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-white text-gray-800 border border-blue-200/30">
                                    ${request.category ? request.category.charAt(0).toUpperCase() + request.category.slice(1) : 'N/A'}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-1 text-gray-500"></i>Description
                            </label>
                            <div class="bg-white border border-blue-200/30 rounded-xl p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">${request.description || 'No description provided'}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Notes -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-purple-200/50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-comment-alt text-white text-sm"></i>
                        </div>
                        Staff Updates
                    </h3>
                    ${request.staff_notes ? `
                                <div class="bg-white/90 backdrop-blur-sm border border-purple-200/50 rounded-xl p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                                                <i class="fas fa-user-tie text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-semibold text-purple-900 mb-1">Staff Notes</h4>
                                            <p class="text-sm text-gray-700 whitespace-pre-wrap">${request.staff_notes}</p>
                                        </div>
                                    </div>
                                </div>
                            ` : `
                                <div class="text-center py-8">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-comment-slash text-purple-400 text-lg"></i>
                                    </div>
                                    <p class="text-sm text-gray-600 font-medium">No staff updates yet</p>
                                    <p class="text-xs text-gray-500 mt-1">Staff will add updates as they work on your request</p>
                                </div>
                            `}
                </div>

                <!-- Timeline -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-gray-200/50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-history text-white text-sm"></i>
                        </div>
                        Request Timeline
                    </h3>
                    <div class="space-y-4">
                        ${generateTimeline(request)}
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 backdrop-blur-sm border border-blue-200/50 rounded-2xl p-5">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i class="fas fa-phone-alt text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-1">Need to Update Your Request?</h4>
                            <p class="text-sm text-blue-800 leading-relaxed">
                                Contact property management at <strong>+63 993 551 0319</strong> and reference request #${request.id}.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }

        function generateTimeline(request) {
            let timeline = `
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-4 ring-white shadow-lg shadow-green-500/30">
                            <i class="fas fa-check text-white text-sm"></i>
                        </span>
                        ${request.status !== 'completed' ? '<div class="w-0.5 h-12 bg-gray-200"></div>' : ''}
                    </div>
                    <div class="pt-1 pb-4">
                        <p class="text-sm text-gray-700 font-medium">Request submitted</p>
                        <p class="text-xs text-gray-500 mt-0.5">${formatDate(request.submitted)}</p>
                    </div>
                </div>
            `;

            const statusConfig = {
                'completed': {
                    bg: 'bg-green-500',
                    shadow: 'shadow-green-500/30',
                    icon: 'fa-check-circle'
                },
                'in_progress': {
                    bg: 'bg-blue-500',
                    shadow: 'shadow-blue-500/30',
                    icon: 'fa-clock'
                },
                'pending': {
                    bg: 'bg-yellow-500',
                    shadow: 'shadow-yellow-500/30',
                    icon: 'fa-exclamation-circle'
                }
            };

            const currentStatus = statusConfig[request.status] || statusConfig.pending;

            timeline += `
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <span class="h-8 w-8 rounded-full ${currentStatus.bg} flex items-center justify-center ring-4 ring-white shadow-lg ${currentStatus.shadow}">
                            <i class="fas ${currentStatus.icon} text-white text-sm"></i>
                        </span>
                        ${request.status !== 'completed' ? '<div class="w-0.5 h-12 bg-gray-200"></div>' : ''}
                    </div>
                    <div class="pt-1">
                        <p class="text-sm text-gray-700 font-medium">Status: ${request.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            ${request.status === 'completed' ? 'Request has been completed' :
                              request.status === 'in_progress' ? 'Currently being worked on' :
                              'Waiting for assignment'}
                        </p>
                    </div>
                </div>
            `;

            if (request.status !== 'completed') {
                timeline += `
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <span class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center ring-4 ring-white">
                                <i class="fas fa-hourglass-half text-gray-500 text-sm"></i>
                            </span>
                        </div>
                        <div class="pt-1">
                            <p class="text-sm text-gray-600">
                                ${request.status === 'pending' ? 'Next: Staff assignment and scheduling' : 'Next: Work completion and verification'}
                            </p>
                        </div>
                    </div>
                `;
            }

            return timeline;
        }
    </script>
@endsection
