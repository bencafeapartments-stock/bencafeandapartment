@extends('layouts.app')

@section('title', 'Create Menu Item')
@section('page-title', 'Create Menu Item')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="mb-6">
                <a href="{{ route('staff.menu-items.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Menu Items
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2">
                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                            <h2 class="text-xl font-semibold text-gray-900">Create New Menu Item</h2>
                            <p class="text-sm text-gray-600 mt-1">Add a new item to your cafe menu</p>
                        </div>

                        <form action="{{ route('staff.menu-items.store') }}" method="POST" enctype="multipart/form-data"
                            class="p-6 space-y-6">
                            @csrf

                            <div>
                                <label for="product_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Item Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="product_name" id="product_name" required
                                    value="{{ old('product_name') }}" placeholder="e.g., Cappuccino, Chicken Sandwich"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('product_name') border-red-500 @enderror">
                                @error('product_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3" placeholder="Brief description of the item (optional)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Price (₱) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">₱</span>
                                    </div>
                                    <input type="number" name="price" id="price" step="0.01" min="0"
                                        required value="{{ old('price') }}" placeholder="0.00"
                                        class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('price') border-red-500 @enderror">
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category" id="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('category') border-red-500 @enderror">
                                    <option value="">Select a category</option>
                                    <option value="beverages" {{ old('category') == 'beverages' ? 'selected' : '' }}>
                                        Beverages</option>
                                    <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>Food</option>
                                    <option value="desserts" {{ old('category') == 'desserts' ? 'selected' : '' }}>Desserts
                                    </option>
                                </select>
                                @error('category')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Product Image
                                </label>
                                <div
                                    class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-gray-400 transition-colors bg-gray-50/50">
                                    <div class="space-y-2 text-center">
                                        <div id="image-preview" class="hidden">
                                            <img id="preview-img" src="" alt="Preview"
                                                class="mx-auto h-32 w-32 object-cover rounded-xl">
                                        </div>
                                        <div id="upload-placeholder">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="image"
                                                    class="relative cursor-pointer bg-white rounded-lg px-3 py-1 font-semibold text-blue-600 hover:text-blue-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 transition-colors">
                                                    <span>Upload an image</span>
                                                    <input id="image" name="image" type="file" accept="image/*"
                                                        class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Availability Status
                                </label>
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all cursor-pointer">
                                        <input type="radio" name="is_active" id="active" value="1"
                                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                        <label for="active" class="ml-3 flex items-center cursor-pointer flex-1">
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-check-circle text-green-600"></i>
                                            </div>
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 block">Active</span>
                                                <span class="text-xs text-gray-500">Available for ordering</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div
                                        class="flex items-center p-3 border-2 border-gray-200 rounded-xl hover:border-red-300 transition-all cursor-pointer">
                                        <input type="radio" name="is_active" id="inactive" value="0"
                                            {{ old('is_active') == '0' ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                        <label for="inactive" class="ml-3 flex items-center cursor-pointer flex-1">
                                            <div
                                                class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-times-circle text-red-600"></i>
                                            </div>
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 block">Inactive</span>
                                                <span class="text-xs text-gray-500">Hidden from menu</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('is_active')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('staff.menu-items.index') }}"
                                    class="px-6 py-3 border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 border border-transparent rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create Menu Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden sticky top-6">
                        <div class="px-6 py-5 border-b border-gray-200/50 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-eye text-blue-600 text-sm"></i>
                                </div>
                                Live Preview
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">See how it will look</p>
                        </div>

                        <div class="p-6">
                            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center"
                                    id="preview-container">
                                    <div class="text-center" id="no-image-placeholder">
                                        <div
                                            class="w-16 h-16 bg-white/50 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium">No image</p>
                                    </div>
                                    <img id="live-preview-img" src="" alt="Preview"
                                        class="h-full w-full object-cover hidden">
                                </div>

                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-semibold text-gray-900 text-lg flex-1 mr-2" id="preview-name">Item
                                            Name</h4>
                                        <span class="text-xl font-bold text-green-600 whitespace-nowrap"
                                            id="preview-price">₱0.00</span>
                                    </div>

                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2" id="preview-description">
                                        Item description will appear here...</p>

                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-200/50"
                                            id="preview-category">
                                            Category
                                        </span>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200/50"
                                            id="preview-status">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-6 bg-gradient-to-r from-blue-50 to-blue-100/50 border border-blue-200/50 rounded-2xl p-5">
                                <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    Tips for Creating Menu Items
                                </h4>
                                <div class="text-sm text-blue-800 space-y-2">
                                    <p class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Use clear, appetizing names</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Add detailed descriptions</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>High-quality images increase orders</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Set competitive pricing</span>
                                    </p>
                                    <p class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Choose appropriate categories</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div
                    class="mt-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200/50 rounded-2xl p-5 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-900">
                                There were errors with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-800">
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
    </div>

    @push('styles')
        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush

    <script>
        function updatePreview() {
            const name = document.getElementById('product_name').value || 'Item Name';
            const description = document.getElementById('description').value || 'Item description will appear here...';
            const price = document.getElementById('price').value || '0.00';
            const category = document.getElementById('category').value || '';
            const isActive = document.querySelector('input[name="is_active"]:checked')?.value || '1';

            document.getElementById('preview-name').textContent = name;
            document.getElementById('preview-description').textContent = description;
            document.getElementById('preview-price').textContent = `₱${parseFloat(price || 0).toFixed(2)}`;

            const categoryBadge = document.getElementById('preview-category');
            categoryBadge.textContent = category ? category.charAt(0).toUpperCase() + category.slice(1) : 'Category';
            categoryBadge.className =
                `inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${getCategoryColor(category)}`;

            const statusBadge = document.getElementById('preview-status');
            if (isActive === '1') {
                statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Active';
                statusBadge.className =
                    'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200/50';
            } else {
                statusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Inactive';
                statusBadge.className =
                    'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200/50';
            }
        }

        function getCategoryColor(category) {
            const colors = {
                'beverages': 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 border border-blue-200/50',
                'food': 'bg-gradient-to-r from-green-100 to-green-200 text-green-700 border border-green-200/50',
                'desserts': 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-700 border border-purple-200/50'
            };
            return colors[category] || 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-200/50';
        }

        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, or GIF)');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.getElementById('preview-img');
                    const livePreviewImg = document.getElementById('live-preview-img');
                    const placeholder = document.getElementById('upload-placeholder');
                    const noImagePlaceholder = document.getElementById('no-image-placeholder');
                    const imagePreview = document.getElementById('image-preview');

                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    placeholder.classList.add('hidden');

                    livePreviewImg.src = e.target.result;
                    livePreviewImg.classList.remove('hidden');
                    noImagePlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('product_name').addEventListener('input', updatePreview);
        document.getElementById('description').addEventListener('input', updatePreview);
        document.getElementById('price').addEventListener('input', updatePreview);
        document.getElementById('category').addEventListener('change', updatePreview);
        document.querySelectorAll('input[name="is_active"]').forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });

        updatePreview();

        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['product_name', 'price', 'category'];
            let hasErrors = false;

            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    hasErrors = true;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (hasErrors) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });

        document.getElementById('price').addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
                updatePreview();
            }
        });

        const descriptionField = document.getElementById('description');
        if (descriptionField) {
            const maxLength = 500;
            const counterElement = document.createElement('div');
            counterElement.className = 'text-xs text-gray-500 mt-2';
            counterElement.textContent = `0 / ${maxLength} characters`;
            descriptionField.parentNode.appendChild(counterElement);

            descriptionField.addEventListener('input', function() {
                const length = this.value.length;
                counterElement.textContent = `${length} / ${maxLength} characters`;

                if (length > maxLength) {
                    this.classList.add('border-red-500');
                    counterElement.classList.add('text-red-500');
                } else {
                    this.classList.remove('border-red-500');
                    counterElement.classList.remove('text-red-500');
                }
            });
        }
    </script>
@endsection
