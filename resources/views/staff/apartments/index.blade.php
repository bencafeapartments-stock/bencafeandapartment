@extends('layouts.app')

@section('title', 'Apartment Management')
@section('page-title', 'Apartment Management')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- @if (session('success'))
                <div
                    class="mb-6 bg-gradient-to-r from-green-500/10 to-emerald-500/10 backdrop-blur-xl rounded-2xl border border-green-200/50 p-4 shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-green-900">
                                {{ session('success') }}
                            </p>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()"
                            class="ml-4 w-8 h-8 bg-green-100 hover:bg-green-200 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-times text-green-600 text-sm"></i>
                        </button>
                    </div>
                </div>
            @endif -->

            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900 mb-2">Apartment Management</h1>
                        <p class="text-gray-500">Manage your apartments, track occupancy, and monitor maintenance</p>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium mb-1">Total Units</p>
                                <p class="text-3xl font-bold text-white">{{ $totalApartments ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium mb-1">Available</p>
                                <p class="text-3xl font-bold text-white">{{ $availableApartments ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium mb-1">Occupied</p>
                                <p class="text-3xl font-bold text-white">{{ $occupiedApartments ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-lg backdrop-blur-sm border border-white/20">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium mb-1">Maintenance</p>
                                <p class="text-3xl font-bold text-white">{{ $maintenanceApartments ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tools text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 mb-8">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="relative flex-1">
                            <input type="text" placeholder="Search apartments..."
                                class="w-full bg-gray-50/50 border-0 rounded-xl px-4 py-3 pl-10 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200">
                            <i
                                class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>
                        <div class="relative">
                            <select
                                class="appearance-none bg-gray-50/50 border-0 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200">
                                <option>All Status</option>
                                <option>Available</option>
                                <option>Occupied</option>
                                <option>Maintenance</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="relative">
                            <select
                                class="appearance-none bg-gray-50/50 border-0 rounded-xl px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all duration-200">
                                <option>All Types</option>
                                <option>Studio</option>
                                <option>1 Bedroom</option>
                                <option>2 Bedroom</option>
                                <option>3 Bedroom</option>
                                <option>Penthouse</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-building mr-3 text-gray-400"></i>All Apartments
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Manage your apartment units and track their status</p>
                </div>

                <div class="p-6">
                    @if (isset($apartmentsData) && count($apartmentsData) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($apartmentsData as $apartment)
                                <div
                                    class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 hover:shadow-lg transition-all duration-300 overflow-hidden group">
                                    <div class="relative">
                                        <div
                                            class="h-48 bg-gradient-to-br from-blue-100 via-blue-50 to-indigo-100 flex items-center justify-center">
                                            <div class="relative">
                                                <i
                                                    class="fas fa-home text-blue-400 text-5xl opacity-80 group-hover:scale-110 transition-transform duration-300"></i>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 left-3">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-xl
                                                @if ($apartment['status'] == 'available') bg-green-500/90 text-white ring-1 ring-green-400/50
                                                @elseif($apartment['status'] == 'occupied') bg-blue-500/90 text-white ring-1 ring-blue-400/50
                                                @else bg-orange-500/90 text-white ring-1 ring-orange-400/50 @endif">
                                                @if ($apartment['status'] == 'available')
                                                    <i class="fas fa-check-circle mr-1"></i>Available
                                                @elseif($apartment['status'] == 'occupied')
                                                    <i class="fas fa-user mr-1"></i>Occupied
                                                @else
                                                    <i class="fas fa-tools mr-1"></i>Maintenance
                                                @endif
                                            </span>
                                        </div>
                                        <div class="absolute top-3 right-3">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/90 backdrop-blur-xl text-gray-700 ring-1 ring-gray-200/50">
                                                <i
                                                    class="fas fa-clock mr-1 text-gray-400"></i>{{ \Carbon\Carbon::parse($apartment['created_at'])->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-xl font-bold text-gray-900">{{ $apartment['apartment_number'] }}
                                            </h3>
                                            @if (isset($apartment['floor_number']) && $apartment['floor_number'])
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                                    <i class="fas fa-layer-group mr-1"></i>Floor
                                                    {{ $apartment['floor_number'] }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="space-y-2.5 mb-4">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <div
                                                    class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-bed text-blue-600 text-sm"></i>
                                                </div>
                                                <span
                                                    class="font-medium">{{ ucfirst(str_replace(['_', 'br'], [' ', ' Bedroom'], $apartment['apartment_type'])) }}</span>
                                            </div>
                                            @if (isset($apartment['size_sqm']) && $apartment['size_sqm'])
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <div
                                                        class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-ruler-combined text-purple-600 text-sm"></i>
                                                    </div>
                                                    <span>{{ $apartment['size_sqm'] }} sqm</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center text-sm">
                                                <div
                                                    class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-peso-sign text-green-600 text-sm"></i>
                                                </div>
                                                <span
                                                    class="font-bold text-green-600">₱{{ number_format($apartment['price'], 0) }}<span
                                                        class="text-gray-500 font-normal">/month</span></span>
                                            </div>
                                        </div>

                                        @if (isset($apartment['description']) && $apartment['description'])
                                            <div class="mb-4">
                                                <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                                    {{ $apartment['description'] }}
                                                </p>
                                            </div>
                                        @endif

                                        @if ($apartment['status'] == 'occupied')
                                            @php
                                                $currentRent = \App\Models\Rent::where('apartment_id', $apartment['id'])
                                                    ->where('status', 'active')
                                                    ->with('tenant')
                                                    ->first();
                                            @endphp
                                            @if ($currentRent && $currentRent->tenant)
                                                <div
                                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-3 mb-4 border border-blue-100/50">
                                                    <div class="flex items-center mb-2">
                                                        <div
                                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                                                            <i class="fas fa-user text-white text-xs"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-semibold text-gray-900">
                                                                {{ $currentRent->tenant->name }}</div>
                                                            <div class="text-xs text-gray-500">Since
                                                                {{ $currentRent->start_date->format('M d, Y') }}</div>
                                                        </div>
                                                    </div>
                                                    @if ($currentRent->end_date)
                                                        <div class="text-xs text-gray-600 flex items-center">
                                                            <i class="fas fa-calendar-alt mr-1.5 text-blue-500"></i>
                                                            Lease until {{ $currentRent->end_date->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif

                                        @if (isset($apartment['amenities']) && is_array($apartment['amenities']) && count($apartment['amenities']) > 0)
                                            <div class="mb-4">
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach (array_slice($apartment['amenities'], 0, 3) as $amenity)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200/50">
                                                            @if ($amenity == 'wifi')
                                                                <i class="fas fa-wifi mr-1 text-blue-500"></i>WiFi
                                                            @elseif($amenity == 'ac')
                                                                <i class="fas fa-snowflake mr-1 text-cyan-500"></i>AC
                                                            @elseif($amenity == 'parking')
                                                                <i class="fas fa-car mr-1 text-indigo-500"></i>Parking
                                                            @elseif($amenity == 'furnished')
                                                                <i class="fas fa-couch mr-1 text-amber-500"></i>Furnished
                                                            @elseif($amenity == 'security')
                                                                <i class="fas fa-shield-alt mr-1 text-red-500"></i>Security
                                                            @elseif($amenity == 'elevator')
                                                                <i
                                                                    class="fas fa-arrows-alt-v mr-1 text-purple-500"></i>Elevator
                                                            @elseif($amenity == 'balcony')
                                                                <i class="fas fa-home mr-1 text-teal-500"></i>Balcony
                                                            @elseif($amenity == 'kitchen')
                                                                <i class="fas fa-utensils mr-1 text-orange-500"></i>Kitchen
                                                            @elseif($amenity == 'laundry')
                                                                <i class="fas fa-tshirt mr-1 text-blue-500"></i>Laundry
                                                            @elseif($amenity == 'gym')
                                                                <i class="fas fa-dumbbell mr-1 text-red-500"></i>Gym
                                                            @elseif($amenity == 'pool')
                                                                <i class="fas fa-swimmer mr-1 text-cyan-500"></i>Pool
                                                            @elseif($amenity == 'city_view')
                                                                <i class="fas fa-city mr-1 text-gray-500"></i>City View
                                                            @else
                                                                <i class="fas fa-check mr-1"></i>{{ ucfirst($amenity) }}
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                    @if (count($apartment['amenities']) > 3)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/50">
                                                            +{{ count($apartment['amenities']) - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex gap-2">
                                            <button onclick="openEditModal({{ $apartment['id'] }})"
                                                class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-center py-2.5 px-3 rounded-xl text-sm font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <button onclick="showApartmentDetails({{ $apartment['id'] }})"
                                                class="flex-1 bg-gray-100 text-gray-700 py-2.5 px-3 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-all duration-200">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            @if ($apartment['status'] == 'available')
                                                <a href="{{ route('staff.apartments.assign', $apartment['id']) }}"
                                                    class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-center py-2.5 px-3 rounded-xl text-sm font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-sm">
                                                    <i class="fas fa-user-plus mr-1"></i>Assign
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if (isset($apartmentsData) && count($apartmentsData) > 0)
                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200/50">
                                <div class="text-sm text-gray-600">
                                    Showing 1 to {{ count($apartmentsData) }} of {{ $totalApartments ?? 0 }} apartments
                                </div>
                                <div class="flex gap-2">
                                    <button
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors duration-200">
                                        <i class="fas fa-chevron-left mr-1"></i>Previous
                                    </button>
                                    <button
                                        class="px-4 py-2 bg-blue-500 text-white rounded-xl text-sm font-semibold">1</button>
                                    <button
                                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors duration-200">
                                        Next<i class="fas fa-chevron-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-16">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-building text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No apartments yet</h3>
                            <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                                Get started by Assigning Tenant for their designated Apartment.
                            </p>
                            <!-- <a href="{{ route('staff.apartments.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <i class="fas fa-plus mr-2"></i>Create Your First Apartment
                            </a> -->
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>

    <div id="apartmentModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div
            class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white/95 backdrop-blur-xl border-b border-gray-200/50 p-6 z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-building text-blue-600 mr-2"></i>Apartment Details
                    </h3>
                    <button type="button" onclick="closeApartmentModal()"
                        class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                        <i class="fas fa-times text-gray-500"></i>
                    </button>
                </div>
            </div>
            <div id="apartmentDetails" class="p-6">
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-3"></i>
                    <p class="text-gray-500 font-medium">Loading apartment details...</p>
                </div>
            </div>
        </div>
    </div>


    <div id="tenantModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 w-full max-w-md">
            <div class="flex items-center justify-between p-6 border-b border-gray-200/50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user text-blue-600 mr-2"></i>Tenant Information
                </h3>
                <button type="button" onclick="closeTenantModal()"
                    class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-150">
                    <i class="fas fa-times text-gray-500 text-sm"></i>
                </button>
            </div>
            <div id="tenantDetails" class="p-6">

            </div>
            <div class="p-6 border-t border-gray-200/50 bg-gray-50/50">
                <button type="button" onclick="closeTenantModal()"
                    class="w-full px-4 py-2.5 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
    <div id="editApartmentModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 overflow-y-auto">
        <div class="min-h-screen px-4 py-8 flex items-center justify-center">
            <div
                class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">


                <div class="sticky top-0 bg-white/95 backdrop-blur-xl border-b border-gray-200/50 px-6 py-5 z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <span>Edit Apartment <span id="modal-apartment-number"
                                        class="text-blue-600"></span></span>
                            </h2>
                            <p class="text-sm text-gray-500 mt-1 ml-13">Update apartment details, amenities, and pricing
                            </p>
                        </div>
                        <button type="button" onclick="closeEditModal()"
                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-times text-gray-500"></i>
                        </button>
                    </div>
                </div>


                <div class="flex-1 overflow-y-auto p-6">
                    <form id="editApartmentForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div
                            class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl p-6 mb-6 border border-blue-200/50">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>
                                    <label for="edit_apartment_number"
                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-door-open mr-1 text-blue-500"></i>Apartment Number *
                                    </label>
                                    <input type="text" name="apartment_number" id="edit_apartment_number" required
                                        class="w-full bg-white border-0 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200"
                                        placeholder="e.g., 101, 202, PH">
                                </div>

                                <div>
                                    <label for="edit_apartment_type"
                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-bed mr-1 text-purple-500"></i>Apartment Type *
                                    </label>
                                    <div class="relative">
                                        <select name="apartment_type" id="edit_apartment_type" required
                                            class="appearance-none w-full bg-white border-0 rounded-xl px-4 py-2.5 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200">
                                            <option value="">Select type</option>
                                            <option value="Standard">Standard</option>
                                            <option value="Ordinary">Ordinary</option>

                                        </select>
                                        <i
                                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label for="edit_price" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-peso-sign mr-1 text-green-500"></i>Monthly Rent (₱) *
                                    </label>
                                    <input type="number" name="price" id="edit_price" required step="0.01"
                                        min="0"
                                        class="w-full bg-white border-0 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200"
                                        placeholder="15000.00">
                                </div>

                                <div>
                                    <label for="edit_floor_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-layer-group mr-1 text-indigo-500"></i>Floor Number
                                    </label>
                                    <input type="number" name="floor_number" id="edit_floor_number" min="1"
                                        max="50"
                                        class="w-full bg-white border-0 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200"
                                        placeholder="1">
                                </div>

                                <div>
                                    <label for="edit_size_sqm" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-ruler-combined mr-1 text-amber-500"></i>Size (sqm)
                                    </label>
                                    <input type="number" name="size_sqm" id="edit_size_sqm" step="0.01"
                                        min="10"
                                        class="w-full bg-white border-0 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200"
                                        placeholder="35.5">
                                </div>

                                <div>
                                    <label for="edit_status" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-toggle-on mr-1 text-teal-500"></i>Status
                                    </label>
                                    <div class="relative">
                                        <select name="status" id="edit_status"
                                            class="appearance-none w-full bg-white border-0 rounded-xl px-4 py-2.5 pr-10 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200">
                                            <option value="available">Available</option>
                                            <option value="occupied">Occupied</option>
                                            <option value="maintenance">Under Maintenance</option>
                                        </select>
                                        <i
                                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50/50 rounded-2xl p-6 mb-6 border border-purple-200/50">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                                Description
                            </h3>
                            <textarea name="description" id="edit_description" rows="3"
                                class="w-full bg-white border-0 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/30 transition-all duration-200"
                                placeholder="Describe the apartment features, layout, and any special characteristics..."></textarea>
                        </div>


                        <div
                            class="bg-gradient-to-br from-amber-50 to-orange-50/50 rounded-2xl p-6 border border-amber-200/50">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-star text-white text-sm"></i>
                                </div>
                                Amenities & Features
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="amenities-container">

                                @php
                                    $amenitiesList = [
                                        ['value' => 'wifi', 'label' => 'WiFi', 'icon' => 'fa-wifi', 'color' => 'blue'],
                                        ['value' => 'ac', 'label' => 'AC', 'icon' => 'fa-snowflake', 'color' => 'cyan'],
                                    ];
                                @endphp
                                @foreach ($amenitiesList as $amenity)
                                    <label
                                        class="cursor-pointer bg-white hover:bg-{{ $amenity['color'] }}-50 border border-gray-200/50 rounded-xl p-3 transition-all duration-200 hover:shadow-md hover:scale-105">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="amenities[]" value="{{ $amenity['value'] }}"
                                                class="amenity-check w-4 h-4 text-{{ $amenity['color'] }}-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-{{ $amenity['color'] }}-500/20 transition-all duration-200">
                                            <div class="ml-2 flex items-center">
                                                <div
                                                    class="w-6 h-6 bg-{{ $amenity['color'] }}-100 rounded-lg flex items-center justify-center mr-2">
                                                    <i
                                                        class="fas {{ $amenity['icon'] }} text-{{ $amenity['color'] }}-600 text-xs"></i>
                                                </div>
                                                <span
                                                    class="text-xs font-medium text-gray-700">{{ $amenity['label'] }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>


                <div class="sticky bottom-0 bg-white/95 backdrop-blur-xl border-t border-gray-200/50 px-6 py-4">
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeEditModal()"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="button" onclick="submitEditForm()"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg shadow-blue-500/25">
                            <i class="fas fa-save mr-2"></i>Update Apartment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showApartmentDetails(apartmentId) {
                document.getElementById('apartmentModal').classList.remove('hidden');

                fetch(`/staff/apartments/${apartmentId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        const apartment = data.apartment;
                        const tenant = data.tenant;
                        const rent = data.rent;

                        let detailsHtml = `
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 backdrop-blur-sm rounded-2xl p-6 border border-blue-200/50">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                Basic Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-blue-200/30">
                                    <span class="font-medium text-gray-700 text-sm">Apartment Number:</span>
                                    <span class="text-gray-900 font-semibold">${apartment.apartment_number}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200/30">
                                    <span class="font-medium text-gray-700 text-sm">Type:</span>
                                    <span class="text-gray-900">${apartment.apartment_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200/30">
                                    <span class="font-medium text-gray-700 text-sm">Floor:</span>
                                    <span class="text-gray-900">${apartment.floor_number || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200/30">
                                    <span class="font-medium text-gray-700 text-sm">Size:</span>
                                    <span class="text-gray-900">${apartment.size_sqm ? apartment.size_sqm + ' sqm' : 'N/A'}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200/30">
                                    <span class="font-medium text-gray-700 text-sm">Base Price:</span>
                                    <span class="text-green-600 font-bold">₱${new Intl.NumberFormat().format(apartment.price)}/mo</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200/30">
                                    <span class="font-medium text-gray-700 text-sm">Status:</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        ${apartment.status === 'available' ? 'bg-green-500 text-white' :
                                          apartment.status === 'occupied' ? 'bg-blue-500 text-white' :
                                          'bg-orange-500 text-white'}">
                                        ${apartment.status.charAt(0).toUpperCase() + apartment.status.slice(1)}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium text-gray-700 text-sm">Created:</span>
                                    <span class="text-gray-900">${new Date(apartment.created_at).toLocaleDateString()}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 backdrop-blur-sm rounded-2xl p-6 border border-purple-200/50">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                Current Tenant
                            </h4>
                            ${tenant ? `
                                                                                                                                                                                        <div class="space-y-3">
                                                                                                                                                                                            <div class="flex justify-between items-center py-2 border-b border-purple-200/30">
                                                                                                                                                                                                <span class="font-medium text-gray-700 text-sm">Name:</span>
                                                                                                                                                                                                <span class="text-gray-900 font-semibold">${tenant.name}</span>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div class="flex justify-between items-center py-2 border-b border-purple-200/30">
                                                                                                                                                                                                <span class="font-medium text-gray-700 text-sm">Email:</span>
                                                                                                                                                                                                <span class="text-gray-900">${tenant.email}</span>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            ${tenant.contact_number ? `
                                    <div class="flex justify-between items-center py-2 border-b border-purple-200/30">
                                        <span class="font-medium text-gray-700 text-sm">Phone:</span>
                                        <span class="text-gray-900">${tenant.contact_number}</span>
                                    </div>
                                    ` : ''}
                                                                                                                                                                                            <div class="flex justify-between items-center py-2 border-b border-purple-200/30">
                                                                                                                                                                                                <span class="font-medium text-gray-700 text-sm">Monthly Rent:</span>
                                                                                                                                                                                                <span class="text-green-600 font-bold">₱${new Intl.NumberFormat().format(rent.monthly_rent)}</span>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div class="flex justify-between items-center py-2 border-b border-purple-200/30">
                                                                                                                                                                                                <span class="font-medium text-gray-700 text-sm">Security Deposit:</span>
                                                                                                                                                                                                <span class="text-gray-900">₱${new Intl.NumberFormat().format(rent.security_deposit || 0)}</span>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <div class="flex justify-between items-center py-2 border-b border-purple-200/30">
                                                                                                                                                                                                <span class="font-medium text-gray-700 text-sm">Lease Start:</span>
                                                                                                                                                                                                <span class="text-gray-900">${new Date(rent.start_date).toLocaleDateString()}</span>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            ${rent.end_date ? `
                                    <div class="flex justify-between items-center py-2">
                                        <span class="font-medium text-gray-700 text-sm">Lease End:</span>
                                        <span class="text-gray-900">${new Date(rent.end_date).toLocaleDateString()}</span>
                                    </div>
                                    ` : ''}
                                                                                                                                                                                        </div>
                                                                                                                                                                                    ` : `
                                                                                                                                                                                        <div class="text-center py-8">
                                                                                                                                                                                            <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-3">
                                                                                                                                                                                                <i class="fas fa-user-slash text-purple-400 text-2xl"></i>
                                                                                                                                                                                            </div>
                                                                                                                                                                                            <p class="text-gray-600 font-medium mb-1">No tenant assigned</p>
                                                                                                                                                                                            <p class="text-gray-500 text-sm mb-4">This apartment is available</p>
                                                                                                                                                                                            ${apartment.status === 'available' ? `
                                    <a href="/staff/apartments/${apartment.id}/assign"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg shadow-green-500/25">
                                        <i class="fas fa-user-plus mr-2"></i>Assign Tenant
                                    </a>
                                    ` : ''}
                                                                                                                                                                                        </div>
                                                                                                                                                                                    `}
                        </div>
                    </div>

                    ${apartment.description ? `
                                                                                                                                                                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50">
                                                                                                                                                                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                                                                                                                                                                    <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mr-2">
                                                                                                                                                                                        <i class="fas fa-align-left text-white text-sm"></i>
                                                                                                                                                                                    </div>
                                                                                                                                                                                    Description
                                                                                                                                                                                </h4>
                                                                                                                                                                                <p class="text-gray-700 leading-relaxed">${apartment.description}</p>
                                                                                                                                                                            </div>
                                                                                                                                                                            ` : ''}

                    ${apartment.amenities && apartment.amenities.length > 0 ? `
                                                                                                                                                                            <div class="bg-gradient-to-br from-amber-50 to-orange-100/50 backdrop-blur-sm rounded-2xl p-6 border border-amber-200/50">
                                                                                                                                                                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                                                                                                                                                                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-2">
                                                                                                                                                                                        <i class="fas fa-star text-white text-sm"></i>
                                                                                                                                                                                    </div>
                                                                                                                                                                                    Amenities
                                                                                                                                                                                </h4>
                                                                                                                                                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                                                                                                                                                                    ${apartment.amenities.map(amenity => {
                                                                                                                                                                                        const icons = {
                                                                                                                                                                                            'wifi': 'fa-wifi',
                                                                                                                                                                                            'ac': 'fa-snowflake',
                                                                                                                                                                                            'parking': 'fa-car',
                                                                                                                                                                                            'furnished': 'fa-couch',
                                                                                                                                                                                            'security': 'fa-shield-alt',
                                                                                                                                                                                            'elevator': 'fa-arrows-alt-v',
                                                                                                                                                                                            'balcony': 'fa-home',
                                                                                                                                                                                            'kitchen': 'fa-utensils',
                                                                                                                                                                                            'laundry': 'fa-tshirt',
                                                                                                                                                                                            'gym': 'fa-dumbbell',
                                                                                                                                                                                            'pool': 'fa-swimmer',
                                                                                                                                                                                            'city_view': 'fa-city'
                                                                                                                                                                                        };
                                                                                                                                                                                        const icon = icons[amenity] || 'fa-check';
                                                                                                                                                                                        const label = amenity.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                                                                                                                                                                        return `
                                <div class="flex items-center p-3 bg-white rounded-xl border border-amber-200/50 hover:shadow-md transition-shadow duration-200">
                                    <i class="fas ${icon} text-amber-600 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">${label}</span>
                                </div>
                                `;
                    }).join('')
            } <
            /div> < /
            div >
                ` : ''}

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-2xl p-6 border border-gray-200/50">
                        <div class="flex flex-wrap gap-3 justify-center">

                            ${apartment.status === 'available' ? `
                                                                                                                                                                                    <a href="/staff/apartments/${apartment.id}/assign"
                                                                                                                                                                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg shadow-green-500/25">
                                                                                                                                                                                        <i class="fas fa-user-plus mr-2"></i>Assign Tenant
                                                                                                                                                                                    </a>
                                                                                                                                                                                    ` : ''}
                            ${apartment.status === 'occupied' && tenant ? `
                                                                                                                                                                                    <button onclick="showTenantDetails(${apartment.id})"
                                                                                                                                                                                            class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200">
                                                                                                                                                                                        <i class="fas fa-user mr-2"></i>View Tenant Details
                                                                                                                                                                                    </button>
                                                                                                                                                                                    ` : ''}
                        </div>
                    </div>
                `;

            document.getElementById('apartmentDetails').innerHTML = detailsHtml;
            })
            .catch(error => {
                console.error('Error fetching apartment details:', error);
                document.getElementById('apartmentDetails').innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                        </div>
                        <p class="text-red-600 font-semibold mb-1">Error loading apartment details</p>
                        <p class="text-gray-500 text-sm">Please try again later</p>
                    </div>
                `;
            });
            }

            function closeApartmentModal() {
                document.getElementById('apartmentModal').classList.add('hidden');
            }

            document.getElementById('apartmentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeApartmentModal();
                }
            });

            function showTenantDetails(apartmentId) {
                closeApartmentModal();

                fetch(`/staff/apartments/${apartmentId}/tenant-details`)
                    .then(response => response.json())
                    .then(data => {
                        let detailsHtml = '';
                        if (data.tenant) {
                            detailsHtml = `
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200/50">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">${data.tenant.name}</h4>
                                    <p class="text-sm text-gray-500">Active Tenant</p>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-envelope text-blue-500 w-5 mr-2"></i>
                                    <span class="text-gray-700">${data.tenant.email}</span>
                                </div>
                                ${data.tenant.contact_number ? `
                                                                                                                                                                                        <div class="flex items-center text-sm">
                                                                                                                                                                                            <i class="fas fa-phone text-green-500 w-5 mr-2"></i>
                                                                                                                                                                                            <span class="text-gray-700">${data.tenant.contact_number}</span>
                                                                                                                                                                                        </div>
                                                                                                                                                                                        ` : ''}
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-building text-purple-500 w-5 mr-2"></i>
                                    <span class="text-gray-700">${data.apartment.apartment_number}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-peso-sign text-green-500 w-5 mr-2"></i>
                                    <span class="font-bold text-green-600">₱${new Intl.NumberFormat().format(data.rent.monthly_rent)}/month</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-calendar-check text-blue-500 w-5 mr-2"></i>
                                    <span class="text-gray-700">Since ${new Date(data.rent.start_date).toLocaleDateString()}</span>
                                </div>
                                ${data.rent.end_date ? `
                                                                                                                                                                                        <div class="flex items-center text-sm">
                                                                                                                                                                                            <i class="fas fa-calendar-times text-orange-500 w-5 mr-2"></i>
                                                                                                                                                                                            <span class="text-gray-700">Until ${new Date(data.rent.end_date).toLocaleDateString()}</span>
                                                                                                                                                                                        </div>
                                                                                                                                                                                        ` : ''}
                            </div>
                        </div>
                    `;
                        } else {
                            detailsHtml = '<p class="text-gray-500 text-center py-8">No tenant information available.</p>';
                        }

                        document.getElementById('tenantDetails').innerHTML = detailsHtml;
                        document.getElementById('tenantModal').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching tenant details:', error);
                        document.getElementById('tenantDetails').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                            <p class="text-red-500 font-medium">Error loading tenant details</p>
                        </div>
                    `;
                        document.getElementById('tenantModal').classList.remove('hidden');
                    });
            }

            function closeTenantModal() {
                document.getElementById('tenantModal').classList.add('hidden');
            }

            document.getElementById('tenantModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeTenantModal();
                }
            });

            function openEditModal(apartmentId) {

                fetch(`/staff/apartments/${apartmentId}/edit-data`)
                    .then(response => response.json())
                    .then(data => {
                        const apartment = data.apartment;

                        // Update form action
                        document.getElementById('editApartmentForm').action = `/staff/apartments/${apartmentId}`;

                        // Populate modal title
                        document.getElementById('modal-apartment-number').textContent = apartment.apartment_number;

                        // Populate form fields
                        document.getElementById('edit_apartment_number').value = apartment.apartment_number || '';
                        document.getElementById('edit_apartment_type').value = apartment.apartment_type || '';
                        document.getElementById('edit_price').value = apartment.price || '';
                        document.getElementById('edit_floor_number').value = apartment.floor_number || '';
                        document.getElementById('edit_size_sqm').value = apartment.size_sqm || '';
                        document.getElementById('edit_status').value = apartment.status || 'available';
                        document.getElementById('edit_description').value = apartment.description || '';


                        const amenities = apartment.amenities || [];
                        document.querySelectorAll('.amenity-check').forEach(checkbox => {
                            checkbox.checked = amenities.includes(checkbox.value);
                        });


                        document.getElementById('editApartmentModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    })
                    .catch(error => {
                        console.error('Error loading apartment data:', error);
                        alert('Failed to load apartment data. Please try again.');
                    });
            }

            function closeEditModal() {
                document.getElementById('editApartmentModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function submitEditForm() {
                document.getElementById('editApartmentForm').submit();
            }


            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('editApartmentModal').classList.contains('hidden')) {
                    closeEditModal();
                }
            });


            document.getElementById('editApartmentModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });
        </script>
    @endpush
@endsection
