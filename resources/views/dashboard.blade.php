@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')


    <!--====================================================================
                                                Start Dashboard Section
                    =====================================================================-->
    <section class="dashboard-section pt-150 rpt-100 pb-150 rpb-100">
        <div class="container">

            <div class="row">
                <!-- Welcome Header -->
                <div class="col-lg-12">
                    <div class="dashboard-header mb-50">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="welcome-content">
                                <h2 class="welcome-title">Welcome back, {{ $user->name }}!</h2>
                                <p class="welcome-subtitle">Manage your appointments and view your schedule</p>
                            </div>
                            <div class="user-profile-section">
                                <div class="user-avatar">
                                    @if ($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                            class="rounded-circle" width="70" height="70">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Tabs -->
                <div class="col-lg-12">
                    <div class="dashboard-tabs">
                        <div class="mobile-tabs-hint d-md-none">
                            <i class="fas fa-arrows-alt-h"></i> Swipe to see more tabs
                        </div>
                        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-toggle="tab" data-target="#overview"
                                    type="button" role="tab" aria-controls="overview" aria-selected="true">
                                    <i class="flaticon-bar-chart"></i> Overview
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="calendar-tab" data-toggle="tab" data-target="#calendar"
                                    type="button" role="tab" aria-controls="calendar" aria-selected="false">
                                    <i class="flaticon-calendar"></i> Calendar
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pending-tab" data-toggle="tab" data-target="#pending"
                                    type="button" role="tab" aria-controls="pending" aria-selected="false">
                                    <i class="flaticon-clock"></i> Pending <span
                                        class="badge badge-warning">{{ $appointmentsByStatus['pending']->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="scheduled-tab" data-toggle="tab" data-target="#scheduled"
                                    type="button" role="tab" aria-controls="scheduled" aria-selected="false">
                                    <i class="flaticon-check"></i> Scheduled <span
                                        class="badge badge-success">{{ $appointmentsByStatus['scheduled']->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="completed-tab" data-toggle="tab" data-target="#completed"
                                    type="button" role="tab" aria-controls="completed" aria-selected="false">
                                    <i class="flaticon-check-mark"></i> Completed <span
                                        class="badge badge-info">{{ $appointmentsByStatus['completed']->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cancelled-tab" data-toggle="tab" data-target="#cancelled"
                                    type="button" role="tab" aria-controls="cancelled" aria-selected="false">
                                    <i class="flaticon-cancel"></i> Cancelled <span
                                        class="badge badge-danger">{{ $appointmentsByStatus['cancelled']->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">
                                    <i class="flaticon-user"></i> Profile
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="dashboardTabsContent">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="row mt-4">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card pending">
                                            <div class="stats-icon">
                                                <i class="flaticon-clock"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $appointmentsByStatus['pending']->count() }}</h3>
                                                <p>Pending</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card scheduled">
                                            <div class="stats-icon">
                                                <i class="flaticon-check"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $appointmentsByStatus['scheduled']->count() }}</h3>
                                                <p>Scheduled</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card completed">
                                            <div class="stats-icon">
                                                <i class="flaticon-check-mark"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $appointmentsByStatus['completed']->count() }}</h3>
                                                <p>Completed</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card cancelled">
                                            <div class="stats-icon">
                                                <i class="flaticon-cancel"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $appointmentsByStatus['cancelled']->count() }}</h3>
                                                <p>Cancelled</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Statistics -->
                                <div class="row mt-4">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card total">
                                            <div class="stats-icon">
                                                <i class="flaticon-calendar"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $totalAppointments }}</h3>
                                                <p>Total Appointments</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card spent">
                                            <div class="stats-icon">
                                                <i class="flaticon-money"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>₱{{ number_format($totalSpent, 2) }}</h3>
                                                <p>Total Spent</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card upcoming">
                                            <div class="stats-icon">
                                                <i class="flaticon-next"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $upcomingAppointments }}</h3>
                                                <p>Upcoming</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="stats-card month">
                                            <div class="stats-icon">
                                                <i class="flaticon-chart"></i>
                                            </div>
                                            <div class="stats-content">
                                                <h3>{{ $thisMonthAppointments }}</h3>
                                                <p>This Month</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Info Cards -->
                                <div class="row mt-4">
                                    <!-- Next Appointment Card -->
                                    <div class="col-lg-6">
                                        <div class="info-card">
                                            <h5><i class="flaticon-clock mr-2"></i>Next Appointment</h5>
                                            @if($nextAppointment)
                                                <div class="appointment-preview">
                                                    <h6>{{ $nextAppointment->service->service_name ?? 'N/A' }}</h6>
                                                    <p><i class="flaticon-calendar mr-1"></i>{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('M j, Y') }}</p>
                                                    <p><i class="flaticon-clock mr-1"></i>{{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('h:i A') }}</p>
                                                    <p><i class="flaticon-user mr-1"></i>{{ $nextAppointment->staff->name ?? 'TBA' }}</p>
                                                    <span class="badge badge-warning">{{ ucfirst($nextAppointment->status) }}</span>
                                                </div>
                                            @else
                                                <div class="empty-state-small">
                                                    <i class="flaticon-calendar text-muted"></i>
                                                    <p class="text-muted">No upcoming appointments</p>
                                                    <a href="{{ url('/services') }}" class="btn btn-primary btn-sm">Book Now</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Popular Service Card -->
                                    <div class="col-lg-6">
                                        <div class="info-card">
                                            <h5><i class="flaticon-star mr-2"></i>Most Booked Service</h5>
                                            @if($popularService)
                                                <div class="service-preview">
                                                    <h6>{{ $popularService['service']->service_name ?? 'N/A' }}</h6>
                                                    <p><i class="flaticon-tag mr-1"></i>₱{{ number_format($popularService['service']->price ?? 0, 2) }}</p>
                                                    <p><i class="flaticon-chart mr-1"></i>Booked {{ $popularService['count'] }} times</p>
                                                    <span class="badge badge-success">Popular Choice</span>
                                                </div>
                                            @else
                                                <div class="empty-state-small">
                                                    <i class="flaticon-beauty text-muted"></i>
                                                    <p class="text-muted">No completed services yet</p>
                                                    <a href="{{ url('/services') }}" class="btn btn-primary btn-sm">Explore Services</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Recent Appointments -->
                                <div class="row mt-5">
                                    <div class="col-lg-12">
                                        <h4 class="mb-4">Recent Appointments</h4>
                                        @if ($appointmentsByStatus['pending']->concat($appointmentsByStatus['scheduled'])->take(5)->count() > 0)
                                            <div class="table-responsive">
                                                <div class="mobile-scroll-hint d-md-none">
                                                    <i class="fas fa-arrows-alt-h"></i> Swipe to see more columns
                                                </div>
                                                <table class="table table-striped table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Service</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Staff</th>
                                                            <th>Price</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($appointmentsByStatus['pending']->concat($appointmentsByStatus['scheduled'])->take(5) as $appointment)
                                                            <tr class="appointment-row-item">
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="flaticon-beauty text-primary mr-2"></i>
                                                                        <div>
                                                                            <span class="font-weight-medium">{{ $appointment->service->service_name ?? 'N/A' }}</span>
                                                                            <br>
                                                                            @php
                                                                                $badgeClass = match($appointment->status) {
                                                                                    'pending' => 'badge-warning',
                                                                                    'scheduled' => 'badge-info',
                                                                                    'on-going' => 'badge-primary',
                                                                                    'completed' => 'badge-success',
                                                                                    'cancelled' => 'badge-danger',
                                                                                    'declined' => 'badge-secondary',
                                                                                    'rescheduled' => 'badge-dark',
                                                                                    default => 'badge-light'
                                                                                };
                                                                            @endphp
                                                                            <small><span class="badge {{ $badgeClass }}">{{ ucfirst(strtolower($appointment->status)) }}</span></small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                                <td>{{ $appointment->staff->name ?? 'N/A' }}</td>
                                                                <td><span class="text-success font-weight-bold">₱{{ number_format($appointment->service->price ?? 0, 2) }}</span></td>
                                                                <td>
                                                                    <div class="action-buttons-container">
                                                                        <div class="action-buttons-scroll" data-appointment-id="{{ $appointment->id }}">
                                                                            <div class="action-buttons" role="group">
                                                                                <button type="button" class="btn btn-sm btn-outline-primary view-details"
                                                                                        data-appointment-id="{{ $appointment->id }}"
                                                                                        data-service="{{ $appointment->service->service_name ?? 'N/A' }}"
                                                                                        data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}"
                                                                                        data-time="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}"
                                                                                        data-staff="{{ $appointment->staff->name ?? 'N/A' }}"
                                                                                        data-price="₱{{ number_format($appointment->service->price ?? 0, 2) }}"
                                                                                        data-status="{{ $appointment->status }}"
                                                                                        title="View Details">
                                                                                    <i class="fas fa-eye"></i>
                                                                                    <span class="btn-text">View</span>
                                                                                </button>
                                                                                @if($appointment->status === 'pending')
                                                                                    <button type="button" class="btn btn-sm btn-outline-danger cancel-appointment"
                                                                                            data-appointment-id="{{ $appointment->id }}"
                                                                                            title="Cancel Appointment">
                                                                                        <i class="fas fa-ban"></i>
                                                                                        <span class="btn-text">Cancel</span>
                                                                                    </button>
                                                                                @elseif($appointment->status === 'scheduled')
                                                                                    <button type="button" class="btn btn-sm btn-outline-warning reschedule-appointment"
                                                                                            data-appointment-id="{{ $appointment->id }}"
                                                                                            title="Reschedule Appointment">
                                                                                        <i class="fas fa-calendar-alt"></i>
                                                                                        <span class="btn-text">Reschedule</span>
                                                                                    </button>
                                                                                @endif
                                                                                
                                                                                @if(!$appointment->form_completed && $appointment->form_type && in_array($appointment->status, ['pending', 'scheduled']))
                                                                                    <a href="{{ route('appointments.form', $appointment->id) }}" class="btn btn-sm btn-outline-warning" title="Fill Medical Form">
                                                                                        <i class="fas fa-file-medical-alt"></i>
                                                                                        <span class="btn-text">Form</span>
                                                                                    </a>
                                                                                @elseif($appointment->form_completed)
                                                                                    <a href="{{ route('appointments.form.view', $appointment->id) }}" class="btn btn-sm btn-outline-info" title="View Completed Form">
                                                                                        <i class="fas fa-file-check"></i>
                                                                                        <span class="btn-text">View Form</span>
                                                                                    </a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="scroll-indicator">
                                                                            <i class="fas fa-chevron-right"></i>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="empty-state text-center py-4">
                                                <i class="flaticon-calendar fa-2x text-muted mb-3"></i>
                                                <h6 class="text-muted">No Recent Appointments</h6>
                                                <p class="text-muted small">Your recent appointments will appear here.</p>
                                                <a href="{{ url('/services') }}" class="btn btn-primary btn-sm">
                                                    <i class="flaticon-next mr-1"></i>Book Appointment
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Calendar Tab -->
                            <div class="tab-pane fade" id="calendar" role="tabpanel">
                                <div class="calendar-container mt-4">
                                    <div id="calendar"></div>
                                </div>

                            </div>

                            <!-- Appointments by Status Tabs -->
                            @foreach (['pending', 'scheduled', 'completed', 'cancelled'] as $status)
                                <div class="tab-pane fade" id="{{ $status }}" role="tabpanel">
                                    <div class="appointments-cards mt-4">
                                        <!-- Search and Filter Controls -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="search-box">
                                                    <input type="text" class="form-control" id="{{ $status }}-search" placeholder="Search appointments...">
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <div class="entries-info" id="{{ $status }}-info">
                                                    Showing <span class="start">1</span> to <span class="end">{{ min(6, $appointmentsByStatus[$status]->count()) }}</span> of <span class="total">{{ $appointmentsByStatus[$status]->count() }}</span> appointments
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bootstrap Table -->
                                        <div class="table-responsive">
                                            <div class="mobile-scroll-hint d-md-none">
                                                <i class="fas fa-arrows-alt-h"></i> Swipe to see more columns
                                            </div>
                                            <table class="table table-striped table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Service</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Staff</th>
                                                        <th>Price</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="{{ $status }}-container">
                                                    @forelse ($appointmentsByStatus[$status] as $appointment)
                                                        <tr class="appointment-row-item searchable" data-search="{{ strtolower($appointment->service->service_name ?? '') }} {{ strtolower($appointment->staff->name ?? '') }} {{ strtolower($appointment->status) }}">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="flaticon-beauty text-primary mr-2"></i>
                                                                    <div>
                                                                        <span class="font-weight-medium">{{ $appointment->service->service_name ?? 'N/A' }}</span>
                                                                        <br>
                                                                        @php
                                                                            $badgeClass = match($appointment->status) {
                                                                                'pending' => 'badge-warning',
                                                                                'scheduled' => 'badge-info',
                                                                                'on-going' => 'badge-primary',
                                                                                'completed' => 'badge-success',
                                                                                'cancelled' => 'badge-danger',
                                                                                'declined' => 'badge-secondary',
                                                                                'rescheduled' => 'badge-dark',
                                                                                default => 'badge-light'
                                                                            };
                                                                        @endphp
                                                                        <small><span class="badge {{ $badgeClass }}">{{ ucfirst(strtolower($appointment->status)) }}</span></small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                            <td>{{ $appointment->staff->name ?? 'N/A' }}</td>
                                                            <td><span class="text-success font-weight-bold">₱{{ number_format($appointment->service->price ?? 0, 2) }}</span></td>
                                                            <td>
                                                                <div class="action-buttons-container">
                                                                    <div class="action-buttons-scroll" data-appointment-id="{{ $appointment->id }}">
                                                                        <div class="action-buttons" role="group">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary view-details"
                                                                                    data-appointment-id="{{ $appointment->id }}"
                                                                                    data-service="{{ $appointment->service->service_name ?? 'N/A' }}"
                                                                                    data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}"
                                                                                    data-time="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}"
                                                                                    data-staff="{{ $appointment->staff->name ?? 'N/A' }}"
                                                                                    data-price="₱{{ number_format($appointment->service->price ?? 0, 2) }}"
                                                                                    data-status="{{ $appointment->status }}"
                                                                                    title="View Details">
                                                                                <i class="fas fa-eye"></i>
                                                                                <span class="btn-text">View</span>
                                                                            </button>
                                                                            @if($appointment->status === 'pending')
                                                                                <button type="button" class="btn btn-sm btn-outline-danger cancel-appointment"
                                                                                        data-appointment-id="{{ $appointment->id }}"
                                                                                        title="Cancel Appointment">
                                                                                    <i class="fas fa-ban"></i>
                                                                                    <span class="btn-text">Cancel</span>
                                                                                </button>
                                                                            @elseif($appointment->status === 'scheduled')
                                                                                <button type="button" class="btn btn-sm btn-outline-warning reschedule-appointment"
                                                                                        data-appointment-id="{{ $appointment->id }}"
                                                                                        title="Reschedule Appointment">
                                                                                    <i class="fas fa-calendar-alt"></i>
                                                                                    <span class="btn-text">Reschedule</span>
                                                                                </button>
                                                                            @endif
                                                                            
                                                                            @if(!$appointment->form_completed && $appointment->form_type && in_array($appointment->status, ['pending', 'scheduled']))
                                                                                <a href="{{ route('appointments.form', $appointment->id) }}" class="btn btn-sm btn-outline-warning" title="Fill Medical Form">
                                                                                    <i class="fas fa-file-medical-alt"></i>
                                                                                    <span class="btn-text">Form</span>
                                                                                </a>
                                                                            @elseif($appointment->form_completed)
                                                                                <a href="{{ route('appointments.form.view', $appointment->id) }}" class="btn btn-sm btn-outline-info" title="View Completed Form">
                                                                                    <i class="fas fa-file-check"></i>
                                                                                    <span class="btn-text">View Form</span>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="scroll-indicator">
                                                                        <i class="fas fa-chevron-right"></i>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center py-4">
                                                                <i class="flaticon-calendar text-muted" style="font-size: 2rem;"></i>
                                                                <p class="text-muted mt-2 mb-0">No {{ strtolower($status) }} appointments found.</p>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination Controls -->
                                        @if($appointmentsByStatus[$status]->count() > 6)
                                            <div class="pagination-wrapper mt-4" id="{{ $status }}-pagination">
                                                <nav aria-label="Appointments pagination">
                                                    <ul class="pagination justify-content-center">
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="#" data-page="prev">
                                                                <i class="flaticon-left-arrow"></i>
                                                            </a>
                                                        </li>
                                                        <!-- Pagination pages will be generated by JavaScript -->
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="#" data-page="next">
                                                                <i class="flaticon-right-arrow-1"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            
                            
                            <!-- Profile Tab -->
                            <div class="tab-pane fade" id="profile" role="tabpanel">
                                <div class="profile-content mt-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="profile-card text-center">
                                                <div class="profile-avatar mb-3">
                                                    @if ($user->avatar_url)
                                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                                            class="rounded-circle border" width="150" height="150">
                                                    @else
                                                        <div class="avatar-placeholder-large rounded-circle border">
                                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <h4 class="mb-2">{{ $user->name }}</h4>
                                                <p class="text-muted mb-3">{{ ucfirst($user->role) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="profile-info">
                                                <h5 class="mb-4">Personal Information</h5>
                                                <div class="row">
                                                    <div class="col-sm-4 profile-label">Email:</div>
                                                    <div class="col-sm-8 profile-value">{{ $user->email }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 profile-label">Phone:</div>
                                                    <div class="col-sm-8 profile-value">{{ $user->phone ?: 'Not provided' }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 profile-label">Date of Birth:</div>
                                                    <div class="col-sm-8 profile-value">
                                                        {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M j, Y') : 'Not provided' }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 profile-label">Address:</div>
                                                    <div class="col-sm-8 profile-value">{{ $user->address ?: 'Not provided' }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 profile-label">Member Since:</div>
                                                    <div class="col-sm-8 profile-value">{{ $user->created_at->format('M j, Y') }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 profile-label">Account Status:</div>
                                                    <div class="col-sm-8 profile-value">
                                                        <span class="badge badge-success">Active</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====================================================================
                                                End Dashboard Section
                    =====================================================================-->


@endsection


@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' />
    <style>
        .dashboard-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: calc(100vh - 120px);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .dashboard-header {
            background: white;
            border-radius: 20px;
            padding: 40px 30px 30px 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-content {
            flex: 1;
        }

        .welcome-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .welcome-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
        }

        .user-profile-section {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            position: relative;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .user-avatar img {
            border: 4px solid #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .user-avatar .avatar-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbaaa9 0%, #ff9a9e 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            border: 4px solid #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .avatar-placeholder-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .avatar-placeholder-large {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 48px;
            margin: 0 auto;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 0;
            min-width: 280px;
            margin-top: 10px;
        }

        .dropdown-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px 10px 0 0;
            border-bottom: 1px solid #dee2e6;
        }

        .dropdown-item {
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
            color: #dc3545;
        }

        .dropdown-item button {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-weight: 500;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            transition: all 0.3s;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 25px;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
            text-shadow: none;
        }

        .modal-header .close:hover {
            color: white;
            opacity: 1;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #dee2e6;
        }

        .profile-picture-container {
            position: relative;
            display: inline-block;
        }

        .upload-overlay {
            margin-top: 10px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .logout-btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
        }

        .logout-btn i {
            margin-right: 5px;
        }

        .dashboard-tabs {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-tabs {
            border-bottom: none;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 0;
            margin: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 20px 25px;
            background: transparent;
            border-radius: 0;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
        }

        .nav-tabs .nav-link:hover {
            color: #fbaaa9;
            background: rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            color: #fbaaa9;
            background: white;
            border-bottom: 3px solid #fbaaa9;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .nav-tabs .badge {
            margin-left: 8px;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        .tab-content {
            padding: 35px;
            background: white;
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #fbaaa9, #ff9a9e);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-right: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stats-card.pending .stats-icon {
            background: linear-gradient(135deg, #ffc107, #ff8f00);
        }

        .stats-card.scheduled .stats-icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .stats-card.completed .stats-icon {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .stats-card.cancelled .stats-icon {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .stats-card.total .stats-icon {
            background: linear-gradient(135deg, #6f42c1, #5a2d91);
        }

        .stats-card.spent .stats-icon {
            background: linear-gradient(135deg, #fd7e14, #e55a00);
        }

        .stats-card.upcoming .stats-icon {
            background: linear-gradient(135deg, #20c997, #17a085);
        }

        .stats-card.month .stats-icon {
            background: linear-gradient(135deg, #e83e8c, #d81b70);
        }

        .stats-content h3 {
            font-size: 36px;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stats-content p {
            color: #6c757d;
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .appointment-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .appointment-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .appointment-date {
            text-align: center;
            margin-right: 20px;
            min-width: 60px;
        }

        .appointment-date .day {
            font-size: 28px;
            font-weight: 700;
            color: #007bff;
            line-height: 1;
        }

        .appointment-date .month {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
        }

        .appointment-details {
            flex: 1;
        }

        .appointment-details h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .appointment-details p {
            margin: 0;
            color: #666;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .appointment-details i {
            margin-right: 5px;
            width: 15px;
        }

        .appointment-status {
            margin-left: 15px;
        }

        .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-warning,
        .badge.status-pending {
            background: #ffc107 !important;
            color: #000 !important;
        }

        .badge-success,
        .badge.status-confirmed {
            background: #28a745 !important;
            color: white !important;
        }

        .badge-info,
        .badge.status-completed {
            background: #17a2b8 !important;
            color: white !important;
        }

        .badge-danger,
        .badge.status-cancelled {
            background: #dc3545 !important;
            color: white !important;
        }

        .appointment-card.detailed {
            display: block;
            padding: 25px;
        }

        .appointment-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .appointment-body p {
            margin-bottom: 10px;
            color: #555;
        }

        .appointment-notes {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ddd;
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .theme-btn {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white !important;
            border: none;
            height: 45px;
            padding: 0 25px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .theme-btn:hover {
            color: white !important;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .calendar-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        #calendar {
            max-width: 100%;
        }

        .fc-toolbar-title {
            font-size: 1.5em !important;
            font-weight: 700 !important;
            color: #333 !important;
        }

        .fc-button-primary {
            background: #007bff !important;
            border-color: #007bff !important;
        }

        .fc-button-primary:hover {
            background: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        @media (max-width: 768px) {
            .nav-tabs .nav-link {
                padding: 15px 10px;
                font-size: 14px;
            }

            .stats-card {
                margin-bottom: 15px;
            }

            .appointment-card {
                flex-direction: column;
                text-align: center;
            }

            .appointment-date {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .appointment-status {
                margin-left: 0;
                margin-top: 10px;
            }
        }

        /* Appointment Cards Styling */
        .appointments-cards {
            padding: 20px 0;
        }

        .search-box input {
            border-radius: 25px;
            padding-left: 20px;
            border: 1px solid #e9ecef;
        }

        .entries-info {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }

        .appointments-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .appointment-card-item {
            transition: transform 0.2s ease;
        }

        .appointment-card-item:hover {
            transform: translateY(-2px);
        }


        /* Modern pagination styling */
        .pagination-controls .btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .pagination-controls .btn:hover {
            background: rgba(0, 123, 255, 0.9);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
        }

        .pagination-controls .btn.active {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-color: transparent;
        }


        /* Recent appointments grid styling */
        .recent-appointments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .action-buttons .btn {
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 12px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            margin: 0 2px;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .action-buttons .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
            background: rgba(0, 123, 255, 0.1);
        }

        .action-buttons .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .action-buttons .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .action-buttons .btn-outline-danger:hover {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .action-buttons .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
        }

        .action-buttons .btn-outline-warning:hover {
            background: #ffc107;
            color: #212529;
            border-color: #ffc107;
        }

        .action-buttons .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
            background: rgba(23, 162, 184, 0.1);
        }

        .action-buttons .btn-outline-info:hover {
            background: #17a2b8;
            color: white;
            border-color: #17a2b8;
        }

        .action-buttons .btn-warning {
            background: linear-gradient(135deg, #ffc107, #ff8f00);
            border: none;
            color: #212529;
        }

        .action-buttons .btn-warning:hover {
            background: linear-gradient(135deg, #ff8f00, #ff6f00);
            color: #212529;
        }

        .action-buttons .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
            color: white;
        }

        .action-buttons .btn-info:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
            color: white;
        }

        /* Horizontal Scrolling Action Buttons */
        .action-buttons-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .action-buttons-scroll {
            display: flex;
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
            gap: 8px;
            padding: 5px 0;
            scroll-behavior: smooth;
        }

        .action-buttons-scroll::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            min-width: max-content;
        }

        .action-buttons .btn {
            flex-shrink: 0;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .action-buttons .btn-text {
            font-size: 12px;
            font-weight: 500;
        }

        .scroll-indicator {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.9));
            padding: 5px 10px;
            color: #6c757d;
            font-size: 12px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .action-buttons-container:hover .scroll-indicator {
            opacity: 1;
        }

        /* Hide scroll indicator when scrolled to end */
        .action-buttons-scroll.scrolled-to-end + .scroll-indicator {
            opacity: 0;
        }

        .empty-state {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state i {
            opacity: 0.5;
        }

        /* Pagination Styling */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .pagination {
            margin: 0;
        }

        .page-item .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: #495057;
            font-weight: 500;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-item.active .page-link {
            background-color: #fbaaa9;
            border-color: #fbaaa9;
            color: white;
        }

        .page-item:hover .page-link {
            background-color: #f8f9fa;
            color: #495057;
        }

        
        /* Mobile Dashboard Layout Fixes */
        @media (max-width: 768px) {
            /* Dashboard Header Mobile Fixes */
            .dashboard-header {
                padding: 30px 20px 20px 20px;
                margin-bottom: 20px;
            }
            
            .dashboard-header .d-flex {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 20px;
            }
            
            .welcome-title {
                font-size: 1.8rem;
                margin-bottom: 5px;
            }
            
            .welcome-subtitle {
                font-size: 1rem;
            }
            
            .user-profile-section {
                order: -1;
            }
            
            /* Navigation Tabs Mobile Fixes */
            .dashboard-tabs {
                margin-bottom: 20px;
            }
            
            /* Mobile tabs hint */
            .mobile-tabs-hint {
                background: linear-gradient(135deg, #fbaaa9, #ff9a9e);
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                text-align: center;
                margin-bottom: 10px;
                box-shadow: 0 2px 8px rgba(251, 170, 169, 0.3);
                animation: pulse 2s infinite;
            }
            
            .mobile-tabs-hint i {
                margin-right: 5px;
            }
            
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                -ms-overflow-style: none;
                padding: 0 10px;
            }
            
            .nav-tabs::-webkit-scrollbar {
                display: none;
            }
            
            .nav-tabs .nav-link {
                flex-shrink: 0;
                padding: 15px 20px;
                font-size: 0.85rem;
                white-space: nowrap;
                min-width: max-content;
            }
            
            .nav-tabs .nav-link i {
                margin-right: 6px;
                font-size: 1rem;
            }
            
            .nav-tabs .badge {
                font-size: 10px;
                padding: 3px 6px;
                margin-left: 6px;
            }
            
            /* Tab Content Mobile Fixes */
            .tab-content {
                padding: 20px 15px;
            }
            
            /* Stats Cards Mobile Layout */
            .stats-card {
                padding: 20px;
                margin-bottom: 15px;
                flex-direction: column;
                text-align: center;
            }
            
            .stats-icon {
                width: 60px;
                height: 60px;
                margin-right: 0;
                margin-bottom: 15px;
                font-size: 24px;
            }
            
            .stats-content h3 {
                font-size: 1.8rem;
                margin-bottom: 5px;
            }
            
            .stats-content p {
                font-size: 0.9rem;
                margin: 0;
            }
            
            /* Appointments Container Mobile */
            .appointments-container {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .modern-card .card-header,
            .modern-card .card-body,
            .modern-card .card-footer {
                padding: 15px;
            }

            .service-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .service-title h5 {
                font-size: 16px;
            }

            /* Action Buttons Mobile */
            .action-buttons {
                justify-content: center;
                flex-wrap: wrap;
                gap: 6px;
            }

            .action-buttons .btn {
                flex: 0 0 auto;
                font-size: 12px;
                padding: 8px 12px;
                min-width: 38px;
                height: 38px;
            }
            
            .action-buttons .btn i {
                font-size: 13px;
            }

            .search-box input {
                border-radius: 8px;
                padding: 10px 15px;
            }

            .entries-info {
                text-align: center;
                margin-top: 10px;
                font-size: 13px;
            }
            
            /* Info Cards Mobile */
            .info-card {
                padding: 20px;
                margin-bottom: 15px;
            }
            
            .info-card h5 {
                font-size: 1.1rem;
                margin-bottom: 15px;
            }
            
            .appointment-preview h6 {
                font-size: 1rem;
                margin-bottom: 8px;
            }
            
            .appointment-preview p {
                font-size: 0.9rem;
                margin-bottom: 5px;
            }
        }
        
        /* Extra Small Mobile Devices */
        @media (max-width: 576px) {
            .dashboard-header {
                padding: 25px 15px 15px 15px;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
            
            .nav-tabs .nav-link {
                padding: 12px 15px;
                font-size: 0.8rem;
            }
            
            .tab-content {
                padding: 15px 10px;
            }
            
            .stats-card {
                padding: 15px;
            }
            
            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .stats-content h3 {
                font-size: 1.5rem;
            }
        }

        /* Enhanced Table Styling */
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Mobile Table Horizontal Scrolling */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                scrollbar-color: #fbaaa9 #f8f9fa;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 6px;
            }
            
            .table-responsive::-webkit-scrollbar-track {
                background: #f8f9fa;
                border-radius: 3px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: #fbaaa9;
                border-radius: 3px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #ff9a9e;
            }
            
            /* Ensure table has minimum width for proper scrolling */
            .table {
                min-width: 600px;
            }
            
            /* Style table cells for mobile */
            .table th,
            .table td {
                white-space: nowrap;
                padding: 12px 8px;
                font-size: 14px;
            }
            
            /* Make action buttons more compact on mobile */
            .action-buttons-container {
                min-width: 200px;
            }
            
            .action-buttons-scroll {
                gap: 4px;
            }
            
            .action-buttons .btn {
                padding: 6px 8px;
                font-size: 11px;
                min-width: 35px;
                height: 35px;
            }
            
            .action-buttons .btn-text {
                display: none; /* Hide text on mobile, show only icons */
            }
            
            /* Mobile scroll hint */
            .mobile-scroll-hint {
                background: linear-gradient(135deg, #fbaaa9, #ff9a9e);
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                text-align: center;
                margin-bottom: 10px;
                box-shadow: 0 2px 8px rgba(251, 170, 169, 0.3);
                animation: pulse 2s infinite;
            }
            
            .mobile-scroll-hint i {
                margin-right: 5px;
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        }

        @media (max-width: 576px) {
            .table {
                min-width: 500px;
            }
            
            .table th,
            .table td {
                padding: 10px 6px;
                font-size: 13px;
            }
            
            .action-buttons .btn {
                padding: 5px 6px;
                font-size: 10px;
                min-width: 30px;
                height: 30px;
            }
        }

        .table {
            margin-bottom: 0;
            background: white;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            color: #2c3e50;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 15px;
            position: relative;
        }

        .table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #fbaaa9, #ff9a9e);
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f8f9fa;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(251, 170, 169, 0.05) 0%, rgba(255, 154, 158, 0.05) 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            padding: 20px 15px;
            vertical-align: middle;
            border: none;
            color: #495057;
            font-weight: 500;
        }

        .table tbody td:first-child {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Enhanced Responsive Design */
        @media (max-width: 1200px) {
            .welcome-title {
                font-size: 1.8rem;
            }
            
            .stats-content h3 {
                font-size: 28px;
            }
        }

        @media (max-width: 992px) {
            .dashboard-header {
                padding: 25px;
                text-align: center;
            }
            
            .welcome-content {
                margin-bottom: 20px;
            }
            
            .user-profile-section {
                justify-content: center;
            }
            
            .nav-tabs .nav-link {
                padding: 15px 20px;
                font-size: 0.9rem;
            }
            
            .tab-content {
                padding: 25px;
            }
            
            .stats-card {
                padding: 25px;
                margin-bottom: 20px;
            }
            
            .stats-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
                margin-right: 20px;
            }
            
            .stats-content h3 {
                font-size: 24px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-section {
                padding: 20px 0;
            }
            
            .welcome-title {
                font-size: 1.6rem;
            }
            
            .welcome-subtitle {
                font-size: 1rem;
            }
            
            .user-avatar img,
            .user-avatar .avatar-placeholder {
                width: 60px;
                height: 60px;
                font-size: 20px;
            }
            
            .nav-tabs {
                flex-wrap: wrap;
            }
            
            .nav-tabs .nav-link {
                padding: 12px 15px;
                font-size: 0.85rem;
                flex: 1;
                min-width: 120px;
            }
            
            .stats-card {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            
            .stats-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .stats-content h3 {
                font-size: 28px;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin: 2px 0;
            }
        }

        @media (max-width: 576px) {
            .dashboard-header {
                padding: 20px;
            }
            
            .welcome-title {
                font-size: 1.4rem;
            }
            
            .tab-content {
                padding: 20px;
            }
            
            .stats-card {
                padding: 15px;
            }
            
            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .stats-content h3 {
                font-size: 24px;
            }
            
            .table thead th {
                padding: 15px 10px;
                font-size: 0.8rem;
            }
            
            .table tbody td {
                padding: 15px 10px;
                font-size: 0.85rem;
            }
            
            .action-buttons .btn {
                padding: 10px 12px;
                font-size: 14px;
                min-width: 44px;
                height: 44px;
                flex: 0 0 auto;
                margin: 2px;
            }
            
            .action-buttons .btn i {
                font-size: 16px;
            }
        }

        /* Global Badge Styling */
        .badge {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-dark {
            background-color: #343a40;
            color: white;
        }

        /* Info Cards */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            height: 100%;
        }

        .info-card h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
        }

        .appointment-preview, .service-preview {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .appointment-preview h6, .service-preview h6 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .appointment-preview p, .service-preview p {
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }

        .empty-state-small {
            text-align: center;
            padding: 30px 15px;
            color: #999;
        }

        .empty-state-small i {
            font-size: 24px;
            margin-bottom: 10px;
            color: #ddd;
        }

        .empty-state-small p {
            margin-bottom: 15px;
        }

        /* Profile Tab Styles */
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .profile-info {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .profile-info .row {
            margin-bottom: 15px;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .profile-info .row:last-child {
            border-bottom: none;
        }

        .profile-label {
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
        }

        .profile-value {
            color: #333;
            display: flex;
            align-items: center;
        }

        .profile-avatar img,
        .profile-avatar .avatar-placeholder-large {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendarEvents = @json($calendarAppointments);
            console.log('Calendar events:', calendarEvents);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: calendarEvents,
                height: 'auto',
                contentHeight: 600,
                eventClick: function(info) {
                    const event = info.event;
                    const props = event.extendedProps;

                    Swal.fire({
                        title: event.title,
                        html: `
                            <div class="appointment-details text-left">
                                <p><strong>Date:</strong> ${props.date || event.start.toLocaleDateString()}</p>
                                <p><strong>Time:</strong> ${props.time || event.start.toLocaleTimeString()}</p>
                                <p><strong>Staff:</strong> ${props.staff}</p>
                                <p><strong>Price:</strong> ${props.price || 'N/A'}</p>
                                <p><strong>Status:</strong>
                                    <span class="badge badge-${props.status === 'pending' ? 'warning' : 'info'}">
                                        ${props.status}
                                    </span>
                                </p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonColor: '#fbaaa9',
                        confirmButtonText: 'Close',
                        showCancelButton: props.status === 'pending',
                        cancelButtonText: 'Cancel Appointment',
                        cancelButtonColor: '#dc3545'
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            // Handle appointment cancellation
                            Swal.fire({
                                title: 'Cancel Appointment',
                                text: 'Are you sure you want to cancel this appointment? This action cannot be undone.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '<i class="flaticon-check mr-1"></i>Yes, cancel it!',
                                cancelButtonText: '<i class="flaticon-cancel mr-1"></i>No, keep it'
                            }).then((cancelResult) => {
                                if (cancelResult.isConfirmed) {
                                    // Show loading state
                                    Swal.fire({
                                        title: 'Cancelling...',
                                        text: 'Please wait while we cancel your appointment.',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        showConfirmButton: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    // Make the API call to cancel appointment
                                    fetch(`/appointments/${event.id}/cancel`, {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Cancelled!',
                                                text: 'Your appointment has been cancelled successfully.',
                                                confirmButtonColor: '#007bff',
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                // Remove the event from calendar
                                                event.remove();
                                                // Refresh the entire page to update statistics
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: data.message || 'Failed to cancel the appointment. Please try again.',
                                                confirmButtonColor: '#dc3545'
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Network error occurred. Please check your connection and try again.',
                                            confirmButtonColor: '#dc3545'
                                        });
                                    });
                                }
                            });
                        }
                    });
                },
                eventDidMount: function(info) {
                    info.el.setAttribute('title',
                        'Service: ' + info.event.extendedProps.service +
                        '\nStaff: ' + info.event.extendedProps.staff +
                        '\nStatus: ' + info.event.extendedProps.status.charAt(0).toUpperCase() +
                        info.event.extendedProps.status.slice(1)
                    );
                },
                height: 'auto',
                eventDisplay: 'block',
                displayEventTime: true,
                timeZone: 'local'
            });

            // Show calendar when tab is clicked and load appointments dynamically
            $('#calendar-tab').on('shown.bs.tab', function(e) {
                setTimeout(function() {
                    calendar.render();
                    // Load appointments dynamically
                    loadCalendarAppointments();
                }, 100);
            });

            // Function to load appointments for calendar
            function loadCalendarAppointments() {
                fetch('{{ route("users.dashboard.appointments") }}')
                    .then(response => response.json())
                    .then(appointments => {
                        console.log('Dynamic appointments:', appointments);
                        calendar.removeAllEvents();
                        calendar.addEventSource(appointments);
                    })
                    .catch(error => {
                        console.error('Error loading appointments:', error);
                    });
            }

            // Initialize tab functionality
            $('.nav-tabs button[data-toggle="tab"]').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Profile picture preview
            $('#avatar').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('profilePreview');
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        } else {
                            // Replace div with img element
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = '{{ $user->name }}';
                            img.className = 'rounded-circle border';
                            img.width = 150;
                            img.height = 150;
                            img.id = 'profilePreview';
                            preview.parentNode.replaceChild(img, preview);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Edit profile form submission
            $('#editProfileForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm me-2"></i>Updating...');

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const toast = `
                            <div class="toast show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
                                <div class="toast-header bg-success text-white">
                                    <strong class="me-auto">Success</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                                </div>
                                <div class="toast-body">
                                    Profile updated successfully!
                                </div>
                            </div>
                        `;
                            $('body').append(toast);

                            // Close modal and reload page after 2 seconds
                            setTimeout(() => {
                                $('#editProfileModal').modal('hide');
                                location.reload();
                            }, 2000);
                        } else {
                            // Show error message
                            let errorMessage = 'An error occurred while updating your profile.';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('<br>');
                            }

                            const toast = `
                            <div class="toast show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
                                <div class="toast-header bg-danger text-white">
                                    <strong class="me-auto">Error</strong>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                                </div>
                                <div class="toast-body">
                                    ${errorMessage}
                                </div>
                            </div>
                        `;
                            $('body').append(toast);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const toast = `
                        <div class="toast show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
                            <div class="toast-header bg-danger text-white">
                                <strong class="me-auto">Error</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                            </div>
                            <div class="toast-body">
                                Network error occurred. Please try again.
                            </div>
                        </div>
                    `;
                        $('body').append(toast);
                    })
                    .finally(() => {
                        submitBtn.prop('disabled', false).html(originalText);
                    });
            });
        });

        // Initialize appointment cards pagination and search
        $(document).ready(function() {
            const itemsPerPage = 6;
            const statuses = ['pending', 'scheduled', 'completed', 'cancelled'];

            // Initialize pagination for each status
            statuses.forEach(function(status) {
                initializePagination(status, itemsPerPage);
                initializeSearch(status);
            });

            function initializePagination(status, perPage) {
                const container = $(`#${status}-container`);
                const items = container.find('.appointment-row-item');
                const totalItems = items.length;
                const totalPages = Math.ceil(totalItems / perPage);

                if (totalPages <= 1) {
                    $(`#${status}-pagination`).hide();
                    return;
                }

                // Show first page
                showPage(status, 1, perPage);

                // Generate pagination buttons
                generatePaginationButtons(status, totalPages);

                // Handle pagination clicks
                $(`#${status}-pagination`).on('click', '.page-link', function(e) {
                    e.preventDefault();
                    const page = $(this).data('page');

                    if (page === 'prev') {
                        const currentPage = parseInt($(`#${status}-pagination .page-item.active .page-link`).data('page')) - 1;
                        if (currentPage > 0) {
                            showPage(status, currentPage, perPage);
                            updatePaginationState(status, currentPage, totalPages);
                        }
                    } else if (page === 'next') {
                        const currentPage = parseInt($(`#${status}-pagination .page-item.active .page-link`).data('page')) + 1;
                        if (currentPage <= totalPages) {
                            showPage(status, currentPage, perPage);
                            updatePaginationState(status, currentPage, totalPages);
                        }
                    } else {
                        showPage(status, page, perPage);
                        updatePaginationState(status, page, totalPages);
                    }
                });
            }

            function showPage(status, page, perPage) {
                const container = $(`#${status}-container`);
                const items = container.find('.appointment-card-item:visible');
                const start = (page - 1) * perPage;
                const end = start + perPage;

                items.hide();
                items.slice(start, end).show();

                // Update info
                const visibleItems = items.length;
                const showingStart = visibleItems > 0 ? start + 1 : 0;
                const showingEnd = Math.min(end, visibleItems);

                $(`#${status}-info .start`).text(showingStart);
                $(`#${status}-info .end`).text(showingEnd);
                $(`#${status}-info .total`).text(visibleItems);
            }

            function generatePaginationButtons(status, totalPages) {
                const pagination = $(`#${status}-pagination .pagination`);
                const prevBtn = pagination.find('[data-page="prev"]').parent();
                const nextBtn = pagination.find('[data-page="next"]').parent();

                // Remove existing page buttons
                pagination.find('.page-item:not(:first):not(:last)').remove();

                // Add page buttons
                for (let i = 1; i <= totalPages; i++) {
                    const pageItem = $(`
                        <li class="page-item ${i === 1 ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                    pageItem.insertBefore(nextBtn);
                }

                // Update prev/next button states
                prevBtn.toggleClass('disabled', true);
                nextBtn.toggleClass('disabled', totalPages <= 1);
            }

            function updatePaginationState(status, currentPage, totalPages) {
                const pagination = $(`#${status}-pagination`);

                // Update active state
                pagination.find('.page-item').removeClass('active');
                pagination.find(`[data-page="${currentPage}"]`).parent().addClass('active');

                // Update prev/next states
                pagination.find('[data-page="prev"]').parent().toggleClass('disabled', currentPage <= 1);
                pagination.find('[data-page="next"]').parent().toggleClass('disabled', currentPage >= totalPages);
            }

            function initializeSearch(status) {
                $(`#${status}-search`).on('input', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    const container = $(`#${status}-container`);
                    const items = container.find('.appointment-row-item');

                    items.each(function() {
                        const searchData = $(this).data('search');
                        if (searchData.includes(searchTerm)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

                    // Re-initialize pagination for filtered results
                    const visibleItems = items.filter(':visible').length;
                    const totalPages = Math.ceil(visibleItems / itemsPerPage);

                    if (totalPages <= 1) {
                        $(`#${status}-pagination`).hide();
                        // Update info for all visible items
                        $(`#${status}-info .start`).text(visibleItems > 0 ? 1 : 0);
                        $(`#${status}-info .end`).text(visibleItems);
                        $(`#${status}-info .total`).text(visibleItems);
                    } else {
                        $(`#${status}-pagination`).show();
                        generatePaginationButtons(status, totalPages);
                        showPage(status, 1, itemsPerPage);
                    }
                });
            }

            // Handle action buttons
            $(document).on('click', '.view-details', function() {
                const appointmentId = $(this).data('appointment-id');
                const service = $(this).data('service');
                const date = $(this).data('date');
                const time = $(this).data('time');
                const staff = $(this).data('staff');
                const price = $(this).data('price');
                const status = $(this).data('status');

                let statusBadge = '';
                switch(status) {
                    case 'pending':
                        statusBadge = '<span class="badge badge-warning">Pending</span>';
                        break;
                    case 'scheduled':
                        statusBadge = '<span class="badge badge-info">Scheduled</span>';
                        break;
                    case 'completed':
                        statusBadge = '<span class="badge badge-success">Completed</span>';
                        break;
                    case 'cancelled':
                        statusBadge = '<span class="badge badge-danger">Cancelled</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge badge-light">' + status.charAt(0).toUpperCase() + status.slice(1) + '</span>';
                }

                Swal.fire({
                    title: '<i class="flaticon-view text-primary mr-2"></i>Appointment Details',
                    html: `
                        <div class="appointment-details-modal">
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold text-right">Service:</div>
                                <div class="col-sm-8">${service}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold text-right">Date:</div>
                                <div class="col-sm-8">${date}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold text-right">Time:</div>
                                <div class="col-sm-8">${time}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold text-right">Staff:</div>
                                <div class="col-sm-8">${staff}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold text-right">Price:</div>
                                <div class="col-sm-8 text-success font-weight-bold">${price}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold text-right">Status:</div>
                                <div class="col-sm-8">${statusBadge}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 font-weight-bold text-right">ID:</div>
                                <div class="col-sm-8 text-muted">#${appointmentId}</div>
                            </div>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: '<i class="flaticon-cancel mr-1"></i>Close',
                    width: '500px',
                    customClass: {
                        popup: 'appointment-details-popup',
                        title: 'appointment-details-title'
                    }
                });
            });

            $(document).on('click', '.reschedule-appointment', function() {
                const appointmentId = $(this).data('appointment-id');
                // TODO: Implement reschedule modal
                Swal.fire({
                    icon: 'info',
                    title: 'Reschedule Appointment',
                    text: 'Please contact the clinic to request a reschedule. You can message us through chat by going to My Account > Chat.',
                    confirmButtonColor: '#fbaaa9'
                });
            });

            $(document).on('click', '.cancel-appointment', function() {
                const appointmentId = $(this).data('appointment-id');
                const button = $(this);

                Swal.fire({
                    icon: 'warning',
                    title: 'Cancel Appointment',
                    text: 'Are you sure you want to cancel this appointment? This action cannot be undone.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="flaticon-check mr-1"></i>Yes, cancel it!',
                    cancelButtonText: '<i class="flaticon-cancel mr-1"></i>No, keep it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Cancelling...',
                            text: 'Please wait while we cancel your appointment.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Make the API call to cancel appointment
                        fetch(`/appointments/${appointmentId}/cancel`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Cancelled!',
                                    text: 'Your appointment has been cancelled successfully.',
                                    confirmButtonColor: '#007bff',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Refresh the page to update the appointment status
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message || 'Failed to cancel the appointment. Please try again.',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Network error occurred. Please check your connection and try again.',
                                confirmButtonColor: '#dc3545'
                            });
                        });
                    }
                });
            });

            // Horizontal Scrolling Action Buttons
            function initializeActionButtonScrolling() {
                $('.action-buttons-scroll').each(function() {
                    const scrollContainer = $(this);
                    const scrollIndicator = scrollContainer.siblings('.scroll-indicator');
                    
                    // Check if scrolling is needed
                    function checkScrollability() {
                        const containerWidth = scrollContainer.width();
                        const contentWidth = scrollContainer[0].scrollWidth;
                        
                        if (contentWidth > containerWidth) {
                            scrollIndicator.show();
                        } else {
                            scrollIndicator.hide();
                        }
                    }
                    
                    // Handle scroll events
                    scrollContainer.on('scroll', function() {
                        const scrollLeft = $(this).scrollLeft();
                        const maxScroll = $(this)[0].scrollWidth - $(this).width();
                        
                        if (scrollLeft >= maxScroll - 5) {
                            $(this).addClass('scrolled-to-end');
                        } else {
                            $(this).removeClass('scrolled-to-end');
                        }
                    });
                    
                    // Touch/swipe support for mobile
                    let startX = 0;
                    let scrollLeft = 0;
                    
                    scrollContainer.on('touchstart', function(e) {
                        startX = e.touches[0].pageX - scrollContainer.offset().left;
                        scrollLeft = scrollContainer.scrollLeft();
                    });
                    
                    scrollContainer.on('touchmove', function(e) {
                        e.preventDefault();
                        const x = e.touches[0].pageX - scrollContainer.offset().left;
                        const walk = (x - startX) * 2;
                        scrollContainer.scrollLeft(scrollLeft - walk);
                    });
                    
                    // Initialize
                    checkScrollability();
                    
                    // Recheck on window resize
                    $(window).on('resize', checkScrollability);
                });
            }
            
            // Initialize scrolling when document is ready
            initializeActionButtonScrolling();
            
            // Reinitialize after tab switches
            $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
                setTimeout(initializeActionButtonScrolling, 100);
            });
        });
    </script>
@endpush
