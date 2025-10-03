@extends('layouts.app')

@section('title', 'Add New Staff')
@section('page-title', 'Add New Staff')

@section('sidebar-nav')
    <a href="{{ route('owner.dashboard') }}"
        class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md mb-1">
        <i class="fas fa-tachometer-alt mr-3"></i>
        Dashboard
    </a>
    <a href="{{ route('owner.staff.index') }}"
        class="bg-blue-100 text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md mb-1">
        <i class="fas fa-users mr-3"></i>
        Staff Management
    </a>
    <a href="{{ route('owner.tenants.index') }}"
        class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md mb-1">
        <i class="fas fa-user-friends mr-3"></i>
        Tenants
    </a>
    <a href="{{ route('owner.apartments.index') }}"
        class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md mb-1">
        <i class="fas fa-building mr-3"></i>
        Apartments
    </a>
    <a href="{{ route('owner.billing.index') }}"
        class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md mb-1">
        <i class="fas fa-file-invoice-dollar mr-3"></i>
        Billing
    </a>
    <a href="{{ route('owner.reports') }}"
        class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md mb-1">
        <i class="fas fa-chart-bar mr-3"></i>
        Reports
    </a>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('owner.staff.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-users mr-1"></i>
                                Staff Management
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-500">Add New Staff</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>Add New Staff Member
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Create a new staff account for cafe and apartment management
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('owner.staff.store') }}" method="POST">
                @csrf

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                        <!-- Name -->
                        <div class="sm:col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-1"></i>Full Name
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                                    placeholder="Enter full name">
                            </div>
                            @error('name')
                            <p id="name-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope mr-1"></i>Email Address
                            </label>
                            <div class="mt-1">
                                <input type="text" name="email" id="email" value="{{ old('email') }}" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror"
                                    placeholder="Enter email address">
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
                                    value="{{ old('contact_number') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('contact_number') border-red-500 @enderror"
                                    placeholder="09XXXXXXXXX">
                            </div>
                            @error('contact_number')
                            <p id="contact-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="sm:col-span-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-1"></i>Password
                            </label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror"
                                    placeholder="Enter password">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="sm:col-span-1">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-1"></i>Confirm Password
                            </label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Confirm password">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="sm:col-span-1">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-toggle-on mr-1"></i>Status
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

                    <!-- Additional Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-400 mr-2"></i>Additional Information
                        </h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Staff Account Details</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Staff members can manage maintenance requests and cafe orders</li>
                                            <li>They will receive login credentials via email</li>
                                            <li>Default role will be set to "staff" automatically</li>
                                            <li>Account can be activated/deactivated as needed</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('owner.staff.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create Staff Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("name");
    const nameError = document.getElementById("name-error");

    nameInput.addEventListener("input", function () {
        const regex = /^[a-zA-Z\s]*$/; // only letters + spaces allowed
        if (!regex.test(this.value)) {
            nameError.textContent = "Only letters and spaces are allowed.";
            this.classList.add("border-red-500");
        } else {
            nameError.textContent = "";
            this.classList.remove("border-red-500");
        }
    });
});

document.getElementById("contact_number").addEventListener("input", function () {
    const regex = /^09\d{0,9}$/; // allow typing gradually, but must start with 09
    const error = document.getElementById("contact-error");

    if (!regex.test(this.value)) {
        error.textContent = "Must start with 09 and contain only numbers.";
        this.classList.add("border-red-500");
    } else {
        error.textContent = "";
        this.classList.remove("border-red-500");
    }
});

document.getElementById("password").addEventListener("input", function () {
    const error = document.getElementById("password-error");

    if (this.value.length < 8) {
        error.textContent = "Password must be at least 8 characters.";
        this.classList.add("border-red-500");
    } else {
        error.textContent = "";
        this.classList.remove("border-red-500");
    }
});
</script>


@endsection
