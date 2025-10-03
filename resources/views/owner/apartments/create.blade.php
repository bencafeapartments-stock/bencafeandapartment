@extends('layouts.app')

@section('title', 'Add New Apartment')
@section('page-title', 'Add New Apartment')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('owner.apartments.index') }}"
                            class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                            Apartments
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-500 font-medium">New Apartment</span>
                    </li>
                </ol>
            </nav>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                    Add New Apartment
                </h1>
                <p class="text-lg text-gray-600">
                    Create a new apartment unit with all the details
                </p>
            </div>

            {{-- Main Form Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">
                <form action="{{ route('owner.apartments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="p-8 sm:p-10 space-y-10">

                        {{-- Basic Information Section --}}
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Basic Information</h2>

                            <div class="space-y-6">
                                {{-- Apartment Number & Type Row --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="apartment_number" class="block text-sm font-medium text-gray-900 mb-2">
                                            Apartment Number
                                        </label>
                                        <input type="text" name="apartment_number" id="apartment_number"
                                            value="{{ old('apartment_number') }}" 
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('apartment_number') border-red-400 @enderror"
                                            placeholder="101">
                                        @error('apartment_number')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="apartment_type" class="block text-sm font-medium text-gray-900 mb-2">
                                            Apartment Type
                                        </label>
                                        <div class="relative">
                                            <select name="apartment_type" id="apartment_type" 
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('apartment_type') border-red-400 @enderror">
                                                <option value="">Select type</option>
                                                <option value="standard"
                                                    {{ old('apartment_type') == 'standard' ? 'selected' : '' }}>
                                                    Standard
                                                </option>
                                                <option value="ordinary"
                                                    {{ old('apartment_type') == 'ordinary' ? 'selected' : '' }}>
                                                    Ordinary
                                                </option>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('apartment_type')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Price & Status Row --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-900 mb-2">
                                            Monthly Rent
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">
                                                ₱
                                            </span>
                                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                                step="0.01" min="0"
                                                class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('price') border-red-400 @enderror"
                                                placeholder="15,000.00">
                                        </div>
                                        @error('price')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-900 mb-2">
                                            Status
                                        </label>
                                        <div class="relative">
                                            <select name="status" id="status"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 appearance-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                                <option value="available"
                                                    {{ old('status', 'available') == 'available' ? 'selected' : '' }}>
                                                    Available
                                                </option>
                                                <option value="occupied"
                                                    {{ old('status') == 'occupied' ? 'selected' : '' }}>
                                                    Occupied
                                                </option>
                                                <option value="maintenance"
                                                    {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                                    Maintenance
                                                </option>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description Section --}}
                        <div class="pt-6 border-t border-gray-100">
                            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Description</h2>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                                    Apartment Description
                                </label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 resize-none @error('description') border-red-400 @enderror"
                                    placeholder="Describe the apartment features, layout, and any special characteristics...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                    </div>


                    <div class="bg-gray-50/80 backdrop-blur-sm px-8 sm:px-10 py-6 border-t border-gray-200/60">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-3">
                            <a href="{{ route('owner.apartments.index') }}"
                                class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-200/50 transition-all duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                Create Apartment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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


        select:focus+div svg {
            transform: rotate(180deg);
        }

        select+div svg {
            transition: transform 0.2s ease;
        }
    </style>
@endsection
