<?php

namespace App\Filament\Client\Widgets;

use App\Models\Bill;
use App\Models\Payment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ClientPaymentWidget extends Widget
{
    protected static string $view = 'filament.client.widgets.client-payment-widget';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        
        // Get client's bills
        $bills = Bill::where('client_id', $user->id)
            ->with(['appointment.service', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get recent payments
        $recentPayments = Payment::whereHas('bill', function ($query) use ($user) {
            $query->where('client_id', $user->id);
        })
        ->with(['bill.appointment.service'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Calculate payment statistics
        $totalBills = $bills->count();
        $paidBills = $bills->where('remaining_balance', 0)->count();
        $pendingBills = $bills->where('remaining_balance', '>', 0)->count();
        $totalAmount = $bills->sum('total_amount');
        $paidAmount = $bills->sum('paid_amount');
        $remainingAmount = $bills->sum('remaining_balance');

        // Get staggered payment bills
        $staggeredBills = $bills->where('payment_type', 'staggered');
        $activeInstallments = $staggeredBills->where('remaining_balance', '>', 0);

        return [
            'bills' => $bills,
            'recentPayments' => $recentPayments,
            'totalBills' => $totalBills,
            'paidBills' => $paidBills,
            'pendingBills' => $pendingBills,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
            'staggeredBills' => $staggeredBills,
            'activeInstallments' => $activeInstallments,
        ];
    }
}
