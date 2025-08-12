<?php

namespace App\Models;

use App\Observers\TreasureObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;

#[ObservedBy(TreasureObserver::class)]
class Treasure extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'location',
    ];

    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'location'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "treasure_{$eventName}")
            ->useLogName('treasures');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'تم إنشاء خزينة جديدة',
            'updated' => 'تم تحديث بيانات الخزينة',
            'deleted' => 'تم حذف الخزينة',
            default => "تم {$eventName} الخزينة",
        };
    }
}
