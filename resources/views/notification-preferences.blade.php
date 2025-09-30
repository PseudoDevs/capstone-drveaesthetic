@extends('layouts.app')

@section('title', 'Notification Preferences | Dr. Ve Aesthetic')

@section('content')
<div class="container py-5">
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
                    <form id="notificationPreferencesForm">
                        @csrf
                        
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
    fetch('/api/client/mobile/notification-preferences', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + getAuthToken(),
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const preferences = data.data;
            
            // Set form values
            document.getElementById('email_notifications').checked = preferences.email_notifications;
            document.getElementById('appointment_confirmations').checked = preferences.appointment_confirmations;
            document.getElementById('appointment_reminders_24h').checked = preferences.appointment_reminders_24h;
            document.getElementById('appointment_reminders_2h').checked = preferences.appointment_reminders_2h;
            document.getElementById('appointment_cancellations').checked = preferences.appointment_cancellations;
            document.getElementById('feedback_requests').checked = preferences.feedback_requests;
            document.getElementById('service_updates').checked = preferences.service_updates;
            document.getElementById('promotional_offers').checked = preferences.promotional_offers;
            document.getElementById('newsletter').checked = preferences.newsletter;
        }
    })
    .catch(error => {
        console.error('Error loading preferences:', error);
        showAlert('Error loading notification preferences', 'error');
    });
}

function saveNotificationPreferences() {
    const formData = new FormData(document.getElementById('notificationPreferencesForm'));
    const preferences = {};
    
    // Convert form data to object
    for (let [key, value] of formData.entries()) {
        preferences[key] = value === 'on';
    }
    
    fetch('/api/client/mobile/notification-preferences', {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + getAuthToken(),
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(preferences)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Notification preferences saved successfully!', 'success');
        } else {
            showAlert('Error saving preferences: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error saving preferences:', error);
        showAlert('Error saving notification preferences', 'error');
    });
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

function getAuthToken() {
    // This should return the user's auth token
    // You might need to implement this based on your auth system
    return localStorage.getItem('auth_token') || '';
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
