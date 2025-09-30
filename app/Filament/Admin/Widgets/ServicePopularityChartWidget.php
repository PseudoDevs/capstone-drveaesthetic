<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\ClinicService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ServicePopularityChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Service Popularity';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $serviceData = ClinicService::withCount(['appointments' => function($query) {
            $query->where('status', 'completed');
        }])
        ->orderBy('appointments_count', 'desc')
        ->limit(8)
        ->get();

        $labels = $serviceData->pluck('service_name')->toArray();
        $data = $serviceData->pluck('appointments_count')->toArray();

        // Generate colors for each service
        $colors = [
            'rgba(251, 170, 169, 0.8)',
            'rgba(255, 154, 158, 0.8)',
            'rgba(255, 183, 197, 0.8)',
            'rgba(255, 192, 203, 0.8)',
            'rgba(255, 218, 225, 0.8)',
            'rgba(255, 228, 225, 0.8)',
            'rgba(255, 239, 213, 0.8)',
            'rgba(255, 245, 238, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Completed Appointments',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.label + ": " + context.parsed + " appointments"; }'
                    ]
                ]
            ]
        ];
    }
}
