<?php

namespace App\Filament\Staff\Widgets;

use App\Models\Appointment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyAppointmentsTrendWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'monthlyAppointmentsTrendWidget';
    protected static ?string $heading = 'Monthly Appointments Trend';
    protected static ?int $sort = 2;

    protected function getOptions(): array
    {
        // Get last 12 months data
        $monthlyData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Appointment::whereYear('appointments.created_at', $date->year)
                ->whereMonth('appointments.created_at', $date->month)
                ->count();
            
            $monthlyData->push([
                'month' => $date->format('M Y'),
                'count' => $count
            ]);
        }

        $months = $monthlyData->pluck('month')->toArray();
        $counts = $monthlyData->pluck('count')->map(fn($value) => (int) $value)->toArray();
        
        // Handle empty data case
        if (empty($months)) {
            $months = ['No Data'];
            $counts = [0];
        }

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Appointments',
                    'data' => $counts,
                ],
            ],
            'xaxis' => [
                'categories' => $months,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
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
            'stroke' => [
                'curve' => 'smooth',
                'width' => 2,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.7,
                    'opacityTo' => 0.9,
                    'stops' => [0, 90, 100],
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
                'y' => [
                    'formatter' => 'function(val) { return val + " appointments" }',
                ],
            ],
        ];
    }
}