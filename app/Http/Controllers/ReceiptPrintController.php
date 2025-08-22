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
            'address' => Setting::where('key', 'company_address')->value('value') ?? 'Company Address',
        ];

        return view('receipt.print', compact('receipt', 'companyInfo'));
    }
}
