<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\ClinicService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue Trend';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get last 12 months revenue data
        $revenueData = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $revenue = Appointment::where('appointments.status', 'completed')
                ->whereMonth('appointment_date', $date->month)
                ->whereYear('appointment_date', $date->year)
                ->join('clinic_services', 'appointments.service_id', '=', 'clinic_services.id')
                ->sum('clinic_services.price');

            $revenueData[] = $revenue;
            $labels[] = $monthName;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₱)',
                    'data' => $revenueData,
                    'backgroundColor' => 'rgba(251, 170, 169, 0.2)',
                    'borderColor' => 'rgba(251, 170, 169, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "₱" + value.toLocaleString(); }'
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Revenue: ₱" + context.parsed.y.toLocaleString(); }'
                    ]
                ]
            ]
        ];
    }
}
