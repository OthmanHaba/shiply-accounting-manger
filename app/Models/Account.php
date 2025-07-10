<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Account extends Model
{
    protected $fillable = [
        'code',
        'amount',
        'currency_id',
        'accountable_id',
        'accountable_type',
    ];

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
