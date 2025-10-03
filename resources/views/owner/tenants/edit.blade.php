@extends('layouts.app')

@section('title', 'Edit Tenant')
@section('page-title', 'Edit Tenant')


@section('content')
    <div class="space-y-6">
        <!-- Header -->
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
                                <span class="text-gray-500">Edit {{ $user->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    <i class="fas fa-user-edit text-blue-600 mr-2"></i>Edit Tenant Information
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Update tenant details and apartment assignment
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="#"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-eye mr-2"></i>
                    View Profile
                </a>
                <a href="#"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-receipt mr-2"></i>
                    View Bills
                </a>
            </div>
        </div>

        <!-- Tenant Summary Card -->
        <div class="bg-gradient-to-r from-purple-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-16 w-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                        <p class="text-purple-100">{{ $user->email }}</p>
                        @if ($user->getCurrentApartment())
                            <p class="text-purple-100">
                                <i class="fas fa-building mr-1"></i>
                                {{ $user->getCurrentApartment()->apartment_number }} -
                                ₱{{ number_format($user->getMonthlyRent(), 0) }}/month
                            </p>
                        @else
                            <p class="text-purple-100">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                No apartment assigned
                            </p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-purple-100">Account Status</div>
                    @if ($user->is_active)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('owner.tenants.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-4 py-5 sm:p-6">
                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-user text-gray-400 mr-2"></i>Personal Information
                        </h3>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                            <!-- Name -->
                            <div class="sm:col-span-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user mr-1"></i>Full Name *
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $user->name) }}" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="sm:col-span-1">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-envelope mr-1"></i>Email Address *
                                </label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $user->email) }}" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Number -->
                            <div class="sm:col-span-1">
                                <label for="contact_number" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-phone mr-1"></i>Contact Number
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="contact_number" id="contact_number"
                                        value="{{ old('contact_number', $user->contact_number) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('contact_number') border-red-500 @enderror">
                                </div>
                                @error('contact_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="sm:col-span-1">
                                <label for="is_active" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-toggle-on mr-1"></i>Account Status
                                </label>
                                <div class="mt-1">
                                    <select name="is_active" id="is_active"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>Change Password (Optional)
                        </h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Leave password fields empty if you don't want to change the current password.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                            <!-- Password -->
                            <div class="sm:col-span-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-1"></i>New Password
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="password" id="password"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror"
                                        placeholder="Enter new password">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="sm:col-span-1">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-1"></i>Confirm New Password
                                </label>
                                <div class="mt-1">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Apartment Info -->
                    @if ($user->getCurrentApartment())
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-building text-gray-400 mr-2"></i>Current Apartment Assignment
                            </h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">Apartment</div>
                                        <div class="text-lg font-bold text-blue-900">
                                            {{ $user->getCurrentApartment()->apartment_number }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">Monthly Rent</div>
                                        <div class="text-lg font-bold text-green-600">
                                            ₱{{ number_format($user->getMonthlyRent(), 0) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">Status</div>
                                        @php $currentRent = $user->currentRent; @endphp
                                        @if ($currentRent)
                                            <div class="text-lg font-bold text-green-600">
                                                {{ ucfirst($currentRent->status) }}</div>
                                            <div class="text-xs text-gray-500">Since
                                                {{ $currentRent->start_date->format('M d, Y') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="#"
                                        class="text-sm bg-white border border-gray-300 rounded-md px-3 py-2 hover:bg-gray-50">
                                        <i class="fas fa-exchange-alt mr-1"></i>Change Apartment
                                    </a>
                                    <a href="#"
                                        class="text-sm bg-white border border-gray-300 rounded-md px-3 py-2 hover:bg-gray-50">
                                        <i class="fas fa-file-contract mr-1"></i>View Lease Details
                                    </a>
                                    <a href="#"
                                        class="text-sm bg-white border border-gray-300 rounded-md px-3 py-2 hover:bg-gray-50">
                                        <i class="fas fa-receipt mr-1"></i>Payment History
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Account Statistics -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-chart-bar text-gray-400 mr-2"></i>Account Statistics
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600">{{ $user->tenantPayments()->count() }}</div>
                                <div class="text-sm text-gray-600">Total Payments</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-600">{{ $user->tenantOrders()->count() }}</div>
                                <div class="text-sm text-gray-600">Cafe Orders</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ $user->tenantMaintenanceRequests()->count() }}</div>
                                <div class="text-sm text-gray-600">Maintenance Requests</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600">
                                    ₱{{ number_format($user->getTotalOutstandingAmount(), 0) }}</div>
                                <div class="text-sm text-gray-600">Outstanding Balance</div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-400 mr-2"></i>Account Information
                        </h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Account Created:</span>
                                    <span
                                        class="text-gray-600">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Last Updated:</span>
                                    <span
                                        class="text-gray-600">{{ $user->updated_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Role:</span>
                                    <span class="text-gray-600 capitalize">{{ $user->role }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">User ID:</span>
                                    <span class="text-gray-600">#{{ $user->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                    <div class="flex justify-between">
                        <div>
                            @if ($user->is_active)
                                <button type="button"
                                    onclick="if(confirm('Are you sure you want to deactivate this tenant account?')) { document.getElementById('deactivate-form').submit(); }"
                                    class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                    <i class="fas fa-user-slash mr-2"></i>
                                    Deactivate Account
                                </button>
                            @endif
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('owner.tenants.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>
                                Update Tenant
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Hidden deactivate form -->
            @if ($user->is_active)
                <form id="deactivate-form" action="{{ route('owner.tenants.destroy', $user) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
@endsection
