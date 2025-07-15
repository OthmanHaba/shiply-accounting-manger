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
            Stat::make('إجمالي العملاء', $totalCustomers)
                ->description('العملاء النشطين في النظام')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('إجمالي الفواتير', $totalInvoices)
                ->description("{$closedInvoices} مغلقة، {$sharedInvoices} مشتركة")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('إجمالي الإيرادات', number_format($totalRevenue, 2))
                ->description('من جميع الفواتير')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('إجمالي الإيصالات', $totalReceipts)
                ->description('القبوض والصرف')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('info'),

            Stat::make('الحركة المالية', number_format($netCashFlow, 2))
                ->description($netCashFlow >= 0 ? ' إيجابي' : ' سلبي')
                ->descriptionIcon($netCashFlow >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($netCashFlow >= 0 ? 'success' : 'danger'),

            Stat::make('الخزائن', $totalTreasures)
                ->description('مواقع الخزانة النشطة')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
