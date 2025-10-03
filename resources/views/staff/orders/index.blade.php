@extends('layouts.app')

@section('title', 'Cafe Orders')
@section('page-title', 'Cafe Orders Management')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Cafe Orders
                        </h1>
                        <p class="text-lg text-gray-600">
                            Manage cafe orders and their payment status
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('staff.orders.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Order
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                            <i class="fas fa-exclamation-circle text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-red-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Unpaid Orders</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $orders->where('status', 'unpaid')->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Paid Orders</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $orders->where('status', 'paid')->count() }}</p>
                    </div>
                </div>

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
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Orders</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $orders->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <i class="fas fa-peso-sign text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Revenue</h3>
                        <p class="text-3xl font-bold text-gray-900">
                            ₱{{ number_format($orders->where('status', 'paid')->sum('total')) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 mb-6 overflow-hidden">
                <div class="border-b border-gray-200/50">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab active"
                            data-status="all">
                            All Orders
                            <span
                                class="bg-gray-100 text-gray-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $orders->count() }}</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab"
                            data-status="unpaid">
                            Unpaid
                            <span
                                class="bg-red-100 text-red-800 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $orders->where('status', 'unpaid')->count() }}</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab"
                            data-status="paid">
                            Paid
                            <span
                                class="bg-green-100 text-green-800 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $orders->where('status', 'paid')->count() }}</span>
                        </button>
                    </nav>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-list text-gray-600 text-sm"></i>
                        </div>
                        Orders Management
                    </h3>
                </div>

                @if ($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200/50">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Order #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Tenant</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Items</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Payment Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white/50 divide-y divide-gray-200/50">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-50/50 transition-colors duration-200 order-row"
                                        data-status="{{ $order['status'] }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            <a href="{{ route('staff.orders.show', $order['id']) }}"
                                                class="text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                                #{{ str_pad($order['id'], 4, '0', STR_PAD_LEFT) }}
                                            </a>
                                            @if ($order['has_bill'])
                                                <div class="text-xs text-blue-600 mt-1">
                                                    <i class="fas fa-file-invoice mr-1"></i>Bill #{{ $order['bill_id'] }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $order['tenant'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div class="max-w-xs truncate" title="{{ $order['items'] }}">
                                                {{ $order['items'] }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $order['formatted_amount'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($order['status'] === 'paid')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200/50">
                                                    <i class="fas fa-check-circle mr-1.5"></i>
                                                    Paid
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200/50">
                                                    <i class="fas fa-exclamation-circle mr-1.5"></i>
                                                    Unpaid
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                @if ($order['status'] === 'unpaid')
                                                    <form action="{{ route('staff.orders.mark-paid', $order['id']) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="inline-flex items-center text-green-600 hover:text-green-700 transition-colors duration-200"
                                                            onclick="return confirm('Mark this order as paid?')">
                                                            <i class="fas fa-check mr-1.5"></i>
                                                            Mark Paid
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('staff.orders.mark-unpaid', $order['id']) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="inline-flex items-center text-red-600 hover:text-red-700 transition-colors duration-200"
                                                            onclick="return confirm('Mark this order as unpaid? This will create a bill if one doesn\'t exist.')">
                                                            <i class="fas fa-times mr-1.5"></i>
                                                            Mark Unpaid
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($order['has_bill'])
                                                    <a href="{{ route('staff.billing.index') }}"
                                                        class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200">
                                                        <i class="fas fa-file-invoice mr-1.5"></i>
                                                        View Bill
                                                    </a>
                                                @endif

                                                <a href="{{ route('staff.orders.show', $order['id']) }}"
                                                    class="inline-flex items-center text-gray-600 hover:text-gray-700 transition-colors duration-200">
                                                    <i class="fas fa-eye mr-1.5"></i>
                                                    View Details
                                                </a>
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
                            <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                        <p class="text-gray-500 mb-4">Get started by creating a new cafe order.</p>
                        <a href="{{ route('staff.orders.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Create Order
                        </a>
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

    <script>
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => {
                    t.classList.remove('active', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });

                this.classList.add('active', 'border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');

                const status = this.dataset.status;
                document.querySelectorAll('.order-row').forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
