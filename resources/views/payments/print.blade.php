<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt {{ $payment->payment_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            border: 3px solid #000;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .clinic-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }

        .receipt-title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
            color: #28a745;
            letter-spacing: 2px;
        }

        .receipt-info {
            margin: 30px 0;
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 5px solid #28a745;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            width: 40%;
        }

        .info-value {
            color: #555;
            width: 60%;
            text-align: right;
        }

        .amount-section {
            margin: 40px 0;
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }

        .amount-label {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .amount-value {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .payment-details {
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #ddd;
        }

        .payment-details h3 {
            margin-bottom: 15px;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }

        .detail-item {
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 3px solid #007bff;
        }

        .detail-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .detail-value {
            font-weight: bold;
            color: #333;
        }

        .bill-summary {
            margin: 30px 0;
            padding: 20px;
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
        }

        .bill-summary h3 {
            margin-bottom: 15px;
            color: #856404;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .status-completed { background-color: #28a745; color: #fff; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-failed { background-color: #dc3545; color: #fff; }
        .status-refunded { background-color: #6c757d; color: #fff; }

        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
        }

        .watermark {
            text-align: center;
            margin: 30px 0;
            padding: 15px;
            background-color: #d4edda;
            border: 2px dashed #28a745;
            color: #155724;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #000;
            width: 250px;
            margin: 50px 0 10px 0;
        }

        @media print {
            body {
                padding: 0;
            }
            .receipt-container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="clinic-name">DR. V AESTHETIC CLINIC</div>
            <div class="clinic-info">123 Medical Center, Healthcare District</div>
            <div class="clinic-info">Phone: (123) 456-7890 | Email: info@drvaesthetic.com</div>
            <div class="clinic-info">Website: www.drvaesthetic.com</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
        </div>

        <!-- Receipt Information -->
        <div class="receipt-info">
            <div class="info-row">
                <span class="info-label">Receipt Number:</span>
                <span class="info-value">{{ $payment->payment_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Date:</span>
                <span class="info-value">{{ $payment->payment_date->format('F d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Time:</span>
                <span class="info-value">{{ $payment->created_at->format('h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Client Name:</span>
                <span class="info-value">{{ $payment->client->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Bill Number:</span>
                <span class="info-value">{{ $payment->bill->bill_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Status:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                </span>
            </div>
        </div>

        <!-- Amount Paid -->
        <div class="amount-section">
            <div class="amount-label">AMOUNT PAID</div>
            <div class="amount-value">₱{{ number_format($payment->amount, 2) }}</div>
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
            <h3>Payment Details</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Received By</div>
                    <div class="detail-value">{{ $payment->receivedBy->name ?? 'N/A' }}</div>
                </div>
                @if($payment->payment_reference)
                <div class="detail-item">
                    <div class="detail-label">Reference Number</div>
                    <div class="detail-value">{{ $payment->payment_reference }}</div>
                </div>
                @endif
                @if($payment->check_number)
                <div class="detail-item">
                    <div class="detail-label">Check Number</div>
                    <div class="detail-value">{{ $payment->check_number }}</div>
                </div>
                @endif
                @if($payment->check_date)
                <div class="detail-item">
                    <div class="detail-label">Check Date</div>
                    <div class="detail-value">{{ $payment->check_date->format('M d, Y') }}</div>
                </div>
                @endif
                @if($payment->bank_name)
                <div class="detail-item">
                    <div class="detail-label">Bank Name</div>
                    <div class="detail-value">{{ $payment->bank_name }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Bill Summary -->
        <div class="bill-summary">
            <h3>Bill Summary</h3>
            <div class="info-row">
                <span class="info-label">Service:</span>
                <span class="info-value">{{ $payment->bill->appointment->service->service_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Bill Amount:</span>
                <span class="info-value">₱{{ number_format($payment->bill->total_amount, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Paid:</span>
                <span class="info-value" style="color: #28a745;">₱{{ number_format($payment->bill->paid_amount, 2) }}</span>
            </div>
            <div class="info-row" style="border-top: 2px solid #856404; margin-top: 10px; padding-top: 10px;">
                <span class="info-label"><strong>Remaining Balance:</strong></span>
                <span class="info-value" style="color: {{ $payment->bill->remaining_balance > 0 ? '#dc3545' : '#28a745' }}; font-weight: bold;">
                    ₱{{ number_format($payment->bill->remaining_balance, 2) }}
                </span>
            </div>
        </div>

        <!-- Notes -->
        @if($payment->notes)
        <div class="notes-section">
            <strong>Payment Notes:</strong><br>
            {{ $payment->notes }}
        </div>
        @endif

        <!-- Watermark -->
        @if($payment->status === 'completed')
        <div class="watermark">
            ✓ PAYMENT RECEIVED
        </div>
        @endif

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div><strong>Authorized Signature</strong></div>
                <div style="color: #666; font-size: 10px;">{{ $payment->receivedBy->name ?? 'Staff' }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your payment!</strong></p>
            <p>This receipt is valid for all purposes and requires no signature.</p>
            <p style="margin-top: 10px;">For questions about this payment, please contact us at (123) 456-7890</p>
            <p style="margin-top: 5px; font-size: 10px; color: #999;">Receipt generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>

