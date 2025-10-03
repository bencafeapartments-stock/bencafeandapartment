@extends('layouts.app')

@section('title', 'Create Maintenance Request')
@section('page-title', 'Create New Maintenance Request')



@section('content')
    <div class="space-y-6">

        <div>
            <a href="{{ route('staff.maintenance.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Maintenance Requests
            </a>
        </div>


        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Create Maintenance Request</h2>
                <p class="text-sm text-gray-600">Fill out the form below to create a new maintenance request</p>
            </div>

            <form action="{{ route('staff.maintenance.store') }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf


                <div>
                    <label for="tenant_id" class="block text-sm font-medium text-gray-700">
                        Tenant <span class="text-red-500">*</span>
                    </label>
                    <select name="tenant_id" id="tenant_id" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror">
                        <option value="">Select a tenant</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }}
                                @if ($tenant->apartment)
                                    - {{ $tenant->apartment->number }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('tenant_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="apartment_id" class="block text-sm font-medium text-gray-700">
                        Apartment <span class="text-red-500">*</span>
                    </label>
                    <select name="apartment_id" id="apartment_id" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('apartment_id') border-red-500 @enderror">
                        <option value="">Select an apartment</option>
                        @foreach ($apartments as $apartment)
                            <option value="{{ $apartment->id }}"
                                {{ old('apartment_id') == $apartment->id ? 'selected' : '' }}>
                                {{ $apartment->number }} - Floor {{ $apartment->floor }}
                                @if ($apartment->tenant)
                                    ({{ $apartment->tenant->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('apartment_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Select the apartment where the issue is located</p>
                </div>


                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">
                        Category
                    </label>
                    <select name="category" id="category"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                        <option value="general" {{ old('category', 'general') == 'general' ? 'selected' : '' }}>General
                        </option>
                        <option value="plumbing" {{ old('category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                        <option value="electrical" {{ old('category') == 'electrical' ? 'selected' : '' }}>Electrical
                        </option>
                        <option value="hvac" {{ old('category') == 'hvac' ? 'selected' : '' }}>HVAC</option>
                        <option value="appliance" {{ old('category') == 'appliance' ? 'selected' : '' }}>Appliance</option>
                        <option value="security" {{ old('category') == 'security' ? 'selected' : '' }}>Security</option>
                        <option value="cleaning" {{ old('category') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                        <option value="structural" {{ old('category') == 'structural' ? 'selected' : '' }}>Structural
                        </option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Choose the category that best describes the issue</p>
                </div>


                <div>
                    <label for="issue_description" class="block text-sm font-medium text-gray-700">
                        Issue Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="issue_description" id="issue_description" rows="4" required
                        placeholder="Describe the maintenance issue in detail..."
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('issue_description') border-red-500 @enderror">{{ old('issue_description') }}</textarea>
                    @error('issue_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Provide detailed information about the issue</p>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">
                        Priority Level <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input type="radio" name="priority" id="priority_urgent" value="urgent"
                                {{ old('priority') == 'urgent' ? 'checked' : '' }}
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="priority_urgent" class="ml-3 flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Urgent</span>
                                <span class="ml-2 text-xs text-gray-500">(Immediate attention - Emergency)</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="priority" id="priority_high" value="high"
                                {{ old('priority') == 'high' ? 'checked' : '' }}
                                class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                            <label for="priority_high" class="ml-3 flex items-center">
                                <i class="fas fa-exclamation text-orange-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">High Priority</span>
                                <span class="ml-2 text-xs text-gray-500">(Safety concern or major issue)</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="priority" id="priority_medium" value="medium"
                                {{ old('priority') == 'medium' ? 'checked' : '' }}
                                class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300">
                            <label for="priority_medium" class="ml-3 flex items-center">
                                <i class="fas fa-minus text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Medium Priority</span>
                                <span class="ml-2 text-xs text-gray-500">(Standard repair within a few days)</span>
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="priority" id="priority_low" value="low"
                                {{ old('priority', 'low') == 'low' ? 'checked' : '' }}
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                            <label for="priority_low" class="ml-3 flex items-center">
                                <i class="fas fa-arrow-down text-green-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Low Priority</span>
                                <span class="ml-2 text-xs text-gray-500">(Minor issue, can wait)</span>
                            </label>
                        </div>
                    </div>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Priority Guidelines</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Urgent:</strong> Life-threatening emergencies (gas leaks, major electrical issues,
                            flooding)</p>
                        <p><strong>High Priority:</strong> Immediate attention required (plumbing emergencies, security
                            problems)</p>
                        <p><strong>Medium Priority:</strong> Standard maintenance issues that affect daily living</p>
                        <p><strong>Low Priority:</strong> Cosmetic issues or non-urgent repairs</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('staff.maintenance.index') }}"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Create Request
                    </button>
                </div>
            </form>
        </div>


        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were errors with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('tenant_id').addEventListener('change', function() {
            const select = this;
            const selectedOption = select.options[select.selectedIndex];
            const apartmentSelect = document.getElementById('apartment_id');

            if (selectedOption.value) {
                const tenantName = selectedOption.text.split(' - ')[0];
                for (let option of apartmentSelect.options) {
                    if (option.text.includes(tenantName) && option.value) {
                        apartmentSelect.value = option.value;
                        break;
                    }
                }
            }
        });


        document.getElementById('apartment_id').addEventListener('change', function() {
            const select = this;
            const selectedOption = select.options[select.selectedIndex];
            const tenantSelect = document.getElementById('tenant_id');

            if (selectedOption.value && selectedOption.text.includes('(')) {

                const tenantName = selectedOption.text.match(/\(([^)]+)\)/);
                if (tenantName && tenantName[1]) {

                    for (let option of tenantSelect.options) {
                        if (option.text.includes(tenantName[1]) && option.value) {
                            tenantSelect.value = option.value;
                            break;
                        }
                    }
                }
            }
        });
    </script>
@endsection
