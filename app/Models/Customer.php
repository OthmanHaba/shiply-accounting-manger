<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;

#[ObservedBy(CustomerObserver::class)]
class Customer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'phone',
    ];

    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'code', 'phone'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "customer_{$eventName}")
            ->useLogName('customers');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'تم إنشاء عميل جديد',
            'updated' => 'تم تحديث بيانات العميل',
            'deleted' => 'تم حذف العميل',
            default => "تم {$eventName} العميل",
        };
    }
}
