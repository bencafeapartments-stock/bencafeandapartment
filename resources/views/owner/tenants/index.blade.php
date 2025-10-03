@extends('layouts.app')

@section('title', 'Tenant Management Report')
@section('page-title', 'Tenant Management Report')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-gray-900 mb-2">Tenant Management Report</h1>
                <p class="text-gray-500">View and monitor tenant information and status</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium mb-1">Total Tenants</p>
                                <p class="text-3xl font-bold text-white">{{ $stats['total_tenants'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium mb-1">Active Tenants</p>
                                <p class="text-3xl font-bold text-white">{{ $stats['active_tenants'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-100 text-sm font-medium mb-1">Inactive Tenants</p>
                                <p class="text-3xl font-bold text-white">{{ $stats['inactive_tenants'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-slash text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium mb-1">Overdue Payments</p>
                                <p class="text-3xl font-bold text-white">{{ $stats['overdue_tenants'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Status Filters -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 mb-6">
                <div class="p-4">
                    <div class="flex flex-wrap gap-3">
                        <button onclick="quickFilterStatus('all')"
                            class="quick-filter-btn {{ !request()->has('status') || request('status') == '' ? 'active' : '' }}">
                            <i class="fas fa-users mr-2"></i>
                            All Tenants
                            <span class="ml-2 px-2 py-0.5 bg-white/50 rounded-full text-xs font-semibold">
                                {{ $stats['total_tenants'] ?? 0 }}
                            </span>
                        </button>
                        <button onclick="quickFilterStatus('active')"
                            class="quick-filter-btn {{ request('status') == 'active' ? 'active' : '' }}">
                            <i class="fas fa-user-check mr-2"></i>
                            Active Only
                            <span class="ml-2 px-2 py-0.5 bg-white/50 rounded-full text-xs font-semibold">
                                {{ $stats['active_tenants'] ?? 0 }}
                            </span>
                        </button>
                        <button onclick="quickFilterStatus('inactive')"
                            class="quick-filter-btn {{ request('status') == 'inactive' ? 'active' : '' }}">
                            <i class="fas fa-user-slash mr-2"></i>
                            Inactive Only
                            <span class="ml-2 px-2 py-0.5 bg-white/50 rounded-full text-xs font-semibold">
                                {{ $stats['inactive_tenants'] ?? 0 }}
                            </span>
                        </button>
                        <button onclick="quickFilterStatus('overdue')"
                            class="quick-filter-btn {{ request('payment_status') == 'overdue' ? 'active' : '' }}">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Overdue Payments
                            <span class="ml-2 px-2 py-0.5 bg-white/50 rounded-full text-xs font-semibold">
                                {{ $stats['overdue_tenants'] ?? 0 }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 mb-8">
                <div class="p-6">
                    <form method="GET" action="{{ route('owner.tenants.index') }}" id="filterForm"
                        class="flex flex-wrap gap-4">
                        <div class="relative">
                            <select name="payment_status" id="paymentStatusFilter"
                                class="appearance-none bg-gray-50/50 border-0 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200">
                                <option value="">All Payment Status</option>
                                <option value="current" {{ request('payment_status') == 'current' ? 'selected' : '' }}>
                                    Current</option>
                                <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>
                                    Overdue</option>
                                <option value="at_risk" {{ request('payment_status') == 'at_risk' ? 'selected' : '' }}>At
                                    Risk</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <select name="lease_status" id="leaseStatusFilter"
                                class="appearance-none bg-gray-50/50 border-0 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200">
                                <option value="">All Lease Status</option>
                                <option value="active" {{ request('lease_status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="expiring" {{ request('lease_status') == 'expiring' ? 'selected' : '' }}>
                                    Expiring Soon</option>
                                <option value="expired" {{ request('lease_status') == 'expired' ? 'selected' : '' }}>
                                    Expired</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <select name="status" id="statusFilter"
                                class="appearance-none bg-gray-50/50 border-0 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200">
                                <option value="">All Tenants</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    Only</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>

                        <div class="relative flex-1 min-w-64">
                            <input type="text" name="search"
                                class="w-full bg-gray-50/50 border-0 rounded-xl px-4 py-3 pl-10 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200"
                                placeholder="Search tenants..." value="{{ request('search') }}">
                            <i
                                class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>

                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-blue-500 text-white text-sm font-semibold rounded-xl hover:bg-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-lg shadow-blue-500/25">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>

                        <a href="{{ route('owner.tenants.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-gray-500/20 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </form>
                </div>
            </div>

            <!-- Tenants Table -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/50 bg-gray-50/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-users mr-3 text-gray-400"></i>Tenants List
                            @if (request()->hasAny(['payment_status', 'lease_status', 'search', 'status']))
                                <span class="text-sm text-gray-500 font-normal ml-2">(Filtered Results)</span>
                            @endif
                        </h3>
                    </div>
                </div>

                <div class="p-6">
                    @if ($tenants->count() > 0)
                        <div class="overflow-hidden rounded-xl border border-gray-200/50">
                            <table class="min-w-full">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr class="border-b border-gray-200/50">
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Tenant Info</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Apartment</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Lease Period</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Payment Status</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Outstanding</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Last Payment</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach ($tenants as $tenant)
                                        @php
                                            $currentRent = $tenant->tenantrents->where('status', 'active')->first();
                                            $overdueCount = $tenant->billings->where('status', 'overdue')->count();
                                            $outstandingAmount = $tenant->billings
                                                ->whereIn('status', ['pending', 'overdue'])
                                                ->sum('amount');
                                            $lastPayment = $tenant->billings
                                                ->where('status', 'paid')
                                                ->whereNotNull('paid_date')
                                                ->sortByDesc('paid_date')
                                                ->first();
                                            $daysSincePayment =
                                                $lastPayment && $lastPayment->paid_date
                                                    ? $lastPayment->paid_date->diffInDays(now())
                                                    : null;

                                            $paymentStatus = 'current';
                                            $statusClass = 'bg-green-100 text-green-700 ring-green-600/20';

                                            if ($overdueCount > 0) {
                                                if (
                                                    $overdueCount >= 3 ||
                                                    ($daysSincePayment !== null && $daysSincePayment > 90)
                                                ) {
                                                    $paymentStatus = 'eviction_risk';
                                                    $statusClass = 'bg-red-100 text-red-700 ring-red-600/20';
                                                } else {
                                                    $paymentStatus = 'overdue';
                                                    $statusClass = 'bg-amber-100 text-amber-700 ring-amber-600/20';
                                                }
                                            } elseif ($outstandingAmount > 0) {
                                                $paymentStatus = 'pending';
                                                $statusClass = 'bg-blue-100 text-blue-700 ring-blue-600/20';
                                            }

                                            $leaseStatus = 'N/A';
                                            $leaseClass = 'bg-gray-100 text-gray-700 ring-gray-600/20';
                                            if ($currentRent) {
                                                $daysUntilExpiry = $currentRent->end_date
                                                    ? now()->diffInDays($currentRent->end_date, false)
                                                    : null;
                                                if ($daysUntilExpiry !== null) {
                                                    if ($daysUntilExpiry < 0) {
                                                        $leaseStatus = 'Expired';
                                                        $leaseClass = 'bg-red-100 text-red-700 ring-red-600/20';
                                                    } elseif ($daysUntilExpiry <= 30) {
                                                        $leaseStatus = 'Expiring Soon';
                                                        $leaseClass = 'bg-amber-100 text-amber-700 ring-amber-600/20';
                                                    } else {
                                                        $leaseStatus = 'Active';
                                                        $leaseClass = 'bg-green-100 text-green-700 ring-green-600/20';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr
                                            class="{{ !$tenant->is_active ? 'bg-gray-50/80 opacity-75' : '' }} hover:bg-gray-50/50 transition-colors duration-150">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-10 h-10 bg-gradient-to-br {{ $tenant->is_active ? 'from-blue-100 to-blue-200' : 'from-gray-100 to-gray-200' }} rounded-full flex items-center justify-center">
                                                        <i
                                                            class="fas fa-user {{ $tenant->is_active ? 'text-blue-500' : 'text-gray-500' }} text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ $tenant->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $tenant->email }}</div>
                                                        @if ($tenant->contact_number)
                                                            <div class="text-xs text-gray-400">
                                                                {{ $tenant->contact_number }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($tenant->is_active)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 ring-1 ring-green-600/20">
                                                        <i class="fas fa-check-circle mr-1 text-xs"></i>
                                                        Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700 ring-1 ring-gray-300">
                                                        <i class="fas fa-user-slash mr-1 text-xs"></i>
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if (!$tenant->is_active)
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-600 ring-1 ring-gray-400/20">
                                                        <i class="fas fa-ban mr-1 text-xs"></i>
                                                        Unassigned
                                                    </div>
                                                @elseif ($currentRent && $currentRent->apartment)
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 ring-1 ring-blue-600/20">
                                                        {{ $currentRent->apartment->apartment_number }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $currentRent->apartment->apartment_type }}</div>
                                                @else
                                                    <span class="text-gray-400 text-sm">No Assignment</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if (!$tenant->is_active)
                                                    <span class="text-gray-400 text-sm">Deactivated</span>
                                                @elseif ($currentRent)
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ring-1 {{ $leaseClass }}">
                                                        {{ $leaseStatus }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $currentRent->start_date->format('M d, Y') }} -
                                                        {{ $currentRent->end_date ? $currentRent->end_date->format('M d, Y') : 'Ongoing' }}
                                                    </div>
                                                    @if ($currentRent->end_date)
                                                        <div class="text-xs text-gray-400 mt-1">
                                                            @php
                                                                $daysLeft = now()->diffInDays(
                                                                    $currentRent->end_date,
                                                                    false,
                                                                );
                                                            @endphp
                                                            @if ($daysLeft < 0)
                                                                Expired {{ abs($daysLeft) }} days ago
                                                            @elseif($daysLeft == 0)
                                                                Expires today
                                                            @else
                                                                {{ $daysLeft }} days remaining
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 text-sm">No Lease</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <div
                                                        class="w-2 h-2 rounded-full {{ $paymentStatus == 'current' ? 'bg-green-500' : '' }} {{ $paymentStatus == 'pending' ? 'bg-blue-500' : '' }} {{ $paymentStatus == 'overdue' ? 'bg-amber-500' : '' }} {{ $paymentStatus == 'eviction_risk' ? 'bg-red-500' : '' }}">
                                                    </div>
                                                    <div
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ring-1 {{ $statusClass }}">
                                                        @switch($paymentStatus)
                                                            @case('current')
                                                                Current
                                                            @break

                                                            @case('pending')
                                                                Pending
                                                            @break

                                                            @case('overdue')
                                                                Overdue ({{ $overdueCount }})
                                                            @break

                                                            @case('eviction_risk')
                                                                Eviction Risk
                                                            @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                                @if ($overdueCount > 0)
                                                    <div class="text-xs text-red-600 mt-1">{{ $overdueCount }} overdue
                                                        bills</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($outstandingAmount > 0)
                                                    <span
                                                        class="text-red-600 font-bold text-sm">₱{{ number_format($outstandingAmount, 2) }}</span>
                                                @else
                                                    <span class="text-green-600 font-semibold text-sm">₱0.00</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($lastPayment)
                                                    <div class="text-green-600 font-medium text-sm">
                                                        {{ $lastPayment->paid_date->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $daysSincePayment }} days ago
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-sm">No payments</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($tenants->hasPages())
                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200/50">
                                <div class="text-sm text-gray-600">
                                    Showing {{ $tenants->firstItem() }} to {{ $tenants->lastItem() }} of
                                    {{ $tenants->total() }} tenants
                                </div>
                                <div class="pagination-wrapper">
                                    {{ $tenants->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No tenants found</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">
                                @if (request()->hasAny(['payment_status', 'lease_status', 'search', 'status']))
                                    No tenants match your current filters. Try adjusting your search criteria.
                                @else
                                    No tenant data available at this time.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function quickFilterStatus(status) {
                const form = document.getElementById('filterForm');
                const statusFilter = document.getElementById('statusFilter');
                const paymentStatusFilter = document.getElementById('paymentStatusFilter');
                const leaseStatusFilter = document.getElementById('leaseStatusFilter');

                paymentStatusFilter.value = '';
                leaseStatusFilter.value = '';

                switch (status) {
                    case 'all':
                        statusFilter.value = '';
                        break;
                    case 'active':
                        statusFilter.value = 'active';
                        break;
                    case 'inactive':
                        statusFilter.value = 'inactive';
                        break;
                    case 'overdue':
                        statusFilter.value = '';
                        paymentStatusFilter.value = 'overdue';
                        break;
                }

                form.submit();
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .quick-filter-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.5rem;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                color: #495057;
                font-size: 0.875rem;
                font-weight: 600;
                border-radius: 0.75rem;
                border: 2px solid transparent;
                transition: all 0.2s ease;
                cursor: pointer;
                white-space: nowrap;
            }

            .quick-filter-btn:hover {
                background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .quick-filter-btn.active {
                background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                color: white;
                border-color: #1d4ed8;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .quick-filter-btn.active:hover {
                background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            }

            tr.bg-gray-50\/80 {
                background-color: rgba(249, 250, 251, 0.8);
            }
        </style>
    @endpush
@endsection
