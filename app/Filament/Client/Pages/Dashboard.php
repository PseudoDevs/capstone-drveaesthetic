<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'My Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Client\Widgets\ClientAppointmentStatsWidget::class,
            \App\Filament\Client\Widgets\UpcomingAppointmentsWidget::class,
            \App\Filament\Client\Widgets\AppointmentCalendarWidget::class,
        ];
    }
    
    public function getColumns(): int | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
        ];
    }
}