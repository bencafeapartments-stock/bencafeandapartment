@extends('layouts.app')

@section('title', 'Billing Management')
@section('page-title', 'Billing Management')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Billing
                        </h1>
                        <p class="text-lg text-gray-600">
                            Manage bills, track payments, and generate invoices
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('owner.billing.export') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-200/50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </a>
                        <button onclick="openGenerateBillsModal()"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-500/50 shadow-lg shadow-green-500/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Generate Bills
                        </button>
                        <!-- <a href="{{ route('owner.billing.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Bill
                        </a> -->
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Total Revenue</h3>
                    <p class="text-3xl font-semibold text-gray-900">₱{{ number_format($stats['total_revenue']) }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Paid Bills</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $stats['paid_bills'] }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Pending</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $stats['pending_bills'] }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Overdue</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $stats['overdue_bills'] }}</p>
                </div>
            </div>


            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">

                <div class="p-6 border-b border-gray-200/60">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Bills Overview</h2>
                            <p class="text-sm text-gray-600 mt-1">Track all billing records and payment statuses</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('owner.billing.index') }}"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by tenant..."
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <div class="relative">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div class="relative">
                            <select name="billing_type" onchange="this.form.submit()"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                <option value="">All Types</option>
                                <option value="rent" {{ request('billing_type') == 'rent' ? 'selected' : '' }}>Rent
                                </option>
                                <option value="utilities" {{ request('billing_type') == 'utilities' ? 'selected' : '' }}>
                                    Utilities</option>
                                <option value="maintenance"
                                    {{ request('billing_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="cafe" {{ request('billing_type') == 'cafe' ? 'selected' : '' }}>Cafe
                                </option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <input type="month" name="month" value="{{ request('month') }}"
                            onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" id="selectAll"
                                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Bill Details</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tenant</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Due Date</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($bills as $bill)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="bill_ids[]" value="{{ $bill->id }}"
                                            class="bill-checkbox w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $bill->billing_type_label }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            @if ($bill->rent && $bill->rent->apartment)
                                                Apt {{ $bill->rent->apartment->apartment_number }} -
                                                {{ $bill->due_date->format('M Y') }}
                                            @endif
                                        </div>
                                        @if ($bill->description)
                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ Str::limit($bill->description, 30) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-lg shadow-blue-500/30">
                                                {{ substr($bill->tenant->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $bill->tenant->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $bill->tenant->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $bill->formatted_amount }}
                                        </div>
                                        @if ($bill->late_fee > 0)
                                            <div class="text-xs text-red-600 mt-0.5">+ {{ $bill->formatted_late_fee }}
                                                late</div>
                                            <div class="text-xs font-medium text-gray-900 mt-0.5">Total:
                                                {{ $bill->formatted_total_amount }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $bill->due_date->format('M d, Y') }}</div>
                                        @if ($bill->is_overdue)
                                            <div class="text-xs text-red-600 mt-0.5">{{ $bill->days_overdue }} days
                                                overdue</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                            @if ($bill->status == 'paid') bg-green-100 text-green-700
                                            @elseif($bill->status == 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($bill->status == 'overdue') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('owner.billing.show', $bill) }}"
                                                class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                                title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <!-- @if ($bill->status !== 'paid')
                                                <button onclick="markAsPaid({{ $bill->id }})"
                                                    class="w-8 h-8 flex items-center justify-center text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-150"
                                                    title="Mark as Paid">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            @endif -->
                                            <button onclick="downloadInvoice({{ $bill->id }})"
                                                class="w-8 h-8 flex items-center justify-center text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-150"
                                                title="Download">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-900 font-medium mb-1">No bills found</p>
                                        <p class="text-sm text-gray-500 mb-4">Get started by creating your first bill</p>
                                        <!-- <a href="{{ route('owner.billing.create') }}"
                                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors duration-200">
                                            Create Bill
                                        </a> -->
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="bulkActions" class="hidden p-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 font-medium">
                            <span id="selectedCount">0</span> bills selected
                        </span>
                        <div class="flex gap-2">
                            <button onclick="sendReminders()"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                Send Reminders
                            </button>
                            <button onclick="bulkExport()"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                Export Selected
                            </button>
                        </div>
                    </div>
                </div>


                @if ($bills->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bills->links() }}
                    </div>
                @endif
            </div>



        </div>
    </div>


    <div id="generateBillsModal"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md transform transition-all">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Generate Monthly Bills</h3>
                    <button onclick="closeGenerateBillsModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('owner.billing.generate-monthly') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-900 mb-2">Billing Month</label>
                    <input type="month" id="month" name="month" value="{{ now()->format('Y-m') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                </div>

                <div>
                    <label for="due_day" class="block text-sm font-medium text-gray-900 mb-2">Due Day of Month</label>
                    <div class="relative">
                        <select id="due_day" name="due_day" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ $i == 15 ? 'selected' : '' }}>Day
                                    {{ $i }}</option>
                            @endfor
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeGenerateBillsModal()"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                        Generate Bills
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div id="markAsPaidModal"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md transform transition-all">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Mark Bill as Paid</h3>
                    <button onclick="closeMarkAsPaidModal()"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form id="markAsPaidForm" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-900 mb-2">Payment
                        Method</label>
                    <div class="relative">
                        <select id="payment_method" name="payment_method" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="check">Check</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-900 mb-2">Reference Number
                        <span class="text-gray-500 font-normal">(Optional)</span></label>
                    <input type="text" id="reference_number" name="reference_number"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200"
                        placeholder="Enter reference number">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-900 mb-2">Notes <span
                            class="text-gray-500 font-normal">(Optional)</span></label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 resize-none"
                        placeholder="Add any additional notes"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeMarkAsPaidModal()"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all duration-200">
                        Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.bill-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateBulkActions();
        });

        document.querySelectorAll('.bill-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.bill-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            if (checkedBoxes.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.classList.add('hidden');
            }
        }


        function openGenerateBillsModal() {
            document.getElementById('generateBillsModal').classList.remove('hidden');
        }

        function closeGenerateBillsModal() {
            document.getElementById('generateBillsModal').classList.add('hidden');
        }

        function markAsPaid(billId) {
            const form = document.getElementById('markAsPaidForm');
            form.action = `/owner/billing/${billId}/mark-paid`;
            document.getElementById('markAsPaidModal').classList.remove('hidden');
        }

        function closeMarkAsPaidModal() {
            document.getElementById('markAsPaidModal').classList.add('hidden');
        }

        function sendReminders() {
            const checkedBoxes = document.querySelectorAll('.bill-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select bills to send reminders for.');
                return;
            }
            alert('Reminders sent successfully!');
        }

        function sendAllReminders() {
            if (confirm('Send reminders to all tenants with pending bills?')) {
                alert('Reminders sent to all pending bills!');
            }
        }

        function updateOverdueBills() {
            if (confirm('Update all overdue bills and calculate late fees?')) {
                window.location.href = '{{ route('owner.billing.update-overdue') }}';
            }
        }

        function downloadInvoice(billId) {
            window.open(`/owner/billing/${billId}/invoice`, '_blank');
        }

        function bulkExport() {
            const checkedBoxes = document.querySelectorAll('.bill-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select bills to export.');
                return;
            }
        }

        escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeGenerateBillsModal();
                closeMarkAsPaidModal();
            }
        });


        document.getElementById('generateBillsModal').addEventListener('click', function(e) {
            if (e.target === this) closeGenerateBillsModal();
        });

        document.getElementById('markAsPaidModal').addEventListener('click', function(e) {
            if (e.target === this) closeMarkAsPaidModal();
        });
    </script>

    <style>
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
        }


        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }


        #generateBillsModal>div,
        #markAsPaidModal>div {
            animation: modalSlideUp 0.3s ease-out;
        }

        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
