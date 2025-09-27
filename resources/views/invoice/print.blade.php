<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.invoice_resource.print.title') }} - {{ $invoice->code }}</title>
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

        .invoice-container {
            max-width: 800px;
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

        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-info, .customer-info {
            flex: 1;
            padding: 15px;
            border: 1px solid #000;
            margin: 0 5px;
        }

        .info-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
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

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .items-table .text-center {
            text-align: center;
        }

        .items-table .text-right {
            text-align: right;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .totals-box {
            border: 2px solid #000;
            padding: 20px;
            min-width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #ccc;
        }

        .total-row:last-child {
            border-bottom: 2px solid #000;
            font-weight: bold;
            font-size: 16px;
        }

        .notes {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #000;
            background: #f9f9f9;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
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

            .invoice-container {
                border: 1px solid #000;
                box-shadow: none;
            }

            @page {
                size: A4;
                margin: 1cm;
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
        {{ __('resources.invoice_resource.print.print_button') }}
    </button>
</div>

<div class="invoice-container">
    <!-- Header with Company Info and Logo -->
    <div class="header">
        <div class="company-info">
            <h1>{{ $companyInfo['name'] }}</h1>
            <p>{{ __('resources.invoice_resource.print.company_phone') }}: {{ $companyInfo['phone'] }}</p>
            <p>{{ __('resources.invoice_resource.print.company_address') }}: {{ $companyInfo['address'] }}</p>
        </div>
        <div class="logo-section">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="logo">
        </div>
    </div>

    <!-- Invoice Title -->
    <div class="invoice-title">
        {{ __('resources.invoice_resource.print.invoice_title') }}
    </div>

    <!-- Invoice and Customer Details -->
    <div class="invoice-details">
        <div class="invoice-info">
            <div class="info-title">{{ __('resources.invoice_resource.print.invoice_info') }}</div>
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.invoice_resource.print.invoice_code') }}:</span>
                <span class="detail-value">{{ $invoice->code }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.invoice_resource.print.date') }}:</span>
                <span class="detail-value">{{ $invoice->created_at->format('Y-m-d') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.invoice_resource.print.type') }}:</span>
                <span class="detail-value">{{ $invoice->type->getLabel() }}</span>
            </div>
        </div>

        <div class="customer-info">
            <div class="info-title">{{ __('resources.invoice_resource.print.customer_info') }}</div>
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.invoice_resource.print.customer_name') }}:</span>
                <span class="detail-value">{{ $invoice->customer->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('resources.invoice_resource.print.customer_code') }}:</span>
                <span class="detail-value">{{ $invoice->customer->code }}</span>
            </div>
            @if($invoice->customer->phone)
                <div class="detail-row">
                    <span class="detail-label">{{ __('resources.invoice_resource.print.customer_phone') }}:</span>
                    <span class="detail-value">{{ $invoice->customer->phone }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes (if exists) -->
    @if($invoice->notes)
        <div class="notes">
            <div class="notes-title">{{ __('resources.invoice_resource.print.notes') }}:</div>
            {{ $invoice->notes }}
        </div>
    @endif

    <!-- Invoice Items -->
    <table class="items-table">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('resources.invoice_resource.print.item_name') }}</th>
            <th>{{ __('resources.invoice_resource.print.quantity') }}</th>
            <th>{{ __('resources.invoice_resource.print.price') }}</th>
            <th>{{ __('resources.invoice_resource.print.total') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->item->name }}</td>
                <td class="text-center">{{ $item->item_count }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Invoice Totals -->
    <div class="totals-section">
        <div class="totals-box">
            @foreach($invoice->invoicePrices as $price)
                <div class="total-row">
                    <span>{{ __('resources.invoice_resource.print.total') }} ({{ $price->currency->code }}):</span>
                    <span>{{ number_format($price->total_price, 2) }} {{ $price->currency->code }}</span>
                </div>
            @endforeach

            @if($invoice->discount > 0)
                <div class="total-row" style="color: red;">
                    <span>{{ __('resources.invoice_resource.print.discount') }}:</span>
                    <span>-{{ number_format($invoice->discount, 2) }}</span>
                </div>
            @endif

            <div class="total-row">
                <div>
                    اجمالي الديون
                </div>
                @foreach($invoice->customer->totalDebit() as $dipit )
                    {{$dipit}} <br>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('resources.invoice_resource.print.thank_you') }}</p>
        <p>{{ __('resources.invoice_resource.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
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
