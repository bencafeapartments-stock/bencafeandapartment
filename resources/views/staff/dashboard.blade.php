@extends('layouts.app')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Welcome Header -->
            <div
                class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-3xl shadow-2xl p-8 text-white mb-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-3xl md:text-4xl font-bold mb-2 tracking-tight">Welcome back,
                                {{ auth()->user()->name }}</h1>
                            <p class="text-blue-100 text-lg font-medium">Manage daily operations and assist tenants</p>
                        </div>
                        <div class="text-left md:text-right">
                            <div class="text-2xl font-semibold mb-1">{{ now()->format('F j, Y') }}</div>
                            <div class="text-blue-200 text-base">{{ now()->format('l') }}</div>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Pending Maintenance -->
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                            <i class="fas fa-tools text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-red-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Pending Maintenance
                        </h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_maintenance'] }}</p>
                    </div>
                    <a href="{{ route('staff.maintenance.index') }}"
                        class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-semibold transition-colors duration-200">
                        View requests
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <!-- New Orders -->
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <i class="fas fa-shopping-cart text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">New Orders</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['new_orders'] }}</p>
                    </div>
                    <a href="{{ route('staff.orders.index') }}"
                        class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-colors duration-200">
                        Process orders
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <!-- Today's Revenue -->
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25">
                            <i class="fas fa-dollar-sign text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Today's Revenue</h3>
                        <p class="text-3xl font-bold text-gray-900">₱{{ number_format($stats['today_billing'], 2) }}</p>
                    </div>
                    <a href="{{ route('staff.billing.index') }}"
                        class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-semibold transition-colors duration-200">
                        View billing
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <!-- Active Tenants -->
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <i class="fas fa-user-friends text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Active Tenants</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_tenants'] }}</p>
                    </div>
                    <a href="{{ route('staff.tenants.index') }}"
                        class="inline-flex items-center text-purple-600 hover:text-purple-700 text-sm font-semibold transition-colors duration-200">
                        View tenants
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">

                <!-- Priority Tasks -->
                <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clipboard-list text-gray-600 text-sm"></i>
                            </div>
                            Today's Priority Tasks
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse ($todayTasks ?? [] as $task)
                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200
                                    @if ($task['priority'] === 'high') border-l-4 border-red-400
                                    @elseif($task['priority'] === 'medium') border-l-4 border-yellow-400
                                    @else border-l-4 border-blue-400 @endif">

                                    <div
                                        class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm
                                        @if ($task['priority'] === 'high') bg-gradient-to-br from-red-500 to-rose-600
                                        @elseif($task['priority'] === 'medium') bg-gradient-to-br from-amber-500 to-orange-500
                                        @else bg-gradient-to-br from-blue-500 to-blue-600 @endif">
                                        <i
                                            class="fas fa-{{ $task['priority'] === 'high' ? 'exclamation-triangle' : ($task['priority'] === 'medium' ? 'clock' : 'info-circle') }} text-white text-sm"></i>
                                    </div>

                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">{{ $task['task'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $task['time'] }}</p>
                                    </div>

                                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </button>
                                </div>
                            @empty
                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200 border-l-4 border-red-400">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">Urgent: Fix plumbing in Apt 205
                                        </p>
                                        <p class="text-xs text-gray-500">Reported 2 hours ago</p>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </button>
                                </div>

                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200 border-l-4 border-yellow-400">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-clock text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">Process 5 pending cafe orders</p>
                                        <p class="text-xs text-gray-500">Due in 30 minutes</p>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </button>
                                </div>

                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200 border-l-4 border-blue-400">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-info-circle text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">Send billing reminders</p>
                                        <p class="text-xs text-gray-500">3 tenants overdue</p>
                                    </div>
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                        <i class="fas fa-arrow-right text-sm"></i>
                                    </button>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-6 text-center">
                            <a href="#"
                                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                View all tasks <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-bolt text-gray-600 text-sm"></i>
                            </div>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('staff.maintenance.index') }}"
                                class="group relative p-6 bg-gradient-to-br from-red-50 to-rose-100 hover:from-red-100 hover:to-rose-200 rounded-2xl border border-red-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-red-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-plus text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-red-700">New Maintenance</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.orders.create') }}"
                                class="group relative p-6 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-2xl border border-blue-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-blue-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-shopping-cart text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-blue-700">Process Order</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.billing.create') }}"
                                class="group relative p-6 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-2xl border border-green-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-green-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-file-invoice text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-green-700">Generate Bill</p>
                                </div>
                            </a>

                            <a href="{{ route('staff.reports') }}"
                                class="group relative p-6 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-2xl border border-purple-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-purple-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-chart-bar text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-purple-700">View Reports</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Overview -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-gray-600 text-sm"></i>
                        </div>
                        This Week's Performance
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Performance Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div
                            class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100/50 rounded-2xl">
                            <div class="text-2xl font-bold text-gray-900">92%</div>
                            <div class="text-sm text-gray-600 mt-1">Tasks Completed</div>
                        </div>
                        <div
                            class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100/50 rounded-2xl">
                            <div class="text-2xl font-bold text-gray-900">156</div>
                            <div class="text-sm text-gray-600 mt-1">Orders Processed</div>
                        </div>
                        <div
                            class="text-center p-4 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100/50 rounded-2xl">
                            <div class="text-2xl font-bold text-gray-900">8</div>
                            <div class="text-sm text-gray-600 mt-1">Avg Response Time (min)</div>
                        </div>
                        <div
                            class="text-center p-4 bg-gradient-to-br from-purple-50 to-violet-50 border border-purple-100/50 rounded-2xl">
                            <div class="text-2xl font-bold text-gray-900">₱18,450</div>
                            <div class="text-sm text-gray-600 mt-1">Weekly Revenue</div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="h-64">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const ctx = document.getElementById('performanceChart').getContext('2d');

            const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
            gradient1.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
            gradient1.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

            const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
            gradient2.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            gradient2.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

            const performanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Tasks Completed',
                        data: [12, 15, 8, 20, 18, 10, 5],
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: gradient1,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Orders Processed',
                        data: [25, 30, 20, 35, 32, 28, 15],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: gradient2,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(107, 114, 128, 0.1)',
                                drawBorder: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .glassmorphism {
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }
            }

            .hover-lift {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hover-lift:hover {
                transform: translateY(-4px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
        </style>
    @endpush
@endsection
