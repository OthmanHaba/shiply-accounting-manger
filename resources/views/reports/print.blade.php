<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.reports.print.title') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
            font-size: 14px;
            line-height: 1.4;
        }

        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .company-info {
            flex: 1;
        }

        .company-info h1 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .company-info p {
            margin: 5px 0;
            font-size: 12px;
        }

        .logo {
            width: 100px;
            height: auto;
            margin-left: 20px;
        }

        .report-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            border: 1px solid #000;
            padding: 15px;
            text-align: center;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            text-transform: uppercase;
        }

        .currency-section {
            margin-bottom: 30px;
            border: 1px solid #000;
            padding: 15px;
        }

        .currency-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .financial-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .financial-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .financial-label {
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .financial-value {
            font-size: 14px;
            font-weight: bold;
        }

        .financial-count {
            font-size: 10px;
            margin-top: 3px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #000;
            font-size: 12px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .report-container {
                border: 1px solid #000;
                box-shadow: none;
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
            padding: 40px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print">
        <button onclick="window.print()" class="print-button">
            {{ __('resources.reports.print.print_button') }}
        </button>
    </div>

    <div class="report-container">
        <!-- Header with Company Info and Logo -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $companyInfo['name'] }}</h1>
                <p>{{ __('resources.reports.print.company_phone') }}: {{ $companyInfo['phone'] }}</p>
                <p>{{ __('resources.reports.print.company_email') }}: {{ $companyInfo['email'] }}</p>
                <p>{{ __('resources.reports.print.company_address') }}: {{ $companyInfo['address'] }}</p>
            </div>
            <div class="logo-section">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
            </div>
        </div>

        <!-- Report Title -->
        <div class="report-title">
            {{ __('resources.reports.print.report_title') }}
        </div>

        <!-- Overall Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($overallStats['total_receipts']) }}</div>
                <div class="stat-label">{{ __('resources.reports.stats.total_receipts') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($overallStats['total_invoices']) }}</div>
                <div class="stat-label">{{ __('resources.reports.stats.total_invoices') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($overallStats['total_customers']) }}</div>
                <div class="stat-label">{{ __('resources.reports.stats.total_customers') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($overallStats['total_treasures']) }}</div>
                <div class="stat-label">{{ __('resources.reports.stats.total_treasures') }}</div>
            </div>
        </div>

        <!-- Financial Reports by Currency -->
        @forelse($reportsData as $currencyCode => $data)
            <div class="currency-section">
                <div class="currency-header">
                    {{ $data['currency']->name ?? $currencyCode }} ({{ $currencyCode }})
                </div>

                <div class="financial-grid">
                    <div class="financial-item">
                        <div class="financial-label">{{ __('resources.reports.financial_reports.total_deposits') }}</div>
                        <div class="financial-value">{{ number_format($data['total_deposits'], 2) }}</div>
                        <div class="financial-count">{{ $data['deposits_count'] }} {{ __('resources.reports.financial_reports.transactions') }}</div>
                    </div>

                    <div class="financial-item">
                        <div class="financial-label">{{ __('resources.reports.financial_reports.total_withdrawals') }}</div>
                        <div class="financial-value">{{ number_format($data['total_withdrawals'], 2) }}</div>
                        <div class="financial-count">{{ $data['withdrawals_count'] }} {{ __('resources.reports.financial_reports.transactions') }}</div>
                    </div>

                    <div class="financial-item">
                        <div class="financial-label">{{ __('resources.reports.financial_reports.net_credits') }}</div>
                        <div class="financial-value" style="{{ $data['total_credits'] >= 0 ? 'color: green;' : 'color: red;' }}">
                            {{ number_format($data['total_credits'], 2) }}
                        </div>
                        <div class="financial-count">{{ __('resources.reports.financial_reports.balance') }}</div>
                    </div>

                    <div class="financial-item">
                        <div class="financial-label">{{ __('resources.reports.financial_reports.total_invoices') }}</div>
                        <div class="financial-value">{{ number_format($data['total_invoices'], 2) }}</div>
                        <div class="financial-count">{{ $data['invoices_count'] }} {{ __('resources.reports.financial_reports.invoices') }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-data">
                {{ __('resources.reports.no_data.description') }}
            </div>
        @endforelse

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('resources.reports.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>{{ __('resources.reports.print.report_validity') }}</p>
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
