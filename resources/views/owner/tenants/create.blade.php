@extends('layouts.app')

@section('title', 'Add New Tenant')
@section('page-title', 'Add New Tenant')



@section('content')
    <div class="space-y-6">

        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('owner.tenants.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-user-friends mr-1"></i>
                                Tenant Management
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-500">Add New Tenant</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>Add New Tenant
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Create a new tenant account and assign them to an apartment
                </p>
            </div>
        </div>


        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('owner.tenants.store') }}" method="POST">
                @csrf

                <div class="px-4 py-5 sm:p-6">

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-user text-gray-400 mr-2"></i>Personal Information
                        </h3>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">

                            <div class="sm:col-span-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user mr-1"></i>Full Name *
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                                        placeholder="Enter tenant's full name">
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-1">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-envelope mr-1"></i>Email Address *
                                </label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror"
                                        placeholder="Enter email address">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="sm:col-span-1">
                                <label for="contact_number" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-phone mr-1"></i>Contact Number
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="contact_number" id="contact_number"
                                        value="{{ old('contact_number') }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('contact_number') border-red-500 @enderror"
                                        placeholder="09XXXXXXXXX">
                                </div>
                                @error('contact_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="sm:col-span-1">
                                <label for="is_active" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-toggle-on mr-1"></i>Account Status
                                </label>
                                <div class="mt-1">
                                    <select name="is_active" id="is_active"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>Account Security
                        </h3>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">

                            <div class="sm:col-span-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-1"></i>Password *
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="password" id="password" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror"
                                        placeholder="Enter secure password">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-1">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-1"></i>Confirm Password *
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Confirm password">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-building text-gray-400 mr-2"></i>Apartment Assignment (Optional)
                        </h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        You can assign an apartment to this tenant now, or do it later from the apartment
                                        management section.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">

                            <div class="sm:col-span-1">
                                <label for="apartment_id" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-home mr-1"></i>Select Apartment
                                </label>
                                <div class="mt-1">
                                    <select name="apartment_id" id="apartment_id"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">No apartment assigned</option>

                                        <option value="1">Apartment 101 - ₱15,000/month</option>
                                        <option value="2">Apartment 102 - ₱15,000/month</option>
                                        <option value="3">Apartment 201 - ₱18,000/month</option>
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Only available apartments are shown</p>
                            </div>


                            <div class="sm:col-span-1">
                                <label for="monthly_rent" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-peso-sign mr-1"></i>Monthly Rent (₱)
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="monthly_rent" id="monthly_rent"
                                        value="{{ old('monthly_rent') }}" step="0.01" min="0"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="15000.00">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Will be auto-filled when apartment is selected</p>
                            </div>


                            <div class="sm:col-span-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-calendar mr-1"></i>Lease Start Date
                                </label>
                                <div class="mt-1">
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ old('start_date', date('Y-m-d')) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>


                            <div class="sm:col-span-1">
                                <label for="security_deposit" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-shield-alt mr-1"></i>Security Deposit (₱)
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="security_deposit" id="security_deposit"
                                        value="{{ old('security_deposit') }}" step="0.01" min="0"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="15000.00">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Usually equivalent to 1 month rent</p>
                            </div>
                        </div>
                    </div>


                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-400 mr-2"></i>Important Information
                        </h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Tenant Account Features:</h4>
                                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                                        <li>Access to personal dashboard</li>
                                        <li>Online rent payment system</li>
                                        <li>Maintenance request submission</li>
                                        <li>Cafe ordering system</li>
                                        <li>Billing and payment history</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">After Creation:</h4>
                                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                                        <li>Login credentials will be provided</li>
                                        <li>Account can be activated/deactivated</li>
                                        <li>Apartment assignment can be changed</li>
                                        <li>Billing will be automatically generated</li>
                                        <li>All activities will be tracked</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('owner.tenants.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create Tenant Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('apartment_id').addEventListener('change', function() {
            const select = this;
            const rentInput = document.getElementById('monthly_rent');
            const securityInput = document.getElementById('security_deposit');

            if (select.value) {

                const optionText = select.options[select.selectedIndex].text;
                const rentMatch = optionText.match(/₱([\d,]+)/);

                if (rentMatch) {
                    const rentAmount = rentMatch[1].replace(',', '');
                    rentInput.value = rentAmount;
                    securityInput.value = rentAmount;
                }
            } else {
                rentInput.value = '';
                securityInput.value = '';
            }
        });
    </script>
@endsection
