@extends('layouts.app')

@section('title', 'Create Bill')
@section('page-title', 'Create New Bill')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('owner.billing.index') }}"
                            class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                            Billing
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500 font-medium">New Bill</span>
                    </li>
                </ol>
            </nav>

            <div class="mb-8">
                <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                    Create New Bill
                </h1>
                <p class="text-lg text-gray-600">
                    Generate a bill for rent, utilities, maintenance, or other charges
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">
                <form action="{{ route('owner.billing.store') }}" method="POST" id="createBillForm">
                    @csrf

                    <div class="p-8 sm:p-10 space-y-8">

                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Bill Details</h2>

                            <div class="space-y-6">
                                <div>
                                    <label for="tenant_id" class="block text-sm font-medium text-gray-900 mb-2">
                                        Tenant
                                    </label>
                                    <div class="relative">
                                        <select id="tenant_id" name="tenant_id" required onchange="updateTenantInfo()"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('tenant_id') border-red-400 @enderror">
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
                                                        - Apt
                                                        {{ $tenant->tenantRents->first()->apartment->apartment_number }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    @error('tenant_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div id="tenantInfo"
                                        class="hidden mt-4 p-4 bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl border border-blue-100/50">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                            <div>
                                                <div class="text-gray-600 mb-1">Email</div>
                                                <div id="tenantEmail" class="font-medium text-gray-900"></div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600 mb-1">Contact</div>
                                                <div id="tenantContact" class="font-medium text-gray-900"></div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600 mb-1">Apartment</div>
                                                <div id="tenantApartment" class="font-medium text-gray-900"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="billing_type" class="block text-sm font-medium text-gray-900 mb-2">
                                            Billing Type
                                        </label>
                                        <div class="relative">
                                            <select id="billing_type" name="billing_type" required
                                                onchange="updateBillingType()"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('billing_type') border-red-400 @enderror">
                                                <option value="">Select type...</option>
                                                <option value="rent"
                                                    {{ old('billing_type') == 'rent' ? 'selected' : '' }}>Monthly Rent
                                                </option>
                                                <option value="utilities"
                                                    {{ old('billing_type') == 'utilities' ? 'selected' : '' }}>Utilities
                                                </option>
                                                <option value="maintenance"
                                                    {{ old('billing_type') == 'maintenance' ? 'selected' : '' }}>
                                                    Maintenance</option>
                                                <option value="cafe"
                                                    {{ old('billing_type') == 'cafe' ? 'selected' : '' }}>Cafe Orders
                                                </option>
                                                <option value="other"
                                                    {{ old('billing_type') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                        @error('billing_type')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="amount" class="block text-sm font-medium text-gray-900 mb-2">
                                            Amount
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">₱</span>
                                            <input type="number" id="amount" name="amount" step="0.01"
                                                min="0" required value="{{ old('amount') }}"
                                                class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('amount') border-red-400 @enderror"
                                                placeholder="0.00">
                                        </div>
                                        @error('amount')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="due_date" class="block text-sm font-medium text-gray-900 mb-2">
                                            Due Date
                                        </label>
                                        <input type="date" id="due_date" name="due_date" required
                                            value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                                            min="{{ now()->format('Y-m-d') }}"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('due_date') border-red-400 @enderror">
                                        @error('due_date')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div id="billingPeriod" class="hidden">
                                        <label for="billing_month" class="block text-sm font-medium text-gray-900 mb-2">
                                            Billing Period
                                        </label>
                                        <input type="month" id="billing_month" name="billing_month"
                                            value="{{ old('billing_month', now()->format('Y-m')) }}"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                    </div>
                                </div>

                                <input type="hidden" id="rent_id" name="rent_id" value="{{ old('rent_id') }}">

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                                        Description/Notes
                                    </label>
                                    <textarea id="description" name="description" rows="4"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 resize-none @error('description') border-red-400 @enderror"
                                        placeholder="Enter bill description or additional notes...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="billPreview" class="hidden pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Bill Preview</h3>
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl p-6 border border-blue-100/50">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <div class="text-gray-600 mb-1">Tenant</div>
                                        <div id="previewTenant" class="font-semibold text-gray-900"></div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 mb-1">Type</div>
                                        <div id="previewType" class="font-semibold text-gray-900"></div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 mb-1">Amount</div>
                                        <div id="previewAmount" class="font-bold text-green-600 text-lg"></div>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 mb-1">Due Date</div>
                                        <div id="previewDueDate" class="font-semibold text-gray-900"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/80 backdrop-blur-sm px-8 sm:px-10 py-6 border-t border-gray-200/60">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                            <a href="{{ route('owner.billing.index') }}"
                                class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-200/50 transition-all duration-200">
                                Cancel
                            </a>
                            <div class="flex gap-3">
                                <button type="button" onclick="previewBill()"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200">
                                    Preview
                                </button>
                                <button type="submit"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                    Create Bill
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-8 bg-gradient-to-br from-yellow-50 to-orange-50/50 rounded-2xl p-6 border border-yellow-100/50">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Quick Tips</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span>Monthly rent bills are automatically linked to the tenant's active lease</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span>Late fees will be automatically calculated for overdue bills</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span>Bills can be edited until they are marked as paid</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
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

                const billingType = document.getElementById('billing_type').value;
                if (billingType === 'rent') {
                    const monthlyRent = selectedOption.dataset.monthlyRent;
                    if (monthlyRent && monthlyRent > 0) {
                        document.getElementById('amount').value = monthlyRent;
                    }
                }
            } else {
                tenantInfo.classList.add('hidden');
                document.getElementById('rent_id').value = '';
            }

            updatePreview();
        }

        function updateBillingType() {
            const billingType = document.getElementById('billing_type').value;
            const billingPeriod = document.getElementById('billingPeriod');
            const amountField = document.getElementById('amount');
            const descriptionField = document.getElementById('description');

            if (billingType === 'rent') {
                billingPeriod.classList.remove('hidden');

                const tenantSelect = document.getElementById('tenant_id');
                const selectedOption = tenantSelect.selectedOptions[0];
                if (selectedOption && selectedOption.value) {
                    const monthlyRent = selectedOption.dataset.monthlyRent;
                    if (monthlyRent && monthlyRent > 0) {
                        amountField.value = monthlyRent;
                    }
                }

                const currentMonth = new Date().toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long'
                });
                descriptionField.value = `Monthly rent for ${currentMonth}`;
            } else {
                billingPeriod.classList.add('hidden');

                if (descriptionField.value.includes('Monthly rent for')) {
                    descriptionField.value = '';
                }
            }

            updatePreview();
        }

        function previewBill() {
            const tenantSelect = document.getElementById('tenant_id');
            const billingType = document.getElementById('billing_type');
            const amount = document.getElementById('amount');
            const dueDate = document.getElementById('due_date');
            const preview = document.getElementById('billPreview');

            if (tenantSelect.value && billingType.value && amount.value && dueDate.value) {
                const selectedTenant = tenantSelect.selectedOptions[0].text;
                const typeLabels = {
                    'rent': 'Monthly Rent',
                    'utilities': 'Utilities',
                    'maintenance': 'Maintenance',
                    'cafe': 'Cafe Orders',
                    'other': 'Other'
                };

                document.getElementById('previewTenant').textContent = selectedTenant;
                document.getElementById('previewType').textContent = typeLabels[billingType.value] || billingType.value;
                document.getElementById('previewAmount').textContent = '₱' + parseFloat(amount.value).toLocaleString(
                    'en-US', {
                        minimumFractionDigits: 2
                    });
                document.getElementById('previewDueDate').textContent = new Date(dueDate.value).toLocaleDateString(
                    'en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                preview.classList.remove('hidden');
                preview.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } else {
                alert('Please fill in all required fields before previewing.');
            }
        }

        function updatePreview() {
            const preview = document.getElementById('billPreview');
            if (!preview.classList.contains('hidden')) {
                previewBill();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('due_date').min = tomorrow.toISOString().split('T')[0];
        });

        document.getElementById('createBillForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount').value);
            if (amount <= 0) {
                e.preventDefault();
                alert('Amount must be greater than zero.');
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
    </script>

    <style>
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
        }

        input::placeholder,
        textarea::placeholder {
            transition: opacity 0.2s ease;
        }

        input:focus::placeholder,
        textarea:focus::placeholder {
            opacity: 0.5;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 0.5;
            margin-left: 8px;
        }

        select:focus+svg {
            transform: rotate(180deg);
        }

        select+svg {
            transition: transform 0.2s ease;
        }
    </style>
@endsection
