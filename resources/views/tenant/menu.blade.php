@extends('layouts.app')

@section('title', 'Cafe Menu')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">Cafe Menu</h1>
                        <p class="text-lg text-gray-600">Order delicious food and beverages delivered to your apartment</p>
                    </div>
                    <div>
                        <a href="{{ route('tenant.orders.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500/20 shadow-lg shadow-gray-500/25 transition-all duration-200">
                            <i class="fas fa-list mr-2"></i>View My Orders
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Menu Items -->
                <div class="lg:col-span-2">
                    @if (count($menuItems) > 0)
                        @foreach ($menuItems as $category)
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 mr-3">
                                        <i class="fas fa-utensils text-white"></i>
                                    </div>
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $category['category'] }}</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($category['items'] as $item)
                                        <div
                                            class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden hover:shadow-lg transition-all duration-300">
                                            @if (isset($item['image_url']) && $item['image_url'])
                                                <div class="h-40 overflow-hidden">
                                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @endif

                                            <div class="p-5">
                                                <div class="flex justify-between items-start mb-3">
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item['name'] }}
                                                        </h3>
                                                        <p class="text-gray-600 text-sm">{{ $item['description'] }}</p>
                                                        @if (isset($item['prep_time']) && $item['prep_time'])
                                                            <p class="text-gray-500 text-xs mt-2 flex items-center">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                {{ $item['prep_time'] }} min
                                                            </p>
                                                        @endif
                                                        @if (isset($item['ingredients']) && is_array($item['ingredients']) && count($item['ingredients']) > 0)
                                                            <p class="text-gray-500 text-xs mt-2">
                                                                <i class="fas fa-list-ul mr-1"></i>
                                                                <span class="font-medium">Ingredients:</span>
                                                                {{ implode(', ', $item['ingredients']) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-xl font-bold text-green-600 ml-3">
                                                        ₱{{ number_format($item['price'], 2) }}
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex items-center justify-between pt-3 border-t border-gray-100">
                                                    <div class="flex items-center space-x-2">
                                                        <button onclick="decreaseQuantity({{ $item['id'] }})"
                                                            class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full flex items-center justify-center transition duration-200">
                                                            <i class="fas fa-minus text-xs"></i>
                                                        </button>
                                                        <span id="qty-{{ $item['id'] }}"
                                                            class="w-10 text-center font-semibold text-gray-900">0</span>
                                                        <button onclick="increaseQuantity({{ $item['id'] }})"
                                                            class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full flex items-center justify-center transition duration-200">
                                                            <i class="fas fa-plus text-xs"></i>
                                                        </button>
                                                    </div>

                                                    <button
                                                        onclick="addToCart({{ $item['id'] }}, '{{ $item['name'] }}', {{ $item['price'] }})"
                                                        class="inline-flex items-center px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
                                                        <i class="fas fa-cart-plus mr-2"></i>Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div
                            class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 p-12 text-center">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-coffee text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No menu items available</h3>
                            <p class="text-gray-600">Please check back later for available items.</p>
                        </div>
                    @endif
                </div>

                <!-- Cart Sidebar -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 p-6 sticky top-4">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30 mr-3">
                                <i class="fas fa-shopping-cart text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Your Order</h3>
                        </div>

                        <div id="cart-items" class="space-y-3 mb-4">
                            <div class="text-center py-8">
                                <i class="fas fa-shopping-basket text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Your cart is empty</p>
                            </div>
                        </div>

                        <div id="cart-summary" class="hidden border-t border-gray-200 pt-4 mb-4">
                            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span id="subtotal" class="font-semibold text-gray-900">₱0.00</span>
                                </div>
                                <div
                                    class="flex justify-between items-center text-lg font-bold border-t border-gray-200 pt-2">
                                    <span class="text-gray-900">Total:</span>
                                    <span id="total" class="text-green-600">₱0.00</span>
                                </div>
                            </div>
                        </div>

                        <form id="order-form" action="{{ route('tenant.orders.store') }}" method="POST"
                            class="hidden space-y-4">
                            @csrf
                            <div id="cart-input-items"></div>

                            <div>
                                <label for="order_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-box mr-1 text-blue-500"></i>Order Type
                                </label>
                                <select name="order_type" id="order_type" required
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-200">
                                    <option value="pickup">Pickup</option>
                                    <option value="delivery">Delivery to Room</option>
                                </select>
                            </div>

                            <div>
                                <label for="special_instructions" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-comment-dots mr-1 text-purple-500"></i>Special Instructions (Optional)
                                </label>
                                <textarea name="special_instructions" id="special_instructions" rows="3"
                                    placeholder="Any special requests for your order..."
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-200"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl hover:from-green-600 hover:to-emerald-700 focus:ring-2 focus:ring-green-500/20 transition-all duration-200 shadow-lg shadow-green-500/25">
                                <i class="fas fa-check-circle mr-2"></i>Place Order
                            </button>
                        </form>

                        <button onclick="clearCart()" id="clear-cart"
                            class="hidden w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all duration-200 mt-3">
                            <i class="fas fa-trash-alt mr-2"></i>Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function increaseQuantity(productId) {
            const qtyElement = document.getElementById(`qty-${productId}`);
            let currentQty = parseInt(qtyElement.textContent);
            qtyElement.textContent = currentQty + 1;
        }

        function decreaseQuantity(productId) {
            const qtyElement = document.getElementById(`qty-${productId}`);
            let currentQty = parseInt(qtyElement.textContent);
            if (currentQty > 0) {
                qtyElement.textContent = currentQty - 1;
            }
        }

        function addToCart(productId, productName, price) {
            const qtyElement = document.getElementById(`qty-${productId}`);
            const quantity = parseInt(qtyElement.textContent);

            if (quantity <= 0) {
                alert('Please select quantity first');
                return;
            }

            const existingItemIndex = cart.findIndex(item => item.product_id === productId);

            if (existingItemIndex > -1) {
                cart[existingItemIndex].quantity += quantity;
            } else {
                cart.push({
                    product_id: productId,
                    name: productName,
                    price: price,
                    quantity: quantity
                });
            }

            qtyElement.textContent = '0';
            updateCartDisplay();
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.product_id !== productId);
            updateCartDisplay();
        }

        function updateCartQuantity(productId, newQuantity) {
            if (newQuantity <= 0) {
                removeFromCart(productId);
                return;
            }

            const itemIndex = cart.findIndex(item => item.product_id === productId);
            if (itemIndex > -1) {
                cart[itemIndex].quantity = newQuantity;
            }

            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            const orderForm = document.getElementById('order-form');
            const clearCartBtn = document.getElementById('clear-cart');
            const cartInputItems = document.getElementById('cart-input-items');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-basket text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Your cart is empty</p>
                    </div>
                `;
                cartSummary.classList.add('hidden');
                orderForm.classList.add('hidden');
                clearCartBtn.classList.add('hidden');
                return;
            }

            let cartHTML = '';
            let subtotal = 0;
            let inputHTML = '';

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                cartHTML += `
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-gray-900">${item.name}</p>
                                <p class="text-gray-500 text-xs">₱${item.price.toFixed(2)} each</p>
                            </div>
                            <button onclick="removeFromCart(${item.product_id})"
                                class="w-6 h-6 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center transition duration-200">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateCartQuantity(${item.product_id}, ${item.quantity - 1})"
                                    class="w-6 h-6 bg-white hover:bg-gray-100 text-gray-700 rounded-full flex items-center justify-center border border-gray-200 transition duration-200">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center text-sm font-semibold text-gray-900">${item.quantity}</span>
                                <button onclick="updateCartQuantity(${item.product_id}, ${item.quantity + 1})"
                                    class="w-6 h-6 bg-white hover:bg-gray-100 text-gray-700 rounded-full flex items-center justify-center border border-gray-200 transition duration-200">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <span class="text-sm font-bold text-gray-900">₱${itemTotal.toFixed(2)}</span>
                        </div>
                    </div>
                `;

                inputHTML +=
                    `<input type="hidden" name="items[${item.product_id}][product_id]" value="${item.product_id}">
                              <input type="hidden" name="items[${item.product_id}][quantity]" value="${item.quantity}">`;
            });

            cartItemsContainer.innerHTML = cartHTML;
            cartInputItems.innerHTML = inputHTML;

            const total = subtotal;
            document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
            document.getElementById('total').textContent = `₱${total.toFixed(2)}`;

            cartSummary.classList.remove('hidden');
            orderForm.classList.remove('hidden');
            clearCartBtn.classList.remove('hidden');
        }

        function clearCart() {
            if (confirm('Are you sure you want to clear your cart?')) {
                cart = [];
                updateCartDisplay();
            }
        }

        @if (session('success'))
            alert('{{ session('success') }}');
        @endif

        @if (session('error'))
            alert('{{ session('error') }}');
        @endif
    </script>
@endsection
