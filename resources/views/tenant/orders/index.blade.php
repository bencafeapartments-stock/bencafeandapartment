@extends('layouts.app')

@section('title', 'My Orders')
@section('page-title', 'Cafe Orders')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">My Orders</h1>
                        <p class="text-lg text-gray-600">View your cafe order history and track current orders</p>
                    </div>
                    <div>
                        <a href="{{ route('tenant.menu') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-lg shadow-blue-500/25 transition-all duration-200">
                            <i class="fas fa-utensils mr-2"></i>Order from Menu
                        </a>
                    </div>
                </div>
            </div>

            @if (count($orders) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i class="fas fa-shopping-bag text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Total Orders</h3>
                        <p class="text-3xl font-semibold text-gray-900">{{ count($orders) }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i class="fas fa-peso-sign text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Total Spent</h3>
                        <p class="text-3xl font-semibold text-gray-900">
                            ₱{{ number_format(collect($orders)->sum('total'), 2) }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Recent Orders</h3>
                        <p class="text-3xl font-semibold text-gray-900">
                            {{ count(array_filter($orders, function ($order) {return strtotime($order['date']) >= strtotime('-30 days');})) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Last 30 days</p>
                    </div>
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list mr-3 text-gray-400"></i>Order History
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Track and manage all your cafe orders</p>
                </div>

                <div class="p-6">
                    @if (count($orders) > 0)
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                <div
                                    class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 hover:shadow-lg transition-all duration-300 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between gap-4 mb-4">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $statusConfig = [
                                                            'pending' => [
                                                                'icon' => 'fa-clock',
                                                                'gradient' => 'from-yellow-400 to-orange-500',
                                                                'shadow' => 'shadow-yellow-500/30',
                                                            ],
                                                            'preparing' => [
                                                                'icon' => 'fa-fire',
                                                                'gradient' => 'from-blue-500 to-cyan-600',
                                                                'shadow' => 'shadow-blue-500/30',
                                                            ],
                                                            'ready' => [
                                                                'icon' => 'fa-check-circle',
                                                                'gradient' => 'from-green-500 to-emerald-600',
                                                                'shadow' => 'shadow-green-500/30',
                                                            ],
                                                            'delivered' => [
                                                                'icon' => 'fa-check-double',
                                                                'gradient' => 'from-gray-500 to-gray-600',
                                                                'shadow' => 'shadow-gray-500/30',
                                                            ],
                                                            'cancelled' => [
                                                                'icon' => 'fa-times-circle',
                                                                'gradient' => 'from-red-500 to-red-600',
                                                                'shadow' => 'shadow-red-500/30',
                                                            ],
                                                        ];
                                                        $config =
                                                            $statusConfig[$order['status']] ?? $statusConfig['pending'];
                                                    @endphp
                                                    <div
                                                        class="w-12 h-12 bg-gradient-to-br {{ $config['gradient'] }} rounded-xl flex items-center justify-center shadow-lg {{ $config['shadow'] }}">
                                                        <i class="fas {{ $config['icon'] }} text-white text-xl"></i>
                                                    </div>
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                        <div>
                                                            <h3 class="text-lg font-bold text-gray-900">Order
                                                                #{{ $order['id'] }}</h3>
                                                            <p class="text-sm text-gray-600 mt-0.5">
                                                                <i
                                                                    class="fas fa-calendar-alt mr-1 text-gray-400"></i>{{ $order['date'] }}
                                                            </p>
                                                        </div>
                                                        @php
                                                            $statusBadges = [
                                                                'pending' =>
                                                                    'bg-yellow-500/90 text-white ring-1 ring-yellow-400/50',
                                                                'preparing' =>
                                                                    'bg-blue-500/90 text-white ring-1 ring-blue-400/50',
                                                                'ready' =>
                                                                    'bg-green-500/90 text-white ring-1 ring-green-400/50',
                                                                'delivered' =>
                                                                    'bg-gray-500/90 text-white ring-1 ring-gray-400/50',
                                                                'cancelled' =>
                                                                    'bg-red-500/90 text-white ring-1 ring-red-400/50',
                                                                'paid' =>
                                                                    'bg-green-500/90 text-white ring-1 ring-green-400/50',
                                                                'unpaid' =>
                                                                    'bg-red-500/90 text-white ring-1 ring-red-400/50',
                                                            ];
                                                            $badgeClass =
                                                                $statusBadges[$order['status']] ??
                                                                'bg-gray-500/90 text-white ring-1 ring-gray-400/50';
                                                        @endphp
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-xl whitespace-nowrap {{ $badgeClass }}">
                                                            {{ ucfirst($order['status']) }}
                                                        </span>
                                                    </div>

                                                    <div class="bg-gray-50 rounded-xl p-3 mb-3">
                                                        <p class="text-sm text-gray-700">
                                                            <i
                                                                class="fas fa-coffee mr-2 text-amber-600"></i>{{ $order['items'] }}
                                                        </p>
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        <div class="flex items-center text-lg font-bold text-green-600">
                                                            <i
                                                                class="fas fa-peso-sign mr-1"></i>{{ number_format($order['total'], 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-shrink-0">
                                                <div class="flex flex-col gap-2">
                                                    <button onclick="openOrderModal({{ $order['id'] }})"
                                                        class="inline-flex items-center px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
                                                        <i class="fas fa-eye mr-2"></i>View Details
                                                    </button>
                                                    @if (in_array($order['status'], ['delivered', 'cancelled']))
                                                        <form action="{{ route('tenant.orders.reorder', $order['id']) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200">
                                                                <i class="fas fa-redo mr-2"></i>Reorder
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
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
                                <i class="fas fa-shopping-bag text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders yet</h3>
                            <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                                You haven't placed any cafe orders yet. Browse our menu to get started!
                            </p>
                            <a href="{{ route('tenant.menu') }}"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <i class="fas fa-utensils mr-2"></i>Browse Menu
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
                        <h3 class="text-base font-semibold text-blue-900 mb-2">Order Information</h3>
                        <p class="text-sm text-blue-800 leading-relaxed">
                            Orders are typically prepared within 15-30 minutes. You'll receive a notification when your
                            order is ready for pickup.
                            For any issues with your order, please contact the cafe staff directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div
                    class="sticky top-0 bg-white/95 backdrop-blur-xl border-b border-gray-200/50 px-6 py-5 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-receipt text-white"></i>
                                </div>
                                <span id="modalTitle">Order Details</span>
                            </h2>
                            <p class="text-sm text-gray-500 mt-1 ml-13" id="modalSubtitle"></p>
                        </div>
                        <button onclick="closeOrderModal()"
                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-times text-gray-500"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div id="modalContent" class="p-6">
                    <div class="text-center py-12">
                        <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-3"></i>
                        <p class="text-gray-500 font-medium">Loading order details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ordersData = @json($orders);

        function openOrderModal(orderId) {
            const order = ordersData.find(o => o.id === orderId);
            if (!order) return;

            const modal = document.getElementById('orderModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');
            const modalContent = document.getElementById('modalContent');

            modalTitle.textContent = `Order #${order.id}`;
            modalSubtitle.textContent = `Placed on ${order.date}`;

            modalContent.innerHTML = `
                <div class="space-y-6">
                    <!-- Status Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 backdrop-blur-sm rounded-2xl p-6 border border-blue-200/50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-info-circle text-white text-sm"></i>
                            </div>
                            Order Status
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700 font-medium">Current Status:</span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold backdrop-blur-xl">
                                ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                            </span>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 backdrop-blur-sm rounded-2xl p-6 border border-purple-200/50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-coffee text-white text-sm"></i>
                            </div>
                            Order Items
                        </h3>
                        <div class="bg-white/90 rounded-xl p-4 border border-purple-200/30">
                            <p class="text-sm text-gray-700">${order.items}</p>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100/50 backdrop-blur-sm rounded-2xl p-6 border border-green-200/50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            Payment Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-green-200/30">
                                <span class="text-sm text-gray-700 font-medium">Total Amount:</span>
                                <span class="text-lg font-bold text-green-600">₱${parseFloat(order.total).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeOrderModal() {
            const modal = document.getElementById('orderModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('orderModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeOrderModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeOrderModal();
            }
        });
    </script>
@endsection
