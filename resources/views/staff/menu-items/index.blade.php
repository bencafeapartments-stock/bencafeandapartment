@extends('layouts.app')

@section('title', 'Menu Items')
@section('page-title', 'Menu Items Management')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">
                            Menu Items
                        </h1>
                        <p class="text-lg text-gray-600">
                            Manage cafe menu items and pricing
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('staff.menu-items.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Menu Item
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                            <i class="fas fa-coffee text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Beverages</h3>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $menuItems->where('category', 'beverages')->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/25">
                            <i class="fas fa-hamburger text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Food</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $menuItems->where('category', 'food')->count() }}</p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <i class="fas fa-birthday-cake text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-purple-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Desserts</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $menuItems->where('category', 'desserts')->count() }}
                        </p>
                    </div>
                </div>

                <div
                    class="group bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/25">
                            <i class="fas fa-peso-sign text-white text-lg"></i>
                        </div>
                        <div
                            class="w-2 h-2 bg-yellow-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Avg Price</h3>
                        <p class="text-3xl font-bold text-gray-900">₱{{ number_format($menuItems->avg('price')) }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 mb-6 overflow-hidden">
                <div class="border-b border-gray-200/50">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab active"
                            data-category="all">
                            All Items
                            <span
                                class="bg-gray-100 text-gray-900 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $menuItems->count() }}</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab"
                            data-category="beverages">
                            Beverages
                            <span
                                class="bg-blue-100 text-blue-800 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $menuItems->where('category', 'beverages')->count() }}</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab"
                            data-category="food">
                            Food
                            <span
                                class="bg-green-100 text-green-800 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $menuItems->where('category', 'food')->count() }}</span>
                        </button>
                        <button
                            class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm filter-tab"
                            data-category="desserts">
                            Desserts
                            <span
                                class="bg-purple-100 text-purple-800 ml-2 py-0.5 px-2.5 rounded-full text-xs">{{ $menuItems->where('category', 'desserts')->count() }}</span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Menu Items Grid -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-list text-gray-600 text-sm"></i>
                        </div>
                        Menu Items
                    </h3>
                </div>

                @if ($menuItems->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @foreach ($menuItems as $item)
                            <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 menu-item-card"
                                data-category="{{ $item->category ?? 'general' }}">

                                <!-- Image Section -->
                                <div
                                    class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden">
                                    @if ($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}"
                                            class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="text-center">
                                            <div
                                                class="w-16 h-16 bg-white/50 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-sm text-gray-500 font-medium">No image available</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content Section -->
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-semibold text-gray-900 text-lg flex-1 mr-2">
                                            {{ $item->product_name }}</h4>
                                        <span
                                            class="text-xl font-bold text-green-600 whitespace-nowrap">₱{{ number_format($item->price) }}</span>
                                    </div>

                                    @if ($item->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $item->description }}</p>
                                    @endif

                                    <!-- Badges -->
                                    <div class="flex items-center space-x-2 mb-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            {{ ($item->category ?? 'general') === 'beverages'
                                                ? 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 border border-blue-200/50'
                                                : (($item->category ?? 'general') === 'food'
                                                    ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-700 border border-green-200/50'
                                                    : (($item->category ?? 'general') === 'desserts'
                                                        ? 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-700 border border-purple-200/50'
                                                        : 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-200/50')) }}">
                                            {{ ucfirst($item->category ?? 'General') }}
                                        </span>

                                        @if (isset($item->is_active))
                                            @if ($item->is_active)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200/50">
                                                    <i class="fas fa-check-circle mr-1"></i>Active
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200/50">
                                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <button
                                            onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->product_name) }}', {{ $item->price }}, '{{ $item->category ?? 'general' }}', '{{ addslashes($item->description ?? '') }}', {{ isset($item->is_active) ? ($item->is_active ? 'true' : 'false') : 'true' }})"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                            <i class="fas fa-edit mr-1.5"></i>
                                            Edit
                                        </button>
                                        <form action="{{ route('staff.menu-items.destroy', $item->id) }}" method="POST"
                                            class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all duration-200"
                                                onclick="return confirm('Are you sure you want to delete this menu item?')">
                                                <i class="fas fa-trash mr-1.5"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No menu items yet</h3>
                        <p class="text-gray-500 mb-4">Get started by adding your first menu item.</p>
                        <a href="{{ route('staff.menu-items.create') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Menu Item
                        </a>
                    </div>
                @endif
            </div>

            <!-- Success Message -->
            <!-- @if (session('success'))
                <div
                    class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200/50 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif -->
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden animate-modal">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-edit text-blue-600"></i>
                        </div>
                        Edit Menu Item
                    </h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="editForm" method="POST" class="overflow-y-auto max-h-[calc(90vh-180px)]">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-5">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name</label>
                        <input type="text" name="product_name" id="edit_product_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price (₱)</label>
                        <input type="number" name="price" id="edit_price" step="0.01" min="0" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select name="category" id="edit_category" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="beverages">Beverages</option>
                            <option value="food">Food</option>
                            <option value="desserts">Desserts</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"></textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                        <i class="fas fa-save mr-1.5"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            @keyframes modal-in {
                from {
                    opacity: 0;
                    transform: scale(0.95) translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            .animate-modal {
                animation: modal-in 0.3s ease-out;
            }
        </style>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterTabs = document.querySelectorAll('.filter-tab');
            const menuCards = document.querySelectorAll('.menu-item-card');

            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => {
                        t.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });

                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    const category = this.dataset.category;
                    menuCards.forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = '';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, 10);
                        } else {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(10px)';
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 300);
                        }
                    });
                });
            });

            menuCards.forEach(card => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            });
        });

        function openEditModal(id, name, price, category, description, isActive) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            form.action = `/staff/menu-items/${id}`;
            document.getElementById('edit_product_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_is_active').checked = isActive;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close modal on backdrop click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
@endsection
