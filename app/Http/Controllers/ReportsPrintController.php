<?php

namespace App\Http\Controllers;

use App\Enums\ReceiptType;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class ReportsPrintController extends Controller
{
    public function print()
    {
        // Get company information from settings and config
        $companyInfo = [
            'name' => config('app.name', 'Your Company Name'),
            'phone' => Setting::where('key', 'company_phone')->value('value') ?? '+1234567890',
            'email' => Setting::where('key', 'company_email')->value('value') ?? config('mail.from.address', 'info@company.com'),
            'address' => Setting::where('key', 'company_address')->value('value') ?? 'Company Address',
            'website' => Setting::where('key', 'company_website')->value('value') ?? config('app.url'),
        ];

        // Get reports data
        $reportsData = $this->getReportsData();
        $overallStats = $this->getOverallStats();

        return view('reports.print', compact('reportsData', 'overallStats', 'companyInfo'));
    }

    private function getReportsData(): array
    {
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

    private function getOverallStats(): array
    {
        return [
            'total_receipts' => Receipt::count(),
            'total_invoices' => Invoice::count(),
            'total_customers' => \App\Models\Customer::count(),
            'total_treasures' => \App\Models\Treasure::count(),
        ];
    }
}
