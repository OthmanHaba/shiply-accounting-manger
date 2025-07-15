<?php

namespace App\Models;

use App\Enums\ReceiptType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'amount', 'note', 'currency_id', 'customer_id', 'treasure_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReceiptType::class,
        ];
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'receipt_invoice', 'receipt_id', 'invoice_id');
    }

    public function treasure(): BelongsTo
    {
        return $this->belongsTo(Treasure::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
