@extends('layouts.app')

@section('title', 'Our Services | Aesthetic - Dermatology and Skin Care')

@section('services-active', 'current')

@section('content')
    <style>
        .price-item .pricing-content {
            padding: 30px 25px;
        }

        .service-details {
            text-align: left;
            margin: 25px 0 20px 0;
            padding: 0 5px;
        }

        .service-details h4 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
            line-height: 1;
        }

        .service-details p {
            margin: 8px 0;
            font-size: 14px;
            color: #7f8c8d;
            line-height: 1.5;
        }

        .service-details .category {
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 15px;
            display: inline-block;
            font-size: 12px;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
        }

        .service-details .description {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.6;
            margin: 12px 0 15px 0;
            font-style: italic;
            text-align: justify;
        }

        .service-details i {
            margin-right: 8px;
            color: #e74c3c;
            width: 14px;
        }

        .service-details .duration,
        .service-details .staff {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .price-item .theme-btn {
            margin-top: 25px;
            padding: 12px 25px;
            width: 100%;
            text-align: center;
            display: block;
        }

        .price-circle {
            margin-bottom: 0px;
        }

        .price-item {
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .price-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .pricing-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .service-details {
            flex-grow: 1;
        }

        .price-item .price-image img {
            transition: all 0.3s ease;
        }

        .price-item:hover .price-image img {
            transform: scale(1.05);
        }

        /* Pagination Styling */
        .pagination-wrapper .pagination {
            margin: 0;
            gap: 10px;
        }

        .pagination-wrapper .page-link {
            border: 2px solid #e9ecef;
            color: #6c757d;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .pagination-wrapper .page-link:hover {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            cursor: not-allowed;
        }

        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            border-radius: 8px;
        }

        /* Modal form fixes */
        #appointmentModal .form-control {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            background-color: #ffffff;
        }

        #appointmentModal .form-control:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }

        #appointmentModal .form-check-input {
            border: 1px solid #ced4da;
            background-color: #ffffff;
        }

        #appointmentModal .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        #appointmentModal .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #495057;
        }

        #appointmentModal .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .time-slots-container .form-check {
            margin-bottom: 8px;
        }

        .time-slots-container .form-check-input:disabled + .form-check-label {
            color: #6c757d;
            opacity: 0.5;
            text-decoration: line-through;
        }

        .time-slots-container .form-check-input:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .time-slots-container .form-check-label {
            cursor: pointer;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            display: block;
        }

        .time-slots-container .form-check-input:checked + .form-check-label {
            background-color: #007bff;
            color: white;
        }

        .time-slots-container .form-check-label:hover {
            background-color: #f8f9fa;
        }

        .time-slots-container .form-check-input:checked + .form-check-label:hover {
            background-color: #0056b3;
        }

        #appointmentModal select.form-control {
            color: #495057 !important;
            background-color: #ffffff !important;
        }

        #appointmentModal select.form-control option {
            color: #495057 !important;
            background-color: #ffffff !important;
        }

        #appointmentModal input.form-control,
        #appointmentModal textarea.form-control {
            color: #495057 !important;
        }

        #appointmentModal input.form-control::placeholder,
        #appointmentModal textarea.form-control::placeholder {
            color: #6c757d !important;
            opacity: 1;
        }
    </style>


    <!--====================================================================
                           Start Banner Section
                       =====================================================================-->
    <section class="banner-section">
        <div class="container">
            <div class="banner-inner">
                <div class="banner-content">
                    <h2 class="page-title">Our Services</h2>
                    <p>Discover our comprehensive range of aesthetic and dermatology services <br> designed to enhance your
                        natural beauty and confidence.</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Services</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="banner-angle">
            <img src="{{ asset('assets/images/banner/banner-angle.png') }}" alt="Angle">
        </div>
    </section>
    <!--====================================================================
                           End Banner Section
                       =====================================================================-->

    <!--  divider-->
    <div class="container">
        <div class="divider"></div>
    </div>


    <!--====================================================================
                           Start Services Section
                       =====================================================================-->
    <section class="pricing-section mt-145 rmt-95 mb-120 rmb-70">
        <div class="container">
            <div class="section-title text-center mb-95">
                <h2>Our <span>Services</span></h2>
                <p>Discover our comprehensive range of aesthetic and dermatology services <br> designed to enhance your
                    natural beauty.</p>
            </div>
            <div class="row justify-content-center">
                @forelse($services as $service)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="price-item style-one">
                            <div class="price-image">
                                @if ($service->thumbnail)
                                    <img src="{{ asset('storage/' . $service->thumbnail) }}"
                                        alt="{{ $service->service_name }}">
                                @else
                                    <img src="{{ asset('assets/images/pricing/price1.png') }}"
                                        alt="{{ $service->service_name }}">
                                @endif
                            </div>
                            <div class="pricing-content">
                                <div class="price-circle">
                                    <p>Starting From</p>
                                    <h3>${{ number_format($service->price, 0) }}</h3>
                                </div>
                                <div class="service-details">
                                    <h4>{{ $service->service_name }}</h4>
                                    @if ($service->category)
                                        <p class="category">{{ $service->category->category_name }}</p>
                                    @endif
                                    @if ($service->description)
                                        <p class="description">{{ $service->description }}</p>
                                    @endif
                                    <p class="duration"><i class="far fa-clock"></i> Duration: {{ $service->duration }}
                                        minutes</p>
                                    @if ($service->staff)
                                        <p class="staff"><i class="far fa-user"></i> By: {{ $service->staff->name }}</p>
                                    @endif
                                </div>
                                <button type="button" class="theme-btn" data-toggle="modal"
                                    data-target="#appointmentModal" data-service-id="{{ $service->id }}"
                                    data-service-name="{{ $service->service_name }}"
                                    data-service-price="{{ $service->price }}">
                                    Set An Appointment
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-2">
                        <div class="no-services-message">
                            <i class="fas fa-spa fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Services Available</h4>
                            <p class="text-muted">Our services are currently being updated. Please check back soon or <a
                                    href="{{ url('/contact') }}" class="text-primary">contact us</a> for more information.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            @if($services->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="pagination-wrapper d-flex justify-content-center mt-5">
                            {{ $services->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!--====================================================================
                           End Services Section
                       =====================================================================-->

    <!-- Appointment Booking Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Book Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="appointmentForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info">
                                    <strong>Service:</strong> <span id="selectedServiceName"></span><br>
                                    <strong>Price:</strong> $<span id="selectedServicePrice"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">Appointment Date</label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferred Time</label>
                                <div id="timeSlots" class="time-slots-container">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="08:00" id="time_08">
                                                <label class="form-check-label" for="time_08">8:00 AM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="09:00" id="time_09">
                                                <label class="form-check-label" for="time_09">9:00 AM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="10:00" id="time_10">
                                                <label class="form-check-label" for="time_10">10:00 AM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="11:00" id="time_11">
                                                <label class="form-check-label" for="time_11">11:00 AM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="12:00" id="time_12">
                                                <label class="form-check-label" for="time_12">12:00 PM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="13:00" id="time_13">
                                                <label class="form-check-label" for="time_13">1:00 PM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="14:00" id="time_14">
                                                <label class="form-check-label" for="time_14">2:00 PM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="15:00" id="time_15">
                                                <label class="form-check-label" for="time_15">3:00 PM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="16:00" id="time_16">
                                                <label class="form-check-label" for="time_16">4:00 PM</label>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="appointment_time" value="17:00" id="time_17">
                                                <label class="form-check-label" for="time_17">5:00 PM</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Past Medical History:</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="allergy" id="allergy">
                                            <label class="form-check-label" for="allergy">Allergy</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="stroke" id="stroke">
                                            <label class="form-check-label" for="stroke">Stroke</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="hypertension" id="hypertension">
                                            <label class="form-check-label" for="hypertension">Hypertension</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="diabetes" id="diabetes">
                                            <label class="form-check-label" for="diabetes">Diabetes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="heart_diseases" id="heart_diseases">
                                            <label class="form-check-label" for="heart_diseases">Heart Diseases</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="asthma" id="asthma">
                                            <label class="form-check-label" for="asthma">Asthma</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="thyroid_diseases" id="thyroid_diseases">
                                            <label class="form-check-label" for="thyroid_diseases">Thyroid diseases</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="medical_history[]" value="kidney_diseases" id="kidney_diseases">
                                            <label class="form-check-label" for="kidney_diseases">Kidney Diseases</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="medical_history_other" class="form-label">Others:</label>
                                    <textarea class="form-control" id="medical_history_other" name="medical_history_other" rows="2" placeholder="Please specify other medical conditions..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="maintenance_medications" class="form-label">Maintenance Medications:</label>
                                <textarea class="form-control" id="maintenance_medications" name="maintenance_medications" rows="3" placeholder="List all maintenance medications you are currently taking..."></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <h6>Are you?</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pregnant" value="1" id="pregnant">
                                            <label class="form-check-label" for="pregnant">Pregnant</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="smoker" value="1" id="smoker">
                                            <label class="form-check-label" for="smoker">Smoker</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="lactating" value="1" id="lactating">
                                            <label class="form-check-label" for="lactating">Lactating</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="alcoholic_drinker" value="1" id="alcoholic_drinker">
                                            <label class="form-check-label" for="alcoholic_drinker">Alcoholic Drinker</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="service_id" name="service_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Book Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Login Required Modal -->
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" role="dialog" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginRequiredModalLabel">Login Required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You need to be logged in to book an appointment.</p>
                    <p>Please log in to continue with your appointment booking.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="{{ url('/users/login') }}" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle appointment modal
    $('#appointmentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var serviceId = button.data('service-id');
        var serviceName = button.data('service-name');
        var servicePrice = button.data('service-price');

        // Check if user is authenticated
        @guest
            // User is not logged in, show login modal instead
            $('#appointmentModal').modal('hide');
            $('#loginRequiredModal').modal('show');
            return false;
        @endguest

        // Update modal with service information
        $('#selectedServiceName').text(serviceName);
        $('#selectedServicePrice').text(servicePrice);
        $('#service_id').val(serviceId);
    });

    // Handle date change to update available time slots
    $('#appointment_date').on('change', function() {
        updateTimeSlots();
    });

    // Function to update available time slots based on selected date
    function updateTimeSlots() {
        var selectedDate = $('#appointment_date').val();
        var today = new Date().toISOString().split('T')[0];
        var currentHour = new Date().getHours();
        
        // Reset all time slots
        $('input[name="appointment_time"]').prop('disabled', false).prop('checked', false);
        $('input[name="appointment_time"] + label').removeClass('text-muted');
        
        if (selectedDate === today) {
            // Disable past time slots for today
            $('input[name="appointment_time"]').each(function() {
                var timeSlot = parseInt($(this).val().split(':')[0]);
                if (timeSlot <= currentHour) {
                    $(this).prop('disabled', true);
                    $(this).prop('checked', false);
                }
            });
        }
        
        // Also disable slots outside clinic hours (8am-5pm)
        $('input[name="appointment_time"]').each(function() {
            var timeSlot = parseInt($(this).val().split(':')[0]);
            if (timeSlot < 8 || timeSlot > 17) {
                $(this).prop('disabled', true);
                $(this).prop('checked', false);
            }
        });
    }

    // Handle form submission
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();

        // Check if a time slot is selected
        if (!$('input[name="appointment_time"]:checked').length) {
            Swal.fire({
                icon: 'warning',
                title: 'Time Selection Required',
                text: 'Please select a preferred time for your appointment.',
                confirmButtonColor: '#fbaaa9'
            });
            return;
        }

        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route("appointments.store") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Appointment Booked!',
                        text: response.message,
                        confirmButtonColor: '#fbaaa9',
                        confirmButtonText: 'Go to Dashboard'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ url("/users/dashboard") }}';
                        } else {
                            $('#appointmentModal').modal('hide');
                            $('#appointmentForm')[0].reset();
                        }
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 419) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Session Expired',
                        text: 'Your session has expired. Please refresh the page and try again.',
                        confirmButtonColor: '#fbaaa9'
                    }).then(() => {
                        location.reload();
                    });
                } else if (xhr.status === 401) {
                    $('#appointmentModal').modal('hide');
                    $('#loginRequiredModal').modal('show');
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'Please correct the following errors:\n';
                    for (var field in errors) {
                        errorMessage += '- ' + errors[field][0] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessage,
                        confirmButtonColor: '#fbaaa9'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                        confirmButtonColor: '#fbaaa9'
                    });
                }
            }
        });
    });

    // Initialize time slots when modal opens
    $('#appointmentModal').on('shown.bs.modal', function() {
        updateTimeSlots();
    });
});
</script>
@endpush
