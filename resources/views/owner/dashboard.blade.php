@extends('layouts.app')

@section('title', 'Owner Dashboard')
@section('page-title', 'Owner Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div
                class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-3xl shadow-2xl p-8 text-white mb-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-3xl md:text-4xl font-bold mb-2 tracking-tight">Welcome back,
                                {{ auth()->user()->name }}</h1>
                            <p class="text-blue-100 text-lg font-medium">Here's an overview of your cafe and apartment
                                business</p>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Staff</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_staff'] }}</p>
                    </div>
                    <a href="{{ route('owner.staff.index') }}"
                        class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-colors duration-200">
                        View all staff
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25">
                            <i class="fas fa-user-friends text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Tenants</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tenants'] }}</p>
                    </div>
                    <a href="{{ route('owner.tenants.index') }}"
                        class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-semibold transition-colors duration-200">
                        View all tenants
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <i class="fas fa-building text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Apartments</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_apartments'] ?: '24' }}</p>
                    </div>
                    <a href="{{ route('owner.apartments.index') }}"
                        class="inline-flex items-center text-purple-600 hover:text-purple-700 text-sm font-semibold transition-colors duration-200">
                        Manage apartments
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/25">
                            <i class="fas fa-peso-sign text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-amber-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Monthly Revenue</h3>
                        <p class="text-3xl font-bold text-gray-900">
                            ₱{{ number_format($stats['monthly_revenue'] ?: 125000, 0) }}</p>
                    </div>
                    <a href="{{ route('owner.billing.index') }}"
                        class="inline-flex items-center text-amber-600 hover:text-amber-700 text-sm font-semibold transition-colors duration-200">
                        View billing
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">

                <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-gray-600 text-sm"></i>
                            </div>
                            Recent Activities
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($recentActivities ?? [] as $activity)
                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br
                                        @if ($activity['type'] === 'staff') from-blue-500 to-blue-600 @endif
                                        @if ($activity['type'] === 'payment') from-green-500 to-emerald-600 @endif
                                        @if ($activity['type'] === 'maintenance') from-amber-500 to-orange-500 @endif
                                        rounded-xl flex items-center justify-center shadow-sm">
                                        <i
                                            class="fas fa-{{ $activity['type'] === 'staff' ? 'user-plus' : ($activity['type'] === 'payment' ? 'money-bill' : 'tools') }} text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">{{ $activity['message'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                    </div>
                                </div>
                            @endforeach

                            @if (empty($recentActivities))
                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-user-plus text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">New staff member added</p>
                                        <p class="text-xs text-gray-500">2 hours ago</p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-money-bill text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">Rent payment received</p>
                                        <p class="text-xs text-gray-500">5 hours ago</p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50/50 transition-colors duration-200">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                                        <i class="fas fa-tools text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 mb-1">Maintenance request completed</p>
                                        <p class="text-xs text-gray-500">1 day ago</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

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
                            <a href="{{ route('owner.staff.create') }}"
                                class="group relative p-6 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-2xl border border-blue-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-blue-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-user-plus text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-blue-700">Add Staff</p>
                                </div>
                            </a>

                            <a href="{{ route('owner.tenants.create') }}"
                                class="group relative p-6 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-2xl border border-green-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-green-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-user-friends text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-green-700">Add Tenant</p>
                                </div>
                            </a>

                            <a href="{{ route('owner.apartments.create') }}"
                                class="group relative p-6 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-2xl border border-purple-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-purple-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-building text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-purple-700">Add Apartment</p>
                                </div>
                            </a>

                            <a href="{{ route('owner.reports') }}"
                                class="group relative p-6 bg-gradient-to-br from-amber-50 to-amber-100 hover:from-amber-100 hover:to-amber-200 rounded-2xl border border-amber-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg shadow-amber-500/25 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-chart-line text-white text-lg"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-amber-700">View Reports</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-area text-gray-600 text-sm"></i>
                        </div>
                        Monthly Revenue Overview
                    </h3>
                </div>
                <div class="p-8">
                    <div class="h-80">
                        <canvas id="revenueChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('revenueChart').getContext('2d');

                const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient1.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                gradient1.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

                const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                gradient2.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

                const revenueChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Rent Revenue',
                            data: [95000, 98000, 102000, 105000, 108000, 112000],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: gradient1,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 3
                        }, {
                            label: 'Cafe Revenue',
                            data: [28000, 32000, 35000, 30000, 38000, 42000],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: gradient2,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgb(16, 185, 129)',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: 'rgb(16, 185, 129)',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 3
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
                                padding: 12,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ₱' + context.parsed.y
                                            .toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
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
                            },
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
                                    },
                                    callback: function(value) {
                                        return '₱' + (value / 1000).toFixed(0) + 'k';
                                    }
                                }
                            }
                        }
                    }
                });
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
