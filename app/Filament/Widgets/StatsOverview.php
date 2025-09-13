<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Appointment;
use App\Models\ClinicService;
use App\Models\Feedback;
use App\Models\MedicalCertificate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Clients', User::where('role', 'Client')->count())
                ->description('Registered clients')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Total Appointments', Appointment::count())
                ->description('All appointments')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
                
            Stat::make('Pending Appointments', Appointment::where('status', 'PENDING')->count())
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Active Services', ClinicService::where('status', 'ACTIVE')->count())
                ->description('Available services')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info'),
                
            Stat::make('Total Revenue', '₱' . number_format(
                Appointment::where('is_paid', true)->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')->sum('clinic_services.price')
            ))
                ->description('From paid appointments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Average Rating', number_format(Feedback::avg('rating') ?? 0, 1) . '/5')
                ->description('Customer satisfaction')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}