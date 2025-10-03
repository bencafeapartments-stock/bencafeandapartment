@extends('layouts.app')

@section('title', 'Cafe POS System')
@section('page-title', 'Point of Sale System')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900 tracking-tight mb-1">
                            <i class="fas fa-cash-register text-green-600 mr-2"></i>
                            Cafe POS System
                        </h1>
                        <p class="text-sm text-gray-600">Point of Sale Terminal • <span id="orderTime"
                                class="font-medium"></span></p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="newOrder()"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>New Order
                        </button>
                        <button onclick="holdOrder()"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-yellow-600 rounded-xl hover:bg-yellow-700 shadow-lg shadow-yellow-500/30 transition-all duration-200">
                            <i class="fas fa-pause mr-2"></i>Hold Order
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


                <div class="lg:col-span-2">
                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden h-[calc(100vh-220px)]">

                        <div class="px-6 py-4 border-b border-gray-200/50 bg-gray-50/50">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-utensils text-gray-600 text-sm"></i>
                                    </div>
                                    Menu Items
                                </h2>
                                <div class="flex space-x-2 w-full sm:w-auto">
                                    <input type="text" id="menuSearch" placeholder="Search menu..."
                                        class="flex-1 sm:flex-none px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <select id="categoryFilter"
                                        class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">All Categories</option>
                                        <option value="beverages">Beverages</option>
                                        <option value="food">Food</option>
                                        <option value="desserts">Desserts</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 overflow-y-auto h-[calc(100vh-340px)]">
                            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="menuGrid">
                                @foreach ($menuItems as $item)
                                    <div class="group bg-white rounded-2xl border-2 border-gray-200 p-4 cursor-pointer transition-all duration-200 hover:border-blue-400 hover:shadow-lg hover:-translate-y-1"
                                        data-id="{{ $item['id'] }}" data-name="{{ $item['name'] }}"
                                        data-price="{{ $item['price'] }}" data-category="{{ $item['category'] }}"
                                        onclick="addToOrder('{{ $item['name'] }}', {{ $item['price'] }})">
                                        <div class="text-center">
                                            <div
                                                class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:from-blue-100 group-hover:to-blue-200 transition-all duration-200">
                                                <i
                                                    class="fas fa-utensils text-gray-500 text-xl group-hover:text-blue-600 transition-colors duration-200"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $item['name'] }}</h4>
                                            <p class="text-green-600 font-bold text-lg">₱{{ number_format($item['price']) }}
                                            </p>
                                            <span
                                                class="inline-block text-xs text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full mt-2">
                                                {{ ucfirst($item['category']) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden h-[calc(100vh-220px)] flex flex-col">

                        <div class="px-6 py-4 border-b border-gray-200/50 bg-gray-50/50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shopping-cart text-gray-600 text-sm"></i>
                                </div>
                                Current Order
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Customer</label>
                                <select id="tenant_id" name="tenant_id" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Choose a customer...</option>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}">
                                            {{ $tenant->name }}
                                            @if ($tenant->apartment_number)
                                                - Apt {{ $tenant->apartment_number }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4">
                            <div id="orderItems" class="space-y-2">
                                <div class="text-center py-12 text-gray-500">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="font-medium">No items in order</p>
                                    <p class="text-sm mt-1">Click menu items to add</p>
                                </div>
                            </div>
                        </div>


                        <div class="border-t border-gray-200/50 p-4 bg-gray-50/30">

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Items:</span>
                                    <span id="itemCount" class="font-medium">0</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal:</span>
                                    <span id="subtotal" class="font-medium">₱0.00</span>
                                </div>
                                <div class="flex justify-between text-xl font-bold border-t border-gray-200 pt-3">
                                    <span class="text-gray-900">Total:</span>
                                    <span id="totalAmount" class="text-green-600">₱0.00</span>
                                </div>
                            </div>


                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" onclick="setPaymentStatus('paid')"
                                        class="payment-btn px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-medium bg-white hover:bg-gray-50 transition-all duration-200"
                                        data-status="paid">
                                        <i class="fas fa-money-bill mr-1.5"></i>Paid
                                    </button>
                                    <button type="button" onclick="setPaymentStatus('unpaid')"
                                        class="payment-btn px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-medium bg-white hover:bg-gray-50 transition-all duration-200"
                                        data-status="unpaid">
                                        <i class="fas fa-credit-card mr-1.5"></i>Unpaid
                                    </button>
                                </div>
                            </div>


                            <div class="space-y-2">
                                <button onclick="processOrder()" id="processBtn" disabled
                                    class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl shadow-lg transition-all duration-200">
                                    <i class="fas fa-check mr-2"></i>Process Order
                                </button>
                                <div class="grid grid-cols-2 gap-2">
                                    <button onclick="clearOrder()"
                                        class="px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors duration-200">
                                        <i class="fas fa-trash mr-1.5"></i>Clear
                                    </button>
                                    <button onclick="printReceipt()"
                                        class="px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors duration-200">
                                        <i class="fas fa-print mr-1.5"></i>Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="orderForm" action="{{ route('staff.orders.store') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="tenant_id" id="form_tenant_id">
                <input type="hidden" name="payment_status" id="form_payment_status">
                <input type="hidden" name="total_amount" id="form_total_amount">
                <div id="form_items_container"></div>
            </form>
        </div>
    </div>


    <!-- @if (session('success'))
        <div id="successMessage"
            class="fixed top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 animate-slide-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-xl mr-3"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif


    @if ($errors->any())
        <div id="errorMessage"
            class="fixed top-4 right-4 bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 animate-slide-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif -->

    @push('styles')
        <style>
            @keyframes slide-in {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .animate-slide-in {
                animation: slide-in 0.3s ease-out;
            }

            /* Custom scrollbar */
            .overflow-y-auto::-webkit-scrollbar {
                width: 8px;
            }

            .overflow-y-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    @endpush

    <script>
        let currentOrder = {};
        let currentPaymentStatus = null;
        let orderTotal = 0;

        document.addEventListener('DOMContentLoaded', function() {
            updateOrderDisplay();
            updateClock();
            setInterval(updateClock, 1000);

            const messages = document.querySelectorAll('#successMessage, #errorMessage');
            if (messages.length > 0) {
                setTimeout(() => {
                    messages.forEach(msg => {
                        msg.style.transition = 'all 0.3s ease-out';
                        msg.style.opacity = '0';
                        msg.style.transform = 'translateX(400px)';
                        setTimeout(() => msg.remove(), 300);
                    });
                }, 5000);
            }
        });

        function updateClock() {
            const now = new Date();
            document.getElementById('orderTime').textContent = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function addToOrder(itemName, itemPrice) {
            if (currentOrder[itemName]) {
                currentOrder[itemName].quantity++;
                currentOrder[itemName].total = currentOrder[itemName].quantity * itemPrice;
            } else {
                currentOrder[itemName] = {
                    name: itemName,
                    price: itemPrice,
                    quantity: 1,
                    total: itemPrice
                };
            }
            updateOrderDisplay();

            const menuItem = document.querySelector(`[data-name="${itemName}"]`);
            menuItem.classList.add('scale-95');
            setTimeout(() => menuItem.classList.remove('scale-95'), 150);
        }

        function removeFromOrder(itemName) {
            if (currentOrder[itemName]) {
                if (currentOrder[itemName].quantity > 1) {
                    currentOrder[itemName].quantity--;
                    currentOrder[itemName].total = currentOrder[itemName].quantity * currentOrder[itemName].price;
                } else {
                    delete currentOrder[itemName];
                }
                updateOrderDisplay();
            }
        }

        function updateQuantity(itemName, newQuantity) {
            if (newQuantity <= 0) {
                delete currentOrder[itemName];
            } else {
                currentOrder[itemName].quantity = newQuantity;
                currentOrder[itemName].total = newQuantity * currentOrder[itemName].price;
            }
            updateOrderDisplay();
        }

        function updateOrderDisplay() {
            const orderContainer = document.getElementById('orderItems');
            const subtotal = document.getElementById('subtotal');
            const itemCount = document.getElementById('itemCount');
            const totalAmount = document.getElementById('totalAmount');
            const processBtn = document.getElementById('processBtn');

            orderTotal = 0;
            let totalItems = 0;

            if (Object.keys(currentOrder).length === 0) {
                orderContainer.innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
                        </div>
                        <p class="font-medium">No items in order</p>
                        <p class="text-sm mt-1">Click menu items to add</p>
                    </div>
                `;
                processBtn.disabled = true;
            } else {
                let orderHtml = '';

                for (const [itemName, item] of Object.entries(currentOrder)) {
                    orderTotal += item.total;
                    totalItems += item.quantity;

                    orderHtml += `
                        <div class="bg-white rounded-xl p-3 border border-gray-200 hover:border-gray-300 transition-all duration-200">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">${item.name}</h4>
                                    <p class="text-xs text-gray-500">₱${item.price.toLocaleString()} each</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <button onclick="removeFromOrder('${itemName}')"
                                        class="w-7 h-7 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" value="${item.quantity}" min="1" max="99"
                                        onchange="updateQuantity('${itemName}', parseInt(this.value))"
                                        class="w-14 text-center border border-gray-300 rounded-lg text-sm py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button onclick="addToOrder('${itemName}', ${item.price})"
                                        class="w-7 h-7 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg flex items-center justify-center transition-colors duration-200">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                <span class="font-bold text-green-600">₱${item.total.toLocaleString()}</span>
                            </div>
                        </div>
                    `;
                }

                orderContainer.innerHTML = orderHtml;
                processBtn.disabled = false;
            }

            subtotal.textContent = `₱${orderTotal.toLocaleString()}`;
            itemCount.textContent = totalItems;
            totalAmount.textContent = `₱${orderTotal.toLocaleString()}`;
        }

        function setPaymentStatus(status) {
            currentPaymentStatus = status;

            document.querySelectorAll('.payment-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            });

            const selectedBtn = document.querySelector(`[data-status="${status}"]`);
            selectedBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
            selectedBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
        }

        function processOrder() {
            const tenantId = document.getElementById('tenant_id').value;

            if (!tenantId) {
                alert('Please select a customer');
                document.getElementById('tenant_id').focus();
                return;
            }

            if (!currentPaymentStatus) {
                alert('Please select payment status');
                return;
            }

            if (Object.keys(currentOrder).length === 0) {
                alert('Please add items to the order');
                return;
            }

            document.getElementById('form_tenant_id').value = tenantId;
            document.getElementById('form_payment_status').value = currentPaymentStatus;
            document.getElementById('form_total_amount').value = orderTotal;

            const itemsContainer = document.getElementById('form_items_container');
            itemsContainer.innerHTML = '';

            for (const [itemName, item] of Object.entries(currentOrder)) {
                for (let i = 0; i < item.quantity; i++) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'items[]';
                    input.value = itemName;
                    itemsContainer.appendChild(input);
                }
            }

            document.getElementById('orderForm').submit();
        }

        function newOrder() {
            clearOrder();
            document.getElementById('tenant_id').value = '';
            currentPaymentStatus = null;

            document.querySelectorAll('.payment-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            });
        }

        function clearOrder() {
            if (Object.keys(currentOrder).length > 0) {
                if (!confirm('Are you sure you want to clear this order?')) {
                    return;
                }
            }
            currentOrder = {};
            updateOrderDisplay();
        }

        function holdOrder() {
            if (Object.keys(currentOrder).length === 0) {
                alert('No items to hold');
                return;
            }

            const tenantId = document.getElementById('tenant_id').value;
            const heldOrders = JSON.parse(localStorage.getItem('heldOrders') || '[]');

            heldOrders.push({
                id: Date.now(),
                items: currentOrder,
                total: orderTotal,
                tenantId: tenantId,
                paymentStatus: currentPaymentStatus,
                timestamp: new Date().toISOString()
            });

            localStorage.setItem('heldOrders', JSON.stringify(heldOrders));
            newOrder();
            alert('Order held successfully');
        }

        function printReceipt() {
            if (Object.keys(currentOrder).length === 0) {
                alert('No items to print');
                return;
            }
            alert('Print functionality coming soon');
        }

        document.getElementById('menuSearch').addEventListener('input', filterMenu);
        document.getElementById('categoryFilter').addEventListener('change', filterMenu);

        function filterMenu() {
            const searchTerm = document.getElementById('menuSearch').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                const name = item.dataset.name.toLowerCase();
                const itemCategory = item.dataset.category;

                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !category || itemCategory === category;

                item.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
            });
        }
    </script>
@endsection
