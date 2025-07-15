<?php

use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt_invoice', function (Blueprint $table) {
            $table->foreignIdFor(Receipt::class);
            $table->foreignIdFor(Invoice::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_invoice');
    }
};
