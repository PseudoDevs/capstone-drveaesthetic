@php
    $record = $getRecord();
    $record->load(['client', 'staff']);
    $generatedDate = now()->format('F d, Y');
    $certificateNumber = 'MC-' . str_pad($record->id, 6, '0', STR_PAD_LEFT);
@endphp

<div class="bg-white p-6 rounded-lg shadow-sm border">
    <div class="mb-4 text-center">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Medical Certificate Preview</h3>
        <p class="text-sm text-gray-600">This is how the printed certificate will appear</p>
    </div>

    <!-- Certificate Preview -->
    <div style="font-family: Arial, sans-serif; border: 2px solid #000; padding: 20px; background: white; max-width: 600px; margin: 0 auto; font-size: 12px;">

        <!-- Certificate Number -->
        <div style="text-align: right; font-size: 10px; margin-bottom: 20px;">
            Certificate No: {{ $certificateNumber }}
        </div>

        <!-- Header -->
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 1px solid #000; padding-bottom: 15px;">
            <h1 style="font-size: 18px; font-weight: bold; margin: 0 0 5px 0; text-transform: uppercase;">Dr. V Aesthetic Clinic</h1>
            <p style="font-size: 12px; margin: 5px 0;">Medical and Aesthetic Services</p>
            <p style="font-size: 10px; margin: 10px 0 0 0;">
                [Clinic Address] | Tel: [Phone Number] | Email: [Email Address]<br>
                Licensed Medical Facility
            </p>
        </div>

        <!-- Certificate Title -->
        <div style="text-align: center; margin: 30px 0;">
            <h2 style="font-size: 20px; font-weight: bold; text-transform: uppercase; margin: 15px 0; text-decoration: underline;">Medical Certificate</h2>
        </div>

        <!-- Certificate Body -->
        <div style="margin: 30px 0; line-height: 2;">
            <div style="margin: 15px 0;">
                <span style="font-weight: bold; display: inline-block; min-width: 120px;">Patient Name:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; padding-bottom: 2px; font-weight: bold; text-transform: uppercase;">{{ $record->client->name ?? 'N/A' }}</span>
            </div>

            @if($record->client && $record->client->date_of_birth)
            <div style="margin: 15px 0;">
                <span style="font-weight: bold; display: inline-block; min-width: 120px;">Date of Birth:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; padding-bottom: 2px;">{{ \Carbon\Carbon::parse($record->client->date_of_birth)->format('F d, Y') }}</span>
            </div>
            @endif

            @if($record->client && $record->client->address)
            <div style="margin: 15px 0;">
                <span style="font-weight: bold; display: inline-block; min-width: 120px;">Address:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; padding-bottom: 2px;">{{ $record->client->address }}</span>
            </div>
            @endif

            <div style="margin: 15px 0;">
                <span style="font-weight: bold; display: inline-block; min-width: 120px;">Date of Examination:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; padding-bottom: 2px;">{{ $generatedDate }}</span>
            </div>

            <div style="margin: 20px 0; text-align: justify;">
                This is to certify that the above-named patient has been examined by me on
                <strong>{{ $generatedDate }}</strong> and found to be:
            </div>

            <div style="margin: 20px 0; text-align: justify;">
                <strong>MEDICALLY FIT</strong> for the purpose of: <strong>{{ strtoupper($record->purpose) }}</strong>
            </div>

            <div style="margin: 20px 0; text-align: justify;">
                The patient is in good health and there are no medical contraindications
                for the above-mentioned purpose as of the date of this examination.
            </div>

            @if($record->amount > 0)
            <div style="margin: 15px 0;">
                <span style="font-weight: bold; display: inline-block; min-width: 120px;">Certificate Fee:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px; padding-bottom: 2px;">₱{{ number_format($record->amount, 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Footer with Signature -->
        <div style="margin-top: 60px; display: table; width: 100%;">
            <div style="display: table-cell; width: 50%; vertical-align: top;">
                <div style="margin-top: 30px; font-size: 11px;">
                    <strong>Date Issued:</strong> {{ $generatedDate }}
                </div>
            </div>

            <div style="display: table-cell; width: 50%; text-align: right; vertical-align: top;">
                <div style="text-align: center; margin-top: 40px;">
                    <div style="border-bottom: 1px solid #000; width: 200px; height: 40px; margin: 0 auto 10px auto;"></div>
                    <div style="font-size: 11px; line-height: 1.3;">
                        <strong>{{ $record->staff->name ?? 'N/A' }}</strong><br>
                        Attending Physician<br>
                        License No: [Medical License]<br>
                        Dr. V Aesthetic Clinic
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-center space-x-4">
        <button type="button"
                onclick="window.open('{{ route('medical-certificate.download', $record->id) }}', '_blank')"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2m-3-9V7m6 6l-6-6-6 6"></path>
            </svg>
            Download PDF
        </button>
        <button type="button"
                onclick="window.print()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2m-3-9V7m6 6l-6-6-6 6"></path>
            </svg>
            Print Preview
        </button>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .certificate-preview, .certificate-preview * {
        visibility: visible;
    }
    .certificate-preview {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>