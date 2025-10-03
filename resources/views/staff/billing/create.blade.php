@extends('layouts.app')

@section('title', 'Create Bill')
@section('page-title', 'Create New Bill')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-plus-circle text-blue-600 mr-2"></i>Create New Bill
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Generate a consolidated bill for a tenant with multiple billing types
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('staff.billing.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Billing
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('staff.billing.store') }}" method="POST" id="createBillForm">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700">
                                Tenant <span class="text-red-500">*</span>
                            </label>
                            <select id="tenant_id" name="tenant_id" required onchange="updateTenantInfo()"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('tenant_id') border-red-300 @enderror">
                                <option value="">Select a tenant...</option>
                                @foreach ($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" data-email="{{ $tenant->email }}"
                                        data-contact="{{ $tenant->contact_number }}"
                                        data-apartment="{{ $tenant->tenantRents->first()?->apartment?->apartment_number ?? 'N/A' }}"
                                        data-rent-id="{{ $tenant->tenantRents->first()?->id ?? '' }}"
                                        data-monthly-rent="{{ $tenant->tenantRents->first()?->monthly_rent ?? 0 }}"
                                        {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }}
                                        @if ($tenant->tenantRents->first()?->apartment)
                                            - Apt {{ $tenant->tenantRents->first()->apartment->apartment_number }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="tenantInfo" class="hidden mt-3 p-3 bg-gray-50 rounded-md">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Email:</span>
                                        <span id="tenantEmail" class="text-gray-600"></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Contact:</span>
                                        <span id="tenantContact" class="text-gray-600"></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Apartment:</span>
                                        <span id="tenantApartment" class="text-gray-600"></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Billing Types <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="billing-type-card" data-type="rent">
                                    <div class="relative">
                                        <input type="checkbox" id="type_rent" name="billing_types[]" value="rent"
                                            class="billing-type-checkbox sr-only" onchange="updateBillingTypes()">
                                        <label for="type_rent"
                                            class="billing-type-label cursor-pointer block p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-all duration-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <i class="fas fa-home text-blue-600 text-xl mb-2"></i>
                                                    <h3 class="font-medium text-gray-900">Monthly Rent</h3>
                                                    <p class="text-sm text-gray-500">Regular monthly rental payment</p>
                                                </div>
                                                <div class="billing-type-check hidden">
                                                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>



                                <div class="billing-type-card" data-type="maintenance">
                                    <div class="relative">
                                        <input type="checkbox" id="type_maintenance" name="billing_types[]"
                                            value="maintenance" class="billing-type-checkbox sr-only"
                                            onchange="updateBillingTypes()">
                                        <label for="type_maintenance"
                                            class="billing-type-label cursor-pointer block p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-all duration-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <i class="fas fa-tools text-orange-600 text-xl mb-2"></i>
                                                    <h3 class="font-medium text-gray-900">Maintenance</h3>
                                                    <p class="text-sm text-gray-500">Repairs and upkeep</p>
                                                </div>
                                                <div class="billing-type-check hidden">
                                                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="billing-type-card" data-type="cafe">
                                    <div class="relative">
                                        <input type="checkbox" id="type_cafe" name="billing_types[]" value="cafe"
                                            class="billing-type-checkbox sr-only" onchange="updateBillingTypes()">
                                        <label for="type_cafe"
                                            class="billing-type-label cursor-pointer block p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-all duration-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <i class="fas fa-coffee text-brown-600 text-xl mb-2"></i>
                                                    <h3 class="font-medium text-gray-900">Cafe Orders</h3>
                                                    <p class="text-sm text-gray-500">Food and beverage orders</p>
                                                </div>
                                                <div class="billing-type-check hidden">
                                                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>



                                <div class="billing-type-card" data-type="other">
                                    <div class="relative">
                                        <input type="checkbox" id="type_other" name="billing_types[]" value="other"
                                            class="billing-type-checkbox sr-only" onchange="updateBillingTypes()">
                                        <label for="type_other"
                                            class="billing-type-label cursor-pointer block p-4 border-2 border-gray-300 rounded-lg hover:border-blue-300 transition-all duration-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <i class="fas fa-ellipsis-h text-gray-600 text-xl mb-2"></i>
                                                    <h3 class="font-medium text-gray-900">Other</h3>
                                                    <p class="text-sm text-gray-500">Miscellaneous charges</p>
                                                </div>
                                                <div class="billing-type-check hidden">
                                                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('billing_types')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div id="billingBreakdown" class="sm:col-span-2 hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-list mr-2"></i>Billing Breakdown
                            </h3>
                            <div id="billingItems" class="space-y-4">

                            </div>
                        </div>


                        <div class="sm:col-span-2">
                            <label for="total_amount" class="block text-sm font-medium text-gray-700">
                                Total Amount (₱) <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" id="total_amount" name="amount" step="0.01" min="0"
                                    required readonly value="0.00"
                                    class="block w-full pl-7 pr-12 py-2 text-base border border-gray-300 rounded-md bg-gray-50 text-gray-900 font-semibold sm:text-sm"
                                    placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Total is automatically calculated from individual billing
                                items</p>
                        </div>


                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">
                                Due Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="due_date" name="due_date" required
                                value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('due_date') border-red-300 @enderror">
                            @error('due_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div id="billingPeriod" class="hidden">
                            <label for="billing_month" class="block text-sm font-medium text-gray-700">
                                Billing Period
                            </label>
                            <input type="month" id="billing_month" name="billing_month"
                                value="{{ old('billing_month', now()->format('Y-m')) }}"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <input type="hidden" id="rent_id" name="rent_id" value="{{ old('rent_id') }}">
                        <input type="hidden" name="selected_orders" id="selected_orders" value="">
                        <input type="hidden" name="billing_breakdown" id="billing_breakdown" value="">

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description/Notes
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror"
                                placeholder="Enter bill description or additional notes...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="cafeOrdersSection" class="hidden mt-8">
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-coffee text-brown-600 mr-2"></i>Unpaid Cafe Orders
                            </h3>
                            <div id="cafeOrdersContainer">
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                                    <p>Select a tenant to view their unpaid cafe orders</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="billPreview" class="hidden mt-8 p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <h4 class="text-sm font-medium text-blue-900 mb-3">
                            <i class="fas fa-eye mr-2"></i>Bill Preview
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-blue-700">Tenant:</span>
                                <span id="previewTenant" class="text-blue-600"></span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700">Types:</span>
                                <span id="previewTypes" class="text-blue-600"></span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700">Total Amount:</span>
                                <span id="previewAmount" class="text-blue-600 font-semibold"></span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700">Due Date:</span>
                                <span id="previewDueDate" class="text-blue-600"></span>
                            </div>
                        </div>
                        <div id="previewBreakdown" class="mt-4">

                        </div>
                    </div>


                    <div class="mt-8 flex items-center justify-end space-x-3">
                        <a href="{{ route('staff.billing.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="button" onclick="previewBill()"
                            class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Create Bill
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Multi-Type Billing Tips
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Select multiple billing types to create consolidated bills</li>
                    <li>• Each type can have individual amounts and descriptions</li>
                    <li>• Rent bills automatically pull from tenant's lease agreement</li>
                    <li>• Cafe orders allow selection of multiple unpaid orders</li>
                </ul>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Total amount is automatically calculated</li>
                    <li>• Due dates apply to the entire consolidated bill</li>
                    <li>• Preview function shows detailed breakdown</li>
                    <li>• Individual line items help with payment tracking</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        let selectedOrders = [];
        let billingBreakdown = {};
        let totalAmount = 0;

        function updateTenantInfo() {
            const tenantSelect = document.getElementById('tenant_id');
            const selectedOption = tenantSelect.selectedOptions[0];
            const tenantInfo = document.getElementById('tenantInfo');

            if (selectedOption && selectedOption.value) {
                document.getElementById('tenantEmail').textContent = selectedOption.dataset.email || 'N/A';
                document.getElementById('tenantContact').textContent = selectedOption.dataset.contact || 'N/A';
                document.getElementById('tenantApartment').textContent = selectedOption.dataset.apartment || 'N/A';

                document.getElementById('rent_id').value = selectedOption.dataset.rentId || '';

                tenantInfo.classList.remove('hidden');

                if (document.getElementById('type_rent').checked) {
                    updateRentAmount();
                }

                if (document.getElementById('type_cafe').checked) {
                    loadCafeOrders(selectedOption.value);
                }
            } else {
                tenantInfo.classList.add('hidden');
                document.getElementById('rent_id').value = '';
            }

            updatePreview();
        }

        function updateBillingTypes() {
            const selectedTypes = document.querySelectorAll('.billing-type-checkbox:checked');
            const billingBreakdown = document.getElementById('billingBreakdown');
            const billingItems = document.getElementById('billingItems');
            const cafeOrdersSection = document.getElementById('cafeOrdersSection');
            const billingPeriod = document.getElementById('billingPeriod');


            document.querySelectorAll('.billing-type-card').forEach(card => {
                const checkbox = card.querySelector('.billing-type-checkbox');
                const label = card.querySelector('.billing-type-label');
                const check = card.querySelector('.billing-type-check');

                if (checkbox.checked) {
                    label.classList.add('border-blue-500', 'bg-blue-50');
                    label.classList.remove('border-gray-300');
                    check.classList.remove('hidden');
                } else {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-300');
                    check.classList.add('hidden');
                }
            });

            if (selectedTypes.length > 0) {
                billingBreakdown.classList.remove('hidden');
                generateBillingItems();

                const rentSelected = document.getElementById('type_rent').checked;
                if (rentSelected) {
                    billingPeriod.classList.remove('hidden');
                } else {
                    billingPeriod.classList.add('hidden');
                }
                const cafeSelected = document.getElementById('type_cafe').checked;
                if (cafeSelected) {
                    cafeOrdersSection.classList.remove('hidden');
                    const tenantSelect = document.getElementById('tenant_id');
                    if (tenantSelect.value) {
                        loadCafeOrders(tenantSelect.value);
                    }
                } else {
                    cafeOrdersSection.classList.add('hidden');
                }
            } else {
                billingBreakdown.classList.add('hidden');
                cafeOrdersSection.classList.add('hidden');
                billingPeriod.classList.add('hidden');
            }

            updateTotalAmount();
            updateDescription();
            updatePreview();
        }

        function updatePreview() {
            const tenantSelect = document.getElementById('tenant_id');
            const selectedTypes = document.querySelectorAll('.billing-type-checkbox:checked');
            const totalAmount = document.getElementById('total_amount').value;
            const dueDate = document.getElementById('due_date').value;
            const preview = document.getElementById('billPreview');

            if (tenantSelect.value && selectedTypes.length > 0 && totalAmount && dueDate) {
                const selectedTenant = tenantSelect.selectedOptions[0].text;

                const typeNames = Array.from(selectedTypes).map(checkbox => {
                    const typeInfo = getBillingTypeInfo(checkbox.value);
                    return typeInfo.name;
                }).join(', ');

                document.getElementById('previewTenant').textContent = selectedTenant;
                document.getElementById('previewTypes').textContent = typeNames;
                document.getElementById('previewAmount').textContent = '₱' + parseFloat(totalAmount).toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2
                    });
                document.getElementById('previewDueDate').textContent = new Date(dueDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                let breakdownHtml =
                    '<div class="mt-4 border-t border-blue-200 pt-3"><h5 class="font-medium text-blue-900 mb-2">Breakdown:</h5><div class="space-y-1 text-sm">';

                Object.keys(billingBreakdown).forEach(type => {
                    const item = billingBreakdown[type];
                    if (item && item.amount > 0) {
                        const typeInfo = getBillingTypeInfo(type);
                        breakdownHtml += `
                    <div class="flex justify-between">
                        <span>${typeInfo.name}</span>
                        <span class="font-medium">₱${parseFloat(item.amount).toLocaleString()}</span>
                    </div>
                `;
                    }
                });

                if (selectedOrders.length > 0) {
                    let cafeTotal = 0;
                    selectedOrders.forEach(orderId => {
                        const checkbox = document.querySelector(`input[data-order-id="${orderId}"]`);
                        if (checkbox) {
                            cafeTotal += parseFloat(checkbox.dataset.amount) || 0;
                        }
                    });

                    breakdownHtml += `
                <div class="flex justify-between">
                    <span>Cafe Orders (${selectedOrders.length})</span>
                    <span class="font-medium">₱${cafeTotal.toLocaleString()}</span>
                </div>
            `;
                }

                breakdownHtml += '</div></div>';
                document.getElementById('previewBreakdown').innerHTML = breakdownHtml;

                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        function generateBillingItems() {
            const selectedTypes = document.querySelectorAll('.billing-type-checkbox:checked');
            const billingItems = document.getElementById('billingItems');

            billingItems.innerHTML = '';

            selectedTypes.forEach(checkbox => {
                const type = checkbox.value;
                const typeInfo = getBillingTypeInfo(type);

                const itemDiv = document.createElement('div');
                itemDiv.className = 'border border-gray-200 rounded-lg p-4';

                const isReadonly = (type === 'rent' || type === 'cafe') ? 'readonly' : '';
                const readonlyClass = (type === 'rent' || type === 'cafe') ? 'bg-gray-50' : '';

                itemDiv.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <i class="${typeInfo.icon} ${typeInfo.color} text-lg"></i>
                    <h4 class="font-medium text-gray-900">${typeInfo.name}</h4>
                </div>
                <button type="button" onclick="removeBillingType('${type}')"
                        class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount (₱)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">₱</span>
                        </div>
                        <input type="number" id="amount_${type}" name="amounts[${type}]"
                               step="0.01" min="0" placeholder="0.00" ${isReadonly}
                               class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm ${readonlyClass}"
                               onchange="updateItemAmount('${type}')">
                    </div>
                    ${type === 'cafe' ? '<p class="mt-1 text-xs text-gray-500">Amount calculated from selected orders</p>' : ''}
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" id="desc_${type}" name="descriptions[${type}]"
                           placeholder="${typeInfo.placeholder}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           onchange="updateItemDescription('${type}')">
                </div>
            </div>
        `;

                billingItems.appendChild(itemDiv);

                if (type === 'rent') {
                    updateRentAmount();
                } else if (type === 'cafe') {

                    billingBreakdown[type] = {
                        amount: 0,
                        description: 'Cafe orders'
                    };

                    if (selectedOrders.length > 0) {
                        updateSelectedOrders();
                    }
                } else {
                    billingBreakdown[type] = {
                        amount: 0,
                        description: ''
                    };
                }
            });
        }


        function getBillingTypeInfo(type) {
            const typeMap = {
                'rent': {
                    name: 'Monthly Rent',
                    icon: 'fas fa-home',
                    color: 'text-blue-600',
                    placeholder: 'Monthly rent payment'
                },

                'maintenance': {
                    name: 'Maintenance',
                    icon: 'fas fa-tools',
                    color: 'text-orange-600',
                    placeholder: 'Repair and maintenance costs'
                },
                'cafe': {
                    name: 'Cafe Orders',
                    icon: 'fas fa-coffee',
                    color: 'text-brown-600',
                    placeholder: 'Food and beverage orders'
                },

                'other': {
                    name: 'Other',
                    icon: 'fas fa-ellipsis-h',
                    color: 'text-gray-600',
                    placeholder: 'Miscellaneous charges'
                }
            };
            return typeMap[type] || typeMap['other'];
        }

        function updateRentAmount() {
            const tenantSelect = document.getElementById('tenant_id');
            const selectedOption = tenantSelect.selectedOptions[0];
            const rentAmountField = document.getElementById('amount_rent');
            const rentDescField = document.getElementById('desc_rent');

            if (selectedOption && selectedOption.value && rentAmountField) {
                const monthlyRent = selectedOption.dataset.monthlyRent;
                if (monthlyRent && monthlyRent > 0) {
                    rentAmountField.value = monthlyRent;
                    const currentMonth = new Date().toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long'
                    });
                    if (rentDescField) {
                        rentDescField.value = `Monthly rent for ${currentMonth}`;
                    }
                    billingBreakdown['rent'] = {
                        amount: parseFloat(monthlyRent),
                        description: `Monthly rent for ${currentMonth}`
                    };
                    updateTotalAmount();
                }
            }
        }

        function updateItemAmount(type) {
            const amountField = document.getElementById(`amount_${type}`);
            const amount = parseFloat(amountField.value) || 0;

            if (!billingBreakdown[type]) {
                billingBreakdown[type] = {
                    amount: 0,
                    description: ''
                };
            }
            billingBreakdown[type].amount = amount;

            updateTotalAmount();
            updatePreview();
        }

        function updateItemDescription(type) {
            const descField = document.getElementById(`desc_${type}`);
            const description = descField.value;

            if (!billingBreakdown[type]) {
                billingBreakdown[type] = {
                    amount: 0,
                    description: ''
                };
            }
            billingBreakdown[type].description = description;

            updateDescription();
            updatePreview();
        }

        function removeBillingType(type) {
            const checkbox = document.getElementById(`type_${type}`);
            checkbox.checked = false;
            delete billingBreakdown[type];
            updateBillingTypes();
        }

        function updateTotalAmount() {
            let total = 0;


            Object.values(billingBreakdown).forEach(item => {
                total += item.amount || 0;
            });

            if (selectedOrders.length > 0) {
                selectedOrders.forEach(orderId => {
                    const checkbox = document.querySelector(`input[data-order-id="${orderId}"]`);
                    if (checkbox) {
                        total += parseFloat(checkbox.dataset.amount) || 0;
                    }
                });
            }

            totalAmount = total;
            document.getElementById('total_amount').value = total.toFixed(2);

            document.getElementById('billing_breakdown').value = JSON.stringify(billingBreakdown);
        }

        function updateDescription() {
            const selectedTypes = Array.from(document.querySelectorAll('.billing-type-checkbox:checked'))
                .map(cb => cb.value);

            let descriptions = [];

            selectedTypes.forEach(type => {
                const typeInfo = getBillingTypeInfo(type);
                if (billingBreakdown[type] && billingBreakdown[type].description) {
                    descriptions.push(billingBreakdown[type].description);
                } else {
                    descriptions.push(typeInfo.name);
                }
            });

            if (selectedOrders.length > 0) {
                if (selectedOrders.length === 1) {
                    descriptions.push(`Cafe order #${selectedOrders[0]}`);
                } else {
                    descriptions.push(`${selectedOrders.length} cafe orders`);
                }
            }

            const descriptionField = document.getElementById('description');
            if (descriptions.length > 0) {
                descriptionField.value = `Consolidated bill: ${descriptions.join(', ')}`;
            }
        }

        async function loadCafeOrders(tenantId) {
            if (!tenantId) return;

            const container = document.getElementById('cafeOrdersContainer');
            container.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading orders...</div>';

            try {
                const response = await fetch(`/staff/tenant/${tenantId}/unpaid-orders`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const orders = await response.json();
                displayCafeOrders(orders);

                selectedOrders = [];
                updateSelectedOrders();

            } catch (error) {
                console.error('Error loading cafe orders:', error);
                container.innerHTML = `
            <div class="text-center py-4 text-red-600">
                <i class="fas fa-exclamation-triangle mb-2"></i>
                <p>Error loading orders: ${error.message}</p>
                <button onclick="loadCafeOrders(${tenantId})" class="mt-2 text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                    Try Again
                </button>
            </div>
        `;
            }
        }

        function displayCafeOrders(orders) {
            const container = document.getElementById('cafeOrdersContainer');

            if (orders.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-3 text-green-400"></i>
                        <p>No unpaid cafe orders found for this tenant</p>
                    </div>
                `;
                return;
            }

            let ordersHtml = '<div class="space-y-3">';

            orders.forEach(order => {
                ordersHtml += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox"
                                       id="order_${order.id}"
                                       class="order-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       data-order-id="${order.id}"
                                       data-amount="${order.amount}"
                                       onchange="updateSelectedOrders()">
                                <label for="order_${order.id}" class="cursor-pointer">
                                    <div class="font-medium text-gray-900">Order #${order.id}</div>
                                    <div class="text-sm text-gray-500">${order.created_at}</div>
                                    <div class="text-xs text-gray-400">${order.items}</div>
                                </label>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-green-600">₱${parseFloat(order.amount).toLocaleString()}</div>
                                <div class="text-xs text-gray-500">${order.quantity} items</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            ordersHtml += `
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="selectAllOrders()"
                                class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                            Select All
                        </button>
                        <button type="button" onclick="deselectAllOrders()"
                                class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">
                            Deselect All
                        </button>
                        <div id="cafeOrdersSummary" class="ml-4 text-sm text-gray-600">
                            <span id="cafeSelectedCount">0</span> orders selected -
                            <span class="font-semibold">₱<span id="cafeSelectedTotal">0.00</span></span>
                        </div>
                    </div>
                </div>
            </div>`;

            container.innerHTML = ordersHtml;
        }

        function selectAllOrders() {
            document.querySelectorAll('.order-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedOrders();
        }

        function deselectAllOrders() {
            document.querySelectorAll('.order-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedOrders();
        }

        function updateSelectedOrders() {
            selectedOrders = [];
            let cafeTotal = 0;

            document.querySelectorAll('.order-checkbox:checked').forEach(checkbox => {
                const orderId = checkbox.dataset.orderId;
                const amount = parseFloat(checkbox.dataset.amount);

                selectedOrders.push(orderId);
                cafeTotal += amount;
            });


            const cafeSelectedCount = document.getElementById('cafeSelectedCount');
            const cafeSelectedTotal = document.getElementById('cafeSelectedTotal');

            if (cafeSelectedCount) {
                cafeSelectedCount.textContent = selectedOrders.length;
            }
            if (cafeSelectedTotal) {
                cafeSelectedTotal.textContent = cafeTotal.toFixed(2);
            }


            const cafeAmountField = document.getElementById('amount_cafe');
            if (cafeAmountField) {
                cafeAmountField.value = cafeTotal.toFixed(2);

                if (!billingBreakdown['cafe']) {
                    billingBreakdown['cafe'] = {
                        amount: 0,
                        description: ''
                    };
                }
                billingBreakdown['cafe'].amount = cafeTotal;

                if (selectedOrders.length > 0) {
                    const cafeDescField = document.getElementById('desc_cafe');
                    if (cafeDescField) {
                        const orderText = selectedOrders.length === 1 ? 'order' : 'orders';
                        cafeDescField.value = `${selectedOrders.length} cafe ${orderText}`;
                        billingBreakdown['cafe'].description = `${selectedOrders.length} cafe ${orderText}`;
                    }
                }
            }

            document.getElementById('selected_orders').value = JSON.stringify(selectedOrders);
            updateTotalAmount();
            updateDescription();
            updatePreview();
        }

        function previewBill() {
            const tenantSelect = document.getElementById('tenant_id');
            const selectedType = document.querySelector('.billing-type-radio:checked');
            const totalAmount = document.getElementById('total_amount').value;
            const dueDate = document.getElementById('due_date').value;
            const preview = document.getElementById('billPreview');

            if (tenantSelect.value && selectedType && totalAmount && dueDate) {
                const selectedTenant = tenantSelect.selectedOptions[0].text;
                const typeInfo = getBillingTypeInfo(selectedType.value);

                document.getElementById('previewTenant').textContent = selectedTenant;
                document.getElementById('previewTypes').textContent = typeInfo.name;
                document.getElementById('previewAmount').textContent = '₱' + parseFloat(totalAmount).toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2
                    });
                document.getElementById('previewDueDate').textContent = new Date(dueDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Generate breakdown for preview
                let breakdownHtml =
                    '<div class="mt-4 border-t border-blue-200 pt-3"><h5 class="font-medium text-blue-900 mb-2">Details:</h5><div class="space-y-1 text-sm">';

                breakdownHtml += `
                    <div class="flex justify-between">
                        <span>${typeInfo.name}</span>
                        <span class="font-medium">₱${parseFloat(totalAmount).toLocaleString()}</span>
                    </div>
                `;

                if (selectedType.value === 'cafe' && selectedOrders.length > 0) {
                    breakdownHtml += `
                        <div class="mt-2 text-xs text-blue-600">
                            Includes ${selectedOrders.length} cafe order(s): #${selectedOrders.join(', #')}
                        </div>
                    `;
                }

                breakdownHtml += '</div></div>';
                document.getElementById('previewBreakdown').innerHTML = breakdownHtml;

                preview.classList.remove('hidden');
                preview.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                alert('Please fill in all required fields before previewing.');
            }
        }


        document.getElementById('createBillForm').addEventListener('submit', function(e) {

            const selectedTypes = document.querySelectorAll('.billing-type-checkbox:checked');
            const totalAmount = parseFloat(document.getElementById('total_amount').value);

            if (selectedTypes.length === 0) {
                e.preventDefault();
                alert('Please select at least one billing type.');
                return false;
            }

            if (totalAmount <= 0) {
                e.preventDefault();
                alert('Amount must be greater than zero.');
                return false;
            }


            const cafeSelected = document.getElementById('type_cafe').checked;
            if (cafeSelected && selectedOrders.length === 0) {
                e.preventDefault();
                alert('Please select at least one cafe order for cafe billing.');
                return false;
            }

            const dueDate = new Date(document.getElementById('due_date').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (dueDate <= today) {
                e.preventDefault();
                alert('Due date must be in the future.');
                return false;
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('due_date').min = tomorrow.toISOString().split('T')[0];


            @if (old('billing_type'))
                const oldType = '{{ old('billing_type') }}';
                const oldRadio = document.getElementById(`type_${oldType}`);
                if (oldRadio) {
                    oldRadio.checked = true;
                    updateBillingType();
                }
            @endif


            const style = document.createElement('style');
            style.textContent = `
                input[name="billing_type"]:checked + .billing-type-label {
                    border-color: #3B82F6 !important;
                    background-color: #EFF6FF !important;
                }
                .billing-type-label:hover {
                    border-color: #93C5FD !important;
                }
                .text-brown-600 {
                    color: #92400e;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endsection
