<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Bill;
use App\Models\Payment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class PaymentOverviewWidget extends Widget
{
    protected static string $view = 'filament.staff.widgets.payment-overview-widget';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        // Get payment statistics
        $totalBills = Bill::count();
        $paidBills = Bill::where('remaining_balance', 0)->count();
        $pendingBills = Bill::where('remaining_balance', '>', 0)->count();
        $staggeredBills = Bill::where('payment_type', 'staggered')->count();
        
        $totalAmount = Bill::sum('total_amount');
        $paidAmount = Bill::sum('paid_amount');
        $remainingAmount = Bill::sum('remaining_balance');
        
        // Get recent payments
        $recentPayments = Payment::with(['bill.client', 'bill.appointment.service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get active staggered payments
        $activeStaggeredPayments = Bill::where('payment_type', 'staggered')
            ->where('remaining_balance', '>', 0)
            ->with(['client', 'appointment.service'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get payment trends (last 30 days)
        $paymentTrends = Payment::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get overdue payments
        $overduePayments = Bill::where('payment_type', 'staggered')
            ->where('remaining_balance', '>', 0)
            ->where('due_date', '<', now())
            ->with(['client', 'appointment.service'])
            ->get();

        return [
            'totalBills' => $totalBills,
            'paidBills' => $paidBills,
            'pendingBills' => $pendingBills,
            'staggeredBills' => $staggeredBills,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
            'recentPayments' => $recentPayments,
            'activeStaggeredPayments' => $activeStaggeredPayments,
            'paymentTrends' => $paymentTrends,
            'overduePayments' => $overduePayments,
        ];
    }
}
