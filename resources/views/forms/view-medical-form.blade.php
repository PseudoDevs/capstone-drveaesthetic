@extends('layouts.app')

@section('title', 'View Medical Form | Dr. Ve Aesthetic')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-2 fw-bold">
                        <i class="fas fa-file-check me-2 text-success"></i>Completed Medical Form
                    </h4>
                    <p class="mb-2 fs-6">Dr. Ve Aesthetic Clinic and Wellness Center</p>
                    <div class="mt-2">
                        <span class="badge bg-success fs-6 px-2 py-1">
                            <i class="fas fa-calendar-check me-1"></i>{{ $appointment->service->service_name }} - {{ $appointment->appointment_date->format('M d, Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $formData = $appointment->medical_form_data ?? [];
                    @endphp

                    <!-- Basic Information -->
                    <div class="form-section mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-user me-2"></i>Basic Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <p class="form-control-plaintext">{{ $formData['patient_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date</label>
                                <p class="form-control-plaintext">{{ $formData['date'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <p class="form-control-plaintext">{{ $formData['address'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Procedure</label>
                                <p class="form-control-plaintext">{{ $formData['procedure'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Medical History -->
                    <div class="form-section mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-heartbeat me-2"></i>Past Medical History
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Medical Conditions</label>
                                @if(isset($formData['medical_history']) && !empty($formData['medical_history']))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($formData['medical_history'] as $condition)
                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $condition)) }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="form-control-plaintext text-muted">No medical conditions reported</p>
                                @endif
                            </div>
                        </div>
                        @if(isset($formData['medical_history_other']) && !empty($formData['medical_history_other']))
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Other Medical Conditions</label>
                                <p class="form-control-plaintext">{{ $formData['medical_history_other'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Medications -->
                    <div class="form-section mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-pills me-2"></i>Current Medications
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Medications</label>
                                <p class="form-control-plaintext">{{ $formData['maintenance_medications'] ?? 'No medications reported' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Allergies -->
                    <div class="form-section mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Allergies
                        </h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Known Allergies</label>
                                <p class="form-control-plaintext">{{ $formData['allergies'] ?? 'No allergies reported' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Health Status -->
                    <div class="form-section mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-heart me-2"></i>Current Health Status
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Blood Pressure</label>
                                <p class="form-control-plaintext">{{ $formData['blood_pressure'] ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pregnancy Status</label>
                                <p class="form-control-plaintext">{{ ucfirst(str_replace('_', ' ', $formData['pregnancy_status'] ?? 'Not provided')) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Digital Signature -->
                    <div class="form-section mb-4">
                        <h5 class="section-title">
                            <i class="fas fa-signature me-2"></i>Digital Signature
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Signature Date</label>
                                <p class="form-control-plaintext">{{ $formData['signature_date'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <p class="form-control-plaintext">{{ $formData['signature_location'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Digital Signature</label>
                                <p class="form-control-plaintext">{{ $formData['signature_data'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <a href="{{ route('users.dashboard') }}" class="btn btn-secondary me-3">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print me-2"></i>Print Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.section-title {
    color: #fbaaa9;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #fbaaa9;
}

.card {
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 20px 20px 0 0 !important;
    border: none;
    padding: 1.5rem 2rem;
}

.btn-primary {
    background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(251, 170, 169, 0.4);
}

.btn-secondary {
    background: #6c757d;
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.form-control-plaintext {
    background: white;
    padding: 10px 15px;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    min-height: 40px;
    display: flex;
    align-items: center;
}

@media (max-width: 768px) {
    .form-section {
        padding: 15px;
    }
    
    .section-title {
        font-size: 1.1rem;
    }
}

@media print {
    .btn {
        display: none !important;
    }
    
    .card-header {
        background: #28a745 !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection
