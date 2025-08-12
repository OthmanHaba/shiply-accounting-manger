<?php

namespace App\Http\Controllers;

use App\Enums\ReceiptType;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Setting;

class CustomerDepositsReportController extends Controller
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

        // Get customer deposits data
        $customerDeposits = $this->getCustomerDepositsData();

        return view('reports.customer-deposits-print', compact('customerDeposits', 'companyInfo'));
    }

    private function getCustomerDepositsData(): array
    {
        // Get all customers who have deposits
        $customersWithDeposits = Customer::whereHas('receipts', function ($query) {
            $query->where('type', ReceiptType::DEPOSIT);
        })->with(['receipts' => function ($query) {
            $query->where('type', ReceiptType::DEPOSIT)->with('currency');
        }])->get();

        $data = [];

        foreach ($customersWithDeposits as $customer) {
            // Group deposits by currency for each customer
            $depositsByCurrency = $customer->receipts
                ->where('type', ReceiptType::DEPOSIT)
                ->groupBy('currency_id');

            $customerData = [
                'customer' => $customer,
                'currencies' => [],
            ];

            foreach ($depositsByCurrency as $currencyId => $receipts) {
                $currency = $receipts->first()->currency;
                $totalDeposits = $receipts->sum('amount');
                $depositsCount = $receipts->count();

                $customerData['currencies'][] = [
                    'currency' => $currency,
                    'total_deposits' => $totalDeposits,
                    'deposits_count' => $depositsCount,
                ];
            }

            if (! empty($customerData['currencies'])) {
                $data[] = $customerData;
            }
        }

        // Sort by customer name
        usort($data, function ($a, $b) {
            return strcmp($a['customer']->name, $b['customer']->name);
        });

        return $data;
    }

    public function getTotalsByCurrency(): array
    {
        $currencies = Currency::all();
        $totals = [];

        foreach ($currencies as $currency) {
            $totalDeposits = Receipt::where('type', ReceiptType::DEPOSIT)
                ->where('currency_id', $currency->id)
                ->sum('amount');

            $totalCustomers = Receipt::where('type', ReceiptType::DEPOSIT)
                ->where('currency_id', $currency->id)
                ->distinct('customer_id')
                ->count();

            if ($totalDeposits > 0) {
                $totals[] = [
                    'currency' => $currency,
                    'total_deposits' => $totalDeposits,
                    'total_customers' => $totalCustomers,
                ];
            }
        }

        return $totals;
    }
}
