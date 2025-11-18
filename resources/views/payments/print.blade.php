<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt {{ $payment->payment_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1a1a1a;
            background: #fff;
            padding: 15px;
        }

        .receipt-container {
            max-width: 100%;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
            background: #fff;
            page-break-inside: avoid;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #000;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
            color: #1a1a1a;
            letter-spacing: 1px;
        }

        .clinic-info {
            font-size: 9px;
            color: #555;
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            color: #1a1a1a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .left-column, .right-column {
            display: flex;
            flex-direction: column;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 12px;
        }

        .info-section-title {
            font-size: 10px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #ddd;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 9px;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            width: 45%;
        }

        .info-value {
            color: #1a1a1a;
            width: 55%;
            text-align: right;
        }

        /* Amount Highlight */
        .amount-highlight {
            text-align: center;
            padding: 12px;
            background: #f8f9fa;
            border: 2px solid #1a1a1a;
            margin: 12px 0;
        }

        .amount-label {
            font-size: 9px;
            color: #555;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            letter-spacing: 1px;
        }

        /* Payment Details */
        .payment-details {
            margin-bottom: 12px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
        }

        .payment-details-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a1a1a;
            text-transform: uppercase;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 9px;
        }

        /* Bill Summary */
        .bill-summary {
            margin-bottom: 12px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
        }

        .bill-summary-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a1a1a;
            text-transform: uppercase;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 9px;
        }

        .summary-row.total {
            border-top: 1px solid #1a1a1a;
            margin-top: 6px;
            padding-top: 6px;
            font-weight: bold;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }

        .status-completed { background-color: #28a745; color: #fff; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-failed { background-color: #dc3545; color: #fff; }
        .status-refunded { background-color: #6c757d; color: #fff; }

        /* Notes */
        .notes-section {
            margin-bottom: 12px;
            padding: 8px;
            background: #f8f9fa;
            border-left: 3px solid #007bff;
            font-size: 9px;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        .signature-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .signature-box {
            text-align: right;
        }

        .signature-line {
            border-top: 1px solid #1a1a1a;
            width: 200px;
            margin: 30px 0 5px auto;
        }

        .signature-label {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .receipt-container {
                border: none;
                padding: 15px;
            }
            @page {
                margin: 0.5cm;
            }
        }

        /* Compact spacing for one page */
        .compact {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="clinic-name">DR. VE AESTHETIC CLINIC AND WELLNESS CENTER</div>
            <div class="clinic-info">Zone 1, San Jose, Iriga City, Camarines Sur</div>
            <div class="clinic-info">Phone: (054) 123-4567 | Email: info@drveaesthetic.com</div>
            <div class="receipt-title">Payment Receipt</div>
        </div>

        <!-- Main Content -->
        <div class="content-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Receipt Information -->
                <div class="info-section compact">
                    <div class="info-section-title">Receipt Information</div>
                    <div class="info-row">
                        <span class="info-label">Receipt Number:</span>
                        <span class="info-value">{{ $payment->payment_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Date:</span>
                        <span class="info-value">{{ $payment->payment_date->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Time:</span>
                        <span class="info-value">{{ $payment->created_at->format('h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                        </span>
                    </div>
                </div>

                <!-- Client Information -->
                <div class="info-section compact">
                    <div class="info-section-title">Client Information</div>
                    <div class="info-row">
                        <span class="info-label">Client Name:</span>
                        <span class="info-value">{{ $payment->client->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bill Number:</span>
                        <span class="info-value">{{ $payment->bill->bill_number }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Payment Details -->
                <div class="payment-details compact">
                    <div class="payment-details-title">Payment Details</div>
                    <div class="detail-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </div>
                    @if($payment->payment_reference)
                    <div class="detail-row">
                        <span class="info-label">Reference Number:</span>
                        <span class="info-value">{{ $payment->payment_reference }}</span>
                    </div>
                    @endif
                    @if($payment->check_number)
                    <div class="detail-row">
                        <span class="info-label">Check Number:</span>
                        <span class="info-value">{{ $payment->check_number }}</span>
                    </div>
                    @endif
                    @if($payment->bank_name)
                    <div class="detail-row">
                        <span class="info-label">Bank Name:</span>
                        <span class="info-value">{{ $payment->bank_name }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="info-label">Received By:</span>
                        <span class="info-value">{{ $payment->receivedBy->name ?? 'Staff' }}</span>
                    </div>
                </div>

                <!-- Bill Summary -->
                <div class="bill-summary compact">
                    <div class="bill-summary-title">Bill Summary</div>
                    <div class="summary-row">
                        <span class="info-label">Service:</span>
                        <span class="info-value">{{ $payment->bill->appointment->service->service_name ?? 'N/A' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="info-label">Total Bill Amount:</span>
                        <span class="info-value">₱{{ number_format($payment->bill->total_amount, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="info-label">Total Paid:</span>
                        <span class="info-value" style="color: #28a745;">₱{{ number_format($payment->bill->paid_amount, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span class="info-label">Remaining Balance:</span>
                        <span class="info-value" style="color: {{ $payment->bill->remaining_balance > 0 ? '#dc3545' : '#28a745' }};">
                            ₱{{ number_format($payment->bill->remaining_balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amount Paid Highlight -->
        <div class="amount-highlight">
            <div class="amount-label">Amount Paid</div>
            <div class="amount-value">₱{{ number_format($payment->amount, 2) }}</div>
        </div>

        <!-- Notes -->
        @if($payment->notes)
        <div class="notes-section compact">
            <strong>Payment Notes:</strong> {{ $payment->notes }}
        </div>
        @endif

        <!-- Signature and Footer -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">
                    <strong>Authorized Signature</strong><br>
                    {{ $payment->receivedBy->name ?? 'Staff' }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Thank you for your payment!</strong></p>
            <p>This receipt is valid for all purposes and requires no signature.</p>
            <p style="margin-top: 4px;">For questions, please contact us at (054) 123-4567</p>
            <p style="margin-top: 2px; font-size: 7px; color: #999;">Receipt generated on {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
