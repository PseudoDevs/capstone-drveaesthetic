<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\ClinicService;
use App\Models\User;
use App\Models\Feedback;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AdvancedAnalyticsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Revenue Analytics
        $totalRevenue = Appointment::where('appointments.status', 'completed')
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->sum('clinic_services.price');

        $monthlyRevenue = Appointment::where('appointments.status', 'completed')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->sum('clinic_services.price');

        $revenueGrowth = $this->calculateRevenueGrowth();

        // Client Analytics
        $totalClients = User::where('role', 'Client')->count();
        $newClientsThisMonth = User::where('role', 'Client')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $clientRetentionRate = $this->calculateClientRetentionRate();

        // Service Analytics
        $mostPopularService = ClinicService::withCount(['appointments' => function($query) {
            $query->where('status', 'completed');
        }])
        ->orderBy('appointments_count', 'desc')
        ->first();

        $averageServiceRating = Feedback::avg('rating');

        // Appointment Analytics
        $totalAppointments = Appointment::count();
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $completionRate = $totalAppointments > 0 ? ($completedAppointments / $totalAppointments) * 100 : 0;

        $averageAppointmentValue = $totalRevenue > 0 ? $totalRevenue / $completedAppointments : 0;

        return [
            Stat::make('Total Revenue', '₱' . number_format($totalRevenue, 2))
                ->description('All time revenue')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Monthly Revenue', '₱' . number_format($monthlyRevenue, 2))
                ->description($revenueGrowth > 0 ? '+' . number_format($revenueGrowth, 1) . '% from last month' : number_format($revenueGrowth, 1) . '% from last month')
                ->descriptionIcon($revenueGrowth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth > 0 ? 'success' : 'danger'),

            Stat::make('Total Clients', number_format($totalClients))
                ->description($newClientsThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Client Retention', number_format($clientRetentionRate, 1) . '%')
                ->description('Returning clients')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Most Popular Service', $mostPopularService ? $mostPopularService->service_name : 'N/A')
                ->description($mostPopularService ? $mostPopularService->appointments_count . ' completed' : 'No data')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),

            Stat::make('Average Rating', number_format($averageServiceRating, 1) . '/5')
                ->description('Service satisfaction')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),

            Stat::make('Completion Rate', number_format($completionRate, 1) . '%')
                ->description($completedAppointments . ' of ' . $totalAppointments . ' appointments')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Avg Appointment Value', '₱' . number_format($averageAppointmentValue, 2))
                ->description('Per completed appointment')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),
        ];
    }

    private function calculateRevenueGrowth(): float
    {
        $currentMonth = Appointment::where('appointments.status', 'completed')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->sum('clinic_services.price');

        $lastMonth = Appointment::where('appointments.status', 'completed')
            ->whereMonth('appointment_date', now()->subMonth()->month)
            ->whereYear('appointment_date', now()->subMonth()->year)
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->sum('clinic_services.price');

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return (($currentMonth - $lastMonth) / $lastMonth) * 100;
    }

    private function calculateClientRetentionRate(): float
    {
        $totalClients = User::where('role', 'Client')->count();
        
        if ($totalClients == 0) {
            return 0;
        }

        $returningClients = User::where('role', 'Client')
            ->whereHas('appointments', function($query) {
                $query->where('status', 'completed');
            })
            ->whereHas('appointments', function($query) {
                $query->where('status', 'completed')
                      ->where('appointment_date', '>=', now()->subMonths(6));
            })
            ->count();

        return ($returningClients / $totalClients) * 100;
    }
}
