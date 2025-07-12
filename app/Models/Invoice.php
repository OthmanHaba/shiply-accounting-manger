<?php

namespace App\Models;

use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'type',
        'note',
        'discount',
    ];

    protected $with = [
        'customer',
        'invoicePrices',
        'items',
    ];

    protected function casts(): array
    {
        return [
            'type' => InvoiceType::class,
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function item(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'invoice_items', 'invoice_id', 'item_id');
    }

    public function invoicePrices(): HasMany
    {
        return $this->hasMany(InvoicePrice::class, 'invoice_id');
    }
}
