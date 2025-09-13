<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AppointmentsChart extends ApexChartWidget
{
    protected static ?string $chartId = 'appointmentsChart';

    protected static ?string $heading = 'Appointments Overview';

    protected static ?int $sort = 4;

    protected function getOptions(): array
    {
        // Get appointments by status
        $statusCounts = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = ['PENDING', 'SCHEDULED', 'ON-GOING', 'CANCELLED', 'DECLINED', 'RESCHEDULE', 'COMPLETED'];
        $data = [];
        $labels = [];

        foreach ($statuses as $status) {
            $count = $statusCounts[$status] ?? 0;
            if ($count > 0) {
                $data[] = $count;
                $labels[] = ucfirst(strtolower(str_replace('-', ' ', $status)));
            }
        }

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => $data,
            'labels' => $labels,
            'colors' => ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#6b7280', '#8b5cf6', '#06b6d4'],
            'legend' => [
                'position' => 'bottom',
            ],
            'responsive' => [
                [
                    'breakpoint' => 480,
                    'options' => [
                        'chart' => [
                            'width' => 200
                        ],
                        'legend' => [
                            'position' => 'bottom'
                        ]
                    ]
                ]
            ]
        ];
    }
}
