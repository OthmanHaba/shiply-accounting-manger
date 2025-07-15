<?php

namespace App\Filament\Widgets;

use App\Models\InvoicePrice;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue Over Time';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '30days';

    protected function getFilters(): ?array
    {
        return [
            '7days' => 'Last 7 days',
            '30days' => 'Last 30 days',
            '90days' => 'Last 90 days',
            '365days' => 'Last year',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        $days = match ($filter) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            '365days' => 365,
            default => 30,
        };

        $startDate = Carbon::now()->subDays($days);

        // Get revenue data grouped by date
        $revenueData = InvoicePrice::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total_revenue')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Create array of all dates in range
        $dateRange = [];
        $labels = [];
        $revenues = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dateRange[$date] = 0;
            $labels[] = Carbon::now()->subDays($i)->format('M j');
        }

        // Fill in actual revenue data
        foreach ($revenueData as $data) {
            $dateRange[$data->date] = (float) $data->total_revenue;
        }

        $revenues = array_values($dateRange);

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenues,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}
