<?php

namespace App\Filament\Widgets;

use App\Enums\ReceiptType;
use App\Models\Receipt;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReceiptsChartWidget extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('widget_'.class_basename(ReceiptsChartWidget::class));
    }

    protected static ?string $heading = 'الإيداعات مقابل السحوبات';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '30days';

    protected function getFilters(): ?array
    {
        return [
            '7days' => 'آخر 7 أيام',
            '30days' => 'آخر 30 يوم',
            '90days' => 'آخر 90 يوم',
            '365days' => 'آخر سنة',
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

        // Get deposits data grouped by date
        $depositsData = Receipt::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('type', ReceiptType::DEPOSIT)
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get withdrawals data grouped by date
        $withdrawalsData = Receipt::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('type', ReceiptType::WITHDRAWAL)
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Create array of all dates in range
        $dateRange = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dateRange[$date] = ['deposits' => 0, 'withdrawals' => 0];
            $labels[] = Carbon::now()->subDays($i)->format('M j');
        }

        // Fill in deposits data
        foreach ($depositsData as $data) {
            $dateRange[$data->date]['deposits'] = (float) $data->total_amount;
        }

        // Fill in withdrawals data
        foreach ($withdrawalsData as $data) {
            $dateRange[$data->date]['withdrawals'] = (float) $data->total_amount;
        }

        $deposits = array_map(fn ($item) => $item['deposits'], $dateRange);
        $withdrawals = array_map(fn ($item) => $item['withdrawals'], $dateRange);

        return [
            'datasets' => [
                [
                    'label' => 'الإيداعات',
                    'data' => $deposits,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                ],
                [
                    'label' => 'السحوبات',
                    'data' => $withdrawals,
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
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
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
