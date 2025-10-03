@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="space-y-6">

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Order #{{ str_pad($order['id'], 4, '0', STR_PAD_LEFT) }}
                    </h2>
                    <p class="text-sm text-gray-600">Placed on {{ $order['order_date'] }}</p>
                </div>
                <a href="{{ route('staff.orders.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Back to Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $order['tenant']['name'] }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $order['tenant']['email'] }}</p>
                        </div>
                        @if ($order['tenant']['contact_number'])
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order['tenant']['contact_number'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>


                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Qty</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($order['items'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $item['category'] }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₱{{ number_format($item['price'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱{{ number_format($item['total'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                @if ($order['special_instructions'])
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Special Instructions</h3>
                        <div class="bg-gray-50 rounded-md p-4">
                            <p class="text-sm text-gray-700">{{ $order['special_instructions'] }}</p>
                        </div>
                    </div>
                @endif

                @if ($order['staff_notes'])
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Staff Notes</h3>
                        <div class="bg-blue-50 rounded-md p-4">
                            <p class="text-sm text-blue-700">{{ $order['staff_notes'] }}</p>
                        </div>
                    </div>
                @endif
            </div>


            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Status</h3>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'preparing' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'ready' => 'bg-green-100 text-green-800 border-green-200',
                            'delivered' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'paid' => 'bg-green-100 text-green-800 border-green-200',
                            'unpaid' => 'bg-red-100 text-red-800 border-red-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        ];
                        $statusClass = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp

                    <div class="flex items-center justify-center">
                        <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full border {{ $statusClass }}">
                            {{ ucfirst($order['status']) }}
                        </span>
                    </div>

                    <!-- Timeline -->
                    <div class="mt-6 space-y-3">
                        @if ($order['ordered_at'])
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Order Placed</p>
                                    <p class="text-xs text-gray-500">{{ $order['ordered_at'] }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($order['prepared_at'])
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Preparation Started</p>
                                    <p class="text-xs text-gray-500">{{ $order['prepared_at'] }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($order['delivered_at'])
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Order Completed</p>
                                    <p class="text-xs text-gray-500">{{ $order['delivered_at'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Subtotal:</span>
                            <span class="text-sm font-medium">₱{{ number_format($order['subtotal'], 2) }}</span>
                        </div>

                        @if ($order['delivery_fee'] > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Delivery Fee:</span>
                                <span class="text-sm font-medium">₱{{ number_format($order['delivery_fee'], 2) }}</span>
                            </div>
                        @endif

                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total:</span>
                                <span>₱{{ number_format($order['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if ($order['status'] === 'unpaid')
                            <form action="{{ route('staff.orders.mark-paid', $order['id']) }}" method="POST"
                                class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                    onclick="return confirm('Mark this order as paid?')">
                                    <i class="fas fa-check mr-2"></i>
                                    Mark as Paid
                                </button>
                            </form>
                        @elseif($order['status'] === 'paid')
                            <form action="{{ route('staff.orders.mark-unpaid', $order['id']) }}" method="POST"
                                class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                    onclick="return confirm('Mark this order as unpaid? This will create a bill if one doesn\'t exist.')">
                                    <i class="fas fa-times mr-2"></i>
                                    Mark as Unpaid
                                </button>
                            </form>
                        @endif

                        @if (in_array($order['status'], ['pending', 'paid', 'unpaid']))
                            <form action="{{ route('staff.orders.start-preparing', $order['id']) }}" method="POST"
                                class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-play mr-2"></i>
                                    Start Preparing
                                </button>
                            </form>
                        @endif

                        @if ($order['status'] === 'preparing')
                            <form action="{{ route('staff.orders.mark-ready', $order['id']) }}" method="POST"
                                class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-check mr-2"></i>
                                    Mark as Ready
                                </button>
                            </form>
                        @endif

                        @if ($order['status'] === 'ready')
                            <form action="{{ route('staff.orders.complete', $order['id']) }}" method="POST"
                                class="w-full">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Complete Order
                                </button>
                            </form>
                        @endif

                        @if ($order['has_bill'])
                            <a href="{{ route('staff.billing.index') }}"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center justify-center">
                                <i class="fas fa-file-invoice mr-2"></i>
                                View Bill #{{ $order['bill_id'] }}
                            </a>
                        @endif
                    </div>
                </div>


                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-3">Contact Customer</h3>
                    <div class="space-y-2">
                        @if ($order['tenant']['contact_number'])
                            <a href="tel:{{ $order['tenant']['contact_number'] }}"
                                class="flex items-center text-blue-700 text-sm hover:text-blue-900">
                                <i class="fas fa-phone mr-2"></i>
                                {{ $order['tenant']['contact_number'] }}
                            </a>
                        @endif
                        <a href="mailto:{{ $order['tenant']['email'] }}"
                            class="flex items-center text-blue-700 text-sm hover:text-blue-900">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $order['tenant']['email'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
