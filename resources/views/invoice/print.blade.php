<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('resources.invoice_resource.print.title') }} - {{ $invoice->code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .print-page {
                page-break-after: always;
            }

            .print-page:last-child {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body class="bg-white text-gray-800 text-sm">
<div class="max-w-4xl mx-auto p-6">
    <!-- Print Button -->
    <div class="no-print mb-4">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            {{ __('resources.invoice_resource.print.print_button') }}
        </button>
    </div>

    <!-- Header with Company and Customer Info -->
    <div class="border-b-2 border-gray-200 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <!-- Company Information (Left) -->
            <div class="w-1/2">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ __('resources.invoice_resource.print.invoice_title') }}</h1>
                <div class="bg-gray-50 p-4 rounded">
                    <h2 class="text-lg font-semibold mb-3 text-gray-900">{{ __('resources.invoice_resource.print.company_info') }}</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.company_name') }}:</span> {{ $companyInfo['name'] }}</p>
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.company_phone') }}:</span> {{ $companyInfo['phone'] }}</p>
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.company_email') }}:</span> {{ $companyInfo['email'] }}</p>
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.company_address') }}:</span> {{ $companyInfo['address'] }}</p>
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.company_website') }}:</span> {{ $companyInfo['website'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Information (Right) -->
            <div class="w-1/2 {{ app()->getLocale() === 'ar' ? 'mr-6' : 'ml-6' }}">
                <div class="text-right mb-4">
                    <p class="text-gray-600">{{ __('resources.invoice_resource.print.invoice_code') }}: {{ $invoice->code }}</p>
                    <p class="text-gray-600">{{ __('resources.invoice_resource.print.date') }}: {{ $invoice->created_at->format('Y-m-d') }}</p>
                    <p class="text-gray-600">{{ __('resources.invoice_resource.print.type') }}: {{ $invoice->type->getLabel() }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h2 class="text-lg font-semibold mb-3 text-gray-900">{{ __('resources.invoice_resource.print.customer_info') }}</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.customer_name') }}:</span> {{ $invoice->customer->name }}</p>
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.customer_code') }}:</span> {{ $invoice->customer->code }}</p>
                        <p><span class="font-medium">{{ __('resources.invoice_resource.print.customer_phone') }}:</span> {{ $invoice->customer->phone ?? __('resources.invoice_resource.print.not_available') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes (if exists) -->
    @if($invoice->notes)
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-3 text-gray-900">{{ __('resources.invoice_resource.print.notes') }}</h2>
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">
                <p class="text-gray-700">{{ $invoice->notes }}</p>
            </div>
        </div>
    @endif

    <!-- Invoice Items -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-3 text-gray-900">{{ __('resources.invoice_resource.print.items') }}</h2>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-left">#</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">{{ __('resources.invoice_resource.print.item_name') }}</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">{{ __('resources.invoice_resource.print.quantity') }}</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">{{ __('resources.invoice_resource.print.price') }}</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">{{ __('resources.invoice_resource.print.total') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $item->item->name }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->item_count }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-right">{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Invoice Totals -->
    <div class="mb-6">
        <div class="flex justify-end">
            <div class="w-64">
                <div class="bg-gray-50 p-4 rounded border">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">{{ __('resources.invoice_resource.print.total') }}</h3>
                    @foreach($invoice->invoicePrices as $price)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="font-medium">{{ __('resources.invoice_resource.print.total') }} ({{ $price->currency->code }}):</span>
                            <span class="font-semibold">{{ number_format($price->total, 2) }} {{ $price->currency->code }}</span>
                        </div>
                    @endforeach

                    @if($invoice->discount > 0)
                        <div class="flex justify-between py-2 text-red-600">
                            <span class="font-medium">{{ __('resources.invoice_resource.print.discount') }}:</span>
                            <span class="font-semibold">-{{ number_format($invoice->discount, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="border-t-2 border-gray-200 pt-4 mt-8">
        <div class="text-center text-gray-600">
            <p class="text-lg">{{ __('resources.invoice_resource.print.thank_you') }}</p>
            <p class="text-xs mt-2">{{ __('resources.invoice_resource.print.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
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
