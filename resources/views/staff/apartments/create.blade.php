@extends('layouts.app')

@section('title', 'Add New Apartment')
@section('page-title', 'Add New Apartment')


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
                                <span class="text-gray-500">Add New Apartment</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    <i class="fas fa-plus text-blue-600 mr-2"></i>Add New Apartment
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Create a new apartment unit with all the details and amenities
                </p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('staff.apartments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="px-4 py-5 sm:p-6">

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-400 mr-2"></i>Basic Information
                        </h3>
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">

                            <div class="sm:col-span-1">
                                <label for="apartment_number" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-door-open mr-1"></i>Apartment Number *
                                </label>
                                <div class="mt-1">
                                    <input type="text" name="apartment_number" id="apartment_number"
                                        value="{{ old('apartment_number') }}" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('apartment_number') border-red-500 @enderror"
                                        placeholder="e.g., 101, 202, PH">
                                </div>
                                @error('apartment_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-1">
                                <label for="apartment_type" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-bed mr-1"></i>Apartment Type *
                                </label>
                                <div class="mt-1">
                                    <select name="apartment_type" id="apartment_type" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('apartment_type') border-red-500 @enderror">
                                        <option value="">Select apartment type</option>
                                        <option value="studio" {{ old('apartment_type') == 'studio' ? 'selected' : '' }}>
                                            Studio</option>
                                        <option value="1br" {{ old('apartment_type') == '1br' ? 'selected' : '' }}>1
                                            Bedroom</option>
                                        <option value="2br" {{ old('apartment_type') == '2br' ? 'selected' : '' }}>2
                                            Bedroom</option>
                                        <option value="3br" {{ old('apartment_type') == '3br' ? 'selected' : '' }}>3
                                            Bedroom</option>
                                        <option value="penthouse"
                                            {{ old('apartment_type') == 'penthouse' ? 'selected' : '' }}>Penthouse</option>
                                    </select>
                                </div>
                                @error('apartment_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="sm:col-span-1">
                                <label for="price" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-peso-sign mr-1"></i>Monthly Rent (₱) *
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="price" id="price" value="{{ old('price') }}"
                                        required step="0.01" min="0"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('price') border-red-500 @enderror"
                                        placeholder="15000.00">
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="sm:col-span-1">
                                <label for="floor_number" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-layer-group mr-1"></i>Floor Number
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="floor_number" id="floor_number"
                                        value="{{ old('floor_number') }}" min="1" max="50"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('floor_number') border-red-500 @enderror"
                                        placeholder="1">
                                </div>
                                @error('floor_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="sm:col-span-1">
                                <label for="size_sqm" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-ruler-combined mr-1"></i>Size (sqm)
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="size_sqm" id="size_sqm" value="{{ old('size_sqm') }}"
                                        step="0.01" min="10"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('size_sqm') border-red-500 @enderror"
                                        placeholder="35.5">
                                </div>
                                @error('size_sqm')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="sm:col-span-1">
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-toggle-on mr-1"></i>Status
                                </label>
                                <div class="mt-1">
                                    <select name="status" id="status"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="available"
                                            {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available
                                        </option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>
                                            Occupied</option>
                                        <option value="maintenance"
                                            {{ old('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-file-alt text-gray-400 mr-2"></i>Description
                        </h3>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Apartment Description
                            </label>
                            <div class="mt-1">
                                <textarea name="description" id="description" rows="4"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror"
                                    placeholder="Describe the apartment features, layout, and any special characteristics...">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-star text-gray-400 mr-2"></i>Amenities & Features
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="wifi" id="amenity_wifi"
                                    {{ in_array('wifi', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_wifi" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-wifi mr-1"></i>WiFi
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="ac" id="amenity_ac"
                                    {{ in_array('ac', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_ac" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-snowflake mr-1"></i>Air Conditioning
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="parking" id="amenity_parking"
                                    {{ in_array('parking', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_parking" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-car mr-1"></i>Parking
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="security" id="amenity_security"
                                    {{ in_array('security', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_security" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-shield-alt mr-1"></i>Security
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="elevator" id="amenity_elevator"
                                    {{ in_array('elevator', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_elevator" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-arrows-alt-v mr-1"></i>Elevator
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="furnished" id="amenity_furnished"
                                    {{ in_array('furnished', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_furnished" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-couch mr-1"></i>Furnished
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="balcony" id="amenity_balcony"
                                    {{ in_array('balcony', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_balcony" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-home mr-1"></i>Balcony
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="kitchen" id="amenity_kitchen"
                                    {{ in_array('kitchen', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_kitchen" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-utensils mr-1"></i>Full Kitchen
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="laundry" id="amenity_laundry"
                                    {{ in_array('laundry', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_laundry" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-tshirt mr-1"></i>Laundry Area
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="gym" id="amenity_gym"
                                    {{ in_array('gym', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_gym" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-dumbbell mr-1"></i>Gym Access
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="pool" id="amenity_pool"
                                    {{ in_array('pool', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_pool" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-swimmer mr-1"></i>Swimming Pool
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="city_view" id="amenity_city_view"
                                    {{ in_array('city_view', old('amenities', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="amenity_city_view" class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-city mr-1"></i>City View
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-calculator text-gray-400 mr-2"></i>Rent Calculation Helper
                        </h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-blue-700">Base Rate per sqm</label>
                                    <input type="number" id="base_rate" value="400"
                                        class="mt-1 block w-full text-sm border-blue-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-700">Size (sqm)</label>
                                    <input type="number" id="calc_size" readonly
                                        class="mt-1 block w-full text-sm border-blue-300 rounded-md bg-blue-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-blue-700">Suggested Rent</label>
                                    <div class="mt-1 p-2 bg-blue-100 rounded-md text-sm font-bold text-blue-900"
                                        id="suggested_rent">
                                        ₱0.00
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="calculateRent()"
                                class="mt-3 text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                Calculate & Apply
                            </button>
                        </div>
                    </div>


                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle text-gray-400 mr-2"></i>Important Information
                        </h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">After Creating Apartment:</h4>
                                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                                        <li>Apartment will be available for tenant assignment</li>
                                        <li>Can be modified later if needed</li>
                                        <li>Rent amount can be adjusted per tenant</li>
                                        <li>Amenities list can be updated anytime</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Recommended:</h4>
                                    <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                                        <li>Use clear apartment numbering system</li>
                                        <li>Set competitive rent prices</li>
                                        <li>Include high-demand amenities</li>
                                        <li>Add detailed descriptions for marketing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('staff.apartments.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create Apartment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('size_sqm').addEventListener('input', function() {
            document.getElementById('calc_size').value = this.value;
            calculateRent();
        });

        function calculateRent() {
            const baseRate = parseFloat(document.getElementById('base_rate').value) || 400;
            const size = parseFloat(document.getElementById('calc_size').value) || 0;
            const suggestedRent = baseRate * size;

            document.getElementById('suggested_rent').textContent = '₱' + suggestedRent.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }


        function applyCalculatedRent() {
            const suggestedText = document.getElementById('suggested_rent').textContent;
            const amount = suggestedText.replace('₱', '').replace(/,/g, '');
            document.getElementById('price').value = amount;
        }


        document.querySelector('button[onclick="calculateRent()"]').setAttribute('onclick',
            'calculateRent(); applyCalculatedRent();');
    </script>
@endsection
