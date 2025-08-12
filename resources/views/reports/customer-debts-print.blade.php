<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.reports.customer_debts.print.title') }}</title>
        <style>
        @page {
            size: A4;
            margin: 1in;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: black;
            font-size: 12px;
            line-height: 1.3;
        }

        .report-container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .company-info {
            flex: 1;
        }

        .company-info h1 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: bold;
        }

        .company-info p {
            margin: 3px 0;
            font-size: 11px;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-left: 15px;
        }

        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
            border: 2px solid #000;
            padding: 8px;
        }

        .customer-section {
            margin-bottom: 20px;
            border: 1px solid #000;
            page-break-inside: avoid;
        }

        .customer-header {
            padding: 8px 12px;
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-size: 14px;
            background: #f0f0f0;
        }

        .customer-details {
            padding: 8px 12px;
            font-size: 11px;
            border-bottom: 1px solid #ccc;
        }

        .accounts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .accounts-table th,
        .accounts-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            font-size: 11px;
        }

        .accounts-table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .debt-amount {
            font-weight: bold;
        }

        .currency-total {
            font-weight: bold;
            background: #f5f5f5;
        }

        .customer-summary {
            padding: 8px 12px;
            border-top: 1px solid #000;
            background: #f9f9f9;
        }

        .customer-summary h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 8px;
        }

        .summary-item {
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
            background: white;
        }

        .summary-value {
            font-size: 12px;
            font-weight: bold;
        }

        .summary-label {
            font-size: 10px;
        }

        .overall-summary {
            margin-top: 20px;
            border: 2px solid #000;
            padding: 12px;
        }

        .overall-summary-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .overall-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .overall-summary-item {
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }

        .overall-summary-value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .overall-summary-label {
            font-size: 10px;
            text-transform: uppercase;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #000;
            font-size: 10px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .customer-section {
                page-break-inside: avoid;
            }

            .overall-summary {
                page-break-before: avoid;
            }
        }

        .print-button {
            background: #000;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .print-button:hover {
            background: #333;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            font-style: italic;
            border: 2px dashed #000;
        }

        .warning-icon {
            font-size: 20px;
            margin-bottom: 8px;
        }

        /* A4 specific optimizations */
        @media print {
            @page {
                margin: 0.75in;
            }

            body {
                font-size: 11px;
            }

            .customer-section {
                margin-bottom: 15px;
            }

            .accounts-table th,
            .accounts-table td {
                padding: 4px 6px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print">
        <button onclick="window.print()" class="print-button">
            {{ __('resources.reports.customer_debts.print.print_button') }}
        </button>
    </div>

    <div class="report-container">
        <!-- Header with Company Info and Logo -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $companyInfo['name'] }}</h1>
                <p>{{ __('resources.reports.customer_debts.print.company_phone') }}: {{ $companyInfo['phone'] }}</p>
                <p>{{ __('resources.reports.customer_debts.print.company_email') }}: {{ $companyInfo['email'] }}</p>
                <p>{{ __('resources.reports.customer_debts.print.company_address') }}: {{ $companyInfo['address'] }}</p>
            </div>
            <div class="logo-section">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
            </div>
        </div>

        <!-- Report Title -->
        <div class="report-title">
            ⚠️ {{ __('resources.reports.customer_debts.print.report_title') }}
        </div>

        <!-- Customer Debts -->
        @forelse($customerDebts as $customerData)
            <div class="customer-section">
                <!-- Customer Header -->
                <div class="customer-header">
                    ⚠️ {{ $customerData['customer']->name }}
                </div>

                <!-- Customer Details -->
                <div class="customer-details">
                    <strong>{{ __('resources.reports.customer_debts.print.customer_code') }}:</strong> {{ $customerData['customer']->code }} |
                    <strong>{{ __('resources.reports.customer_debts.print.customer_phone') }}:</strong> {{ $customerData['customer']->phone ?? __('resources.reports.customer_debts.print.not_available') }}
                </div>

                <!-- Accounts Table -->
                <table class="accounts-table">
                    <thead>
                        <tr>
                            <th>{{ __('resources.reports.customer_debts.print.account_id') }}</th>
                            <th>{{ __('resources.reports.customer_debts.print.currency') }}</th>
                            <th>{{ __('resources.reports.customer_debts.print.account_balance') }}</th>
                            <th>{{ __('resources.reports.customer_debts.print.debt_amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customerData['accounts'] as $accountData)
                            <tr>
                                <td>#{{ $accountData['account']->id }}</td>
                                <td>{{ $accountData['currency']->name ?? $accountData['currency']->code }} ({{ $accountData['currency']->code }})</td>
                                <td>{{ number_format($accountData['original_amount'], 2) }}</td>
                                <td class="debt-amount">{{ number_format($accountData['debt_amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Customer Summary -->
                <div class="customer-summary">
                    <h4>{{ __('resources.reports.customer_debts.print.customer_total_debts') }}</h4>
                    <div class="summary-grid">
                        @foreach($customerData['total_debt_by_currency'] as $currencyCode => $currencyData)
                            <div class="summary-item">
                                <div class="summary-value">{{ number_format($currencyData['total_debt'], 2) }} {{ $currencyCode }}</div>
                                <div class="summary-label">{{ __('resources.reports.customer_debts.print.total_debt') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="no-data">
                <div class="warning-icon">✅</div>
                <h3>{{ __('resources.reports.customer_debts.print.no_debts_title') }}</h3>
                <p>{{ __('resources.reports.customer_debts.print.no_debts_found') }}</p>
            </div>
        @endforelse

        <!-- Overall Summary -->
        @if(!empty($customerDebts))
            @php
                $controller = new App\Http\Controllers\CustomerDebtsReportController();
                $totals = $controller->getTotalDebtsByCurrency();
            @endphp

            @if(!empty($totals))
                <div class="overall-summary">
                    <div class="overall-summary-title">
                        {{ __('resources.reports.customer_debts.print.summary_title') }}
                    </div>

                    <div class="overall-summary-grid">
                        @foreach($totals as $total)
                            <div class="overall-summary-item">
                                <div class="overall-summary-value">{{ number_format($total['total_debt'], 2) }} {{ $total['currency']->code }}</div>
                                <div class="overall-summary-label">
                                    {{ __('resources.reports.customer_debts.print.total_debt_for_currency', ['currency' => $total['currency']->code]) }}
                                </div>
                                <div class="overall-summary-label">
                                    {{ $total['total_customers'] }} {{ __('resources.reports.customer_debts.print.customers_with_debt') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ __('resources.reports.customer_debts.print.important_note') }}:</strong> {{ __('resources.reports.customer_debts.print.debt_explanation') }}</p>
            <p>{{ __('resources.reports.customer_debts.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>{{ __('resources.reports.customer_debts.print.report_validity') }}</p>
        </div>
    </div>

    <script>
        // Auto-focus for print shortcut
        document.addEventListener('keydown', function (e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
