@extends('layouts.app')

@section('title', 'Assign Tenant')
@section('page-title', 'Assign Tenant')



@section('content')
    <div class="space-y-6">

        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('staff.apartments.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-building mr-1"></i>
                                Apartment Management
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-500">Assign Tenant</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    <i class="fas fa-user-plus text-green-600 mr-2"></i>Assign Tenant to Apartment
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Select a tenant to assign to {{ $apartment->apartment_number }}
                </p>
            </div>
        </div>


        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-home text-gray-400 mr-2"></i>Apartment Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $apartment->apartment_number }}</h4>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Available
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-bed mr-2 w-4"></i>
                                <span>{{ ucfirst(str_replace(['_', 'br'], [' ', ' Bedroom'], $apartment->apartment_type)) }}</span>
                            </div>
                            @if ($apartment->floor_number)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-layer-group mr-2 w-4"></i>
                                    <span>Floor {{ $apartment->floor_number }}</span>
                                </div>
                            @endif
                            @if ($apartment->size_sqm)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-ruler-combined mr-2 w-4"></i>
                                    <span>{{ $apartment->size_sqm }} sqm</span>
                                </div>
                            @endif
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-peso-sign mr-2 w-4"></i>
                                <span class="font-semibold">₱{{ number_format($apartment->price, 0) }}/month</span>
                            </div>
                        </div>
                        @if ($apartment->amenities && count($apartment->amenities) > 0)
                            <div class="mt-4">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Amenities:</h5>
                                <div class="flex flex-wrap gap-1">
                                    @foreach (array_slice($apartment->amenities, 0, 4) as $amenity)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                            @if ($amenity == 'wifi')
                                                <i class="fas fa-wifi mr-1"></i>WiFi
                                            @elseif($amenity == 'ac')
                                                <i class="fas fa-snowflake mr-1"></i>AC
                                            @elseif($amenity == 'parking')
                                                <i class="fas fa-car mr-1"></i>Parking
                                            @else
                                                <i class="fas fa-check mr-1"></i>{{ ucfirst($amenity) }}
                                            @endif
                                        </span>
                                    @endforeach
                                    @if (count($apartment->amenities) > 4)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-200 text-gray-600">
                                            +{{ count($apartment->amenities) - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h5 class="text-sm font-medium text-blue-900 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>Assignment Information
                        </h5>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>• Tenant will be assigned immediately</li>
                            <li>• Apartment status will change to "Occupied"</li>
                            <li>• Lease start date will be set to today</li>
                            <li>• Monthly billing will be automatically generated</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('staff.apartments.assign.store', $apartment->id) }}" method="POST">
                @csrf

                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">
                        <i class="fas fa-users text-gray-400 mr-2"></i>Select Tenant
                    </h3>

                    @if ($availableTenants->count() > 0)
                        <div class="space-y-4">
                            @foreach ($availableTenants as $tenant)
                                <div class="relative">
                                    <input type="radio" name="tenant_id" value="{{ $tenant->id }}"
                                        id="tenant_{{ $tenant->id }}" class="sr-only peer" required>
                                    <label for="tenant_{{ $tenant->id }}"
                                        class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-colors">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $tenant->name }}</h4>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>Available
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $tenant->email }}</p>
                                                @if ($tenant->contact_number)
                                                    <p class="text-sm text-gray-500">
                                                        <i class="fas fa-phone mr-1"></i>{{ $tenant->contact_number }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">
                                                    <i class="fas fa-calendar mr-1"></i>Registered
                                                    {{ $tenant->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 relative">
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div
                                                            class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-file-contract text-gray-400 mr-2"></i>Lease Details
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="lease_start" class="block text-sm font-medium text-gray-700">
                                        Lease Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="lease_start" id="lease_start"
                                        value="{{ old('lease_start', date('Y-m-d')) }}" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('lease_start')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="lease_duration" class="block text-sm font-medium text-gray-700">
                                        Lease Duration (months) <span class="text-red-500">*</span>
                                    </label>
                                    <select name="lease_duration" id="lease_duration" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Select duration</option>
                                        <option value="6" {{ old('lease_duration') == '6' ? 'selected' : '' }}>6
                                            months</option>
                                        <option value="12"
                                            {{ old('lease_duration', '12') == '12' ? 'selected' : '' }}>12 months</option>
                                        <option value="24" {{ old('lease_duration') == '24' ? 'selected' : '' }}>24
                                            months</option>
                                        <option value="36" {{ old('lease_duration') == '36' ? 'selected' : '' }}>36
                                            months</option>
                                    </select>
                                    @error('lease_duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="monthly_rent" class="block text-sm font-medium text-gray-700">
                                        Monthly Rent (₱) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="monthly_rent" id="monthly_rent"
                                        value="{{ old('monthly_rent', $apartment->price) }}" required step="0.01"
                                        min="0"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('monthly_rent')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Default apartment rent is
                                        ₱{{ number_format($apartment->price, 0) }}</p>
                                </div>

                                <div>
                                    <label for="security_deposit" class="block text-sm font-medium text-gray-700">
                                        Security Deposit (₱) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="security_deposit" id="security_deposit"
                                        value="{{ old('security_deposit', $apartment->price * 2) }}" required
                                        step="0.01" min="0"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('security_deposit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Recommended: 2 months rent
                                        (₱{{ number_format($apartment->price * 2, 0) }})</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">
                                    Additional Notes
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Any special conditions or notes for this lease...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Available Tenants</h3>
                            <p class="text-gray-500 mb-4">
                                There are no unassigned tenants available to assign to this apartment.
                            </p>
                            <a href="{{ route('staff.tenants.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Tenant
                            </a>
                        </div>
                    @endif
                </div>


                @if ($availableTenants->count() > 0)
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('staff.apartments.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-user-plus mr-2"></i>
                                Assign Tenant
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        document.getElementById('monthly_rent').addEventListener('input', function() {
            const monthlyRent = parseFloat(this.value) || 0;
            const securityDeposit = monthlyRent * 2;
            document.getElementById('security_deposit').value = securityDeposit.toFixed(2);
        });
    </script>
@endsection
