<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.receipt_resource.print.title') }} - {{ $receipt->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
            color: black;
            font-size: 13px;
            line-height: 1.3;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 0;
        }

        .cutting-line {
            margin: 15px auto;
            height: 2px;
            max-width: 800px;
            background: repeating-linear-gradient(
                to right,
                #000 0,
                #000 5px,
                transparent 5px,
                transparent 10px
            );
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

        .receipt-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }

        .receipt-details {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }

        .detail-label {
            font-weight: bold;
            min-width: 120px;
        }

        .detail-value {
            flex: 1;
            text-align: right;
        }

        .amount-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #000;
        }

        .amount-value {
            font-size: 20px;
            font-weight: bold;
            margin: 8px 0;
        }

        .notes {
            margin: 20px 0;
            padding: 10px;
            border-left: 3px solid #000;
            background: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #000;
            font-size: 11px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 5px;
                font-size: 10px;
                line-height: 1.2;
            }

            .receipt-container {
                border: 1px solid #000;
                box-shadow: none;
                padding: 10px;
                margin-bottom: 0;
                page-break-inside: avoid;
                max-height: 45vh;
            }

            .cutting-line {
                margin: 8px auto;
                page-break-inside: avoid;
                height: 1px;
                background: repeating-linear-gradient(
                    to right,
                    #000 0,
                    #000 3px,
                    transparent 3px,
                    transparent 8px
                );
            }

            .header {
                margin-bottom: 12px;
                padding-bottom: 10px;
            }

            .company-info h1 {
                font-size: 14px;
                margin: 0 0 5px 0;
            }

            .company-info p {
                font-size: 9px;
                margin: 2px 0;
            }

            .logo {
                width: 60px;
                height: auto;
            }

            .receipt-title {
                margin: 10px 0;
                font-size: 14px;
            }

            .detail-row {
                margin-bottom: 4px;
                padding-bottom: 2px;
            }

            .amount-section {
                margin: 12px 0;
                padding: 10px;
            }

            .amount-value {
                font-size: 16px;
                margin: 5px 0;
            }

            .notes {
                margin: 10px 0;
                padding: 8px;
                font-size: 9px;
            }

            .footer {
                margin-top: 12px;
                padding-top: 10px;
                font-size: 8px;
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
    </style>
</head>
<body>
<!-- Print Button -->
<div class="no-print">
    <button onclick="window.print()" class="print-button">
        {{ __('resources.receipt_resource.print.print_button') }}
    </button>
</div>

<!-- First Receipt -->
<div class="receipt-container">
    <!-- Header with Company Info and Logo -->
    <div class="header">
        <div class="company-info">
            <h1>{{ $companyInfo['name'] }}</h1>
            <p>{{ __('resources.receipt_resource.print.company_phone') }}: {{ $companyInfo['phone'] }}</p>
            <p>{{ __('resources.receipt_resource.print.company_address') }}: {{ $companyInfo['address'] }}</p>
        </div>
        <div class="logo-section">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
        </div>
    </div>

    <!-- Receipt Title -->
    <div class="receipt-title">
        {!! __('resources.receipt_resource.print.receipt_title') . ($receipt->type !== \App\Enums\ReceiptType::DEPOSIT ? 'صرف' : 'قبض') !!}
    </div>

    <!-- Receipt Details -->
    <div class="receipt-details">
        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.receipt_number') }}:</span>
            <span class="detail-value">#{{ $receipt->id }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.date') }}:</span>
            <span class="detail-value">{{ $receipt->created_at->format('Y-m-d H:i') }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.type') }}:</span>
            <span class="detail-value">{{ $receipt->type->getLabel() }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.customer_name') }}:</span>
            <span class="detail-value">{{ $receipt->customer->name }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.customer_code') }}:</span>
            <span class="detail-value">{{ $receipt->customer->code }}</span>
        </div>

        @if($receipt->customer->phone)
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.receipt_resource.print.customer_phone') }}:</span>
                <span class="detail-value">{{ $receipt->customer->phone }}</span>
            </div>
        @endif

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.treasure') }}:</span>
            <span class="detail-value">{{ $receipt->treasure->name }}</span>
        </div>
    </div>

    <!-- Amount Section -->
    <div class="amount-section">
        <div>{{ __('resources.receipt_resource.print.amount') }}</div>
        <div class="amount-value">{{ number_format($receipt->amount, 2) }} {{ $receipt->currency->code }}</div>
    </div>

    <!-- Notes -->
    @if($receipt->note)
        <div class="notes">
            <strong>{{ __('resources.receipt_resource.print.notes') }}:</strong><br>
            {{ $receipt->note }}
        </div>
    @endif

    <!-- Related Invoices -->
    @if($receipt->invoices->count() > 0)
        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.related_invoices') }}:</span>
            <span class="detail-value">
                @foreach($receipt->invoices as $invoice)
                    #{{ $invoice->code }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </span>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('resources.receipt_resource.print.thank_you') }}</p>
        <p>{{ __('resources.receipt_resource.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</div>

<!-- Cutting Line -->
<div class="cutting-line"></div>

<!-- Second Receipt (Duplicate) -->
<div class="receipt-container">
    <!-- Header with Company Info and Logo -->
    <div class="header">
        <div class="company-info">
            <h1>{{ $companyInfo['name'] }}</h1>
            <p>{{ __('resources.receipt_resource.print.company_phone') }}: {{ $companyInfo['phone'] }}</p>
            <p>{{ __('resources.receipt_resource.print.company_address') }}: {{ $companyInfo['address'] }}</p>
        </div>
        <div class="logo-section">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
        </div>
    </div>

    <!-- Receipt Title -->
    <div class="receipt-title">
        {!! __('resources.receipt_resource.print.receipt_title') . ($receipt->type !== \App\Enums\ReceiptType::DEPOSIT ? 'صرف' : 'قبض') !!}
    </div>

    <!-- Receipt Details -->
    <div class="receipt-details">
        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.receipt_number') }}:</span>
            <span class="detail-value">#{{ $receipt->id }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.date') }}:</span>
            <span class="detail-value">{{ $receipt->created_at->format('Y-m-d H:i') }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.type') }}:</span>
            <span class="detail-value">{{ $receipt->type->getLabel() }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.customer_name') }}:</span>
            <span class="detail-value">{{ $receipt->customer->name }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.customer_code') }}:</span>
            <span class="detail-value">{{ $receipt->customer->code }}</span>
        </div>

        @if($receipt->customer->phone)
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.receipt_resource.print.customer_phone') }}:</span>
                <span class="detail-value">{{ $receipt->customer->phone }}</span>
            </div>
        @endif

        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.treasure') }}:</span>
            <span class="detail-value">{{ $receipt->treasure->name }}</span>
        </div>
    </div>

    <!-- Amount Section -->
    <div class="amount-section">
        <div>{{ __('resources.receipt_resource.print.amount') }}</div>
        <div class="amount-value">{{ number_format($receipt->amount, 2) }} {{ $receipt->currency->code }}</div>
    </div>

    <!-- Notes -->
    @if($receipt->note)
        <div class="notes">
            <strong>{{ __('resources.receipt_resource.print.notes') }}:</strong><br>
            {{ $receipt->note }}
        </div>
    @endif

    <!-- Related Invoices -->
    @if($receipt->invoices->count() > 0)
        <div class="detail-row">
            <span class="detail-label">{{ __('resources.receipt_resource.print.related_invoices') }}:</span>
            <span class="detail-value">
                @foreach($receipt->invoices as $invoice)
                    #{{ $invoice->code }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </span>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('resources.receipt_resource.print.thank_you') }}</p>
        <p>{{ __('resources.receipt_resource.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
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
