@extends('layouts.app')

@section('title', 'My Bills')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-semibold text-gray-900 tracking-tight mb-2">My Bills</h1>
                        <p class="text-lg text-gray-600">View and manage your billing statements</p>
                    </div>
                    <div>
                        <a href="{{ route('tenant.dashboard') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500/20 shadow-lg shadow-gray-500/25 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            @if (count($bills) > 0)
                @php
                    $pendingBills = collect($bills)->where('status', 'pending');
                    $overdueBills = collect($bills)->where('status', 'overdue');
                    $paidBills = collect($bills)->where('status', 'paid');
                    $totalOutstanding = $pendingBills->sum('amount') + $overdueBills->sum('amount');
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Outstanding Balance</h3>
                        <p class="text-3xl font-semibold text-red-600">₱{{ number_format($totalOutstanding, 2) }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/30">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Pending Bills</h3>
                        <p class="text-3xl font-semibold text-gray-900">{{ $pendingBills->count() }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Paid This Month</h3>
                        <p class="text-3xl font-semibold text-gray-900">
                            {{ $paidBills->where('due_date', '>=', now()->startOfMonth())->count() }}</p>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i class="fas fa-file-invoice text-white text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Total Bills</h3>
                        <p class="text-3xl font-semibold text-gray-900">{{ count($bills) }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200/50 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-history mr-3 text-gray-400"></i>Billing History
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Track and manage all your bills</p>
                </div>

                <div class="p-6">
                    @if (count($bills) > 0)
                        <div class="space-y-4">
                            @foreach ($bills as $bill)
                                <div
                                    class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 hover:shadow-lg transition-all duration-300 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $typeConfig = [
                                                            'rent' => [
                                                                'icon' => 'fa-home',
                                                                'gradient' => 'from-purple-500 to-indigo-600',
                                                                'shadow' => 'shadow-purple-500/30',
                                                            ],
                                                            'utilities' => [
                                                                'icon' => 'fa-bolt',
                                                                'gradient' => 'from-yellow-400 to-orange-500',
                                                                'shadow' => 'shadow-yellow-500/30',
                                                            ],
                                                            'cafe' => [
                                                                'icon' => 'fa-coffee',
                                                                'gradient' => 'from-amber-500 to-orange-600',
                                                                'shadow' => 'shadow-amber-500/30',
                                                            ],
                                                            'maintenance' => [
                                                                'icon' => 'fa-wrench',
                                                                'gradient' => 'from-blue-500 to-cyan-600',
                                                                'shadow' => 'shadow-blue-500/30',
                                                            ],
                                                            'other' => [
                                                                'icon' => 'fa-file-invoice',
                                                                'gradient' => 'from-gray-500 to-gray-600',
                                                                'shadow' => 'shadow-gray-500/30',
                                                            ],
                                                        ];
                                                        $config =
                                                            $typeConfig[strtolower($bill['type'])] ??
                                                            $typeConfig['other'];
                                                    @endphp
                                                    <div
                                                        class="w-12 h-12 bg-gradient-to-br {{ $config['gradient'] }} rounded-xl flex items-center justify-center shadow-lg {{ $config['shadow'] }}">
                                                        <i class="fas {{ $config['icon'] }} text-white text-xl"></i>
                                                    </div>
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                        <div>
                                                            <h3 class="text-lg font-bold text-gray-900">{{ $bill['type'] }}
                                                            </h3>
                                                            <p class="text-sm text-gray-600">Bill ID: #{{ $bill['id'] }}
                                                            </p>
                                                        </div>
                                                        @php
                                                            $statusBadges = [
                                                                'paid' =>
                                                                    'bg-green-500/90 text-white ring-1 ring-green-400/50',
                                                                'pending' =>
                                                                    'bg-yellow-500/90 text-white ring-1 ring-yellow-400/50',
                                                                'overdue' =>
                                                                    'bg-red-500/90 text-white ring-1 ring-red-400/50',
                                                                'cancelled' =>
                                                                    'bg-gray-500/90 text-white ring-1 ring-gray-400/50',
                                                            ];
                                                            $badgeClass =
                                                                $statusBadges[$bill['status']] ??
                                                                'bg-gray-500/90 text-white ring-1 ring-gray-400/50';
                                                        @endphp
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-xl whitespace-nowrap {{ $badgeClass }}">
                                                            {{ ucfirst($bill['status']) }}
                                                        </span>
                                                    </div>

                                                    <div class="bg-gray-50 rounded-xl p-3 mb-3 space-y-2">
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-600">
                                                                <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>Due
                                                                Date:
                                                            </span>
                                                            <span
                                                                class="font-medium text-gray-900">{{ $bill['due_date'] }}</span>
                                                        </div>
                                                        @if ($bill['status'] === 'overdue' || ($bill['status'] === 'pending' && strtotime($bill['due_date']) < time()))
                                                            <div class="text-xs text-red-600 font-medium">
                                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                                {{ abs(\Carbon\Carbon::parse($bill['due_date'])->diffInDays(now())) }}
                                                                days overdue
                                                            </div>
                                                        @elseif($bill['status'] === 'pending')
                                                            <div class="text-xs text-yellow-600 font-medium">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                Due in
                                                                {{ \Carbon\Carbon::parse($bill['due_date'])->diffInDays(now()) }}
                                                                days
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="text-2xl font-bold text-green-600">
                                                        <i
                                                            class="fas fa-peso-sign mr-1"></i>{{ number_format($bill['amount'], 2) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-shrink-0">
                                                <div class="flex flex-col gap-2">
                                                    <button onclick="showBillDetails({{ $bill['id'] }})"
                                                        class="inline-flex items-center px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm whitespace-nowrap">
                                                        <i class="fas fa-eye mr-2"></i>View Details
                                                    </button>
                                                    @if (in_array($bill['status'], ['pending', 'overdue']))
                                                        <button
                                                            onclick="showPaymentQR({{ $bill['id'] }}, '{{ $bill['type'] }}', {{ $bill['amount'] }})"
                                                            class="inline-flex items-center px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-sm whitespace-nowrap">
                                                            <i class="fas fa-qrcode mr-2"></i>Request Payment
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-invoice text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No bills yet</h3>
                            <p class="text-gray-600 mb-6 max-w-sm mx-auto">
                                You don't have any billing statements at the moment.
                            </p>
                            <a href="{{ route('tenant.dashboard') }}"
                                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-lg shadow-blue-500/25">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if (count($bills) > 0 && ($pendingBills->count() > 0 || $overdueBills->count() > 0))
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/50 rounded-2xl shadow-sm p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-base font-semibold text-blue-900 mb-2">Payment Information</h3>
                            <div class="text-sm text-blue-800 leading-relaxed space-y-1">
                                <p><i class="fas fa-qrcode mr-2"></i><strong>QR Code Payment:</strong> Click "Request
                                    Payment" to generate a QR code for staff to scan.</p>
                                <p><i class="fas fa-map-marker-alt mr-2"></i><strong>Office Location:</strong> Cafe (8:00 AM
                                    - 6:00 PM, Mon-Fri)</p>
                                <p><i class="fas fa-money-bill-wave mr-2"></i><strong>Accepted Payment:</strong> Cash</p>
                                <p><i class="fas fa-phone mr-2"></i><strong>Contact:</strong> (123) 456-7890</p>
                                @if ($overdueBills->count() > 0)
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                                        <p class="text-red-800 font-medium">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>You have
                                            {{ $overdueBills->count() }} overdue bill(s). Please visit the office
                                            immediately to avoid late fees.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 max-w-md w-full"
                onclick="event.stopPropagation()">
                <div
                    class="sticky top-0 bg-white/95 backdrop-blur-xl border-b border-gray-200/50 px-6 py-5 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-green-500/30">
                                    <i class="fas fa-qrcode text-white"></i>
                                </div>
                                <span id="modalTitle">Payment Request</span>
                            </h2>
                        </div>
                        <button onclick="closePaymentModal()"
                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fas fa-times text-gray-500"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="text-center">
                        <div id="qrcode" class="flex justify-center mb-4"></div>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-2xl p-4 border border-gray-200/50">
                        <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-keyboard mr-2 text-blue-500"></i>Manual Code
                        </h4>
                        <div class="bg-white border border-gray-300 rounded-xl p-3 mb-2">
                            <code id="manualCode" class="text-sm font-mono text-gray-800 break-all"></code>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Staff can enter this code manually if QR scanner doesn't work
                        </p>
                        <button onclick="copyManualCode()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-200">
                            <i class="fas fa-copy mr-2"></i>Copy Code
                        </button>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-200/50">
                        <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                            <i class="fas fa-list-ol mr-2"></i>Payment Instructions
                        </h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p>1. Show this QR code to the front desk staff</p>
                            <p>2. If QR scan fails, staff can use the manual code above</p>
                            <p>3. Complete payment with cash</p>
                            <p>4. Get your receipt for confirmation</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-4 border border-green-200/50">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-file-invoice-dollar mr-2 text-green-600"></i>Payment Details
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bill ID:</span>
                                <span id="billId" class="font-semibold text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bill Type:</span>
                                <span id="billType" class="font-semibold text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span id="billAmount" class="font-bold text-green-600"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tenant:</span>
                                <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl p-4 border border-yellow-200/50">
                        <h4 class="font-semibold text-yellow-900 mb-2 flex items-center">
                            <i class="fas fa-building mr-2"></i>Office Information
                        </h4>
                        <div class="text-sm text-yellow-700 space-y-1">
                            <p><strong>Location:</strong> Cafe</p>
                            <p><strong>Hours:</strong> Monday - Friday, 8:00 AM - 6:00 PM</p>
                            <p><strong>Phone:</strong> (123) 456-7890</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="closePaymentModal()"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Close
                        </button>
                        <button onclick="downloadQR()"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
                            <i class="fas fa-download mr-2"></i>Save QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        let currentQR = null;
        let currentManualCode = null;

        function showBillDetails(billId) {
            window.location.href = '/tenant/billing/' + billId;
        }

        function showPaymentQR(billId, billType, amount) {
            try {
                document.getElementById('qrcode').innerHTML = '';
                document.getElementById('billId').textContent = '#' + billId;
                document.getElementById('billType').textContent = billType;
                document.getElementById('billAmount').textContent = '₱' + amount.toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });

                const qrData = {
                    type: 'bill_payment',
                    bill_id: parseInt(billId),
                    tenant_id: {{ auth()->user()->id }},
                    tenant_name: '{{ auth()->user()->name }}',
                    bill_type: billType,
                    amount: parseFloat(amount),
                    timestamp: new Date().toISOString(),
                    office_use_only: true
                };

                const manualCode = generateManualCode(billId, amount, '{{ auth()->user()->id }}');
                currentManualCode = manualCode;
                document.getElementById('manualCode').textContent = manualCode;

                try {
                    const qrString = JSON.stringify(qrData);
                    currentQR = new QRious({
                        element: document.createElement('canvas'),
                        value: qrString,
                        size: 200,
                        background: 'white',
                        foreground: 'black',
                        level: 'M'
                    });
                    document.getElementById('qrcode').appendChild(currentQR.canvas);
                } catch (qrError) {
                    document.getElementById('qrcode').innerHTML =
                        '<div class="text-center p-4 bg-gray-100 border rounded-xl">' +
                        '<p class="text-gray-600 text-sm">QR generation failed</p>' +
                        '<p class="text-xs text-gray-500">Please use manual code below</p>' +
                        '</div>';
                }

                document.getElementById('paymentModal').classList.remove('hidden');
            } catch (error) {
                alert('Failed to generate payment request. Please try again or contact support.');
            }
        }

        function generateManualCode(billId, amount, tenantId) {
            try {
                const timestamp = Math.floor(Date.now() / 1000);
                const amountCents = Math.floor(parseFloat(amount) * 100);
                const billIdStr = parseInt(billId).toString().padStart(4, '0');
                const tenantIdStr = parseInt(tenantId).toString().padStart(3, '0');
                const amountStr = amountCents.toString().padStart(6, '0');
                const timestampLast4 = timestamp.toString().slice(-4);
                return `PAY-${billIdStr}-${tenantIdStr}-${amountStr}-${timestampLast4}`;
            } catch (error) {
                return `PAY-${billId}-${tenantId}-${Math.floor(amount * 100)}-${Date.now().toString().slice(-4)}`;
            }
        }

        function copyManualCode() {
            if (currentManualCode) {
                navigator.clipboard.writeText(currentManualCode).then(function() {
                    const button = event.target;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                    button.classList.remove('from-gray-600', 'to-gray-700');
                    button.classList.add('from-green-600', 'to-emerald-600');
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('from-green-600', 'to-emerald-600');
                        button.classList.add('from-gray-600', 'to-gray-700');
                    }, 2000);
                }, function(err) {
                    alert('Failed to copy code. Please select and copy manually.');
                });
            }
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            currentQR = null;
            currentManualCode = null;
        }

        function downloadQR() {
            if (currentQR) {
                const link = document.createElement('a');
                link.download = 'payment-qr-bill-' + document.getElementById('billId').textContent.replace('#', '') +
                    '.png';
                link.href = currentQR.canvas.toDataURL();
                link.click();
            }
        }

        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
    </script>
@endsection
