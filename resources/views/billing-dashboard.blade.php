@extends('layouts.app')

@section('title', 'Billing Dashboard - ' . config('app.name'))

@section('content')

<!--====================================================================
                                            Start Billing Dashboard Section
                =====================================================================-->
<section class="billing-dashboard-section pt-150 rpt-100 pb-150 rpb-100">
    <div class="container">
        
        <!-- Billing Header -->
        <div class="billing-header">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="billing-title">Billing Dashboard</h1>
                    <p class="billing-subtitle">Manage your bills, payments, and financial overview.</p>
                </div>
            </div>
        </div>

        <!-- Billing Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="billing-stats-card total-bills">
                    <div class="stats-icon">
                        <i class="flaticon-document"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $totalBills }}</h3>
                        <p class="stats-label">TOTAL BILLS</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="billing-stats-card amount-paid">
                    <div class="stats-icon">
                        <i class="flaticon-check-mark"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">₱{{ number_format($paidAmount, 2) }}</h3>
                        <p class="stats-label">AMOUNT PAID</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="billing-stats-card remaining-balance">
                    <div class="stats-icon">
                        <i class="flaticon-wallet"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">₱{{ number_format($remainingBalance, 2) }}</h3>
                        <p class="stats-label">REMAINING BALANCE</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="billing-stats-card payment-progress">
                    <div class="stats-icon">
                        <i class="flaticon-bar-chart"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">{{ $paymentProgress }}%</h3>
                        <p class="stats-label">PAYMENT PROGRESS</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Tabs -->
        <div class="billing-tabs">
            <ul class="nav nav-tabs" id="billingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="my-bills-tab" data-toggle="tab" data-target="#my-bills"
                        type="button" role="tab" aria-controls="my-bills" aria-selected="true">
                        My Bills
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payment-history-tab" data-toggle="tab" data-target="#payment-history"
                        type="button" role="tab" aria-controls="payment-history" aria-selected="false">
                        Payment History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="billing-overview-tab" data-toggle="tab" data-target="#billing-overview"
                        type="button" role="tab" aria-controls="billing-overview" aria-selected="false">
                        Billing Overview
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="billingTabsContent">
                <!-- My Bills Tab -->
                <div class="tab-pane fade show active" id="my-bills" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="section-title">My Bills</h3>
                        <span class="bills-count">{{ $bills->count() }} bills</span>
                    </div>

                    @if($bills->count() > 0)
                    <div class="bills-table-container">
                        <table class="table bills-table">
                            <thead>
                                <tr>
                                    <th>Bill Number</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $bill)
                                <tr>
                                    <td>
                                        <div class="bill-number">{{ $bill->bill_number }}</div>
                                        @if($bill->payment_type === 'staggered')
                                            <span class="installment-badge">
                                                <i class="fas fa-check"></i> Installment Plan
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $bill->appointment->service->service_name ?? 'N/A' }}</td>
                                    <td>₱{{ number_format($bill->total_amount, 2) }}</td>
                                    <td>₱{{ number_format($bill->paid_amount, 2) }}</td>
                                    <td>
                                        @if($bill->remaining_balance > 0)
                                            <span class="balance-badge outstanding">₱{{ number_format($bill->remaining_balance, 2) }}</span>
                                        @else
                                            <span class="balance-badge paid">₱{{ number_format($bill->remaining_balance, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $bill->status }}">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $bill->due_date ? $bill->due_date->format('M j, Y') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-bills-message text-center">
                        <div class="no-bills-icon">
                            <i class="flaticon-document"></i>
                        </div>
                        <h4>No Bills Found</h4>
                        <p>You don't have any bills at the moment.</p>
                    </div>
                    @endif
                </div>

                <!-- Payment History Tab -->
                <div class="tab-pane fade" id="payment-history" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="section-title">Payment History</h3>
                        <span class="payments-count">{{ $recentPayments->count() }} payments</span>
                    </div>

                    @if($recentPayments->count() > 0)
                    <div class="payments-table-container">
                        <table class="table payments-table">
                            <thead>
                                <tr>
                                    <th>Payment #</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>
                                        <div class="payment-number">{{ $payment->payment_number ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="service-name">
                                            @if(isset($payment->is_virtual) && $payment->is_virtual)
                                                {{ $payment->bill->appointment->service->service_name ?? 'Service' }}
                                            @else
                                                {{ $payment->bill->appointment->service->service_name ?? 'Service' }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $paymentDate = isset($payment->is_virtual) && $payment->is_virtual 
                                                ? ($payment->created_at instanceof \Carbon\Carbon ? $payment->created_at : \Carbon\Carbon::parse($payment->created_at))
                                                : $payment->created_at;
                                        @endphp
                                        <div class="payment-date">{{ $paymentDate->format('M j, Y') }}</div>
                                        <div class="payment-time">{{ $paymentDate->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="payment-amount">₱{{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    <td>
                                        <span class="payment-method-badge {{ strtolower(str_replace('_', '-', $payment->payment_method ?? 'cash')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(isset($payment->payment_reference) && $payment->payment_reference)
                                            <div class="payment-reference">{{ $payment->payment_reference }}</div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ ($payment->status ?? 'completed') === 'completed' ? 'completed' : 'pending' }}">
                                            {{ ucfirst($payment->status ?? 'Completed') }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="view-payment-btn">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="no-payments-message text-center">
                        <div class="no-payments-icon">
                            <i class="flaticon-credit-card"></i>
                        </div>
                        <h4>No Payment History</h4>
                        <p>You haven't made any payments yet.</p>
                    </div>
                    @endif
                </div>

                <!-- Billing Overview Tab -->
                <div class="tab-pane fade" id="billing-overview" role="tabpanel">
                    <div class="billing-overview-content">
                        <!-- Status Breakdown -->
                        <div class="overview-section">
                            <h4 class="overview-section-title">Status Breakdown</h4>
                            <div class="overview-table-container">
                                <table class="table overview-table">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th class="text-right">Count</th>
                                            <th class="text-right">Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="status-indicator paid"></span>
                                                Paid Bills
                                            </td>
                                            <td class="text-right"><strong>{{ $paidBills->count() }}</strong></td>
                                            <td class="text-right">{{ $totalBills > 0 ? round(($paidBills->count() / $totalBills) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="status-indicator pending"></span>
                                                Pending Bills
                                            </td>
                                            <td class="text-right"><strong>{{ $pendingBills->count() }}</strong></td>
                                            <td class="text-right">{{ $totalBills > 0 ? round(($pendingBills->count() / $totalBills) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="status-indicator partial"></span>
                                                Partial Bills
                                            </td>
                                            <td class="text-right"><strong>{{ $partialBills->count() }}</strong></td>
                                            <td class="text-right">{{ $totalBills > 0 ? round(($partialBills->count() / $totalBills) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="status-indicator overdue"></span>
                                                Overdue Bills
                                            </td>
                                            <td class="text-right"><strong>{{ $overdueBills->count() }}</strong></td>
                                            <td class="text-right">{{ $totalBills > 0 ? round(($overdueBills->count() / $totalBills) * 100, 1) : 0 }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="overview-section">
                            <h4 class="overview-section-title">Payment Methods</h4>
                            <div class="overview-table-container">
                                <table class="table overview-table">
                                    <thead>
                                        <tr>
                                            <th>Method</th>
                                            <th class="text-right">Count</th>
                                            <th class="text-right">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $paymentMethods = $recentPayments->groupBy('payment_method');
                                        @endphp
                                        @foreach($paymentMethods as $method => $payments)
                                        <tr>
                                            <td>
                                                <span class="method-indicator {{ strtolower(str_replace('_', '-', $method)) }}"></span>
                                                {{ ucfirst(str_replace('_', ' ', $method)) }}
                                            </td>
                                            <td class="text-right"><strong>{{ $payments->count() }}</strong></td>
                                            <td class="text-right">₱{{ number_format($payments->sum('amount'), 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Staggered Payment Stats -->
                        <div class="overview-section">
                            <h4 class="overview-section-title">Staggered Payment Statistics</h4>
                            <div class="overview-table-container">
                                <table class="table overview-table">
                                    <thead>
                                        <tr>
                                            <th>Metric</th>
                                            <th class="text-right">Count</th>
                                            <th class="text-right">Total Amount</th>
                                            <th class="text-right">Remaining Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="stat-indicator total"></span>
                                                Total Staggered Bills
                                            </td>
                                            <td class="text-right"><strong>{{ $staggeredBills->count() }}</strong></td>
                                            <td class="text-right">₱{{ number_format($staggeredBills->sum('total_amount'), 2) }}</td>
                                            <td class="text-right">₱{{ number_format($staggeredBills->sum('remaining_balance'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="stat-indicator active"></span>
                                                Active Installments
                                            </td>
                                            <td class="text-right"><strong>{{ $activeInstallments->count() }}</strong></td>
                                            <td class="text-right">₱{{ number_format($activeInstallments->sum('total_amount'), 2) }}</td>
                                            <td class="text-right">₱{{ number_format($activeInstallments->sum('remaining_balance'), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="stat-indicator completed"></span>
                                                Completed Plans
                                            </td>
                                            <td class="text-right"><strong>{{ $staggeredBills->where('remaining_balance', 0)->count() }}</strong></td>
                                            <td class="text-right">₱{{ number_format($staggeredBills->where('remaining_balance', 0)->sum('total_amount'), 2) }}</td>
                                            <td class="text-right">₱0.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
