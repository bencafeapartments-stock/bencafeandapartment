@extends('layouts.app')

@section('title', 'Staff Management')
@section('page-title', 'Staff Management')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Staff Management
                        </h1>
                        <p class="text-lg text-gray-600">
                            Manage your cafe and apartment staff members
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('owner.staff.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Staff
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Active Staff</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $staff->where('is_active', true)->count() }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">On Duty Today</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $staff->where('is_active', true)->count() }}</p>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Total Staff</h3>
                    <p class="text-3xl font-semibold text-gray-900">{{ $staff->count() }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">Staff Members</h2>
                            <p class="text-sm text-gray-600 mt-1">A list of all staff members in your system</p>
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="Search staff..."
                                class="w-full sm:w-72 pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    @if ($staff->count() > 0)
                        <div class="overflow-x-auto -mx-6 sm:-mx-8">
                            <div class="inline-block min-w-full align-middle px-6 sm:px-8">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50/50">
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Staff Member
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Contact
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Joined
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($staff as $member)
                                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="relative">
                                                            <div
                                                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-lg shadow-blue-500/30">
                                                                {{ substr($member->name, 0, 1) }}
                                                            </div>
                                                            @if ($member->is_active)
                                                                <div
                                                                    class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white">
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-semibold text-gray-900">
                                                                {{ $member->name }}</div>
                                                            <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $member->contact_number ?: 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($member->is_active)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-700">
                                                            Active
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700">
                                                            Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $member->created_at->format('M d, Y') }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $member->created_at->diffForHumans() }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('owner.staff.edit', $member) }}"
                                                            class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        @if ($member->is_active)
                                                            <form action="{{ route('owner.staff.destroy', $member) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="w-8 h-8 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                                                    onclick="return confirm('Are you sure you want to deactivate this staff member?')">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No staff members yet</h3>
                            <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                                Get started by adding your first staff member to begin managing your team
                            </p>
                            <a href="{{ route('owner.staff.create') }}"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add First Staff Member
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        input:focus {
            outline: none;
        }

        input::placeholder {
            transition: opacity 0.2s ease;
        }

        input:focus::placeholder {
            opacity: 0.5;
        }
    </style>
@endsection
