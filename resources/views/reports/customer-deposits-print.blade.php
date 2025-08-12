<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.reports.customer_deposits.print.title') }}</title>
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
            background: #f0f0f0;
            padding: 8px 12px;
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-size: 14px;
        }

        .customer-details {
            padding: 8px 12px;
            font-size: 11px;
            border-bottom: 1px solid #ccc;
        }

        .deposits-table {
            width: 100%;
            border-collapse: collapse;
        }

        .deposits-table th,
        .deposits-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            font-size: 11px;
        }

        .deposits-table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .currency-total {
            font-weight: bold;
            background: #f5f5f5;
        }

        .summary-section {
            margin-top: 20px;
            border: 2px solid #000;
            padding: 12px;
        }

        .summary-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .summary-item {
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .summary-label {
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

            .summary-section {
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

            .deposits-table th,
            .deposits-table td {
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
            {{ __('resources.reports.customer_deposits.print.print_button') }}
        </button>
    </div>

    <div class="report-container">
        <!-- Header with Company Info and Logo -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $companyInfo['name'] }}</h1>
                <p>{{ __('resources.reports.customer_deposits.print.company_phone') }}: {{ $companyInfo['phone'] }}</p>
                <p>{{ __('resources.reports.customer_deposits.print.company_email') }}: {{ $companyInfo['email'] }}</p>
                <p>{{ __('resources.reports.customer_deposits.print.company_address') }}: {{ $companyInfo['address'] }}</p>
            </div>
            <div class="logo-section">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
            </div>
        </div>

        <!-- Report Title -->
        <div class="report-title">
            {{ __('resources.reports.customer_deposits.print.report_title') }}
        </div>

        <!-- Customer Deposits -->
        @forelse($customerDeposits as $customerData)
            <div class="customer-section">
                <!-- Customer Header -->
                <div class="customer-header">
                    {{ $customerData['customer']->name }}
                </div>

                <!-- Customer Details -->
                <div class="customer-details">
                    <strong>{{ __('resources.reports.customer_deposits.print.customer_code') }}:</strong> {{ $customerData['customer']->code }} |
                    <strong>{{ __('resources.reports.customer_deposits.print.customer_phone') }}:</strong> {{ $customerData['customer']->phone ?? __('resources.reports.customer_deposits.print.not_available') }}
                </div>

                <!-- Deposits Table -->
                <table class="deposits-table">
                    <thead>
                        <tr>
                            <th>{{ __('resources.reports.customer_deposits.print.currency') }}</th>
                            <th>{{ __('resources.reports.customer_deposits.print.total_deposits') }}</th>
                            <th>{{ __('resources.reports.customer_deposits.print.transactions_count') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customerData['currencies'] as $currencyData)
                            <tr>
                                <td>{{ $currencyData['currency']->name ?? $currencyData['currency']->code }} ({{ $currencyData['currency']->code }})</td>
                                <td class="currency-total">{{ number_format($currencyData['total_deposits'], 2) }}</td>
                                <td>{{ $currencyData['deposits_count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="no-data">
                {{ __('resources.reports.customer_deposits.print.no_deposits_found') }}
            </div>
        @endforelse

        <!-- Summary Section -->
        @if(!empty($customerDeposits))
            @php
                $controller = new App\Http\Controllers\CustomerDepositsReportController();
                $totals = $controller->getTotalsByCurrency();
            @endphp

            @if(!empty($totals))
                <div class="summary-section">
                    <div class="summary-title">
                        {{ __('resources.reports.customer_deposits.print.summary_title') }}
                    </div>

                    <div class="summary-grid">
                        @foreach($totals as $total)
                            <div class="summary-item">
                                <div class="summary-value">{{ number_format($total['total_deposits'], 2) }} {{ $total['currency']->code }}</div>
                                <div class="summary-label">
                                    {{ __('resources.reports.customer_deposits.print.total_for_currency', ['currency' => $total['currency']->code]) }}
                                </div>
                                <div class="summary-label">
                                    {{ $total['total_customers'] }} {{ __('resources.reports.customer_deposits.print.customers') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('resources.reports.customer_deposits.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>{{ __('resources.reports.customer_deposits.print.report_validity') }}</p>
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
