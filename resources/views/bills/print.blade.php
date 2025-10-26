<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill {{ $bill->bill_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 10px;
        }

        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #2c3e50;
        }

        .clinic-info {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }

        .bill-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 8px;
            color: #e74c3c;
        }

        .bill-info-section {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .bill-info-left, .bill-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-group {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .info-value {
            color: #555;
            padding-left: 8px;
        }

        .bill-items {
            margin: 15px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #2c3e50;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .items-table tr:last-child td {
            border-bottom: 2px solid #2c3e50;
        }

        .totals-section {
            width: 60%;
            margin-left: auto;
            margin-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        .total-row.subtotal {
            background-color: #f8f9fa;
        }

        .total-row.grand-total {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 14px;
            border: none;
            margin-top: 5px;
        }

        .payment-status {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .status-pending { background-color: #ffc107; color: #000; }
        .status-partial { background-color: #17a2b8; color: #fff; }
        .status-paid { background-color: #28a745; color: #fff; }
        .status-overdue { background-color: #dc3545; color: #fff; }
        .status-cancelled { background-color: #6c757d; color: #fff; }

        .payment-history {
            margin-top: 15px;
        }

        .payment-history h3 {
            margin-bottom: 8px;
            color: #2c3e50;
            border-bottom: 1px solid #2c3e50;
            padding-bottom: 3px;
            font-size: 11px;
        }

        .notes-section {
            margin-top: 15px;
            padding: 8px;
            background-color: #fff3cd;
            border-left: 3px solid #ffc107;
            font-size: 10px;
        }

        .terms-section {
            margin-top: 15px;
            padding: 8px;
            background-color: #e7f3ff;
            border-left: 3px solid #007bff;
            font-size: 9px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin: 20px auto 5px;
        }

        @media print {
            body {
                padding: 0;
            }
            .bill-container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="bill-container">
        <!-- Header -->
        <div class="header">
            <div class="clinic-name">DR. VE AESTHETIC CLINIC</div>
            <div class="clinic-info">123 Medical Center, Healthcare District</div>
            <div class="clinic-info">Phone: (123) 456-7890 | Email: info@drvaesthetic.com</div>
            <div class="clinic-info">Website: www.drvaesthetic.com</div>
            <div class="bill-title">BILL / INVOICE</div>
        </div>

        <!-- Bill Information -->
        <div class="bill-info-section">
            <div class="bill-info-left">
                <div class="info-group">
                    <div class="info-label">Bill To:</div>
                    <div class="info-value">{{ $bill->client->name }}</div>
                    <div class="info-value">{{ $bill->client->email }}</div>
                    <div class="info-value">{{ $bill->client->phone }}</div>
                    @if($bill->client->address)
                        <div class="info-value">{{ $bill->client->address }}</div>
                    @endif
                </div>
            </div>
            <div class="bill-info-right">
                <div class="info-group">
                    <div class="info-label">Bill Number:</div>
                    <div class="info-value">{{ $bill->bill_number }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Bill Date:</div>
                    <div class="info-value">{{ $bill->bill_date->format('F d, Y') }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Due Date:</div>
                    <div class="info-value">{{ $bill->due_date->format('F d, Y') }}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $bill->status }}">{{ ucfirst($bill->status) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill Items -->
        <div class="bill-items">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="width: 100px; text-align: center;">Type</th>
                        <th style="width: 150px; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $bill->appointment->service->service_name }}</strong>
                            @if($bill->description)
                                <br><small style="color: #666;">{{ $bill->description }}</small>
                            @endif
                            <br><small style="color: #999;">Appointment Date: {{ $bill->appointment->appointment_date->format('F d, Y') }}</small>
                        </td>
                        <td style="text-align: center;">
                            <span style="background-color: #e9ecef; padding: 3px 8px; border-radius: 3px; font-size: 10px;">
                                {{ ucfirst($bill->bill_type) }}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: bold;">₱{{ number_format($bill->subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row subtotal">
                <span>Subtotal:</span>
                <span>₱{{ number_format($bill->subtotal, 2) }}</span>
            </div>
            @if($bill->tax_amount > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>₱{{ number_format($bill->tax_amount, 2) }}</span>
            </div>
            @endif
            @if($bill->discount_amount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>-₱{{ number_format($bill->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL AMOUNT:</span>
                <span>₱{{ number_format($bill->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="payment-status">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <strong>Payment Summary:</strong>
            </div>
            <div class="total-row">
                <span>Total Amount:</span>
                <span>₱{{ number_format($bill->total_amount, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Amount Paid:</span>
                <span style="color: #28a745;">₱{{ number_format($bill->paid_amount, 2) }}</span>
            </div>
            <div class="total-row" style="border-top: 2px solid #000; margin-top: 5px; padding-top: 10px;">
                <strong>Remaining Balance:</strong>
                <strong style="color: {{ $bill->remaining_balance > 0 ? '#dc3545' : '#28a745' }};">
                    ₱{{ number_format($bill->remaining_balance, 2) }}
                </strong>
            </div>
        </div>

        <!-- Payment History -->
        @if($bill->payments->count() > 0)
        <div class="payment-history">
            <h3>Payment History</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment #</th>
                        <th>Method</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill->payments->where('status', 'completed') as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ $payment->payment_number }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td style="text-align: right;">₱{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Notes -->
        @if($bill->notes)
        <div class="notes-section">
            <strong>Notes:</strong><br>
            {{ $bill->notes }}
        </div>
        @endif

        <!-- Terms & Conditions -->
        @if($bill->terms_conditions)
        <div class="terms-section">
            <strong>Terms & Conditions:</strong><br>
            {{ $bill->terms_conditions }}
        </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div><strong>Client Signature</strong></div>
                <div style="color: #666; font-size: 10px;">Date: _______________</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div><strong>Authorized Signature</strong></div>
                <div style="color: #666; font-size: 10px;">{{ $bill->createdBy->name ?? 'Staff' }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing Dr. VE Aesthetic Clinic!</p>
            <p>For inquiries, please contact us at (123) 456-7890 or info@drvaesthetic.com</p>
            <p style="margin-top: 10px; font-size: 10px;">This is a computer-generated bill and requires no signature.</p>
        </div>
    </div>
</body>
</html>

