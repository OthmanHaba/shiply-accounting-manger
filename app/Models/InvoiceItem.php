<?php

namespace App\Models;

use App\Enums\InvoiceItemsTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'item_id',
        'description',
        'weight',
        'item_type',
        'item_count',
        'currency_id',
        'invoice_id',
        'unit_price',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'item_type' => InvoiceItemsTypes::class,
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
