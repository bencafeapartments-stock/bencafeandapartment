@extends('layouts.app')

@section('title', 'Staff Reports')
@section('page-title', 'Reports & Analytics')


@section('content')
    <div class="space-y-6">

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
                    <p class="mt-1 text-sm text-gray-600">View performance metrics and generate reports</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('staff.reports.download') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-download mr-2"></i>
                        Download Report
                    </a>
                    <form method="GET" action="{{ route('staff.reports') }}" id="periodForm">
                        <select name="period" id="reportPeriod" class="px-4 py-2 border border-gray-300 rounded-lg"
                            onchange="document.getElementById('periodForm').submit();">
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week"
                                {{ request('period') == 'week' || !request('period') ? 'selected' : '' }}>This Week
                            </option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Tasks Completed Today</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $reportData['completed_tasks'] }}</p>
                        @php
                            $yesterday = \App\Models\Maintenance::where('status', 'completed')
                                ->whereDate('completed_at', now()->yesterday())
                                ->count();
                            $change =
                                $yesterday > 0
                                    ? round((($reportData['completed_tasks'] - $yesterday) / $yesterday) * 100)
                                    : 0;
                        @endphp
                        <p class="text-xs {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $change >= 0 ? '+' : '' }}{{ $change }}% from yesterday
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Pending Tasks</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ $reportData['pending_tasks'] }}</p>
                        <p class="text-xs text-yellow-600">Requires attention</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-peso-sign text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Today's Revenue</h3>
                        <p class="text-2xl font-bold text-blue-600">₱{{ number_format($reportData['revenue_today'], 2) }}
                        </p>
                        @php
                            $yesterdayRevenue = \App\Models\Order::whereDate('created_at', now()->yesterday())
                                ->where('status', 'paid')
                                ->sum('amount');
                            $revenueChange =
                                $yesterdayRevenue > 0
                                    ? round(
                                        (($reportData['revenue_today'] - $yesterdayRevenue) / $yesterdayRevenue) * 100,
                                    )
                                    : 0;
                        @endphp
                        <p class="text-xs {{ $revenueChange >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            {{ $revenueChange >= 0 ? '+' : '' }}{{ $revenueChange }}% from yesterday
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <i class="fas fa-users text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Active Tenants</h3>
                        <p class="text-2xl font-bold text-indigo-600">{{ $reportData['active_tenants'] ?? 0 }}</p>
                        <p class="text-xs text-indigo-600">{{ $reportData['occupancy_rate'] ?? 0 }}% occupancy</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="fas fa-building text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Active Apartments</h3>
                        <p class="text-2xl font-bold text-orange-600">{{ $apartmentStats['active_apartments'] ?? 0 }}</p>
                        @php
                            $occupancyTrend = $apartmentStats['occupancy_trend'] ?? 0;
                        @endphp
                        <p class="text-xs {{ $occupancyTrend >= 0 ? 'text-orange-600' : 'text-red-600' }}">
                            {{ $occupancyTrend >= 0 ? '+' : '' }}{{ $occupancyTrend }}% this month
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-tools text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Maintenance This Month</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $reportData['maintenance_completed'] }}</p>
                        <p class="text-xs text-purple-600">Completed requests</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Task Completion Trend</h3>
                    <p class="text-sm text-gray-500">Daily task completion over the past week</p>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas id="taskChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Revenue Trend</h3>
                    <p class="text-sm text-gray-500">Daily revenue from cafe orders</p>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Active Tenants Trend</h3>
                    <p class="text-sm text-gray-500">Active tenants over the past week</p>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas id="tenantsChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- NEW: Apartment Activity Chart -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Apartment Activity</h3>
                    <p class="text-sm text-gray-500">Apartment assignments & vacancies</p>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas id="apartmentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Apartment Management Overview</h3>
                <p class="text-sm text-gray-500">Detailed apartment statistics and performance metrics</p>
            </div>
            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">
                            {{ $apartmentStats['active_apartments'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Occupied Apartments</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full"
                                style="width: {{ ($apartmentStats['total_apartments'] ?? 0) > 0 ? (($apartmentStats['active_apartments'] ?? 0) / ($apartmentStats['total_apartments'] ?? 1)) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            {{ $apartmentStats['available_apartments'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Available Apartments</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full"
                                style="width: {{ ($apartmentStats['total_apartments'] ?? 0) > 0 ? (($apartmentStats['available_apartments'] ?? 0) / ($apartmentStats['total_apartments'] ?? 1)) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600 mb-2">
                            {{ $apartmentStats['maintenance_apartments'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Under Maintenance</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-yellow-600 h-2 rounded-full"
                                style="width: {{ ($apartmentStats['total_apartments'] ?? 0) > 0 ? (($apartmentStats['maintenance_apartments'] ?? 0) / ($apartmentStats['total_apartments'] ?? 1)) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $apartmentStats['occupancy_rate'] ?? 0 }}%
                        </div>
                        <div class="text-sm text-gray-600">Overall Occupancy Rate</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-purple-600 h-2 rounded-full"
                                style="width: {{ $apartmentStats['occupancy_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>

                @if (isset($apartmentStats['apartments_by_type']) && $apartmentStats['apartments_by_type']->isNotEmpty())
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Occupancy by Apartment Type</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($apartmentStats['apartments_by_type'] as $typeData)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h5 class="font-medium text-gray-900">{{ ucfirst($typeData['type']) }}</h5>
                                        <span class="text-sm text-gray-600">{{ $typeData['occupancy_rate'] }}%</span>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        {{ $typeData['occupied'] }} / {{ $typeData['total'] }} occupied
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full"
                                            style="width: {{ $typeData['occupancy_rate'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($apartmentStats['avg_rent_by_type']) && $apartmentStats['avg_rent_by_type']->isNotEmpty())
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Average Rent by Apartment Type</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach ($apartmentStats['avg_rent_by_type'] as $rentData)
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <div class="text-lg font-bold text-green-600">
                                        ₱{{ number_format($rentData->avg_rent, 2) }}</div>
                                    <div class="text-sm text-gray-600">{{ ucfirst($rentData->apartment_type) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Maintenance Report</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $pendingMaintenance = \App\Models\Maintenance::where('status', 'pending')->count();
                            $inProgressMaintenance = \App\Models\Maintenance::where('status', 'in_progress')->count();
                            $weeklyCompleted = \App\Models\Maintenance::where('status', 'completed')
                                ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
                                ->count();

                            $avgResolution = \App\Models\Maintenance::where('status', 'completed')
                                ->whereNotNull('completed_at')
                                ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as avg_days')
                                ->first();
                            $avgDays = $avgResolution ? round($avgResolution->avg_days, 1) : 0;
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Pending Requests</span>
                            <span class="text-sm font-semibold text-red-600">{{ $pendingMaintenance }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">In Progress</span>
                            <span class="text-sm font-semibold text-yellow-600">{{ $inProgressMaintenance }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Completed This Week</span>
                            <span class="text-sm font-semibold text-green-600">{{ $weeklyCompleted }}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">Avg Resolution Time</span>
                                <span class="text-sm font-semibold text-blue-600">{{ $avgDays }} days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $todayOrders = \App\Models\Order::whereDate('created_at', today())->count();
                            $weeklyOrders = \App\Models\Order::whereBetween('created_at', [
                                now()->startOfWeek(),
                                now()->endOfWeek(),
                            ])->count();
                            $pendingOrders = \App\Models\Order::where('status', 'unpaid')->count();
                            $avgOrderValue = \App\Models\Order::where('status', 'paid')->avg('amount') ?? 0;
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Orders Today</span>
                            <span class="text-sm font-semibold text-blue-600">{{ $todayOrders }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Orders This Week</span>
                            <span class="text-sm font-semibold text-blue-600">{{ $weeklyOrders }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Pending Orders</span>
                            <span class="text-sm font-semibold text-yellow-600">{{ $pendingOrders }}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">Avg Order Value</span>
                                <span
                                    class="text-sm font-semibold text-green-600">₱{{ number_format($avgOrderValue, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tenant Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $activeTenants = \App\Models\User::where('role', 'tenant')
                                ->where('is_active', true)
                                ->count();
                            $totalTenants = \App\Models\User::where('role', 'tenant')->count();
                            $newTenantsThisWeek = \App\Models\User::where('role', 'tenant')
                                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                ->count();
                            $tenantsWithOverdueBills = \App\Models\User::where('role', 'tenant')
                                ->whereHas('tenantBills', function ($query) {
                                    $query->where('status', 'overdue');
                                })
                                ->count();
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Active Tenants</span>
                            <span class="text-sm font-semibold text-green-600">{{ $activeTenants }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Tenants</span>
                            <span class="text-sm font-semibold text-blue-600">{{ $totalTenants }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">New This Week</span>
                            <span class="text-sm font-semibold text-indigo-600">{{ $newTenantsThisWeek }}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">With Overdue Bills</span>
                                <span class="text-sm font-semibold text-red-600">{{ $tenantsWithOverdueBills }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Apartment Performance</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Apartments</span>
                            <span
                                class="text-sm font-semibold text-blue-600">{{ $apartmentStats['total_apartments'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Currently Occupied</span>
                            <span
                                class="text-sm font-semibold text-green-600">{{ $apartmentStats['active_apartments'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Available Now</span>
                            <span
                                class="text-sm font-semibold text-orange-600">{{ $apartmentStats['available_apartments'] ?? 0 }}</span>
                        </div>
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">Occupancy Rate</span>
                                <span
                                    class="text-sm font-semibold text-purple-600">{{ $apartmentStats['occupancy_rate'] ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Popular Items</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @php
                            $popularItems = \App\Models\OrderItem::select(
                                'product_id',
                                \DB::raw('SUM(quantity) as total_quantity'),
                            )
                                ->whereHas('order', function ($query) {
                                    $query->whereMonth('created_at', now()->month);
                                })
                                ->groupBy('product_id')
                                ->orderBy('total_quantity', 'desc')
                                ->limit(5)
                                ->with('product')
                                ->get();
                        @endphp

                        @forelse($popularItems as $index => $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 {{ $index === 0 ? 'bg-yellow-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                        <span
                                            class="text-xs font-bold {{ $index === 0 ? 'text-yellow-800' : 'text-gray-800' }}">{{ $index + 1 }}</span>
                                    </div>
                                    <span
                                        class="ml-3 text-sm text-gray-900">{{ $item->product->product_name ?? 'Unknown Item' }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $item->total_quantity }} orders</span>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 text-sm">No order data available</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Performance Summary</h3>
                <p class="text-sm text-gray-500">Overall staff performance metrics</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    @php
                        $totalTasks = \App\Models\Maintenance::count();
                        $completedTasks = \App\Models\Maintenance::where('status', 'completed')->count();
                        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                        $avgResponseTime = \App\Models\Maintenance::whereNotNull('staff_id')
                            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
                            ->first();
                        $avgHours = $avgResponseTime ? round($avgResponseTime->avg_hours, 1) : 0;

                        $tenantSatisfaction = 4.7;
                    @endphp

                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $completionRate }}%</div>
                        <div class="text-sm text-gray-600">Task Completion Rate</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $avgHours }}</div>
                        <div class="text-sm text-gray-600">Average Response Time (hours)</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($avgHours * 10, 100) }}%">
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $tenantSatisfaction }}</div>
                        <div class="text-sm text-gray-600">Tenant Satisfaction Rating</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-purple-600 h-2 rounded-full"
                                style="width: {{ ($tenantSatisfaction / 5) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $apartmentStats['occupancy_rate'] ?? 0 }}%
                        </div>
                        <div class="text-sm text-gray-600">Occupancy Rate</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-indigo-600 h-2 rounded-full"
                                style="width: {{ $apartmentStats['occupancy_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        @php
                            $apartmentTurnover = $apartmentStats['occupancy_trend'] ?? 0;
                            $turnoverDisplay = abs($apartmentTurnover);
                        @endphp
                        <div class="text-3xl font-bold text-orange-600 mb-2">{{ $turnoverDisplay }}%</div>
                        <div class="text-sm text-gray-600">Monthly Turnover</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: {{ min($turnoverDisplay, 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const taskData = @json($chartData['tasks']);
        const revenueData = @json($chartData['revenue']);
        const tenantsData = @json($chartData['tenants'] ?? ['labels' => [], 'values' => []]);
        const apartmentsData = @json($chartData['apartments'] ?? ['labels' => [], 'data' => []]);

        const taskCtx = document.getElementById('taskChart').getContext('2d');
        const taskChart = new Chart(taskCtx, {
            type: 'line',
            data: {
                labels: taskData.labels,
                datasets: [{
                    label: 'Tasks Completed',
                    data: taskData.values,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: revenueData.labels,
                datasets: [{
                    label: 'Revenue (₱)',
                    data: revenueData.values,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        const tenantsCtx = document.getElementById('tenantsChart').getContext('2d');
        const tenantsChart = new Chart(tenantsCtx, {
            type: 'line',
            data: {
                labels: tenantsData.labels,
                datasets: [{
                    label: 'Active Tenants',
                    data: tenantsData.values,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        const apartmentsCtx = document.getElementById('apartmentsChart').getContext('2d');
        const apartmentsChart = new Chart(apartmentsCtx, {
            type: 'bar',
            data: {
                labels: apartmentsData.labels || [],
                datasets: [{
                        label: 'New Assignments',
                        data: apartmentsData.data ? apartmentsData.data.map(d => d.newly_occupied) : [],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1
                    },
                    {
                        label: 'Vacancies',
                        data: apartmentsData.data ? apartmentsData.data.map(d => d.newly_available) : [],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
