<?php

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Item::class)
                ->constrained();
            $table->string('description');
            $table->decimal('weight');
            $table->string('item_type');
            $table->integer('item_count');
            $table->foreignIdFor(Currency::class)
                ->constrained();
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->decimal('unit_price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
