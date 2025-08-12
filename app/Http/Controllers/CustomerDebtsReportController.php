<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Setting;

class CustomerDebtsReportController extends Controller
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

        // Get customer debts data
        $customerDebts = $this->getCustomerDebtsData();

        return view('reports.customer-debts-print', compact('customerDebts', 'companyInfo'));
    }

    private function getCustomerDebtsData(): array
    {
        // Get all customers who have negative account balances (debts)
        $customersWithDebts = Customer::whereHas('accounts', function ($query) {
            $query->where('amount', '<', 0);
        })->with(['accounts' => function ($query) {
            $query->where('amount', '<', 0)->with('currency');
        }])->get();

        $data = [];

        foreach ($customersWithDebts as $customer) {
            // Get all negative balance accounts for this customer
            $debtAccounts = $customer->accounts->where('amount', '<', 0);

            if ($debtAccounts->count() > 0) {
                $customerData = [
                    'customer' => $customer,
                    'accounts' => [],
                    'total_debt_by_currency' => [],
                ];

                foreach ($debtAccounts as $account) {
                    $debtAmount = abs($account->amount); // Convert to positive for display

                    $customerData['accounts'][] = [
                        'account' => $account,
                        'currency' => $account->currency,
                        'debt_amount' => $debtAmount,
                        'original_amount' => $account->amount,
                    ];

                    // Sum by currency
                    $currencyCode = $account->currency->code;
                    if (! isset($customerData['total_debt_by_currency'][$currencyCode])) {
                        $customerData['total_debt_by_currency'][$currencyCode] = [
                            'currency' => $account->currency,
                            'total_debt' => 0,
                        ];
                    }
                    $customerData['total_debt_by_currency'][$currencyCode]['total_debt'] += $debtAmount;
                }

                $data[] = $customerData;
            }
        }

        // Sort by customer name
        usort($data, function ($a, $b) {
            return strcmp($a['customer']->name, $b['customer']->name);
        });

        return $data;
    }

    public function getTotalDebtsByCurrency(): array
    {
        $currencies = Currency::all();
        $totals = [];

        foreach ($currencies as $currency) {
            // Get total debt amount for this currency (negative balances)
            $totalDebt = Account::where('currency_id', $currency->id)
                ->where('amount', '<', 0)
                ->sum('amount');

            // Convert to positive amount
            $totalDebt = abs($totalDebt);

            // Count customers with debt in this currency
            $totalCustomers = Customer::whereHas('accounts', function ($query) use ($currency) {
                $query->where('currency_id', $currency->id)
                    ->where('amount', '<', 0);
            })->count();

            if ($totalDebt > 0) {
                $totals[] = [
                    'currency' => $currency,
                    'total_debt' => $totalDebt,
                    'total_customers' => $totalCustomers,
                ];
            }
        }

        return $totals;
    }
}
