@extends('layouts.app')

@section('title', 'Submit Maintenance Request')
@section('page-title', 'Submit Maintenance Request')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="{{ route('tenant.maintenance.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Maintenance
                </a>
                <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                    Submit Maintenance Request
                </h1>
                <p class="text-lg text-gray-600">
                    Report issues or request repairs for your apartment
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                <form action="{{ route('tenant.maintenance.store') }}" method="POST">
                    @csrf

                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Category
                                </label>
                                <select id="category" name="category" required
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}"
                                            {{ old('category') == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Priority Level
                                </label>
                                <select id="priority" name="priority" required
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                    <option value="">Select priority</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority }}"
                                            {{ old('priority') == $priority ? 'selected' : '' }}>
                                            {{ ucfirst($priority) }} Priority
                                            @if ($priority === 'high')
                                                - Urgent/Safety Issue
                                            @elseif($priority === 'medium')
                                                - Affects Daily Use
                                            @else
                                                - Minor Issue
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="issue_description" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Issue Description
                                </label>
                                <textarea id="issue_description" name="issue_description" rows="5" required
                                    placeholder="Please describe the issue in detail, including location, symptoms, and any relevant information..."
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 resize-none">{{ old('issue_description') }}</textarea>
                                <p class="mt-2 text-xs text-gray-500">
                                    Minimum 10 characters. Be as specific as possible to help us resolve the issue quickly.
                                </p>
                                @error('issue_description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="preferred_time" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Preferred Time for Service <span class="text-gray-400 font-normal">(Optional)</span>
                                </label>
                                <input type="text" id="preferred_time" name="preferred_time"
                                    value="{{ old('preferred_time') }}"
                                    placeholder="e.g., Weekday mornings, After 3 PM, Weekends only"
                                    class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                                <p class="mt-2 text-xs text-gray-500">
                                    Let us know your availability preferences. We'll do our best to accommodate your
                                    schedule.
                                </p>
                                @error('preferred_time')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 sm:px-8 bg-gray-50/50 border-t border-gray-200/60 flex justify-end gap-3">
                        <a href="{{ route('tenant.maintenance.index') }}"
                            class="inline-flex items-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-500/10 transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                <div class="p-6 sm:p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Quick Reference Guide</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                        <div class="bg-gradient-to-br from-red-50 to-rose-50 border border-red-200 rounded-2xl p-5">
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center shadow-lg shadow-red-500/30 mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-red-900">Emergency Issues</h3>
                            </div>
                            <ul class="text-sm text-red-800 space-y-1.5 mb-3">
                                <li>• Water leaks or flooding</li>
                                <li>• Electrical hazards</li>
                                <li>• Security concerns</li>
                                <li>• Heating/cooling failures</li>
                            </ul>
                            <p class="text-xs text-red-700 font-semibold">
                                Call property management immediately!
                            </p>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/30 mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-blue-900">Common Categories</h3>
                            </div>
                            <ul class="text-sm text-blue-800 space-y-1.5">
                                <li>• Plumbing (leaks, clogs)</li>
                                <li>• Electrical (outlets, lights)</li>
                                <li>• Appliances (AC, refrigerator)</li>
                                <li>• Security (locks, doors)</li>
                            </ul>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-5">
                            <div class="flex items-center mb-3">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-green-500/30 mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-green-900">Expected Response</h3>
                            </div>
                            <ul class="text-sm text-green-800 space-y-1.5">
                                <li>• High Priority: Same day</li>
                                <li>• Medium Priority: 24-48 hours</li>
                                <li>• Low Priority: 3-5 business days</li>
                                <li>• Emergency: Immediate</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-rose-50 border border-red-200 rounded-3xl shadow-sm p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-base font-semibold text-red-900 mb-2">
                            Emergency Maintenance
                        </h3>
                        <p class="text-sm text-red-800 leading-relaxed">
                            For urgent issues that pose safety risks or cause significant property damage, please contact
                            the
                            property management office immediately at <strong>(555) 123-4567</strong> or email
                            <strong>emergency@property.com</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input:focus,
        textarea:focus,
        select:focus {
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
    </style>

    <script>
        document.getElementById('issue_description').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const description = document.getElementById('issue_description').value;
            if (description.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed description (at least 10 characters).');
                document.getElementById('issue_description').focus();
            }
        });
    </script>
@endsection
