<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ClientAcquisitionChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Client Acquisition Trend';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Get last 12 months client acquisition data
        $clientData = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $newClients = User::where('role', 'Client')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $clientData[] = $newClients;
            $labels[] = $monthName;
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Clients',
                    'data' => $clientData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                        'stepSize' => 1
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "New Clients: " + context.parsed.y; }'
                    ]
                ]
            ]
        ];
    }
}
