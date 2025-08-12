<?php

namespace App\Filament\Pages;

use App\Enums\ReceiptType;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Receipt;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class ReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.reports';

    public static function getNavigationGroup(): ?string
    {
        return 'الاعدادات';
    }

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('resources.reports.navigation_label');
    }

    public function getTitle(): string
    {
        return __('resources.reports.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label(__('resources.reports.actions.print'))
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn () => route('reports.print'))
                ->openUrlInNewTab(),
            Action::make('print_customer_deposits')
                ->label(__('resources.reports.actions.print_customer_deposits'))
                ->icon('heroicon-o-users')
                ->color('success')
                ->url(fn () => route('reports.customer-deposits.print'))
                ->openUrlInNewTab(),
            Action::make('print_customer_debts')
                ->label(__('resources.reports.actions.print_customer_debts'))
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->url(fn () => route('reports.customer-debts.print'))
                ->openUrlInNewTab(),
        ];
    }

    public function getReportsData(): array
    {
        // Get all currencies for grouping
        $currencies = Currency::all();

        $data = [];

        foreach ($currencies as $currency) {
            // Total Deposits (DEPOSIT receipts)
            $totalDeposits = Receipt::where('type', ReceiptType::DEPOSIT)
                ->where('currency_id', $currency->id)
                ->sum('amount');

            // Total Withdrawals (WITHDRAWAL receipts)
            $totalWithdrawals = Receipt::where('type', ReceiptType::WITHDRAWAL)
                ->where('currency_id', $currency->id)
                ->sum('amount');

            // Total Credits (Deposits - Withdrawals)
            $totalCredits = $totalDeposits - $totalWithdrawals;

            // Total Invoices amount by currency
            $totalInvoices = DB::table('invoice_prices')
                ->where('currency_id', $currency->id)
                ->sum('total_price');

            // Count of receipts by type
            $depositsCount = Receipt::where('type', ReceiptType::DEPOSIT)
                ->where('currency_id', $currency->id)
                ->count();

            $withdrawalsCount = Receipt::where('type', ReceiptType::WITHDRAWAL)
                ->where('currency_id', $currency->id)
                ->count();

            // Count of invoices
            $invoicesCount = Invoice::whereHas('invoicePrices', function ($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->count();

            // Only include currencies that have transactions
            if ($totalDeposits > 0 || $totalWithdrawals > 0 || $totalInvoices > 0) {
                $data[$currency->code] = [
                    'currency' => $currency,
                    'total_deposits' => $totalDeposits,
                    'total_withdrawals' => $totalWithdrawals,
                    'total_credits' => $totalCredits,
                    'total_invoices' => $totalInvoices,
                    'deposits_count' => $depositsCount,
                    'withdrawals_count' => $withdrawalsCount,
                    'invoices_count' => $invoicesCount,
                ];
            }
        }

        return $data;
    }

    public function getOverallStats(): array
    {
        return [
            'total_receipts' => Receipt::count(),
            'total_invoices' => Invoice::count(),
            'total_customers' => \App\Models\Customer::count(),
            'total_treasures' => \App\Models\Treasure::count(),
        ];
    }
}
