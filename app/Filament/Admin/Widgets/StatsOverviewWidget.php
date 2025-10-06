<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Feedback;
use App\Models\Training;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalClients = User::where('role', 'Client')->count();
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'PENDING')->count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $completedAppointments = Appointment::where('status', 'COMPLETED')->count();
        $averageRating = Feedback::avg('rating') ?? 0;
        $totalTrainings = Training::where('is_published', true)->count();
        $totalRevenue = Appointment::where('appointments.is_paid', true)
            ->where('appointments.status', 'COMPLETED')
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->sum('clinic_services.price') ?? 0;

        return [
            Stat::make('Total Clients', $totalClients)
                ->description('Registered clients')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Today\'s Appointments', $todayAppointments)
                ->description('Scheduled for today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Pending Appointments', $pendingAppointments)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Completed', $completedAppointments)
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Average Rating', number_format($averageRating, 1) . '/5')
                ->description('Client satisfaction')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            
            Stat::make('Total Revenue', '₱' . number_format($totalRevenue, 2))
                ->description('All-time earnings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}