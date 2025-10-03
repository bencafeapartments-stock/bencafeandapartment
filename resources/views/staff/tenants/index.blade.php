@extends('layouts.app')

@section('title', 'Manage Tenants')
@section('page-title', 'Tenant Management')


@section('content')
    <div class="space-y-6">

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tenant Management</h1>
                    <p class="mt-2 text-gray-600">Monitor and manage tenant information</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-900">{{ $tenants->count() }} Total Tenants</div>
                        <div class="text-sm text-gray-600">{{ $tenants->where('is_active', true)->count() }} Active</div>
                    </div>
                    <a href="{{ route('staff.tenants.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Tenant
                    </a>
                </div>
            </div>
        </div>


        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="filterTenants('all')" id="tab-all"
                        class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        All Tenants ({{ $tenants->count() }})
                    </button>
                    <button onclick="filterTenants('active')" id="tab-active"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Active ({{ $tenants->where('is_active', true)->count() }})
                    </button>
                    <button onclick="filterTenants('inactive')" id="tab-inactive"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Inactive ({{ $tenants->where('is_active', false)->count() }})
                    </button>
                    <button onclick="filterTenants('with-apartments')" id="tab-with-apartments"
                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        With Apartments ({{ $tenants->whereNotNull('currentRent')->count() }})
                    </button>
                </nav>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Tenants</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $tenants->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Active Tenants</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $tenants->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-home text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">With Apartments</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $tenants->whereNotNull('currentRent')->count() }}
                        </p>
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="fas fa-user-times text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Inactive Tenants</h3>
                        <p class="text-2xl font-bold text-red-600">{{ $tenants->where('is_active', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                <div class="flex space-x-3">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Search tenants..." onkeyup="searchTenants()"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button onclick="clearFilters()"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Clear Filters
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <div id="results-count" class="text-sm text-gray-600">
                        Showing all {{ $tenants->count() }} tenants
                    </div>
                    {{-- <a href="{{ route('staff.tenants.create') }}"
                        class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-1"></i>
                        Add Tenant
                    </a> --}}
                </div>
            </div>
        </div>


        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-list text-gray-400 mr-2"></i><span id="table-title">All Tenants</span>
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tenant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Apartment
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Join Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tenants-table-body" class="bg-white divide-y divide-gray-200">
                        @forelse($tenants as $tenant)
                            <tr class="tenant-row hover:bg-gray-50"
                                data-status="{{ $tenant->is_active ? 'active' : 'inactive' }}"
                                data-has-apartment="{{ $tenant->currentRent ? 'yes' : 'no' }}"
                                data-name="{{ strtolower($tenant->name) }}" data-email="{{ strtolower($tenant->email) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <i class="fas fa-user text-purple-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $tenant->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $tenant->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $tenant->contact_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($tenant->currentRent && $tenant->currentRent->apartment)
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-building text-gray-400 mr-1"></i>
                                            {{ $tenant->currentRent->apartment->apartment_number }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ₱{{ number_format($tenant->currentRent->monthly_rent, 2) }}/month
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($tenant->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-green-400 mr-1" style="font-size: 6px;"></i>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-circle text-red-400 mr-1" style="font-size: 6px;"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $tenant->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('staff.tenants.show', $tenant->id) }}"
                                            class="text-purple-600 hover:text-purple-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="text-blue-600 hover:text-blue-900" title="Send Message"
                                            onclick="sendMessage('{{ $tenant->id }}')">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        @if ($tenant->is_active)
                                            <button class="text-red-600 hover:text-red-900" title="Deactivate Tenant"
                                                onclick="deactivateTenant('{{ $tenant->id }}')">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        @else
                                            <button class="text-green-600 hover:text-green-900" title="Activate Tenant"
                                                onclick="activateTenant('{{ $tenant->id }}')">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-tenants-row">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-users text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No tenants found</p>
                                        <p class="text-sm mb-4">Tenants will appear here once they register</p>
                                        <a href="{{ route('staff.tenants.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-plus mr-2"></i>
                                            Add Your First Tenant
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div id="filtered-stats" class="grid grid-cols-1 lg:grid-cols-2 gap-6" style="display: none;">
            <!-- Recent Tenant Activities -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-info-circle text-gray-400 mr-2"></i>Filter Information
                    </h3>
                </div>
                <div class="p-6">
                    <div id="filter-info" class="text-sm text-gray-600">

                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-bolt text-gray-400 mr-2"></i>Quick Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('staff.tenants.create') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 text-sm text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>Add New Tenant
                        </a>
                        <button onclick="activateAllInactive()"
                            class="w-full text-left px-4 py-2 text-sm text-green-700 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200">
                            <i class="fas fa-user-check mr-2"></i>Activate All Inactive Tenants
                        </button>
                        <button onclick="exportFiltered()"
                            class="w-full text-left px-4 py-2 text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200">
                            <i class="fas fa-download mr-2"></i>Export Filtered Results
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <style>
            .tab-button.active {
                border-color: #10b981;
                color: #10b981;
            }

            .hidden-row {
                display: none;
            }
        </style>

        <script>
            let currentFilter = 'all';

            function filterTenants(filter) {
                currentFilter = filter;
                const rows = document.querySelectorAll('.tenant-row');
                const tabButtons = document.querySelectorAll('.tab-button');
                const tableTitle = document.getElementById('table-title');
                const resultsCount = document.getElementById('results-count');
                const filteredStats = document.getElementById('filtered-stats');
                const filterInfo = document.getElementById('filter-info');


                tabButtons.forEach(btn => btn.classList.remove('active'));
                document.getElementById(`tab-${filter}`).classList.add('active');

                let visibleCount = 0;

                rows.forEach(row => {
                    const isActive = row.getAttribute('data-status') === 'active';
                    const hasApartment = row.getAttribute('data-has-apartment') === 'yes';
                    let shouldShow = false;

                    switch (filter) {
                        case 'all':
                            shouldShow = true;
                            break;
                        case 'active':
                            shouldShow = isActive;
                            break;
                        case 'inactive':
                            shouldShow = !isActive;
                            break;
                        case 'with-apartments':
                            shouldShow = hasApartment;
                            break;
                    }

                    if (shouldShow) {
                        row.style.display = 'table-row';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });


                const titles = {
                    'all': 'All Tenants',
                    'active': 'Active Tenants',
                    'inactive': 'Inactive Tenants',
                    'with-apartments': 'Tenants with Apartments'
                };

                tableTitle.textContent = titles[filter];
                resultsCount.textContent =
                    `Showing ${visibleCount} ${filter === 'all' ? 'tenants' : filter.replace('-', ' ') + ' tenants'}`;


                if (filter !== 'all') {
                    filteredStats.style.display = 'grid';
                    const infoText = getFilterInfo(filter, visibleCount);
                    filterInfo.innerHTML = infoText;
                } else {
                    filteredStats.style.display = 'none';
                }

                document.getElementById('searchInput').value = '';
            }

            function getFilterInfo(filter, count) {
                const info = {
                    'active': `<p><strong>${count}</strong> tenants currently have active accounts and can access the system.</p>`,
                    'inactive': `<p><strong>${count}</strong> tenants have been deactivated and cannot access their accounts.</p><p class="mt-2 text-red-600">These tenants cannot log in or access any services.</p>`,
                    'with-apartments': `<p><strong>${count}</strong> tenants are currently assigned to apartments with active rental agreements.</p>`
                };
                return info[filter] || '';
            }

            function searchTenants() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const rows = document.querySelectorAll('.tenant-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.style.display === 'none') return;
                    const name = row.getAttribute('data-name');
                    const email = row.getAttribute('data-email');
                    const shouldShow = name.includes(searchTerm) || email.includes(searchTerm);

                    if (shouldShow) {
                        row.classList.remove('hidden-row');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden-row');
                    }
                });

                const resultsCount = document.getElementById('results-count');
                resultsCount.textContent = searchTerm ? `Found ${visibleCount} tenants matching "${searchTerm}"` :
                    `Showing ${visibleCount} tenants`;
            }

            function clearFilters() {
                document.getElementById('searchInput').value = '';
                document.querySelectorAll('.tenant-row').forEach(row => {
                    row.classList.remove('hidden-row');
                });
                filterTenants('all');
            }

            function sendMessage(tenantId) {
                alert('Send message to tenant #' + tenantId);
            }

            function deactivateTenant(tenantId) {
                if (confirm('Are you sure you want to deactivate this tenant? They will lose access to their account.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/staff/tenants/${tenantId}/deactivate`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PATCH">
                `;

                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function activateTenant(tenantId) {
                if (confirm('Are you sure you want to activate this tenant? They will regain access to their account.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/staff/tenants/${tenantId}/activate`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PATCH">
                `;

                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function activateAllInactive() {
                if (confirm(
                        'Are you sure you want to activate ALL inactive tenants? This will restore access for all deactivated accounts.'
                    )) {
                    alert('Bulk activation feature would be implemented here.');

                }
            }

            function exportFiltered() {
                alert('Export feature would be implemented here.');

            }
        </script>
    @endpush
@endsection
