<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'appointmentChartWidget';
    protected static ?string $heading = 'Appointment Status Overview';
    protected static ?int $sort = 2;

    protected function getOptions(): array
    {
        // Define status colors mapping
        $statusColors = [
            'PENDING' => '#F59E0B',     // Orange
            'CONFIRMED' => '#3B82F6',   // Blue
            'ON-GOING' => '#8B5CF6',    // Purple
            'COMPLETED' => '#10B981',   // Green
            'CANCELLED' => '#EF4444',   // Red
            'DECLINED' => '#6B7280',    // Gray
            'RESCHEDULED' => '#F97316', // Dark Orange
        ];

        $statusCounts = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusLabels = array_keys($statusCounts);
        $statusValues = array_values($statusCounts);

        // Map colors to the actual statuses present
        $chartColors = [];
        foreach ($statusLabels as $status) {
            $chartColors[] = $statusColors[$status] ?? '#6B7280';
        }

        // Fallback if no data
        if (empty($statusLabels)) {
            $statusLabels = ['No Data'];
            $statusValues = [1];
            $chartColors = ['#6B7280'];
        }

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => $statusValues,
            'labels' => $statusLabels,
            'colors' => $chartColors,
            'legend' => [
                'position' => 'bottom',
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%',
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
                'formatter' => 'function(val) { return Math.round(val) + "%" }',
            ],
            'responsive' => [
                [
                    'breakpoint' => 480,
                    'options' => [
                        'chart' => [
                            'width' => 200,
                        ],
                        'legend' => [
                            'position' => 'bottom',
                        ],
                    ],
                ],
            ],
        ];
    }
}