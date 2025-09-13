<?php

namespace App\Filament\Client\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;

class ClientAppointmentStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $clientId = Filament::auth()->id();
        
        $totalAppointments = Appointment::where('client_id', $clientId)->count();
        $pendingAppointments = Appointment::where('client_id', $clientId)->where('status', 'PENDING')->count();
        $completedAppointments = Appointment::where('client_id', $clientId)->where('status', 'COMPLETED')->count();
        $formsToComplete = Appointment::where('client_id', $clientId)
            ->whereNotNull('form_type')
            ->where('form_completed', false)
            ->whereIn('status', ['PENDING', 'SCHEDULED'])
            ->count();

        return [
            Stat::make('Total Appointments', $totalAppointments)
                ->description('All time appointments')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
                
            Stat::make('Pending Appointments', $pendingAppointments)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Completed Appointments', $completedAppointments)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Forms to Complete', $formsToComplete)
                ->description('Pending form submissions')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($formsToComplete > 0 ? 'danger' : 'success'),
        ];
    }
}