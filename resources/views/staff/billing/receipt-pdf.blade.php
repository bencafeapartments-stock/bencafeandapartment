<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $receiptData['receipt_number'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #10b981;
        }

        .receipt-title {
            font-size: 28px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }

        .paid-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 8px 20px;
            display: inline-block;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        .receipt-info {
            background-color: #f9fafb;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }

        .info-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }

        .info-value {
            font-weight: bold;
            font-size: 13px;
        }

        .received-from {
            background-color: #fff;
            border: 1px solid #e5e7eb;
            padding: 15px;
            margin: 20px 0;
        }

        .received-from h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #374151;
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
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #d1d5db;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .text-right {
            text-align: right;
        }

        .amount-paid {
            float: right;
            width: 300px;
            margin-top: 20px;
            background-color: #ecfdf5;
            padding: 20px;
            border: 2px solid #10b981;
        }

        .amount-row {
            font-size: 18px;
            font-weight: bold;
            color: #065f46;
        }

        .amount-label {
            float: left;
        }

        .amount-value {
            float: right;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .signature-section {
            margin-top: 60px;
            padding-top: 20px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 250px;
            margin: 40px 0 5px 0;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(16, 185, 129, 0.1);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="watermark">PAID</div>

    <div class="header">
        <h1 class="receipt-title">PAYMENT RECEIPT</h1>
        <div class="paid-badge">✓ PAID IN FULL</div>
    </div>

    <div class="receipt-info">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Receipt Number</div>
                    <div class="info-value">{{ $receiptData['receipt_number'] }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Invoice Number</div>
                    <div class="info-value">{{ $receiptData['invoice_number'] }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <div class="info-label">Payment Date</div>
                    <div class="info-value">{{ $receiptData['payment_date'] }}</div>
                </div>
                <div class="info-cell">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">{{ $receiptData['payment_method'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="received-from">
        <h3>RECEIVED FROM:</h3>
        <p style="margin: 3px 0;"><strong>{{ $receiptData['tenant']['name'] }}</strong></p>
        <p style="margin: 3px 0;">{{ $receiptData['tenant']['email'] }}</p>
        @if ($receiptData['tenant']['contact'])
            <p style="margin: 3px 0;">{{ $receiptData['tenant']['contact'] }}</p>
        @endif
        <p style="margin: 3px 0;">Apartment: {{ $receiptData['tenant']['apartment'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right" style="width: 150px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receiptData['items'] as $item)
                <tr>
                    <td>
                        <strong>{{ $item['description'] }}</strong>
                        @if ($item['details'])
                            <br><span style="font-size: 10px; color: #666;">{{ $item['details'] }}</span>
                        @endif
                    </td>
                    <td class="text-right"><strong>₱{{ number_format($item['total'], 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="amount-paid clearfix">
        <div class="amount-row clearfix">
            <span class="amount-label">AMOUNT PAID:</span>
            <span class="amount-value">₱{{ number_format($receiptData['amount_paid'], 2) }}</span>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="signature-section">
        <p><strong>Received By:</strong> {{ $receiptData['received_by'] }}</p>
        <div class="signature-line"></div>
        <p style="margin: 0; font-size: 10px; color: #6b7280;">Authorized Signature</p>
    </div>

    <div class="footer">
        <p style="font-size: 14px; font-weight: bold; color: #10b981; margin-bottom: 10px;">
            Thank you for your payment!
        </p>

    </div>
</body>

</html>
