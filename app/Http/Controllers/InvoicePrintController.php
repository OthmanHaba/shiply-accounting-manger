<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Setting;

class InvoicePrintController extends Controller
{
    public function print(Invoice $invoice)
    {
        // Load all necessary relationships
        $invoice->load([
            'customer',
            'items.item',
            'items.currency',
            'invoicePrices.currency',
        ]);

        // Get company information from settings and config
        $companyInfo = [
            'name' => config('app.name', 'Your Company Name'),
            'phone' => Setting::where('key', 'company_phone')->value('value') ?? '+1234567890',
            'address' => Setting::where('key', 'company_address')->value('value') ?? 'Company Address',
        ];

        return view('invoice.print', compact('invoice', 'companyInfo'));
    }
}
