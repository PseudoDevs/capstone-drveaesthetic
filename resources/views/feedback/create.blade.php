@extends('layouts.app')

@section('title', 'Leave Feedback | Dr. Ve Aesthetic')

@section('content')
<style>
    .feedback-page-content {
        margin-top: 120px;
        padding-top: 2rem;
    }
    
    @media (max-width: 768px) {
        .feedback-page-content {
            margin-top: 100px;
            padding-top: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .feedback-page-content {
            margin-top: 80px;
            padding-top: 1rem;
        }
    }
</style>

<div class="container py-5 feedback-page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-2 fw-bold">
                        <i class="fas fa-star me-2 text-warning"></i>Share Your Experience
                    </h4>
                    <p class="mb-2 fs-6">Help us improve by sharing your feedback</p>
                    <div class="mt-2">
                        <span class="badge bg-success fs-6 px-2 py-1">
                            <i class="fas fa-shield-alt me-1"></i>Your feedback is valuable
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Thank you!</strong> {{ session('success') }}
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

                    <form method="POST" action="{{ route('feedback.submit') }}">
                        @csrf
                        
                        <!-- Appointment Selection -->
                        <div class="form-group mb-4">
                            <label for="appointment_id" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>Select Appointment
                            </label>
                            <select name="appointment_id" id="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror" required>
                                <option value="">Choose your appointment...</option>
                                @if(auth()->check())
                                    @php
                                        $userAppointments = auth()->user()->appointments()
                                            ->where('status', 'completed')
                                            ->with(['service', 'staff'])
                                            ->orderBy('appointment_date', 'desc')
                                            ->get();
                                    @endphp
                                    @foreach($userAppointments as $appointment)
                                        <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                            {{ $appointment->service->service_name }} - {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(auth()->check() && $userAppointments->count() == 0)
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    No completed appointments found. You can only leave feedback for completed appointments.
                                </div>
                            @endif
                        </div>

                        <!-- Rating -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-star me-1 text-warning"></i>Overall Rating
                                <span class="text-danger">*</span>
                            </label>
                            <div class="rating-input bg-light p-4 rounded-3">
                                <div class="stars d-flex justify-content-center gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" 
                                               {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" class="star">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <div class="rating-labels mt-3 text-center">
                                    <div class="alert alert-info mb-0 py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <span id="rating-text" class="fw-bold">Click on a star to rate your experience</span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-star text-warning me-1"></i>1 = Poor
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i>2 = Fair
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i>3 = Good
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i>4 = Very Good
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i>5 = Excellent
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-2 fw-bold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="form-group mb-4">
                            <label for="comment" class="form-label fw-bold">
                                <i class="fas fa-comment me-1 text-primary"></i>Your Review
                                <span class="text-danger">*</span>
                            </label>
                            <div class="bg-light p-3 rounded-3">
                                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror border-0 bg-transparent" 
                                          rows="5" placeholder="Tell us about your experience..." required>{{ old('comment') }}</textarea>
                                <div class="form-text mt-2">
                                    <i class="fas fa-lightbulb me-1 text-warning"></i>
                                    <strong>Share details about:</strong> Your experience, service quality, staff professionalism, and suggestions for improvement.
                                </div>
                            </div>
                            @error('comment')
                                <div class="text-danger small mt-2 fw-bold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Anonymous Option -->
                        <div class="form-group mb-4">
                            <div class="bg-light p-3 rounded-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anonymous" id="anonymous" value="1">
                                    <label class="form-check-label fw-bold" for="anonymous">
                                        <i class="fas fa-user-secret me-1 text-secondary"></i>
                                        Submit anonymously (your name won't be displayed publicly)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>About Your Feedback
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Your feedback helps us improve our services</li>
                                <li><i class="fas fa-check text-success me-2"></i>We use your reviews to enhance client experience</li>
                                <li><i class="fas fa-check text-success me-2"></i>Your privacy is always protected</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Reviews help other clients make informed decisions</li>
                                <li><i class="fas fa-check text-success me-2"></i>We respond to all feedback within 24 hours</li>
                                <li><i class="fas fa-check text-success me-2"></i>Your input shapes our service improvements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input .stars {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star {
    font-size: 2.5rem;
    color: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 5px;
    border-radius: 50%;
}

/* Hover effect for individual stars */
.rating-input .star:hover {
    color: #ffc107;
    transform: scale(1.1);
    background-color: rgba(255, 193, 7, 0.1);
}

/* When hovering, reset stars after the hovered one */
.rating-input .star:hover ~ .star {
    color: #ddd;
    transform: scale(1);
}

/* Star rating will be handled by JavaScript */
.rating-input .star.active {
    color: #ffc107 !important;
    background-color: rgba(255, 193, 7, 0.1);
    transform: scale(1.05);
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

@media (max-width: 768px) {
    .rating-input .star {
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingText = document.getElementById('rating-text');
    
    const ratingLabels = [
        'Click on a star to rate your experience',
        'Poor - Not satisfied',
        'Fair - Below expectations', 
        'Good - Met expectations',
        'Very Good - Exceeded expectations',
        'Excellent - Outstanding experience'
    ];
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            ratingText.textContent = ratingLabels[this.value];
            updateStarDisplay(this.value);
        });
    });

    // Add click handlers to stars for better UX
    const stars = document.querySelectorAll('.rating-input .star');
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = index + 1;
            const input = document.getElementById('star' + rating);
            input.checked = true;
            ratingText.textContent = ratingLabels[rating];
            updateStarDisplay(rating);
        });
    });

    function updateStarDisplay(rating) {
        const stars = document.querySelectorAll('.rating-input .star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const rating = document.querySelector('input[name="rating"]:checked');
        const comment = document.getElementById('comment').value.trim();
        
        if (!rating) {
            e.preventDefault();
            alert('Please select a rating before submitting.');
            return;
        }
        
        if (comment.length < 10) {
            e.preventDefault();
            alert('Please provide a more detailed review (at least 10 characters).');
            return;
        }
    });
});
</script>
@endsection
