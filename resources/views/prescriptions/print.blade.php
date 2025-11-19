<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - {{ $prescription->medication_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #000;
        }
        
        .prescription-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .clinic-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .prescription-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
        }
        
        .prescription-content {
            margin: 30px 0;
        }
        
        .patient-info {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #000;
            background: #f9f9f9;
        }
        
        .patient-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            min-width: 150px;
        }
        
        .info-value {
            flex: 1;
        }
        
        .medication-section {
            margin: 25px 0;
            padding: 20px;
            border: 2px solid #000;
            background: white;
        }
        
        .medication-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }
        
        .medication-name {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }
        
        .medication-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .detail-item {
            padding: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        
        .detail-label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #000;
        }
        
        .detail-value {
            font-size: 14px;
            color: #000;
        }
        
        .instructions-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #000;
            background: #fff8dc;
        }
        
        .instructions-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .notes-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #000;
            background: #f0f8ff;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }
        
        .prescriber-info {
            text-align: left;
        }
        
        .date-info {
            text-align: right;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin-top: 30px;
        }
        
        .signature-label {
            font-size: 12px;
            margin-top: 5px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .prescription-header {
                page-break-inside: avoid;
            }
            
            .medication-section {
                page-break-inside: avoid;
            }
            
            .instructions-section,
            .notes-section {
                page-break-inside: avoid;
            }
            
            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="prescription-header">
        <div class="clinic-name">Dr. Ve Aesthetic Clinic and Wellness Center</div>
        <div class="clinic-subtitle">Medical Prescription</div>
        <div class="prescription-title">PRESCRIPTION</div>
    </div>

    <!-- Patient Information -->
    <div class="patient-info">
        <h3>Patient Information</h3>
        <div class="info-row">
            <div class="info-label">Patient Name:</div>
            <div class="info-value">{{ $prescription->client->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date of Birth:</div>
            <div class="info-value">{{ $prescription->client->date_of_birth ? $prescription->client->date_of_birth->format('M d, Y') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Contact:</div>
            <div class="info-value">{{ $prescription->client->phone ?? $prescription->client->email ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Address:</div>
            <div class="info-value">{{ $prescription->client->address ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Appointment:</div>
            <div class="info-value">{{ $prescription->appointment->service->service_name }} - {{ $prescription->appointment->appointment_date->format('M d, Y') }}</div>
        </div>
    </div>

    <!-- Medication Information -->
    <div class="medication-section">
        <div class="medication-header">
            <div class="medication-name">{{ $prescription->medication_name }}</div>
            <div>Prescribed on: {{ $prescription->prescribed_date->format('M d, Y') }}</div>
        </div>
        
        <div class="medication-details">
            <div class="detail-item">
                <div class="detail-label">Dosage</div>
                <div class="detail-value">{{ $prescription->dosage }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Frequency</div>
                <div class="detail-value">{{ $prescription->frequency }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value">{{ $prescription->duration }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Treatment</div>
                <div class="detail-value">{{ $prescription->appointment->service->service_name }}</div>
            </div>
        </div>
    </div>

    <!-- Special Instructions -->
    @if($prescription->instructions)
    <div class="instructions-section">
        <div class="instructions-title">Special Instructions:</div>
        <div>{{ $prescription->instructions }}</div>
    </div>
    @endif

    <!-- Doctor's Notes -->
    @if($prescription->notes)
    <div class="notes-section">
        <div class="notes-title">Doctor's Notes:</div>
        <div>{{ $prescription->notes }}</div>
    </div>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="prescriber-info">
            <div class="signature-line"></div>
            <div class="signature-label">Prescribed by: {{ $prescription->prescribedBy->name ?? 'N/A' }}</div>
            <div class="signature-label">License Number: _______________</div>
        </div>
        <div class="date-info">
            <div>Date: {{ $prescription->prescribed_date->format('M d, Y') }}</div>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>


























