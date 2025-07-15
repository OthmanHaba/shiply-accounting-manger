<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function withdraw($amount): static
    {
        $this->amount = $this->amount - $amount;

        $this->transactions()->create([
            'code' => 'WITHDRAW',
            'title' => 'Withdraw from Account',
            'description' => 'withdraw from account to Treasure',
            'amount' => $amount,
        ]);
        $this->save();

        return $this;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    public function deposit(float $amount): static
    {
        $this->amount = $this->amount + $amount;
        $this->transactions()->create([
            'code' => 'DEPOSIT',
            'title' => 'Deposit to Account',
            'description' => 'deposit to account from Treasure',
            'amount' => $amount,
        ]);
        $this->save();

        return $this;
    }
}
