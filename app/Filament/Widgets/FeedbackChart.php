<?php

namespace App\Filament\Widgets;

use App\Models\Feedback;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class FeedbackChart extends ApexChartWidget
{
    protected static ?string $chartId = 'feedbackChart';

    protected static ?string $heading = 'Customer Ratings Distribution';

    protected static ?int $sort = 5;

    protected function getOptions(): array
    {
        // Get rating distribution
        $ratingCounts = Feedback::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $ratings = [1, 2, 3, 4, 5];
        $data = [];

        foreach ($ratings as $rating) {
            $data[] = $ratingCounts[$rating] ?? 0;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Number of Ratings',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
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
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'grid' => [
                'show' => true,
                'borderColor' => '#e5e7eb',
            ],
        ];
    }
}
