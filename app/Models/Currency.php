<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Currency extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'code',
    ];

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name', 'code'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "currency_{$eventName}")
            ->useLogName('currencies');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'تم إنشاء عملة جديدة',
            'updated' => 'تم تحديث بيانات العملة',
            'deleted' => 'تم حذف العملة',
            default => "تم {$eventName} العملة",
        };
    }
}
