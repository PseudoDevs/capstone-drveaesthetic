<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Appointment;
use App\Enums\AppointmentType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OnlineAppointmentsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalOnline = Appointment::online()->count();
        $todayOnline = Appointment::online()->whereDate('appointment_date', today())->count();
        $pendingOnline = Appointment::online()->where('status', 'PENDING')->count();
        $completedOnline = Appointment::online()->where('status', 'COMPLETED')->count();

        return [
            Stat::make('Total Online', $totalOnline)
                ->description('All online appointments')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Today Online', $todayOnline)
                ->description('Online appointments today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Pending Online', $pendingOnline)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Completed Online', $completedOnline)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}