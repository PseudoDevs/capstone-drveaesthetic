<?php

namespace App\Filament\Staff\Widgets;

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
            'pending' => '#F59E0B',     // Orange
            'confirmed' => '#3B82F6',   // Blue
            'on-going' => '#8B5CF6',    // Purple
            'completed' => '#10B981',   // Green
            'cancelled' => '#EF4444',   // Red
            'decline' => '#6B7280',    // Gray
            'rescheduled' => '#F97316', // Dark Orange
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
            $chartColors[] = $statusColors[$status] ?? '#244ea2ff';
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
