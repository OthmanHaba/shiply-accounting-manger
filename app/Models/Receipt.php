<?php

namespace App\Models;

use App\Enums\ReceiptType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReceiptType::class,
        ];
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'respect_invoice', 'respect_id', 'invoice_id');
    }
}
