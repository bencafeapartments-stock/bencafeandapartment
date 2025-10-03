@extends('layouts.app')

@section('title', 'Rent Information')
@section('page-title', 'Rent Information')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Rent Information
                        </h1>
                        <p class="text-lg text-gray-600">
                            Your rental information and payment status
                        </p>
                    </div>
                    <div>
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold shadow-lg transition-all duration-200
                            {{ $rentData['status'] === 'current' ? 'bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-green-500/30' : 'bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-red-500/30' }}">
                            {{ ucfirst($rentData['status']) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Apartment</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $rentData['apartment_number'] }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Monthly Rent</h3>
                    <p class="text-3xl font-semibold text-gray-900">₱{{ number_format($rentData['monthly_rent'], 2) }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Lease Period</h3>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($rentData['lease_start'])->format('M d, Y') }} -
                        {{ \Carbon\Carbon::parse($rentData['lease_end'])->format('M d, Y') }}
                    </p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Next Due Date</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $rentData['next_due_date']->format('M d') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $rentData['next_due_date']->format('Y') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Recent Payment History</h2>
                            <p class="text-sm text-gray-600 mt-1">Your last few rent payments</p>
                        </div>
                        <div>
                            <a href="{{ route('tenant.rent.history') }}"
                                class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                View Full History
                            </a>
                        </div>
                    </div>

                    @if (count($paymentHistory) > 0)
                        <div class="overflow-x-auto -mx-6 sm:-mx-8">
                            <div class="inline-block min-w-full align-middle px-6 sm:px-8">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50/50">
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Payment Period
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Amount
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Payment Date
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($paymentHistory as $payment)
                                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="w-10 h-10 rounded-full flex items-center justify-center
                                                            {{ $payment['status'] === 'paid' ? 'bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg shadow-green-500/30' : 'bg-gradient-to-br from-yellow-400 to-orange-500 shadow-lg shadow-yellow-500/30' }}">
                                                            @if ($payment['status'] === 'paid')
                                                                <svg class="w-5 h-5 text-white" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-white" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-semibold text-gray-900">
                                                                {{ $payment['month'] }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        ₱{{ number_format($payment['amount'], 2) }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($payment['status'] === 'paid')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-700">
                                                            Paid
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-700">
                                                            Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($payment['paid_date'])
                                                        <div class="text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse($payment['paid_date'])->format('M d, Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($payment['paid_date'])->diffForHumans() }}
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">Not paid yet</div>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No payment history</h3>
                            <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                                Get started by making your first rent payment.
                            </p>
                        </div>
                    @endif
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
                            Payment Information
                        </h3>
                        <p class="text-sm text-blue-800 leading-relaxed">
                            Rent is due on the 1st of each month. Late payments may incur additional fees. If you have any
                            questions about your rent or payment methods, please contact the property management office.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input:focus {
            outline: none;
        }
    </style>
@endsection
