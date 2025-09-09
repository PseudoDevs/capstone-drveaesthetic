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
                            <div>
                                <h2>Welcome back, {{ $user->name }}!
                                </h2>
                                <p class="text-muted">Manage your appointments and view your schedule</p>

                            </div>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar">
                                    @if ($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                            class="rounded-circle" width="60" height="60">
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

                                <!-- Recent Appointments -->
                                <div class="row mt-5">
                                    <div class="col-lg-12">
                                        <h4 class="mb-4">Recent Appointments</h4>
                                        @if ($appointmentsByStatus['pending']->concat($appointmentsByStatus['scheduled'])->take(5)->count() > 0)
                                            <div class="table-responsive">
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
                                                                    <div class="btn-group" role="group">
                                                                        <button type="button" class="btn btn-sm btn-outline-primary view-details"
                                                                                data-appointment-id="{{ $appointment->id }}"
                                                                                data-service="{{ $appointment->service->service_name ?? 'N/A' }}"
                                                                                data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}"
                                                                                data-time="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}"
                                                                                data-staff="{{ $appointment->staff->name ?? 'N/A' }}"
                                                                                data-price="₱{{ number_format($appointment->service->price ?? 0, 2) }}"
                                                                                data-status="{{ $appointment->status }}"
                                                                                title="View Details">
                                                                            <i class="flaticon-view"></i>
                                                                        </button>
                                                                        @if($appointment->status === 'pending')
                                                                            <button type="button" class="btn btn-sm btn-outline-danger cancel-appointment"
                                                                                    data-appointment-id="{{ $appointment->id }}"
                                                                                    title="Cancel">
                                                                                <i class="flaticon-cancel"></i>
                                                                            </button>
                                                                        @elseif($appointment->status === 'scheduled')
                                                                            <button type="button" class="btn btn-sm btn-outline-warning reschedule-appointment"
                                                                                    data-appointment-id="{{ $appointment->id }}"
                                                                                    title="Reschedule">
                                                                                <i class="flaticon-calendar"></i>
                                                                            </button>
                                                                        @endif
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
                                                                <div class="btn-group" role="group">
                                                                    <button type="button" class="btn btn-sm btn-outline-primary view-details"
                                                                            data-appointment-id="{{ $appointment->id }}"
                                                                            data-service="{{ $appointment->service->service_name ?? 'N/A' }}"
                                                                            data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}"
                                                                            data-time="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}"
                                                                            data-staff="{{ $appointment->staff->name ?? 'N/A' }}"
                                                                            data-price="₱{{ number_format($appointment->service->price ?? 0, 2) }}"
                                                                            data-status="{{ $appointment->status }}"
                                                                            title="View Details">
                                                                        <i class="flaticon-view"></i>
                                                                    </button>
                                                                    @if($appointment->status === 'pending')
                                                                        <button type="button" class="btn btn-sm btn-outline-danger cancel-appointment"
                                                                                data-appointment-id="{{ $appointment->id }}"
                                                                                title="Cancel">
                                                                            <i class="flaticon-cancel"></i>
                                                                        </button>
                                                                    @elseif($appointment->status === 'scheduled')
                                                                        <button type="button" class="btn btn-sm btn-outline-warning reschedule-appointment"
                                                                                data-appointment-id="{{ $appointment->id }}"
                                                                                title="Reschedule">
                                                                            <i class="flaticon-calendar"></i>
                                                                        </button>
                                                                    @endif
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
            background: #f8f9fa;
            min-height: calc(100vh - 120px);
        }

        .dashboard-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .user-avatar .avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
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
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .nav-tabs {
            border-bottom: none;
            background: #f8f9fa;
            padding: 0;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 600;
            padding: 20px 25px;
            background: transparent;
            border-radius: 0;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:hover {
            color: #007bff;
            background: white;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            background: white;
            border-bottom: 3px solid #007bff;
        }

        .nav-tabs .nav-link i {
            margin-right: 8px;
        }

        .nav-tabs .badge {
            margin-left: 5px;
            font-size: 12px;
        }

        .tab-content {
            padding: 30px;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-right: 20px;
        }

        .stats-card.pending .stats-icon {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }

        .stats-card.confirmed .stats-icon {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .stats-card.completed .stats-icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .stats-card.cancelled .stats-icon {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .stats-content h3 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stats-content p {
            color: #666;
            margin: 0;
            font-weight: 600;
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
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
            padding: 6px 15px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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

        /* Responsive Design */
        @media (max-width: 768px) {
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

            /* Mobile table styling handled by Bootstrap responsive classes */


            .action-buttons {
                justify-content: center;
            }

            .action-buttons .btn {
                flex: 1;
                font-size: 11px;
                padding: 5px 10px;
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
        }

        @media (max-width: 480px) {
            .table-responsive table {
                font-size: 0.875rem;
            }

            .btn-group .btn {
                padding: 4px 8px;
                font-size: 0.75rem;
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
                                text: 'Are you sure you want to cancel this appointment?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Yes, cancel it!'
                            }).then((cancelResult) => {
                                if (cancelResult.isConfirmed) {
                                    // TODO: Implement actual cancellation logic
                                    Swal.fire(
                                        'Cancelled!',
                                        'Your appointment has been cancelled.',
                                        'success'
                                    );
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
                    text: 'Reschedule functionality will be implemented here.',
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
        });
    </script>
@endpush
