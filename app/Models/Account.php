<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['code', 'amount', 'currency_id', 'accountable_id', 'accountable_type'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "account_{$eventName}")
            ->useLogName('accounts');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'تم إنشاء حساب جديد',
            'updated' => 'تم تحديث رصيد الحساب',
            'deleted' => 'تم حذف الحساب',
            default => "تم {$eventName} الحساب",
        };
    }
}
