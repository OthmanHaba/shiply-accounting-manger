<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\InvoicePrice;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class FinancialReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.financial-report';

    protected static ?string $navigationGroup = 'الاعدادات';

    public static function getNavigationLabel(): string
    {
        return __('resources.financial_report_page.navigation_label');
    }

    public function getTitle(): string
    {
        return __('resources.financial_report_page.title');
    }

    public string $type = 'daily';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public static function canAccess(): bool
    {
        return auth()->user()->can('view'.class_basename(self::class));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label(__('resources.financial_report_page.form.report_type'))
                    ->native(false)
                    ->options([
                        'daily' => __('resources.financial_report_page.form.daily'),
                        'weekly' => __('resources.financial_report_page.form.weekly'),
                        'yearly' => __('resources.financial_report_page.form.yearly'),
                        'custom' => __('resources.financial_report_page.form.custom'),
                    ])
                    ->default('daily')
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->type = $state),

                DatePicker::make('start_date')
                    ->label(__('resources.financial_report_page.form.start_date'))
                    ->native(false)
                    ->visible(fn () => $this->type === 'custom')
                    ->live()
                    ->required(fn () => $this->type === 'custom'),

                DatePicker::make('end_date')
                    ->label(__('resources.financial_report_page.form.end_date'))
                    ->native(false)
                    ->visible(fn () => $this->type === 'custom')
                    ->live()
                    ->required(fn () => $this->type === 'custom')
                    ->afterOrEqual('start_date'),
            ]);
    }

    public function getDailyReportData(): array
    {
        $today = Carbon::today();

        $dailyIncomeByCurrency = InvoicePrice::select(
            'currency_id',
            DB::raw('SUM(total_price) as total')
        )
            ->with('currency')
            ->whereHas('invoice', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->groupBy('currency_id')
            ->get();

        $invoiceCount = Invoice::whereDate('created_at', $today)->count();

        $totalIncome = $dailyIncomeByCurrency->sum('total');

        return [
            'date' => $today->format('Y-m-d'),
            'income_by_currency' => $dailyIncomeByCurrency,
            'total_income' => $totalIncome,
            'invoice_count' => $invoiceCount,
        ];
    }

    public function getWeeklyReportData(): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyIncomeByCurrency = InvoicePrice::select(
            'currency_id',
            DB::raw('SUM(total_price) as total')
        )
            ->with('currency')
            ->whereHas('invoice', function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            })
            ->groupBy('currency_id')
            ->get();

        $invoiceCount = Invoice::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $dailyBreakdown = InvoicePrice::select(
            'invoice_prices.currency_id',
            DB::raw('DATE(invoices.created_at) as date'),
            DB::raw('SUM(invoice_prices.total_price) as total')
        )
            ->with('currency')
            ->join('invoices', 'invoice_prices.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('invoice_prices.currency_id', DB::raw('DATE(invoices.created_at)'))
            ->get();

        $totalIncome = $weeklyIncomeByCurrency->sum('total');

        return [
            'week_start' => $startOfWeek->format('Y-m-d'),
            'week_end' => $endOfWeek->format('Y-m-d'),
            'income_by_currency' => $weeklyIncomeByCurrency,
            'total_income' => $totalIncome,
            'invoice_count' => $invoiceCount,
            'daily_breakdown' => $dailyBreakdown,
        ];
    }

    public function getYearlyReportData(): array
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $yearlyIncomeByCurrency = InvoicePrice::select(
            'currency_id',
            DB::raw('SUM(total_price) as total')
        )
            ->with('currency')
            ->whereHas('invoice', function ($query) use ($startOfYear, $endOfYear) {
                $query->whereBetween('created_at', [$startOfYear, $endOfYear]);
            })
            ->groupBy('currency_id')
            ->get();

        $invoiceCount = Invoice::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        $monthlyBreakdown = InvoicePrice::select(
            'invoice_prices.currency_id',
            DB::raw('YEAR(invoices.created_at) as year'),
            DB::raw('MONTH(invoices.created_at) as month'),
            DB::raw('SUM(invoice_prices.total_price) as total')
        )
            ->with('currency')
            ->join('invoices', 'invoice_prices.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startOfYear, $endOfYear])
            ->groupBy('invoice_prices.currency_id', DB::raw('YEAR(invoices.created_at)'), DB::raw('MONTH(invoices.created_at)'))
            ->get();

        $totalIncome = $yearlyIncomeByCurrency->sum('total');

        return [
            'year' => $startOfYear->format('Y'),
            'income_by_currency' => $yearlyIncomeByCurrency,
            'total_income' => $totalIncome,
            'invoice_count' => $invoiceCount,
            'monthly_breakdown' => $monthlyBreakdown,
        ];
    }

    public function getCustomReportData(): array
    {
        if (! $this->start_date || ! $this->end_date) {
            return [
                'start_date' => null,
                'end_date' => null,
                'income_by_currency' => collect(),
                'total_income' => 0,
                'invoice_count' => 0,
                'daily_breakdown' => collect(),
            ];
        }

        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = Carbon::parse($this->end_date)->endOfDay();

        $customIncomeByCurrency = InvoicePrice::select(
            'currency_id',
            DB::raw('SUM(total_price) as total')
        )
            ->with('currency')
            ->whereHas('invoice', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('currency_id')
            ->get();

        $invoiceCount = Invoice::whereBetween('created_at', [$startDate, $endDate])->count();

        $dailyBreakdown = InvoicePrice::select(
            'invoice_prices.currency_id',
            DB::raw('DATE(invoices.created_at) as date'),
            DB::raw('SUM(invoice_prices.total_price) as total')
        )
            ->with('currency')
            ->join('invoices', 'invoice_prices.invoice_id', '=', 'invoices.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->groupBy('invoice_prices.currency_id', DB::raw('DATE(invoices.created_at)'))
            ->orderBy(DB::raw('DATE(invoices.created_at)'))
            ->get();

        $totalIncome = $customIncomeByCurrency->sum('total');

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'income_by_currency' => $customIncomeByCurrency,
            'total_income' => $totalIncome,
            'invoice_count' => $invoiceCount,
            'daily_breakdown' => $dailyBreakdown,
        ];
    }

    public function getReportData(): array
    {
        return match ($this->type) {
            'daily' => $this->getDailyReportData(),
            'weekly' => $this->getWeeklyReportData(),
            'yearly' => $this->getYearlyReportData(),
            'custom' => $this->getCustomReportData(),
            default => $this->getDailyReportData(),
        };
    }
}
