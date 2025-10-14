@extends('layouts.app')

@section('title', 'Notification Preferences | Dr. Ve Aesthetic')

@section('content')
<style>
    .notification-page-content {
        margin-top: 120px;
        padding-top: 2rem;
    }
    
    @media (max-width: 768px) {
        .notification-page-content {
            margin-top: 100px;
            padding-top: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .notification-page-content {
            margin-top: 80px;
            padding-top: 1rem;
        }
    }
</style>

<div class="container py-5 notification-page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Notification Preferences
                    </h4>
                    <p class="text-muted mb-0">Manage how you receive notifications from Dr. Ve Aesthetic</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form id="notificationPreferencesForm" action="{{ route('notification-preferences.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                        <label class="form-check-label" for="email_notifications">
                                            <strong>Enable Email Notifications</strong>
                                            <small class="d-block text-muted">Receive notifications via email</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Appointment Notifications</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="appointment_confirmations" name="appointment_confirmations" checked>
                                        <label class="form-check-label" for="appointment_confirmations">
                                            Appointment Confirmations
                                            <small class="d-block text-muted">When your appointment is confirmed</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="appointment_cancellations" name="appointment_cancellations" checked>
                                        <label class="form-check-label" for="appointment_cancellations">
                                            Appointment Cancellations
                                            <small class="d-block text-muted">When appointments are cancelled</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="appointment_reminders_24h" name="appointment_reminders_24h" checked>
                                        <label class="form-check-label" for="appointment_reminders_24h">
                                            24-Hour Reminders
                                            <small class="d-block text-muted">Reminder 24 hours before appointment</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="appointment_reminders_2h" name="appointment_reminders_2h" checked>
                                        <label class="form-check-label" for="appointment_reminders_2h">
                                            2-Hour Reminders
                                            <small class="d-block text-muted">Reminder 2 hours before appointment</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Service & Feedback</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="feedback_requests" name="feedback_requests" checked>
                                        <label class="form-check-label" for="feedback_requests">
                                            Feedback Requests
                                            <small class="d-block text-muted">Request for service feedback</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="service_updates" name="service_updates" checked>
                                        <label class="form-check-label" for="service_updates">
                                            Service Updates
                                            <small class="d-block text-muted">New services and updates</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Marketing Communications</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="promotional_offers" name="promotional_offers">
                                        <label class="form-check-label" for="promotional_offers">
                                            Promotional Offers
                                            <small class="d-block text-muted">Special deals and promotions</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                        <label class="form-check-label" for="newsletter">
                                            Newsletter
                                            <small class="d-block text-muted">Monthly newsletter and tips</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load current preferences
    loadNotificationPreferences();
    
    // Handle form submission
    document.getElementById('notificationPreferencesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveNotificationPreferences();
    });
});

function loadNotificationPreferences() {
    // Set form values from server-side data
    @if(isset($preferences))
        document.getElementById('email_notifications').checked = {{ $preferences->email_notifications ? 'true' : 'false' }};
        document.getElementById('appointment_confirmations').checked = {{ $preferences->appointment_confirmations ? 'true' : 'false' }};
        document.getElementById('appointment_reminders_24h').checked = {{ $preferences->appointment_reminders_24h ? 'true' : 'false' }};
        document.getElementById('appointment_reminders_2h').checked = {{ $preferences->appointment_reminders_2h ? 'true' : 'false' }};
        document.getElementById('appointment_cancellations').checked = {{ $preferences->appointment_cancellations ? 'true' : 'false' }};
        document.getElementById('feedback_requests').checked = {{ $preferences->feedback_requests ? 'true' : 'false' }};
        document.getElementById('service_updates').checked = {{ $preferences->service_updates ? 'true' : 'false' }};
        document.getElementById('promotional_offers').checked = {{ $preferences->promotional_offers ? 'true' : 'false' }};
        document.getElementById('newsletter').checked = {{ $preferences->newsletter ? 'true' : 'false' }};
    @endif
}

function saveNotificationPreferences() {
    // Use regular form submission to avoid CSRF issues
    document.getElementById('notificationPreferencesForm').submit();
}

function resetForm() {
    // Reset to default values
    document.getElementById('email_notifications').checked = true;
    document.getElementById('appointment_confirmations').checked = true;
    document.getElementById('appointment_reminders_24h').checked = true;
    document.getElementById('appointment_reminders_2h').checked = true;
    document.getElementById('appointment_cancellations').checked = true;
    document.getElementById('feedback_requests').checked = true;
    document.getElementById('service_updates').checked = true;
    document.getElementById('promotional_offers').checked = false;
    document.getElementById('newsletter').checked = false;
}


function showAlert(message, type) {
    // Use SweetAlert2 if available, otherwise use basic alert
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type === 'success' ? 'success' : 'error',
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            confirmButtonColor: '#fbaaa9'
        });
    } else {
        alert(message);
    }
}
</script>
@endsection
