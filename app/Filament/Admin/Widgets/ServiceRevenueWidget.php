<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class ServiceRevenueWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'serviceRevenueWidget';
    protected static ?string $heading = 'Revenue by Service';
    protected static ?int $sort = 3;

    protected function getOptions(): array
    {
        // Get revenue data with proper aggregation
        $revenueData = DB::table('appointments')
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->select(
                'clinic_services.service_name',
                DB::raw('SUM(CAST(clinic_services.price AS DECIMAL(10,2))) as total_revenue')
            )
            ->where('appointments.is_paid', '=', 1)
            ->where('appointments.status', '=', 'COMPLETED')
            ->groupBy('clinic_services.id', 'clinic_services.service_name')
            ->orderByDesc('total_revenue')
            ->limit(8)
            ->get();

        $serviceNames = $revenueData->pluck('service_name')->toArray();
        $revenues = $revenueData->pluck('total_revenue')->map(function($value) {
            return floatval($value ?? 0);
        })->toArray();

        // Handle empty data case
        if (empty($serviceNames) || empty($revenues) || array_sum($revenues) == 0) {
            $serviceNames = ['No Paid Services'];
            $revenues = [0];
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Revenue',
                    'data' => $revenues,
                ],
            ],
            'xaxis' => [
                'categories' => $serviceNames,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                    'rotate' => -45,
                    'maxHeight' => 60,
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#10B981'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => false,
                    'columnWidth' => '70%',
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'grid' => [
                'show' => true,
                'strokeDashArray' => 3,
            ],
            'tooltip' => [
                'enabled' => true,
            ],
        ];
    }
}