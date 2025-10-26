<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate - {{ $medicalCertificate->certificate_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background: white;
            color: #000;
            line-height: 1.4;
            font-size: 11px;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }
        
        .clinic-subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .certificate-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .certificate-number {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .certificate-content {
            margin: 40px 0;
            text-align: justify;
        }
        
        .patient-info {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #000;
            background: #f9f9f9;
        }
        
        .patient-info h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 5px;
            align-items: center;
            font-size: 10px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            min-width: 120px;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
            border-bottom: 1px solid #000;
            min-height: 15px;
            padding-bottom: 2px;
        }
        
        .certificate-body {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #000;
            background: white;
        }
        
        .certificate-text {
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 10px;
        }
        
        .purpose-section {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #000;
            background: #fff8dc;
        }
        
        .purpose-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .recommendations-section {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #000;
            background: #f0f8ff;
        }
        
        .recommendations-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .restrictions-section {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #000;
            background: #ffe4e1;
        }
        
        .restrictions-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .validity-section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
            background: #f5f5f5;
            text-align: center;
        }
        
        .validity-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 12px;
            text-decoration: underline;
        }
        
        .validity-dates {
            display: flex;
            justify-content: space-around;
            margin: 10px 0;
        }
        
        .validity-date {
            text-align: center;
        }
        
        .validity-label {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .validity-value {
            font-size: 11px;
            border-bottom: 1px solid #000;
            min-width: 100px;
            padding-bottom: 3px;
        }
        
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }
        
        .signature-block {
            text-align: center;
            width: 180px;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 30px;
            margin-bottom: 5px;
        }
        
        .signature-label {
            font-weight: bold;
            font-size: 10px;
        }
        
        .signature-details {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }
        
        .certificate-footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 5px;
                font-size: 10px;
            }
            
            .certificate-header {
                page-break-inside: avoid;
                margin-bottom: 10px;
            }
            
            .certificate-body {
                page-break-inside: avoid;
                margin: 10px 0;
                padding: 10px;
            }
            
            .purpose-section,
            .recommendations-section,
            .restrictions-section {
                page-break-inside: avoid;
                margin: 5px 0;
                padding: 5px;
            }
            
            .validity-section {
                page-break-inside: avoid;
                margin: 10px 0;
                padding: 8px;
            }
            
            .signature-section {
                page-break-inside: avoid;
                margin-top: 10px;
            }
            
            .certificate-footer {
                margin-top: 10px;
                padding-top: 5px;
            }
            
            /* Ensure all sections are visible */
            .purpose-section,
            .recommendations-section,
            .restrictions-section,
            .validity-section {
                display: block !important;
                visibility: visible !important;
            }
        }
    </style>
</head>
<body>
    <!-- Certificate Header -->
    <div class="certificate-header">
        <div class="clinic-name">Dr. Ve Aesthetic Clinic and Wellness Center</div>
        <div class="clinic-subtitle">Medical Certificate</div>
        <div class="certificate-title">{{ ucwords(str_replace('_', ' ', $medicalCertificate->certificate_type)) }}</div>
        <div class="certificate-number">Certificate No: {{ $medicalCertificate->certificate_number }}</div>
    </div>

    <!-- Patient Information -->
    <div class="patient-info">
        <h3>PATIENT INFORMATION</h3>
        <div class="info-row">
            <div class="info-label">Patient Name:</div>
            <div class="info-value">{{ $medicalCertificate->client->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date of Birth:</div>
            <div class="info-value">{{ $medicalCertificate->client->date_of_birth ? $medicalCertificate->client->date_of_birth->format('M d, Y') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Address:</div>
            <div class="info-value">{{ $medicalCertificate->client->address ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Contact:</div>
            <div class="info-value">{{ $medicalCertificate->client->phone ?? $medicalCertificate->client->email ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Treatment/Service:</div>
            <div class="info-value">{{ $medicalCertificate->appointment?->service?->service_name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Treatment Date:</div>
            <div class="info-value">{{ $medicalCertificate->appointment?->appointment_date?->format('M d, Y') ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Certificate Body -->
    <div class="certificate-body">
        <div class="certificate-text">
            This is to certify that <strong>{{ $medicalCertificate->client->name }}</strong> has been examined and treated at our clinic{{ $medicalCertificate->appointment ? ' on ' . $medicalCertificate->appointment->appointment_date->format('F d, Y') : '' }}{{ $medicalCertificate->appointment?->service?->service_name ? ' for ' . $medicalCertificate->appointment->service->service_name : '' }}.
        </div>

        <div class="purpose-section">
            <div class="purpose-title">PURPOSE:</div>
            <div>{{ $medicalCertificate->purpose ?? 'N/A' }}</div>
        </div>

        <div class="recommendations-section">
            <div class="recommendations-title">RECOMMENDATIONS:</div>
            <div>{{ $medicalCertificate->recommendations ?? 'N/A' }}</div>
        </div>

        <div class="restrictions-section">
            <div class="restrictions-title">RESTRICTIONS:</div>
            <div>{{ $medicalCertificate->restrictions ?? 'N/A' }}</div>
        </div>

        <div class="purpose-section">
            <div class="purpose-title">ADDITIONAL NOTES:</div>
            <div>{{ $medicalCertificate->additional_notes ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Validity Period -->
    <div class="validity-section">
        <div class="validity-title">CERTIFICATE VALIDITY</div>
        <div class="validity-dates">
            <div class="validity-date">
                <div class="validity-label">Valid From:</div>
                <div class="validity-value">{{ $medicalCertificate->valid_from->format('M d, Y') }}</div>
            </div>
            <div class="validity-date">
                <div class="validity-label">Valid Until:</div>
                <div class="validity-value">{{ $medicalCertificate->valid_until->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-label">Dr. {{ $medicalCertificate->issuedBy->name ?? 'N/A' }}</div>
            <div class="signature-details">Licensed Medical Practitioner</div>
            <div class="signature-details">License No: _______________</div>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-label">Date Issued</div>
            <div class="signature-details">{{ $medicalCertificate->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    <!-- Certificate Footer -->
    <div class="certificate-footer">
        <p><strong>Dr. Ve Aesthetic Clinic and Wellness Center</strong></p>
        <p>This certificate is valid only for the period stated above and is subject to verification.</p>
        <p>For verification, please contact our clinic directly.</p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

