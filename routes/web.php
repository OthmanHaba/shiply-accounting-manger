<?php

use App\Http\Controllers\CustomerDebtsReportController;
use App\Http\Controllers\CustomerDepositsReportController;
use App\Http\Controllers\InvoicePrintController;
use App\Http\Controllers\ReceiptPrintController;
use App\Http\Controllers\ReportsPrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('filament.admin.pages.dashboard');
});

Route::get('/invoice/{invoice}/print', [InvoicePrintController::class, 'print'])
    ->name('invoice.print');

Route::get('/receipt/{receipt}/print', [ReceiptPrintController::class, 'print'])
    ->name('receipt.print');

Route::get('/reports/print', [ReportsPrintController::class, 'print'])
    ->name('reports.print');

Route::get('/reports/customer-deposits/print', [CustomerDepositsReportController::class, 'print'])
    ->name('reports.customer-deposits.print');

Route::get('/reports/customer-debts/print', [CustomerDebtsReportController::class, 'print'])
    ->name('reports.customer-debts.print');
