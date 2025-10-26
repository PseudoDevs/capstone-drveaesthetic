<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Bill;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BalanceManagementWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBills = Bill::count();
        $unpaidBills = Bill::where('remaining_balance', '>', 0)->count();
        $staggeredBills = Bill::where('payment_type', 'staggered')->count();
        $overdueBills = Bill::where('due_date', '<', now())->where('remaining_balance', '>', 0)->count();
        
        $totalOutstanding = Bill::where('remaining_balance', '>', 0)->sum('remaining_balance');
        $totalPaid = Bill::sum('paid_amount');

        return [
            Stat::make('Total Bills', $totalBills)
                ->description('All bills in system')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('primary'),
                
            Stat::make('Unpaid Bills', $unpaidBills)
                ->description('Bills with outstanding balance')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
                
            Stat::make('Staggered Bills', $staggeredBills)
                ->description('Bills with installment plans')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),
                
            Stat::make('Overdue Bills', $overdueBills)
                ->description('Bills past due date')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
                
            Stat::make('Outstanding Balance', '₱' . number_format($totalOutstanding, 2))
                ->description('Total amount owed')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
                
            Stat::make('Total Collected', '₱' . number_format($totalPaid, 2))
                ->description('Total payments received')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
