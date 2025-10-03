<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $receiptData['receipt_number'] }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 650px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .paid-badge {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .content {
            padding: 30px 20px;
        }

        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .info-item {
            padding: 10px;
            background: #f9fafb;
            border-radius: 4px;
        }

        .info-item label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .info-item value {
            display: block;
            font-weight: bold;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .text-right {
            text-align: right;
        }

        .amount-paid {
            margin: 30px 0;
            padding: 20px;
            background: #d1fae5;
            border-radius: 8px;
            text-align: center;
        }

        .amount-paid-label {
            font-size: 14px;
            color: #065f46;
            margin-bottom: 8px;
        }

        .amount-paid-value {
            font-size: 32px;
            color: #047857;
            font-weight: bold;
        }

        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        @media only screen and (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>✓ PAYMENT RECEIPT</h1>
            <div class="paid-badge">PAID</div>
        </div>

        <div class="content">
            <p>Dear {{ $receiptData['tenant']['name'] }},</p>
            <p>Thank you for your payment! This email confirms that we have received your payment.</p>

            <div class="info-grid">
                <div class="info-item">
                    <label>Receipt Number</label>
                    <value>{{ $receiptData['receipt_number'] }}</value>
                </div>
                <div class="info-item">
                    <label>Invoice Number</label>
                    <value>{{ $receiptData['invoice_number'] }}</value>
                </div>
                <div class="info-item">
                    <label>Payment Date</label>
                    <value>{{ $receiptData['payment_date'] }}</value>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <value>{{ ucfirst($receiptData['payment_method']) }}</value>
                </div>
            </div>

            <div class="info-box">
                <h3 style="margin: 0 0 10px 0;">Tenant Information</h3>
                <p style="margin: 5px 0;"><strong>Name:</strong> {{ $receiptData['tenant']['name'] }}</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> {{ $receiptData['tenant']['email'] }}</p>
                <p style="margin: 5px 0;"><strong>Apartment:</strong> {{ $receiptData['tenant']['apartment'] }}</p>
            </div>

            <h3 style="margin: 20px 0 10px 0;">Payment Details</h3>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receiptData['items'] as $item)
                        <tr>
                            <td>
                                <strong>{{ $item['description'] }}</strong>
                                @if (isset($item['details']) && $item['details'])
                                    <br><small style="color: #6b7280;">{{ $item['details'] }}</small>
                                @endif
                            </td>
                            <td class="text-right"><strong>₱{{ number_format($item['total'], 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="amount-paid">
                <div class="amount-paid-label">TOTAL AMOUNT PAID</div>
                <div class="amount-paid-value">₱{{ number_format($receiptData['amount_paid'], 2) }}</div>
            </div>

            <div style="background: #f9fafb; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Received By:</strong> {{ $receiptData['received_by'] }}</p>
            </div>

            <p style="margin-top: 30px; color: #059669;">
                <strong>Your payment has been successfully processed. Thank you for your prompt payment!</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>Bencafe Apartments</strong></p>
            <p>Thank you for being a valued tenant!</p>
            <p style="font-size: 12px; margin-top: 10px;">
                This is an automated receipt. Please keep this for your records.
            </p>
            <p style="font-size: 12px; margin-top: 5px;">
                If you have any questions, please contact us at bencafeapartments@gmail.com
            </p>
        </div>
    </div>
</body>

</html>
