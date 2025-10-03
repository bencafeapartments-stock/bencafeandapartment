<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceData['invoice_number'] }}</title>
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
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 30px 20px;
        }

        .info-box {
            background: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
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

        .total-section {
            margin: 20px 0;
            text-align: right;
        }

        .grand-total {
            font-size: 18px;
            color: #2563eb;
            font-weight: bold;
            padding-top: 10px;
            border-top: 2px solid #1f2937;
        }

        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 INVOICE</h1>
            <p>{{ $invoiceData['invoice_number'] }}</p>
        </div>

        <div class="content">
            <p>Dear {{ $invoiceData['tenant']['name'] }},</p>
            <p>Please find your invoice details below:</p>

            <div class="info-box">
                <h3 style="margin: 0 0 10px 0;">Invoice Information</h3>
                <div class="info-row">
                    <strong>Invoice Date:</strong> {{ $invoiceData['invoice_date'] }}
                </div>
                <div class="info-row">
                    <strong>Due Date:</strong> {{ $invoiceData['due_date'] }}
                </div>
                <div class="info-row">
                    <strong>Apartment:</strong> {{ $invoiceData['tenant']['apartment'] }}
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoiceData['items'] as $item)
                        <tr>
                            <td>
                                <strong>{{ $item['description'] }}</strong>
                                @if (isset($item['details']) && $item['details'])
                                    <br><small style="color: #6b7280;">{{ $item['details'] }}</small>
                                @endif
                            </td>
                            <td class="text-right">{{ $item['quantity'] }}</td>
                            <td class="text-right">₱{{ number_format($item['unit_price'], 2) }}</td>
                            <td class="text-right"><strong>₱{{ number_format($item['total'], 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div style="margin-bottom: 10px;">
                    <strong>Subtotal:</strong> ₱{{ number_format($invoiceData['subtotal'], 2) }}
                </div>
                <div class="grand-total">
                    <strong>Total Due:</strong> ₱{{ number_format($invoiceData['total'], 2) }}
                </div>
            </div>

            @if (isset($invoiceData['notes']) && $invoiceData['notes'])
                <div
                    style="background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; border-radius: 4px; margin: 20px 0;">
                    <strong>Notes:</strong> {{ $invoiceData['notes'] }}
                </div>
            @endif

            <p style="margin-top: 30px;">{{ $invoiceData['payment_terms'] ?? 'Please make payment by the due date.' }}
            </p>
        </div>

        <div class="footer">
            <p><strong>Bencafe Apartments</strong></p>
            <p>Thank you for your business!</p>
            <p style="font-size: 12px; margin-top: 10px;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>

</html>
