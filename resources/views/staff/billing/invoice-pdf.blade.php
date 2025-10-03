{{-- resources/views/staff/billing/invoice-pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoiceData['invoice_number'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }

        .company-info {
            float: left;
            width: 50%;
        }

        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }

        .invoice-number {
            background-color: #2563eb;
            color: white;
            padding: 10px 15px;
            display: inline-block;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .bill-to {
            background-color: #f3f4f6;
            padding: 15px;
            margin: 20px 0;
        }

        .bill-to h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table thead {
            background-color: #f3f4f6;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }

        .totals-row {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .totals-row.total {
            border-top: 2px solid #333;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
        }

        .totals-label {
            float: left;
        }

        .totals-value {
            float: right;
        }

        .notes {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="header clearfix">
        <div class="company-info">
            <h1 style="margin: 0; font-size: 24px;">INVOICE</h1>
            <p style="margin: 5px 0;"><strong>Apartment Management</strong></p>
            <p style="margin: 2px 0; font-size: 11px;">123 Main Street, City, State 12345</p>
            <p style="margin: 2px 0; font-size: 11px;">Phone: (123) 456-7890</p>
            <p style="margin: 2px 0; font-size: 11px;">Email: billing@apartment.com</p>
        </div>
        <div class="invoice-info">
            <div class="invoice-number">{{ $invoiceData['invoice_number'] }}</div>
            <p><strong>Invoice Date:</strong><br>{{ $invoiceData['invoice_date'] }}</p>
            <p><strong>Due Date:</strong><br>{{ $invoiceData['due_date'] }}</p>
            <p>
                <span class="status-badge status-{{ $invoiceData['status'] }}">
                    {{ strtoupper($invoiceData['status']) }}
                </span>
            </p>
        </div>
    </div>

    <div class="bill-to clearfix">
        <h3>BILL TO:</h3>
        <p style="margin: 3px 0;"><strong>{{ $invoiceData['tenant']['name'] }}</strong></p>
        <p style="margin: 3px 0;">{{ $invoiceData['tenant']['email'] }}</p>
        @if ($invoiceData['tenant']['contact'])
            <p style="margin: 3px 0;">{{ $invoiceData['tenant']['contact'] }}</p>
        @endif
        <p style="margin: 3px 0;">Apartment: {{ $invoiceData['tenant']['apartment'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center" style="width: 80px;">Quantity</th>
                <th class="text-right" style="width: 120px;">Unit Price</th>
                <th class="text-right" style="width: 120px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceData['items'] as $item)
                <tr>
                    <td>
                        <strong>{{ $item['description'] }}</strong>
                        @if ($item['details'])
                            <br><span style="font-size: 10px; color: #666;">{{ $item['details'] }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-right">₱{{ number_format($item['unit_price'], 2) }}</td>
                    <td class="text-right"><strong>₱{{ number_format($item['total'], 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals clearfix">
        <div class="totals-row clearfix">
            <span class="totals-label">Subtotal:</span>
            <span class="totals-value">₱{{ number_format($invoiceData['subtotal'], 2) }}</span>
        </div>
        @if ($invoiceData['tax'] > 0)
            <div class="totals-row clearfix">
                <span class="totals-label">Tax:</span>
                <span class="totals-value">₱{{ number_format($invoiceData['tax'], 2) }}</span>
            </div>
        @endif
        <div class="totals-row total clearfix">
            <span class="totals-label">Total Due:</span>
            <span class="totals-value" style="color: #2563eb;">₱{{ number_format($invoiceData['total'], 2) }}</span>
        </div>
    </div>

    <div style="clear: both;"></div>

    @if ($invoiceData['notes'])
        <div class="notes">
            <p><strong>Notes:</strong></p>
            <p>{{ $invoiceData['notes'] }}</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>{{ $invoiceData['payment_terms'] }}</p>
        <p style="margin-top: 10px;">For questions about this invoice, please contact our billing department.</p>
    </div>
</body>

</html>
