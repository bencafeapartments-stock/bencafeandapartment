@extends('layouts.app')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')


@section('content')
    <div class="space-y-6">

        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>Reports & Analytics
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Comprehensive insights into your property performance and financial metrics
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Date Range
                </button>
                <a href="{{ route('owner.reports.download') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-download mr-2"></i>
                    Download Report
                </a>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-peso-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Monthly Revenue</h3>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($reportData['monthly_revenue']) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>+12.5% from last month
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-percentage text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Occupancy Rate</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $reportData['occupancy_rate'] }}%</p>
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-arrow-up mr-1"></i>+3.2% from last month
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-tools text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Maintenance Cost</h3>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($reportData['maintenance_cost']) }}
                        </p>
                        <p class="text-xs text-red-600">
                            <i class="fas fa-arrow-up mr-1"></i>+5.8% from last month
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-coffee text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Cafe Revenue</h3>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($reportData['cafe_revenue']) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>+8.3% from last month
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Revenue Chart --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-chart-line text-gray-400 mr-2"></i>Revenue Trend
                    </h3>
                    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">Revenue Chart</p>
                            <p class="text-xs text-gray-400">Chart visualization will be implemented</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-between text-sm text-gray-600">
                        <span>Jan 2025</span>
                        <span>Aug 2025</span>
                    </div>
                </div>
            </div>

            {{-- Occupancy Chart --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-chart-pie text-gray-400 mr-2"></i>Occupancy Distribution
                    </h3>
                    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-chart-pie text-gray-400 text-4xl mb-2"></i>
                            <p class="text-gray-500">Occupancy Chart</p>
                            <p class="text-xs text-gray-400">Chart visualization will be implemented</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-1"></div>
                            <span class="text-gray-600">Occupied: 18</span>
                        </div>
                        <div class="text-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mx-auto mb-1"></div>
                            <span class="text-gray-600">Available: 5</span>
                        </div>
                        <div class="text-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mx-auto mb-1"></div>
                            <span class="text-gray-600">Maintenance: 1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Reports Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Financial Summary --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-money-bill-wave text-gray-400 mr-2"></i>Financial Summary
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Income</span>
                            <span
                                class="font-semibold text-green-600">₱{{ number_format($reportData['monthly_revenue'] + $reportData['cafe_revenue']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Maintenance Costs</span>
                            <span
                                class="font-semibold text-red-600">₱{{ number_format($reportData['maintenance_cost']) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Operating Expenses</span>
                            <span class="font-semibold text-red-600">₱15,000</span>
                        </div>
                        <hr>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-900">Net Profit</span>
                            <span
                                class="font-bold text-green-600">₱{{ number_format($reportData['monthly_revenue'] + $reportData['cafe_revenue'] - $reportData['maintenance_cost'] - 15000) }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('owner.reports.revenue') }}"
                            class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors inline-block">
                            View Detailed Report
                        </a>
                    </div>
                </div>
            </div>

            {{-- Top Performing Units --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-star text-gray-400 mr-2"></i>Top Performing Units
                    </h3>
                    <div class="space-y-3">
                        @php
                            $topUnits = [
                                ['number' => '401', 'type' => 'Penthouse', 'revenue' => 35000],
                                ['number' => '304', 'type' => '2 Bedroom', 'revenue' => 21000],
                                ['number' => '305', 'type' => '2 Bedroom', 'revenue' => 21000],
                                ['number' => '203', 'type' => '1 Bedroom', 'revenue' => 18000],
                                ['number' => '204', 'type' => '1 Bedroom', 'revenue' => 18000],
                            ];
                        @endphp
                        @foreach ($topUnits as $unit)
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-900">Unit {{ $unit['number'] }}</span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $unit['type'] }}</span>
                                </div>
                                <span class="font-semibold text-green-600">₱{{ number_format($unit['revenue']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent Activities --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-clock text-gray-400 mr-2"></i>Recent Activities
                    </h3>
                    <div class="space-y-3">
                        @php
                            $activities = [
                                [
                                    'type' => 'payment',
                                    'message' => 'Payment received from Unit 101',
                                    'time' => '2 hours ago',
                                ],
                                [
                                    'type' => 'maintenance',
                                    'message' => 'Maintenance completed in Unit 203',
                                    'time' => '5 hours ago',
                                ],
                                [
                                    'type' => 'new_tenant',
                                    'message' => 'New tenant assigned to Unit 105',
                                    'time' => '1 day ago',
                                ],
                                ['type' => 'bill', 'message' => 'Monthly bills generated', 'time' => '2 days ago'],
                                ['type' => 'cafe', 'message' => 'Cafe order from Unit 304', 'time' => '3 days ago'],
                            ];
                        @endphp
                        @foreach ($activities as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if ($activity['type'] == 'payment')
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-peso-sign text-green-600 text-xs"></i>
                                        </div>
                                    @elseif($activity['type'] == 'maintenance')
                                        <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-tools text-yellow-600 text-xs"></i>
                                        </div>
                                    @elseif($activity['type'] == 'new_tenant')
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600 text-xs"></i>
                                        </div>
                                    @elseif($activity['type'] == 'bill')
                                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-file-invoice text-purple-600 text-xs"></i>
                                        </div>
                                    @else
                                        <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-coffee text-orange-600 text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Report Actions --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-bolt text-gray-400 mr-2"></i>Quick Report Actions
                </h3>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <a href="{{ route('owner.reports.revenue') }}"
                        class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors">
                        <i class="fas fa-chart-line text-green-600 text-2xl mb-2"></i>
                        <span class="text-sm font-medium text-green-700">Revenue Report</span>
                    </a>

                    <a href="{{ route('owner.reports.occupancy') }}"
                        class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition-colors">
                        <i class="fas fa-building text-blue-600 text-2xl mb-2"></i>
                        <span class="text-sm font-medium text-blue-700">Occupancy Report</span>
                    </a>

                    <button
                        class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg border border-yellow-200 transition-colors">
                        <i class="fas fa-tools text-yellow-600 text-2xl mb-2"></i>
                        <span class="text-sm font-medium text-yellow-700">Maintenance Report</span>
                    </button>

                    <a href="{{ route('owner.reports.download') }}"
                        class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition-colors">
                        <i class="fas fa-download text-purple-600 text-2xl mb-2"></i>
                        <span class="text-sm font-medium text-purple-700">Download All</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Monthly Performance Table --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-table text-gray-400 mr-2"></i>Monthly Performance
                </h3>
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Month</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Occupancy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Maintenance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Net Profit</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $monthlyData = [
                                    [
                                        'month' => 'August 2025',
                                        'revenue' => 160000,
                                        'occupancy' => 85,
                                        'maintenance' => 8500,
                                        'profit' => 136500,
                                    ],
                                    [
                                        'month' => 'July 2025',
                                        'revenue' => 142500,
                                        'occupancy' => 82,
                                        'maintenance' => 12000,
                                        'profit' => 115500,
                                    ],
                                    [
                                        'month' => 'June 2025',
                                        'revenue' => 155000,
                                        'occupancy' => 88,
                                        'maintenance' => 7500,
                                        'profit' => 132500,
                                    ],
                                    [
                                        'month' => 'May 2025',
                                        'revenue' => 148000,
                                        'occupancy' => 84,
                                        'maintenance' => 9800,
                                        'profit' => 123200,
                                    ],
                                ];
                            @endphp
                            @foreach ($monthlyData as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['month'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱{{ number_format($data['revenue']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $data['occupancy'] }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱{{ number_format($data['maintenance']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span
                                            class="font-semibold text-green-600">₱{{ number_format($data['profit']) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
