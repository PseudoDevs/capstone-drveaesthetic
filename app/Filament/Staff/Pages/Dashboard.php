<?php

namespace App\Filament\Staff\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Staff\Widgets\BalanceManagementWidget;
use App\Filament\Staff\Widgets\PaymentOverviewWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?int $navigationSort = 1;

    protected function getHeaderWidgets(): array
    {
        return [
            BalanceManagementWidget::class,
            PaymentOverviewWidget::class,
        ];
    }
}
