@extends('layouts.app')

@section('title', 'Billing Management')
@section('page-title', 'Billing Management')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }

            .print-content {
                display: block !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-file-invoice-dollar text-blue-600 mr-2"></i>Billing Management
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Manage bills and track payment statuses
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button onclick="openQRScanner()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-qrcode mr-2"></i>
                    Scan QR Payment
                </button>
                <a href="{{ route('staff.billing.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Create Bill
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="sm:flex sm:items-center sm:justify-between mb-6">
                    <div class="sm:flex-auto">
                        <h3 class="text-lg font-medium text-gray-900">Bills Overview</h3>
                        <p class="mt-1 text-sm text-gray-600">Track all billing records and payment statuses.</p>
                    </div>
                </div>

                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Tenant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Due Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bills as $bill)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $bill['tenant_name'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $bill['tenant_email'] }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $bill['billing_type_label'] }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                @if (isset($bill['apartment_number']))
                                                    Apt {{ $bill['apartment_number'] }}
                                                @endif
                                                @if (isset($bill['billing_month']))
                                                    - {{ $bill['billing_month'] }}
                                                @endif
                                            </div>
                                            @if (isset($bill['description']) && $bill['description'])
                                                <div class="text-xs text-gray-400">
                                                    {{ Str::limit($bill['description'], 30) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-semibold">{{ $bill['formatted_amount'] }}</span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($bill['due_date'])->format('M d, Y') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($bill['status'] == 'paid') bg-green-100 text-green-800
                                            @elseif($bill['status'] == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($bill['status'] == 'overdue') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if ($bill['status'] == 'paid')
                                                <i class="fas fa-check-circle mr-1"></i>Paid
                                            @elseif($bill['status'] == 'pending')
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            @elseif($bill['status'] == 'overdue')
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i>{{ ucfirst($bill['status']) }}
                                            @endif
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewInvoice({{ $bill['id'] }})"
                                                class="text-blue-600 hover:text-blue-900" title="View Invoice">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>

                                            @if ($bill['status'] === 'paid')
                                                <button onclick="viewReceipt({{ $bill['id'] }})"
                                                    class="text-green-600 hover:text-green-900" title="View Receipt">
                                                    <i class="fas fa-receipt"></i>
                                                </button>


                                                <button onclick="sendReceiptEmail({{ $bill['id'] }})"
                                                    class="text-purple-600 hover:text-purple-900"
                                                    title="Send Receipt Email">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            @else
                                                <button onclick="sendInvoiceEmail({{ $bill['id'] }})"
                                                    class="text-purple-600 hover:text-purple-900"
                                                    title="Send Invoice Email">
                                                    <i class="fas fa-envelope"></i>
                                                </button>

                                                <form action="{{ route('staff.billing.paid', $bill['id']) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-green-600 hover:text-green-900"
                                                        title="Mark as Paid">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-file-invoice text-4xl mb-4"></i>
                                        <div>No bills found.</div>
                                        <a href="{{ route('staff.billing.create') }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            Create your first bill
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Invoice Modal --}}
    <div id="invoiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4 print:hidden">
                <h3 class="text-xl font-bold text-gray-900">Invoice</h3>
                <div class="flex space-x-2">
                    <button onclick="printInvoice()" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                        <i class="fas fa-print mr-1"></i>Print
                    </button>
                    <button onclick="downloadInvoicePDF()"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                        <i class="fas fa-download mr-1"></i>PDF
                    </button>
                    <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div id="invoiceContent" class="p-6 border-t">
                {{-- Invoice content will be loaded here --}}
            </div>
        </div>
    </div>

    {{-- Receipt Modal --}}
    <div id="receiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4 print:hidden">
                <h3 class="text-xl font-bold text-gray-900">Receipt</h3>
                <div class="flex space-x-2">
                    <button onclick="printReceipt()" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                        <i class="fas fa-print mr-1"></i>Print
                    </button>
                    <button onclick="downloadReceiptPDF()"
                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                        <i class="fas fa-download mr-1"></i>PDF
                    </button>
                    <button onclick="closeReceiptModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div id="receiptContent" class="p-6 border-t">
                {{-- Receipt content will be loaded here --}}
            </div>
        </div>
    </div>

    {{-- QR Scanner Modal --}}
    <div id="qrScannerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Scan Payment QR Code</h3>
                    <button onclick="closeQRScanner()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <div id="scanner"
                        class="w-full h-64 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Camera starting automatically...</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Or Enter QR Data Manually</h4>
                    <textarea id="manualQRData" rows="3" placeholder="Paste QR code data here..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <button onclick="processManualQR()"
                        class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Process Manual Entry
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Process Modal --}}
    <div id="paymentProcessModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Process Payment</h3>
                    <button onclick="closePaymentProcess()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="paymentDetails" class="bg-gray-50 rounded-lg p-4 mb-6"></div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="paymentMethod"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="cash">Cash</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Staff Notes (Optional)</label>
                    <textarea id="staffNotes" rows="2" placeholder="Add any notes about this payment..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex space-x-3">
                    <button onclick="closePaymentProcess()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button onclick="confirmPayment()"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrcodeScanner = null;
        let currentPaymentData = null;
        let currentBillId = null;

        // Invoice Modal Functions
        function viewInvoice(billId) {
            currentBillId = billId;
            document.getElementById('invoiceModal').classList.remove('hidden');
            loadInvoiceData(billId);
        }

        function closeInvoiceModal() {
            document.getElementById('invoiceModal').classList.add('hidden');
            currentBillId = null;
        }

        function loadInvoiceData(billId) {
            fetch(`/staff/billing/${billId}/invoice-data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('invoiceContent').innerHTML = generateInvoiceHTML(data);
                })
                .catch(error => {
                    console.error('Error loading invoice:', error);
                    alert('Failed to load invoice data');
                });
        }

        function generateInvoiceHTML(data) {
            return `
                <div class="invoice-content">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">INVOICE</h2>
                            <p class="text-gray-600 mt-2">Apartment Management</p>
                            <p class="text-sm text-gray-500">123 Main Street, City 12345</p>
                            <p class="text-sm text-gray-500">Phone: (123) 456-7890</p>
                        </div>
                        <div class="text-right">
                            <div class="bg-blue-600 text-white px-4 py-2 rounded mb-2">
                                ${data.invoice_number}
                            </div>
                            <p class="text-sm"><strong>Date:</strong> ${data.invoice_date}</p>
                            <p class="text-sm"><strong>Due:</strong> ${data.due_date}</p>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <h3 class="font-semibold mb-2">BILL TO:</h3>
                        <p class="font-medium">${data.tenant.name}</p>
                        <p class="text-sm text-gray-600">${data.tenant.email}</p>
                        ${data.tenant.contact ? `<p class="text-sm text-gray-600">${data.tenant.contact}</p>` : ''}
                        <p class="text-sm text-gray-600">Apartment: ${data.tenant.apartment}</p>
                    </div>

                    <table class="w-full mb-6">
                        <thead class="bg-gray-100 border-b-2 border-gray-300">
                            <tr>
                                <th class="text-left py-2 px-4">Description</th>
                                <th class="text-center py-2 px-4">Qty</th>
                                <th class="text-right py-2 px-4">Unit Price</th>
                                <th class="text-right py-2 px-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.items.map(item => `
                                                    <tr class="border-b">
                                                        <td class="py-2 px-4">
                                                            <p class="font-medium">${item.description}</p>
                                                            ${item.details ? `<p class="text-sm text-gray-600">${item.details}</p>` : ''}
                                                        </td>
                                                        <td class="text-center py-2 px-4">${item.quantity}</td>
                                                        <td class="text-right py-2 px-4">₱${parseFloat(item.unit_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                        <td class="text-right py-2 px-4 font-medium">₱${parseFloat(item.total).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                    </tr>
                                                `).join('')}
                        </tbody>
                    </table>

                    <div class="flex justify-end mb-6">
                        <div class="w-64">
                            <div class="flex justify-between py-2 border-b">
                                <span>Subtotal:</span>
                                <span>₱${parseFloat(data.subtotal).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                            </div>
                            <div class="flex justify-between py-3 border-t-2 border-gray-900 font-bold">
                                <span class="text-lg">Total Due:</span>
                                <span class="text-lg text-blue-600">₱${parseFloat(data.total).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                    </div>

                    ${data.notes ? `
                                            <div class="border-t pt-4">
                                                <p class="text-sm text-gray-600"><strong>Notes:</strong> ${data.notes}</p>
                                            </div>
                                        ` : ''}

                    <div class="mt-8 pt-4 border-t text-center text-sm text-gray-500">
                        <p>Thank you for your business!</p>
                        <p class="mt-2">${data.payment_terms}</p>
                    </div>
                </div>
            `;
        }

        function printInvoice() {
            window.print();
        }

        function downloadInvoicePDF() {
            if (currentBillId) {
                window.location.href = `/staff/billing/${currentBillId}/invoice/pdf`;
            }
        }

        // Receipt Modal Functions
        function viewReceipt(billId) {
            currentBillId = billId;
            document.getElementById('receiptModal').classList.remove('hidden');
            loadReceiptData(billId);
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.add('hidden');
            currentBillId = null;
        }

        function loadReceiptData(billId) {
            fetch(`/staff/billing/${billId}/receipt-data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('receiptContent').innerHTML = generateReceiptHTML(data);
                })
                .catch(error => {
                    console.error('Error loading receipt:', error);
                    alert('Failed to load receipt data');
                });
        }

        function generateReceiptHTML(data) {
            return `
                <div class="receipt-content">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">PAYMENT RECEIPT</h2>
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded inline-block mt-2">
                            <i class="fas fa-check-circle mr-1"></i>PAID
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">Receipt Number</p>
                            <p class="font-semibold">${data.receipt_number}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Invoice Number</p>
                            <p class="font-semibold">${data.invoice_number}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Date</p>
                            <p class="font-semibold">${data.payment_date}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Payment Method</p>
                            <p class="font-semibold">${data.payment_method}</p>
                        </div>
                    </div>

                    <div class="mb-6 p-4 border rounded">
                        <h3 class="font-semibold mb-2">RECEIVED FROM:</h3>
                        <p class="font-medium">${data.tenant.name}</p>
                        <p class="text-sm text-gray-600">${data.tenant.email}</p>
                        <p class="text-sm text-gray-600">Apartment: ${data.tenant.apartment}</p>
                    </div>

                    <table class="w-full mb-6">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-2 px-4">Description</th>
                                <th class="text-right py-2 px-4">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.items.map(item => `
                                                    <tr class="border-b">
                                                        <td class="py-2 px-4">
                                                            <p class="font-medium">${item.description}</p>
                                                            ${item.details ? `<p class="text-sm text-gray-600">${item.details}</p>` : ''}
                                                        </td>
                                                        <td class="text-right py-2 px-4">₱${parseFloat(item.total).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                    </tr>
                                                `).join('')}
                        </tbody>
                    </table>

                    <div class="flex justify-end mb-6">
                        <div class="w-64">
                            <div class="flex justify-between py-3 border-t-2 border-gray-900 font-bold text-lg">
                                <span>Amount Paid:</span>
                                <span class="text-green-600">₱${parseFloat(data.amount_paid).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t">
                        <p class="text-sm"><strong>Received By:</strong> ${data.received_by}</p>
                        <p class="text-sm mt-4 text-center text-gray-600">Thank you for your payment!</p>
                    </div>
                </div>
            `;
        }

        function printReceipt() {
            window.print();
        }

        function downloadReceiptPDF() {
            if (currentBillId) {
                window.location.href = `/staff/billing/${currentBillId}/receipt/pdf`;
            }
        }

        // QR Scanner Functions
        function openQRScanner() {
            document.getElementById('qrScannerModal').classList.remove('hidden');
            startScanner();
        }

        function closeQRScanner() {
            stopScanner();
            document.getElementById('qrScannerModal').classList.add('hidden');
            document.getElementById('manualQRData').value = '';
            document.getElementById('scanner').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-camera text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Camera starting automatically...</p>
                </div>
            `;
        }

        function startScanner() {
            if (html5QrcodeScanner) {
                stopScanner();
            }

            html5QrcodeScanner = new Html5Qrcode("scanner");

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    html5QrcodeScanner.start(
                        devices[0].id, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        (decodedText) => processQRData(decodedText),
                        () => {}
                    ).catch(err => alert('Failed to start camera. Please use manual entry.'));
                } else {
                    alert('No cameras found. Please use manual entry.');
                }
            }).catch(() => alert('Camera access failed. Please use manual entry.'));
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    html5QrcodeScanner = null;
                }).catch(() => {
                    html5QrcodeScanner = null;
                });
            }
        }

        function processManualQR() {
            const qrData = document.getElementById('manualQRData').value.trim();
            if (!qrData) {
                alert('Please enter QR code data');
                return;
            }
            processQRData(qrData);
        }

        function processQRData(qrText) {
            try {
                let paymentData;
                try {
                    paymentData = JSON.parse(qrText);
                    if (paymentData.type !== 'bill_payment' || !paymentData.office_use_only) {
                        throw new Error('Invalid payment QR code structure');
                    }
                    if (!paymentData.bill_id || !paymentData.tenant_id || !paymentData.amount) {
                        throw new Error('Missing required payment data');
                    }
                } catch (jsonError) {
                    return processManualCodeFormat(qrText);
                }

                currentPaymentData = paymentData;
                closeQRScanner();
                showPaymentProcess(paymentData);
            } catch (error) {
                console.error('QR processing error:', error);
                alert('Invalid QR code format: ' + error.message);
            }
        }

        function processManualCodeFormat(qrText) {
            try {
                const parts = qrText.trim().split('-');
                if (parts.length !== 5 || parts[0] !== 'PAY') {
                    throw new Error('Invalid manual code format. Expected: PAY-XXXX-XXX-XXXXXX-XXXX');
                }

                const billId = parseInt(parts[1]);
                const tenantId = parseInt(parts[2]);
                const amountCents = parseInt(parts[3]);
                const amount = amountCents / 100;

                const apiUrl = "{{ route('staff.api.tenant.details', ':tenantId') }}".replace(':tenantId', tenantId);

                fetch(apiUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(tenantData => {
                        const paymentData = {
                            type: 'bill_payment',
                            bill_id: billId,
                            tenant_id: tenantId,
                            tenant_name: tenantData.name || 'Unknown Tenant',
                            bill_type: 'Manual Entry',
                            amount: amount,
                            timestamp: new Date().toISOString(),
                            office_use_only: true,
                            manual_code: qrText.trim()
                        };

                        currentPaymentData = paymentData;
                        closeQRScanner();
                        showPaymentProcess(paymentData);
                    })
                    .catch(error => {
                        console.error('API call failed:', error);
                        alert('Failed to load tenant details. Please try again.');
                    });
            } catch (error) {
                console.error('Manual code processing error:', error);
                throw new Error('Failed to process manual code: ' + error.message);
            }
        }

        function showPaymentProcess(paymentData) {
            const detailsHTML = `
                <h4 class="font-medium text-gray-900 mb-2">Payment Details</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bill ID:</span>
                        <span class="font-medium">#${paymentData.bill_id}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tenant:</span>
                        <span class="font-medium">${paymentData.tenant_name}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bill Type:</span>
                        <span class="font-medium">${paymentData.bill_type}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-medium text-green-600">₱${paymentData.amount.toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Request Time:</span>
                        <span class="font-medium">${new Date(paymentData.timestamp).toLocaleString()}</span>
                    </div>
                </div>
            `;

            document.getElementById('paymentDetails').innerHTML = detailsHTML;
            document.getElementById('paymentProcessModal').classList.remove('hidden');
        }

        function closePaymentProcess() {
            document.getElementById('paymentProcessModal').classList.add('hidden');
            document.getElementById('staffNotes').value = '';
            currentPaymentData = null;
        }

        function confirmPayment() {
            if (!currentPaymentData) {
                alert('No payment data available');
                return;
            }

            const paymentMethod = document.getElementById('paymentMethod').value;
            const staffNotes = document.getElementById('staffNotes').value;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('staff.billing.paid', ':bill_id') }}'.replace(':bill_id', currentPaymentData.bill_id);

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            const paymentMethodInput = document.createElement('input');
            paymentMethodInput.type = 'hidden';
            paymentMethodInput.name = 'payment_method';
            paymentMethodInput.value = paymentMethod;
            form.appendChild(paymentMethodInput);

            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'staff_notes';
            notesInput.value =
                `QR Payment - ${staffNotes} | Tenant: ${currentPaymentData.tenant_name} | Method: ${paymentMethod}`;
            form.appendChild(notesInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Close modals when clicking outside
        document.getElementById('qrScannerModal').addEventListener('click', function(e) {
            if (e.target === this) closeQRScanner();
        });

        document.getElementById('paymentProcessModal').addEventListener('click', function(e) {
            if (e.target === this) closePaymentProcess();
        });

        document.getElementById('invoiceModal').addEventListener('click', function(e) {
            if (e.target === this) closeInvoiceModal();
        });

        document.getElementById('receiptModal').addEventListener('click', function(e) {
            if (e.target === this) closeReceiptModal();
        });

        // Email Sending Functions
        function sendInvoiceEmail(billId) {
            if (!confirm('Send invoice email to tenant?')) {
                return;
            }

            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch(`/staff/billing/${billId}/send-invoice-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Invoice email sent successfully!', 'success');
                    } else {
                        showNotification('Failed to send invoice email: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to send invoice email', 'error');
                })
                .finally(() => {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                });
        }

        function sendReceiptEmail(billId) {
            if (!confirm('Send receipt email to tenant?')) {
                return;
            }

            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch(`/staff/billing/${billId}/send-receipt-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Receipt email sent successfully!', 'success');
                    } else {
                        showNotification('Failed to send receipt email: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to send receipt email', 'error');
                })
                .finally(() => {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                });
        }

        // Notification Helper Function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white transform transition-all duration-300`;

            notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => notification.classList.add('opacity-100'), 10);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('opacity-0');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
@endsection
