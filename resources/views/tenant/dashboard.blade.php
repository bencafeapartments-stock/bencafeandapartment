@extends('layouts.app')

@section('title', 'Tenant Dashboard')
@section('page-title', 'My Dashboard')

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
                                {{ $data['user']->name }}</h1>
                            <p class="text-purple-100 text-lg font-medium">Your personal apartment and cafe services
                                dashboard</p>
                        </div>
                        <div class="text-left md:text-right">
                            <div class="text-2xl font-semibold mb-1">{{ now()->format('F j, Y') }}</div>
                            <div class="text-purple-200 text-base">{{ now()->format('l') }}</div>
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
                            <i class="fas fa-home text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Rent Status</h3>
                        <p class="text-3xl font-bold text-green-600">{{ ucfirst($data['rent_status']) }}</p>
                    </div>
                    <a href="{{ route('tenant.rent.index') }}"
                        class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-colors duration-200">
                        View details
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/25">
                            <i class="fas fa-receipt text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Pending Bills</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $data['pending_bills'] }}</p>
                    </div>
                    <a href="{{ route('tenant.billing.index') }}"
                        class="inline-flex items-center text-yellow-600 hover:text-yellow-700 text-sm font-semibold transition-colors duration-200">
                        View bills
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

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
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Active Requests</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $data['maintenance_requests'] }}</p>
                    </div>
                    <a href="{{ route('tenant.maintenance.index') }}"
                        class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-semibold transition-colors duration-200">
                        Manage requests
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25">
                            <i class="fas fa-coffee text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">This Month</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $data['recent_orders'] }}</p>
                    </div>
                    <a href="{{ route('tenant.orders.index') }}"
                        class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-semibold transition-colors duration-200">
                        Order now
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">

                <div
                    class="xl:col-span-2 bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-building text-gray-600 text-sm"></i>
                            </div>
                            My Apartment
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200/50">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                                    Basic Information
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Apartment:</span>
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ $data['apartment_number'] }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Monthly Rent:</span>
                                        <span
                                            class="text-sm font-semibold text-gray-900">₱{{ number_format($data['monthly_rent'], 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Status:</span>
                                        <span
                                            class="text-sm font-semibold text-green-600">{{ ucfirst($data['rent_status']) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Next Due:</span>
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ $data['next_due_date'] ? $data['next_due_date']->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-bolt text-gray-400 mr-2"></i>
                                    Quick Actions
                                </h4>
                                <div class="space-y-3">
                                    <a href="{{ route('tenant.rent.payment') }}"
                                        class="group relative p-4 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl border border-blue-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center justify-center">
                                        <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                                        <span class="font-semibold text-blue-700">Pay Rent</span>
                                    </a>
                                    <a href="{{ route('tenant.maintenance.create') }}"
                                        class="group relative p-4 bg-gradient-to-br from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 rounded-xl border border-red-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center justify-center">
                                        <i class="fas fa-tools text-red-600 mr-2"></i>
                                        <span class="font-semibold text-red-700">Request Maintenance</span>
                                    </a>
                                    <a href="{{ route('tenant.menu') }}"
                                        class="group relative p-4 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl border border-green-200/50 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg flex items-center justify-center">
                                        <i class="fas fa-coffee text-green-600 mr-2"></i>
                                        <span class="font-semibold text-green-700">Order from Cafe</span>
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bell text-gray-600 text-sm"></i>
                                </div>
                                Notifications
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div
                                    class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-lg">
                                    <p class="text-sm font-semibold text-blue-800 mb-1">Rent Due Reminder</p>
                                    <p class="text-xs text-blue-600">Payment due in
                                        {{ $data['next_due_date']?->diffInDays(now()) ?? 'N/A' }} days</p>
                                </div>

                                <div
                                    class="p-4 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg">
                                    <p class="text-sm font-semibold text-green-800 mb-1">Maintenance Complete</p>
                                    <p class="text-xs text-green-600">AC repair has been completed</p>
                                </div>

                                <div
                                    class="p-4 bg-gradient-to-r from-amber-50 to-amber-100 border-l-4 border-amber-500 rounded-lg">
                                    <p class="text-sm font-semibold text-amber-800 mb-1">Cafe Special</p>
                                    <p class="text-xs text-amber-600">20% off on coffee today!</p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('paymentChart').getContext('2d');

                const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient1.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                gradient1.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

                const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                gradient2.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

                const paymentChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Rent Payments',
                            data: [15000, 15000, 15000, 15000, 15000, 15000],
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
                            label: 'Cafe Spending',
                            data: [1200, 1800, 2100, 1500, 2200, 2450],
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
