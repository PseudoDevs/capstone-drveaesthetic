<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Report - {{ $client->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        
        /* Paper form styling */
        .document {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #000;
            padding: 30px;
        }
        
        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .subtitle {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
        }
        
        /* Form sections */
        .form-section {
            margin-bottom: 25px;
            border: 1px solid #000;
            padding: 15px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            background: #000;
            color: #fff;
            margin: -15px -15px 10px -15px;
            padding: 8px 15px;
            letter-spacing: 0.5px;
        }
        
        /* Simple form fields */
        .form-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        
        .form-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding-right: 10px;
            vertical-align: top;
        }
        
        .form-value {
            display: table-cell;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            min-height: 16px;
        }
        
        /* Statistics boxes */
        .stats-grid {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        
        .stat-box {
            display: table-cell;
            border: 2px solid #000;
            text-align: center;
            padding: 10px 5px;
            width: 25%;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }
        
        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            margin-top: 3px;
        }
        
        /* Simple tables */
        .simple-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            border: 2px solid #000;
        }
        
        .simple-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }
        
        .simple-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
            vertical-align: top;
        }
        
        .simple-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        /* Status styling */
        .status {
            font-weight: bold;
            padding: 2px 6px;
            border: 1px solid #000;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        .status-completed { background: #e8f5e8; }
        .status-pending { background: #fff3cd; }
        .status-cancelled { background: #f8d7da; }
        .status-declined { background: #f8d7da; }
        .status-scheduled { background: #d1ecf1; }
        
        /* Revenue box */
        .revenue-box {
            border: 3px solid #000;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            background: #f0f0f0;
        }
        
        .revenue-label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .revenue-amount {
            font-size: 22px;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
        }
        
        .footer-info {
            margin-bottom: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Date/signature area */
        .signature-area {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 10px;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 20px;
            margin-top: 20px;
        }
        
        .signature-label {
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="document">
        <!-- Header -->
        <div class="header">
            <h1>{{ setting('general.site_name', 'Capstone Aesthetic') }}</h1>
            <div class="subtitle">CLIENT REPORT FORM</div>
        </div>

        <!-- Client Information Section -->
        <div class="form-section">
            <div class="section-title">Client Information</div>
            
            <div class="form-row">
                <div class="form-label">Full Name:</div>
                <div class="form-value">{{ $client->name }}</div>
            </div>
            
            <div class="form-row">
                <div class="form-label">Email Address:</div>
                <div class="form-value">{{ $client->email }}</div>
            </div>
            
            <div class="form-row">
                <div class="form-label">Member Since:</div>
                <div class="form-value">{{ $client->created_at->format('F j, Y') }}</div>
            </div>
            
            <div class="form-row">
                <div class="form-label">Email Verified:</div>
                <div class="form-value">{{ $client->email_verified_at ? 'Yes (' . $client->email_verified_at->format('M j, Y') . ')' : 'No' }}</div>
            </div>
            
            <div class="form-row">
                <div class="form-label">Report Date:</div>
                <div class="form-value">{{ now()->format('F j, Y') }}</div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="form-section">
            <div class="section-title">Appointment Statistics</div>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-number">{{ $totalAppointments }}</span>
                    <div class="stat-label">Total</div>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $completedAppointments }}</span>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $pendingAppointments }}</span>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $cancelledAppointments }}</span>
                    <div class="stat-label">Cancelled</div>
                </div>
            </div>
        </div>

        <!-- Revenue Section -->
        @if($totalRevenue > 0)
        <div class="revenue-box">
            <div class="revenue-label">Total Revenue Generated</div>
            <div class="revenue-amount">₱{{ number_format($totalRevenue, 2) }}</div>
        </div>
        @endif

        <!-- Services Summary -->
        @if($servicesAvailed->count() > 0)
        <div class="form-section">
            <div class="section-title">Services Availed Summary</div>
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Times Used</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicesAvailed as $service)
                    <tr>
                        <td>{{ $service['service'] }}</td>
                        <td>{{ $service['count'] }}x</td>
                        <td>₱{{ number_format($service['total_paid'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Appointment History -->
        <div class="form-section">
            <div class="section-title">Appointment History</div>
            @if($client->appointments->count() > 0)
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                        <th>Staff</th>
                        <th>Status</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->appointments->sortByDesc('appointment_date') as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                        <td>{{ $appointment->service->service_name ?? 'N/A' }}</td>
                        <td>{{ $appointment->staff->name ?? 'N/A' }}</td>
                        <td>
                            <span class="status status-{{ strtolower($appointment->status) }}">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td>₱{{ number_format($appointment->service->price ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; font-style: italic;">No appointments found for this client.</p>
            @endif
        </div>

        <!-- Feedback History -->
        @if($client->feedbacks->count() > 0)
        <div class="form-section page-break">
            <div class="section-title">Client Feedback History</div>
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Rating</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->feedbacks->sortByDesc('created_at') as $feedback)
                    <tr>
                        <td>{{ $feedback->created_at->format('M j, Y') }}</td>
                        <td>{{ $feedback->appointment->service->service_name ?? 'N/A' }}</td>
                        <td>{{ $feedback->rating ?? 'N/A' }}/5</td>
                        <td>{{ $feedback->comment ?? 'No comments' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Signature Area -->
        <div class="signature-area">
            <div class="signature-box">
                <div style="font-weight: bold;">Prepared By:</div>
                <div class="signature-line"></div>
                <div class="signature-label">Staff Signature</div>
            </div>
            <div class="signature-box">
                <div style="font-weight: bold;">Date:</div>
                <div class="signature-line"></div>
                <div class="signature-label">{{ now()->format('F j, Y') }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                <strong>{{ setting('general.site_name', 'Capstone Aesthetic') }}</strong><br>
                {{ setting('footer.contact_address_line_1', 'Zone 1, San Jose, Iriga City') }}<br>
                Phone: {{ setting('contact.phone_1', '09123123') }} | Email: {{ setting('contact.email_1', 'support@gmail.com') }}
            </div>
            <div style="border-top: 1px solid #000; padding-top: 10px; margin-top: 10px;">
                CONFIDENTIAL DOCUMENT - For Authorized Personnel Only<br>
                Generated: {{ now()->format('F j, Y \a\t g:i A') }}
            </div>
        </div>
    </div>
</body>
</html>