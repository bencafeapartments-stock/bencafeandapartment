<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ben's Cafe Apartments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .welcome-message {
            font-size: 18px;
            color: #2563eb;
            margin-bottom: 20px;
        }

        .credentials-box {
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .credentials-box h3 {
            margin-top: 0;
            color: #1e40af;
            font-size: 16px;
        }

        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: white;
            border-radius: 4px;
        }

        .credential-label {
            font-weight: bold;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .credential-value {
            font-size: 16px;
            color: #1e293b;
            margin-top: 5px;
            font-family: 'Courier New', monospace;
        }

        .apartment-info {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .apartment-info h3 {
            margin-top: 0;
            color: #059669;
        }

        .features-list {
            background-color: #f8fafc;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .features-list h3 {
            margin-top: 0;
            color: #1e40af;
        }

        .features-list ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .features-list li {
            margin: 8px 0;
            color: #475569;
        }

        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }

        .cta-button:hover {
            background-color: #1e40af;
        }

        .security-notice {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }

        .security-notice strong {
            color: #d97706;
        }

        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            margin: 5px 0;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 20px 0;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🏢 Ben Cafe Apartments</h1>
            <p>Welcome to Your New Home</p>
        </div>

        <div class="content">
            <p class="welcome-message">Hello {{ $tenantName }},</p>

            <p>Welcome to Ben Cafe Apartments! We're excited to have you join our community. Your tenant account has
                been successfully created, and you now have access to our online portal.</p>

            <div class="credentials-box">
                <h3>🔐 Your Login Credentials</h3>
                <div class="credential-item">
                    <div class="credential-label">Email Address</div>
                    <div class="credential-value">{{ $email }}</div>
                </div>
                <div class="credential-item">
                    <div class="credential-label">Temporary Password</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
            </div>

            <div class="security-notice">
                <strong>⚠️ Security Notice:</strong> For your security, please change your password immediately after
                your first login. Go to your profile settings to update your password.
            </div>

            @if ($apartmentInfo)
                <div class="apartment-info">
                    <h3>🏠 Your Apartment Details</h3>
                    <p><strong>Apartment Number:</strong> {{ $apartmentInfo['apartment_number'] }}</p>
                    <p><strong>Monthly Rent:</strong> ₱{{ number_format($apartmentInfo['monthly_rent'], 2) }}</p>
                    <p><strong>Lease Start Date:</strong> {{ date('F d, Y', strtotime($apartmentInfo['start_date'])) }}
                    </p>
                    @if (isset($apartmentInfo['security_deposit']))
                        <p><strong>Security Deposit:</strong>
                            ₱{{ number_format($apartmentInfo['security_deposit'], 2) }}</p>
                    @endif
                </div>
            @endif


            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="cta-button">Login to Your Account</a>
            </div>

            <div class="divider"></div>

            <h3>Need Help?</h3>
            <p>If you have any questions or need assistance, please don't hesitate to contact our staff:</p>
            <ul>
                <li>📧 Email: bencafeapartments@gmail.com</li>
                <li>🏢 Visit our office during business hours</li>
            </ul>

            <p style="margin-top: 30px;">We look forward to serving you!</p>
            <p><strong>The Ben Cafe Apartments Team</strong></p>
        </div>

        <div class="footer">
            <p><strong>Ben Cafe Apartments</strong></p>
            <p>Your Comfort, Our Priority</p>
            <p style="font-size: 12px; margin-top: 10px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>

</html>
