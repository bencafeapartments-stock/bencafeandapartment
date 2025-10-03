@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="mb-8">
                <a href="{{ route('tenant.orders.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200 mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Orders
                </a>
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Order #{{ $order['id'] }}
                        </h1>
                        <p class="text-lg text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i>Placed on {{ $order['order_date'] }}
                        </p>
                    </div>
                    @php
                        $statusBadges = [
                            'pending' => 'bg-yellow-500/90 text-white ring-1 ring-yellow-400/50',
                            'preparing' => 'bg-blue-500/90 text-white ring-1 ring-blue-400/50',
                            'ready' => 'bg-green-500/90 text-white ring-1 ring-green-400/50',
                            'delivered' => 'bg-gray-500/90 text-white ring-1 ring-gray-400/50',
                            'cancelled' => 'bg-red-500/90 text-white ring-1 ring-red-400/50',
                        ];
                        $badgeClass =
                            $statusBadges[$order['status']] ?? 'bg-gray-500/90 text-white ring-1 ring-gray-400/50';
                    @endphp
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-xl {{ $badgeClass }}">
                        <i class="fas fa-circle mr-2 text-xs"></i>{{ ucfirst($order['status']) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <i class="fas fa-truck mr-3 text-gray-400"></i>Order Status
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                @php
                                    $statusConfig = [
                                        'pending' => ['icon' => 'fa-clock', 'text' => 'Your order is being reviewed'],
                                        'preparing' => ['icon' => 'fa-fire', 'text' => 'Your order is being prepared'],
                                        'ready' => [
                                            'icon' => 'fa-check-circle',
                                            'text' => 'Your order is ready for pickup',
                                        ],
                                        'delivered' => ['icon' => 'fa-check-double', 'text' => 'Order completed'],
                                        'cancelled' => ['icon' => 'fa-times-circle', 'text' => 'Order cancelled'],
                                    ];
                                    $config = $statusConfig[$order['status']] ?? $statusConfig['pending'];
                                @endphp
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 mr-4">
                                    <i class="fas {{ $config['icon'] }} text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Current Status</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $config['text'] }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-4 ring-white shadow-lg shadow-green-500/30">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </span>
                                        @if (in_array($order['status'], ['preparing', 'ready', 'delivered']))
                                            <div class="w-0.5 h-12 bg-green-500"></div>
                                        @else
                                            <div class="w-0.5 h-12 bg-gray-200"></div>
                                        @endif
                                    </div>
                                    <div class="pt-1 pb-4">
                                        <p class="text-sm text-gray-700 font-medium">Order Placed</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $order['order_date'] }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white shadow-lg
                                            {{ in_array($order['status'], ['preparing', 'ready', 'delivered']) ? 'bg-green-500 shadow-green-500/30' : 'bg-gray-300' }}">
                                            <i
                                                class="fas {{ in_array($order['status'], ['preparing', 'ready', 'delivered']) ? 'fa-check' : 'fa-circle' }} text-white text-sm"></i>
                                        </span>
                                        @if (in_array($order['status'], ['ready', 'delivered']))
                                            <div class="w-0.5 h-12 bg-green-500"></div>
                                        @else
                                            <div class="w-0.5 h-12 bg-gray-200"></div>
                                        @endif
                                    </div>
                                    <div class="pt-1 pb-4">
                                        <p class="text-sm text-gray-700 font-medium">Preparing</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ in_array($order['status'], ['preparing', 'ready', 'delivered']) ? 'In progress' : 'Pending' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white shadow-lg
                                            {{ in_array($order['status'], ['ready', 'delivered']) ? 'bg-green-500 shadow-green-500/30' : 'bg-gray-300' }}">
                                            <i
                                                class="fas {{ in_array($order['status'], ['ready', 'delivered']) ? 'fa-check' : 'fa-circle' }} text-white text-sm"></i>
                                        </span>
                                        @if ($order['status'] === 'delivered')
                                            <div class="w-0.5 h-12 bg-green-500"></div>
                                        @else
                                            <div class="w-0.5 h-12 bg-gray-200"></div>
                                        @endif
                                    </div>
                                    <div class="pt-1 pb-4">
                                        <p class="text-sm text-gray-700 font-medium">Ready for Pickup</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ in_array($order['status'], ['ready', 'delivered']) ? 'Ready' : 'Waiting' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white shadow-lg
                                            {{ $order['status'] === 'delivered' ? 'bg-green-500 shadow-green-500/30' : 'bg-gray-300' }}">
                                            <i
                                                class="fas {{ $order['status'] === 'delivered' ? 'fa-check' : 'fa-circle' }} text-white text-sm"></i>
                                        </span>
                                    </div>
                                    <div class="pt-1">
                                        <p class="text-sm text-gray-700 font-medium">Completed</p>
                                        @if ($order['status'] === 'delivered' && isset($order['delivered_date']))
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $order['delivered_date'] }}</p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-0.5">Pending</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <i class="fas fa-coffee mr-3 text-gray-400"></i>Order Items
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($order['items'] as $item)
                                    <div
                                        class="bg-white/90 backdrop-blur-sm rounded-xl p-4 border border-gray-200/50 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 mb-1">{{ $item['name'] }}</h3>
                                                <p class="text-sm text-gray-600">
                                                    <i
                                                        class="fas fa-peso-sign mr-1"></i>{{ number_format($item['price'], 2) }}
                                                    each
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-600 mb-1">
                                                    <i class="fas fa-times mr-1"></i>{{ $item['quantity'] }}
                                                </p>
                                                <p class="text-lg font-bold text-gray-900">
                                                    ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


                <div class="lg:col-span-1 space-y-6">

                    <div
                        class="bg-gradient-to-br from-green-50 to-emerald-50 backdrop-blur-sm rounded-2xl shadow-sm border border-green-200/50 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-receipt text-white text-sm"></i>
                            </div>
                            Order Summary
                        </h3>
                        <div class="bg-white/90 rounded-xl p-4 border border-green-200/30">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Total Amount:</span>
                                <span
                                    class="text-2xl font-bold text-green-600">₱{{ number_format($order['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>


                    @if ($order['special_instructions'])
                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100/50 backdrop-blur-sm rounded-2xl shadow-sm border border-purple-200/50 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-comment-dots text-white text-sm"></i>
                                </div>
                                Special Instructions
                            </h3>
                            <div class="bg-white/90 rounded-xl p-4 border border-purple-200/30">
                                <p class="text-sm text-gray-700">{{ $order['special_instructions'] }}</p>
                            </div>
                        </div>
                    @endif

                    <div
                        class="bg-gradient-to-br from-blue-50 to-indigo-50 backdrop-blur-sm border border-blue-200/50 rounded-2xl shadow-sm p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-semibold text-blue-900 mb-2">Need Help?</h3>
                                <p class="text-sm text-blue-800 leading-relaxed mb-3">
                                    For questions about your order, please contact our staff.
                                </p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-blue-700">
                                        <i class="fas fa-phone mr-2"></i>
                                        Front Desk: (123) 456-7890
                                    </div>
                                    <div class="flex items-center text-sm text-blue-700">
                                        <i class="fas fa-clock mr-2"></i>
                                        Available 24/7
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="mt-8 flex flex-wrap justify-center gap-4">
                @if ($order['status'] === 'pending')
                    <form action="{{ route('tenant.orders.cancel', $order['id']) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel this order?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-xl hover:from-red-600 hover:to-red-700 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 shadow-lg shadow-red-500/25">
                            <i class="fas fa-times-circle mr-2"></i>Cancel Order
                        </button>
                    </form>
                @endif

                @if (in_array($order['status'], ['delivered', 'cancelled']))
                    <form action="{{ route('tenant.orders.reorder', $order['id']) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-lg shadow-blue-500/25">
                            <i class="fas fa-redo mr-2"></i>Reorder Items
                        </button>
                    </form>
                @endif

                <a href="{{ route('tenant.menu') }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl hover:from-green-600 hover:to-emerald-700 focus:ring-2 focus:ring-green-500/20 transition-all duration-200 shadow-lg shadow-green-500/25">
                    <i class="fas fa-plus-circle mr-2"></i>Order More Items
                </a>
            </div>
        </div>
    </div>
@endsection
