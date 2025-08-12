<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
    ];

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "item_{$eventName}")
            ->useLogName('items');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'تم إنشاء صنف جديد',
            'updated' => 'تم تحديث بيانات الصنف',
            'deleted' => 'تم حذف الصنف',
            default => "تم {$eventName} الصنف",
        };
    }
}
