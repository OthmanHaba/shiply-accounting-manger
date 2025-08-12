<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Setting;

class ReceiptPrintController extends Controller
{
    public function print(Receipt $receipt)
    {
        // Load all necessary relationships
        $receipt->load([
            'customer',
            'currency',
            'treasure',
            'invoices',
        ]);

        // Get company information from settings and config
        $companyInfo = [
            'name' => config('app.name', 'Your Company Name'),
            'phone' => Setting::where('key', 'company_phone')->value('value') ?? '+1234567890',
            'email' => Setting::where('key', 'company_email')->value('value') ?? config('mail.from.address', 'info@company.com'),
            'address' => Setting::where('key', 'company_address')->value('value') ?? 'Company Address',
            'website' => Setting::where('key', 'company_website')->value('value') ?? config('app.url'),
        ];

        return view('receipt.print', compact('receipt', 'companyInfo'));
    }
}
