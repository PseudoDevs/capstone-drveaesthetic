<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Appointment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class PopularServicesWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'popularServicesWidget';
    protected static ?string $heading = 'Most Popular Services';
    protected static ?int $sort = 4;

    protected function getOptions(): array
    {
        $servicesData = Appointment::select('clinic_services.service_name', DB::raw('count(*) as appointment_count'))
            ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
            ->where('appointments.status', 'COMPLETED')
            ->groupBy('clinic_services.service_name', 'clinic_services.id')
            ->orderBy('appointment_count', 'desc')
            ->limit(8)
            ->get();

        $serviceNames = $servicesData->pluck('service_name')->toArray();
        $appointmentCounts = $servicesData->pluck('appointment_count')->map(fn($value) => (int) $value)->toArray();
        
        // Handle empty data case
        if (empty($serviceNames)) {
            $serviceNames = ['No Data'];
            $appointmentCounts = [0];
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
                    'name' => 'Appointments',
                    'data' => $appointmentCounts,
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
            'colors' => ['#3B82F6'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => false,
                    'columnWidth' => '70%',
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'offsetY' => -20,
                'style' => [
                    'fontSize' => '12px',
                    'colors' => ['#304758'],
                ],
            ],
            'grid' => [
                'show' => true,
                'strokeDashArray' => 3,
            ],
            'tooltip' => [
                'enabled' => true,
                'y' => [
                    'formatter' => 'function(val) { return val + " appointments" }',
                ],
            ],
        ];
    }
}