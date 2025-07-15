<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceType;
use App\Enums\ReceiptType;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePrice;
use App\Models\Receipt;
use App\Models\Treasure;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Get total customers
        $totalCustomers = Customer::count();

        // Get total invoices by type
        $totalInvoices = Invoice::count();
        $closedInvoices = Invoice::where('type', InvoiceType::Closed)->count();
        $sharedInvoices = Invoice::where('type', InvoiceType::Shared)->count();

        // Get total receipts by type
        $totalReceipts = Receipt::count();
        $totalDeposits = Receipt::where('type', ReceiptType::DEPOSIT)->sum('amount');
        $totalWithdrawals = Receipt::where('type', ReceiptType::WITHDRAWAL)->sum('amount');

        // Get total revenue from invoices
        $totalRevenue = InvoicePrice::sum('total_price');

        // Get treasures count
        $totalTreasures = Treasure::count();

        // Calculate net cash flow
        $netCashFlow = $totalDeposits - $totalWithdrawals;

        return [
            Stat::make('Total Customers', $totalCustomers)
                ->description('Active customers in system')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Invoices', $totalInvoices)
                ->description("{$closedInvoices} closed, {$sharedInvoices} shared")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Total Revenue', number_format($totalRevenue, 2))
                ->description('From all invoices')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Total Receipts', $totalReceipts)
                ->description('Deposits and withdrawals')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('info'),

            Stat::make('Net Cash Flow', number_format($netCashFlow, 2))
                ->description($netCashFlow >= 0 ? 'Positive flow' : 'Negative flow')
                ->descriptionIcon($netCashFlow >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($netCashFlow >= 0 ? 'success' : 'danger'),

            Stat::make('Treasures', $totalTreasures)
                ->description('Active treasury locations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
