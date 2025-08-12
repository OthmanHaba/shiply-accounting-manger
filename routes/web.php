<?php

use App\Http\Controllers\InvoicePrintController;
use App\Http\Controllers\ReceiptPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('filament.admin.pages.dashboard');
});

Route::get('/invoice/{invoice}/print', [InvoicePrintController::class, 'print'])
    ->name('invoice.print');

Route::get('/receipt/{receipt}/print', [ReceiptPrintController::class, 'print'])
    ->name('receipt.print');
