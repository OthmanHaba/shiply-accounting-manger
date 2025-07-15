<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'type', 'currency', 'amount', 'account_id', 'description', 'title',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
