<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Appointment;
use App\Enums\AppointmentType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WalkInAppointmentsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalWalkIn = Appointment::walkIn()->count();
        $todayWalkIn = Appointment::walkIn()->whereDate('appointment_date', today())->count();
        $pendingWalkIn = Appointment::walkIn()->where('status', 'PENDING')->count();
        $completedWalkIn = Appointment::walkIn()->where('status', 'COMPLETED')->count();

        return [
            Stat::make('Total Walk-ins', $totalWalkIn)
                ->description('All walk-in appointments')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('secondary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Today Walk-ins', $todayWalkIn)
                ->description('Walk-in appointments today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Pending Walk-ins', $pendingWalkIn)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Completed Walk-ins', $completedWalkIn)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}