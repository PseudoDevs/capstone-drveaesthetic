<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Appointment;
use App\Models\Feedback;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SatisfactionTrendChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Client Satisfaction Trend';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Get last 6 months satisfaction data
        $satisfactionData = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $avgRating = Feedback::whereHas('appointment', function($query) use ($date) {
                $query->whereMonth('appointment_date', $date->month)
                      ->whereYear('appointment_date', $date->year);
            })
            ->avg('rating');

            $satisfactionData[] = $avgRating ? round($avgRating, 1) : 0;
            $labels[] = $monthName;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Average Rating',
                    'data' => $satisfactionData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
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
                    'min' => 0,
                    'max' => 5,
                    'ticks' => [
                        'stepSize' => 0.5,
                        'callback' => 'function(value) { return value + "/5"; }'
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Average Rating: " + context.parsed.y + "/5"; }'
                    ]
                ]
            ]
        ];
    }
}
