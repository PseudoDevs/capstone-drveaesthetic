@extends('layouts.app')

@section('title', 'Billing & Payments - ' . config('app.name'))

@section('content')
<section class="billing-section pt-150 rpt-100 pb-150 rpb-100">
    <div class="container">
        <div class="row">
            <!-- Header -->
            <div class="col-lg-12">
                <div class="billing-header mb-50">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="billing-content">
                            <h2 class="billing-title">Billing & Payments</h2>
                            <p class="billing-subtitle">Track your bills, payments, and installment plans</p>
                        </div>
                        <div class="user-profile-section">
                            <div class="user-avatar">
                                @if (auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                                        class="rounded-circle" width="70" height="70">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Overview Cards -->
            <div class="col-lg-12">
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card pending">
                            <div class="stats-icon">
                                <i class="flaticon-receipt"></i>
                            </div>
                            <div class="stats-content">
                                <h3>{{ auth()->user()->bills()->count() }}</h3>
                                <p>Total Bills</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card completed">
                            <div class="stats-icon">
                                <i class="flaticon-check-mark"></i>
                            </div>
                            <div class="stats-content">
                                <h3>{{ auth()->user()->bills()->where('remaining_balance', 0)->count() }}</h3>
                                <p>Paid Bills</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card cancelled">
                            <div class="stats-icon">
                                <i class="flaticon-clock"></i>
                            </div>
                            <div class="stats-content">
                                <h3>{{ auth()->user()->bills()->where('remaining_balance', '>', 0)->count() }}</h3>
                                <p>Pending Bills</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stats-card scheduled">
                            <div class="stats-icon">
                                <i class="flaticon-credit-card"></i>
                            </div>
                            <div class="stats-content">
                                <h3>{{ auth()->user()->bills()->where('payment_type', 'staggered')->count() }}</h3>
                                <p>Staggered Payments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="row mb-4">
                    <div class="col-lg-4 mb-3">
                        <div class="financial-card total">
                            <div class="financial-icon">
                                <i class="flaticon-banknotes"></i>
                            </div>
                            <div class="financial-content">
                                <h4>₱{{ number_format(auth()->user()->bills()->sum('total_amount'), 2) }}</h4>
                                <p>Total Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="financial-card paid">
                            <div class="financial-icon">
                                <i class="flaticon-check-mark"></i>
                            </div>
                            <div class="financial-content">
                                <h4>₱{{ number_format(auth()->user()->bills()->sum('paid_amount'), 2) }}</h4>
                                <p>Paid Amount</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="financial-card remaining">
                            <div class="financial-icon">
                                <i class="flaticon-exclamation-triangle"></i>
                            </div>
                            <div class="financial-content">
                                <h4>₱{{ number_format(auth()->user()->bills()->sum('remaining_balance'), 2) }}</h4>
                                <p>Remaining Balance</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staggered Payments Section -->
                @php
                    $staggeredBills = auth()->user()->bills()->where('payment_type', 'staggered')->with(['appointment.service'])->get();
                @endphp
                
                @if($staggeredBills->count() > 0)
                <div class="staggered-payments-section mb-4">
                    <h4 class="section-title">
                        <i class="flaticon-credit-card"></i> Active Staggered Payments
                    </h4>
                    <div class="row">
                        @foreach($staggeredBills as $bill)
                        <div class="col-lg-6 mb-3">
                            <div class="staggered-payment-card">
                                <div class="payment-header">
                                    <h5>{{ $bill->appointment->service->service_name }}</h5>
                                    <span class="bill-number">Bill #{{ $bill->bill_number }}</span>
                                </div>
                                <div class="payment-progress">
                                    <div class="progress-info">
                                        <span>{{ $bill->getPaidInstallmentsCount() }}/{{ $bill->total_installments }} installments paid</span>
                                        <span class="amount">₱{{ number_format($bill->total_amount, 2) }}</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ ($bill->getPaidInstallmentsCount() / $bill->total_installments) * 100 }}%"></div>
                                    </div>
                                </div>
                                <div class="payment-details">
                                    <div class="detail-item">
                                        <span class="label">Next Payment:</span>
                                        <span class="value">₱{{ number_format($bill->installment_amount, 2) }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Due Date:</span>
                                        <span class="value">{{ $bill->getNextDueDate() ? $bill->getNextDueDate()->format('M d, Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Remaining:</span>
                                        <span class="value">₱{{ number_format($bill->remaining_balance, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Payments -->
                @php
                    $recentPayments = auth()->user()->payments()->with(['bill.appointment.service'])->orderBy('created_at', 'desc')->limit(5)->get();
                @endphp
                
                @if($recentPayments->count() > 0)
                <div class="recent-payments-section mb-4">
                    <h4 class="section-title">
                        <i class="flaticon-history"></i> Recent Payments
                    </h4>
                    <div class="recent-payments-list">
                        @foreach($recentPayments as $payment)
                        <div class="payment-item">
                            <div class="payment-info">
                                <h6>{{ $payment->bill->appointment->service->service_name }}</h6>
                                <p class="payment-date">{{ $payment->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div class="payment-amount">
                                <span class="amount">₱{{ number_format($payment->amount, 2) }}</span>
                                <span class="method">{{ ucfirst($payment->payment_method) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Services with Staggered Payment Option -->
                <div class="staggered-services-section">
                    <h4 class="section-title">
                        <i class="flaticon-star"></i> Services Available for Staggered Payment
                    </h4>
                    <div class="row">
                        @php
                            $staggeredServices = \App\Models\ClinicService::where('allows_staggered_payment', true)->get();
                        @endphp
                        
                        @foreach($staggeredServices as $service)
                        <div class="col-lg-4 mb-3">
                            <div class="service-card">
                                <div class="service-info">
                                    <h6>{{ $service->service_name }}</h6>
                                    <p class="service-price">₱{{ number_format($service->price, 2) }}</p>
                                    <div class="installment-info">
                                        <i class="flaticon-credit-card"></i>
                                        <span>{{ $service->min_installments ?? 2 }} - {{ $service->max_installments ?? 6 }} installments available</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .billing-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: calc(100vh - 120px);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .billing-header {
        background: white;
        border-radius: 20px;
        padding: 40px 30px 30px 30px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .billing-title {
        color: #2c3e50;
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .billing-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        font-weight: 500;
        margin: 0;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .stats-card.pending {
        border-left: 4px solid #ffc107;
    }

    .stats-card.completed {
        border-left: 4px solid #28a745;
    }

    .stats-card.cancelled {
        border-left: 4px solid #dc3545;
    }

    .stats-card.scheduled {
        border-left: 4px solid #6f42c1;
    }

    .stats-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        opacity: 0.8;
    }

    .stats-card.pending .stats-icon {
        color: #ffc107;
    }

    .stats-card.completed .stats-icon {
        color: #28a745;
    }

    .stats-card.cancelled .stats-icon {
        color: #dc3545;
    }

    .stats-card.scheduled .stats-icon {
        color: #6f42c1;
    }

    .stats-content h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .stats-content p {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .financial-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        align-items: center;
    }

    .financial-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .financial-card.total {
        border-left: 4px solid #007bff;
    }

    .financial-card.paid {
        border-left: 4px solid #28a745;
    }

    .financial-card.remaining {
        border-left: 4px solid #ffc107;
    }

    .financial-icon {
        font-size: 2.5rem;
        margin-right: 20px;
        opacity: 0.8;
    }

    .financial-card.total .financial-icon {
        color: #007bff;
    }

    .financial-card.paid .financial-icon {
        color: #28a745;
    }

    .financial-card.remaining .financial-icon {
        color: #ffc107;
    }

    .financial-content h4 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: #2c3e50;
    }

    .financial-content p {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    .section-title {
        color: #2c3e50;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #007bff;
    }

    .staggered-payment-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        height: 100%;
    }

    .staggered-payment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .payment-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
    }

    .payment-header h5 {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
        flex: 1;
    }

    .bill-number {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .payment-progress {
        margin-bottom: 20px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .progress-info span {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .progress-info .amount {
        color: #2c3e50;
        font-weight: 600;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #007bff, #0056b3);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .payment-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-item .label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .detail-item .value {
        color: #2c3e50;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .recent-payments-list {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .payment-item:last-child {
        border-bottom: none;
    }

    .payment-info h6 {
        color: #2c3e50;
        font-weight: 600;
        margin: 0 0 5px 0;
    }

    .payment-date {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    .payment-amount {
        text-align: right;
    }

    .payment-amount .amount {
        color: #28a745;
        font-weight: 700;
        font-size: 1.1rem;
        display: block;
    }

    .payment-amount .method {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .service-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .service-info h6 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .service-price {
        color: #007bff;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .installment-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .installment-info i {
        color: #6f42c1;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush
