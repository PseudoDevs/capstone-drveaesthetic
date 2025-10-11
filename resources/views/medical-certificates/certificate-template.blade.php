<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate - {{ $client ? $client->name : 'Unknown Client' }}</title>
    <style>
        @page {
            margin: 1in;
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .certificate-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 2px solid #000;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #000;
            padding-bottom: 15px;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .clinic-subtitle {
            font-size: 12px;
            margin: 5px 0;
        }

        .clinic-address {
            font-size: 10px;
            margin: 10px 0 0 0;
        }

        /* Certificate title */
        .certificate-title {
            text-align: center;
            margin: 30px 0;
        }

        .title-main {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0;
            text-decoration: underline;
        }

        /* Certificate content */
        .certificate-body {
            margin: 30px 0;
            line-height: 2;
            text-align: left;
        }

        .field-label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }

        .field-value {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            padding-bottom: 2px;
        }

        .full-width {
            width: 100%;
            margin: 15px 0;
        }

        .patient-name {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Certificate content paragraphs */
        .cert-paragraph {
            margin: 20px 0;
            text-align: justify;
        }

        /* Footer section */
        .footer {
            margin-top: 60px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .signature-section {
            text-align: center;
            margin-top: 40px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            height: 40px;
            margin: 0 auto 10px auto;
        }

        .doctor-info {
            font-size: 11px;
            line-height: 1.3;
        }

        .certificate-number {
            text-align: right;
            font-size: 10px;
            margin-bottom: 20px;
        }

        .date-issued {
            margin-top: 30px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Certificate Number -->
        <div class="certificate-number">
            Certificate No: {{ $certificateNumber }}
        </div>

        <!-- Header -->
        <div class="header">
            <h1 class="clinic-name">Dr. V Aesthetic Clinic</h1>
            <p class="clinic-subtitle">Medical and Aesthetic Services</p>
            <p class="clinic-address">
                [Clinic Address] | Tel: [Phone Number] | Email: [Email Address]<br>
                Licensed Medical Facility
            </p>
        </div>

        <!-- Certificate Title -->
        <div class="certificate-title">
            <h2 class="title-main">Medical Certificate</h2>
        </div>

        <!-- Certificate Body -->
        <div class="certificate-body">
            @if($client)
            <div class="full-width">
                <span class="field-label">Patient Name:</span>
                <span class="field-value patient-name">{{ $client->name }}</span>
            </div>

            @if($client->date_of_birth)
            <div class="full-width">
                <span class="field-label">Date of Birth:</span>
                <span class="field-value">{{ \Carbon\Carbon::parse($client->date_of_birth)->format('F d, Y') }}</span>
            </div>
            @endif

            @if($client->address)
            <div class="full-width">
                <span class="field-label">Address:</span>
                <span class="field-value">{{ $client->address }}</span>
            </div>
            @endif
            @else
            <div class="full-width" style="color: red;">
                <span class="field-label">Error:</span>
                <span class="field-value">Client information not found for this medical certificate.</span>
            </div>
            @endif

            <div class="full-width">
                <span class="field-label">Date of Examination:</span>
                <span class="field-value">{{ $generatedDate }}</span>
            </div>

            <div class="cert-paragraph">
                This is to certify that the above-named patient has been examined by me on
                <strong>{{ $generatedDate }}</strong> and found to be:
            </div>

            <div class="cert-paragraph">
                <strong>MEDICALLY FIT</strong> for the purpose of: <strong>{{ strtoupper($certificate->purpose) }}</strong>
            </div>

            <div class="cert-paragraph">
                The patient is in good health and there are no medical contraindications
                for the above-mentioned purpose as of the date of this examination.
            </div>

            <div class="cert-paragraph">
                This medical certificate is issued upon the request of the patient and is
                valid only for the stated purpose.
            </div>

            @if($certificate->amount > 0)
            <div class="full-width">
                <span class="field-label">Certificate Fee:</span>
                <span class="field-value">₱{{ number_format($certificate->amount, 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Footer with Signature -->
        <div class="footer">
            <div class="footer-left">
                <div class="date-issued">
                    <strong>Date Issued:</strong> {{ $generatedDate }}
                </div>
            </div>

            <div class="footer-right">
                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="doctor-info">
                        <strong>{{ $staff ? $staff->name : 'Dr. V Aesthetic Clinic' }}</strong><br>
                        Attending Physician<br>
                        License No: [Medical License]<br>
                        Dr. V Aesthetic Clinic
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>