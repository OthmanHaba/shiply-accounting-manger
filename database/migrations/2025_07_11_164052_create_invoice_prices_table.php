<?php

use App\Models\Currency;
use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_price', 10, 2);
            $table->foreignIdFor(Currency::class)->constrained();
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_prices');
    }
};
