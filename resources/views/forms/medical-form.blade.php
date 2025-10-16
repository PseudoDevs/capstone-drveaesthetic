@extends('layouts.app')

@section('title', 'Complete Medical Form | Dr. Ve Aesthetic')

@section('content')
<style>
    .medical-form-create-page-content {
        margin-top: 120px;
        padding-top: 2rem;
    }
    
    @media (max-width: 768px) {
        .medical-form-create-page-content {
            margin-top: 100px;
            padding-top: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .medical-form-create-page-content {
            margin-top: 80px;
            padding-top: 1rem;
        }
    }
</style>

<div class="container py-5 medical-form-create-page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-2 fw-bold">
                        <i class="fas fa-file-medical me-2 text-primary"></i>{{ $appointment->form_type ? \App\Enums\FormType::from($appointment->form_type)->getLabel() : 'Medical Form' }}
                    </h4>
                    <p class="mb-2 fs-6">Dr. Ve Aesthetic Clinic and Wellness Center</p>
                    <div class="mt-2">
                        <span class="badge bg-info fs-6 px-2 py-1">
                            <i class="fas fa-calendar-check me-1"></i>{{ $appointment->service->service_name }} - {{ $appointment->appointment_date->format('M d, Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Form Completed!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.complete-form', $appointment->id) }}">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-user me-2"></i>Basic Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="patient_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="patient_name" name="medical_form_data[patient_name]" 
                                           value="{{ old('medical_form_data.patient_name', auth()->user()->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="medical_form_data[date_of_birth]" 
                                           value="{{ old('medical_form_data.date_of_birth', auth()->user()->date_of_birth) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="medical_form_data[phone]" 
                                           value="{{ old('medical_form_data.phone', auth()->user()->phone) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="medical_form_data[email]" 
                                           value="{{ old('medical_form_data.email', auth()->user()->email) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Complete Address</label>
                                    <textarea class="form-control" id="address" name="medical_form_data[address]" rows="2" required>{{ old('medical_form_data.address', auth()->user()->address) }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Form Date</label>
                                    <input type="date" class="form-control" id="date" name="medical_form_data[date]" 
                                           value="{{ old('medical_form_data.date', now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="medical_form_data[age]" 
                                           value="{{ old('medical_form_data.age', auth()->user()->date_of_birth ? \Carbon\Carbon::parse(auth()->user()->date_of_birth)->age : '') }}" 
                                           min="1" max="120">
                                    <div class="form-text">Auto-calculated from date of birth (can be edited)</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="procedure" class="form-label">Procedure</label>
                                    <input type="text" class="form-control" id="procedure" name="medical_form_data[procedure]" 
                                           value="{{ old('medical_form_data.procedure', $appointment->service->service_name) }}" required>
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
                                    <label class="form-label">Do you have any of the following conditions?</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="diabetes" id="diabetes">
                                                <label class="form-check-label" for="diabetes">Diabetes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="hypertension" id="hypertension">
                                                <label class="form-check-label" for="hypertension">Hypertension</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="heart_disease" id="heart_disease">
                                                <label class="form-check-label" for="heart_disease">Heart Disease</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="asthma" id="asthma">
                                                <label class="form-check-label" for="asthma">Asthma</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="epilepsy" id="epilepsy">
                                                <label class="form-check-label" for="epilepsy">Epilepsy</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="thyroid_disorder" id="thyroid_disorder">
                                                <label class="form-check-label" for="thyroid_disorder">Thyroid Disorder</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="cancer" id="cancer">
                                                <label class="form-check-label" for="cancer">Cancer</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="medical_form_data[medical_history][]" value="none" id="none">
                                                <label class="form-check-label" for="none">None of the above</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="medical_history_other" class="form-label">Other Medical Conditions</label>
                                    <textarea class="form-control" id="medical_history_other" name="medical_form_data[medical_history_other]" rows="2" placeholder="Please specify any other medical conditions...">{{ old('medical_form_data.medical_history_other') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Medications -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-pills me-2"></i>Current Medications
                            </h5>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="maintenance_medications" class="form-label">Are you currently taking any medications?</label>
                                    <textarea class="form-control" id="maintenance_medications" name="medical_form_data[maintenance_medications]" rows="3" placeholder="Please list all current medications, dosages, and frequency...">{{ old('medical_form_data.maintenance_medications') }}</textarea>
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
                                    <label for="allergies" class="form-label">Do you have any known allergies?</label>
                                    <textarea class="form-control" id="allergies" name="medical_form_data[allergies]" rows="2" placeholder="Please list any known allergies to medications, foods, or other substances...">{{ old('medical_form_data.allergies') }}</textarea>
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
                                    <label for="blood_pressure" class="form-label">Blood Pressure</label>
                                    <input type="text" class="form-control" id="blood_pressure" name="medical_form_data[blood_pressure]" 
                                           placeholder="e.g., 120/80" value="{{ old('medical_form_data.blood_pressure') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pregnancy_status" class="form-label">Pregnancy Status (if applicable)</label>
                                    <select class="form-select" id="pregnancy_status" name="medical_form_data[pregnancy_status]">
                                        <option value="">Select status</option>
                                        <option value="not_applicable" {{ old('medical_form_data.pregnancy_status') == 'not_applicable' ? 'selected' : '' }}>Not Applicable</option>
                                        <option value="not_pregnant" {{ old('medical_form_data.pregnancy_status') == 'not_pregnant' ? 'selected' : '' }}>Not Pregnant</option>
                                        <option value="pregnant" {{ old('medical_form_data.pregnancy_status') == 'pregnant' ? 'selected' : '' }}>Pregnant</option>
                                        <option value="breastfeeding" {{ old('medical_form_data.pregnancy_status') == 'breastfeeding' ? 'selected' : '' }}>Breastfeeding</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-check me-2"></i>Complete Form
                            </button>
                        </div>
                    </form>
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
    background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%);
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

.form-control:focus,
.form-select:focus {
    border-color: #fbaaa9;
    box-shadow: 0 0 0 0.2rem rgba(251, 170, 169, 0.25);
}

.form-check-input:checked {
    background-color: #fbaaa9;
    border-color: #fbaaa9;
}

@media (max-width: 768px) {
    .form-section {
        padding: 15px;
    }
    
    .section-title {
        font-size: 1.1rem;
    }
}
</style>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate age when date of birth changes
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const ageInput = document.getElementById('age');
    
    function calculateAge() {
        if (dateOfBirthInput.value) {
            const birthDate = new Date(dateOfBirthInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            ageInput.value = age;
        }
    }
    
    // Calculate age on page load
    calculateAge();
    
    // Calculate age when date of birth changes
    dateOfBirthInput.addEventListener('change', calculateAge);
    
    // Auto-populate form with user data
    const userData = {
        name: '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
        phone: '{{ auth()->user()->phone ?? "" }}',
        address: '{{ auth()->user()->address ?? "" }}',
        dateOfBirth: '{{ auth()->user()->date_of_birth ?? "" }}'
    };
    
    // Show a notification that form is auto-populated and editable
    if (userData.name && userData.email) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-info alert-dismissible fade show';
        notification.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>
            <strong>Form Auto-Populated!</strong> Your account information has been automatically filled in. All fields are editable - please review and update as needed.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(notification, cardBody.firstChild);
    }
});
</script>
