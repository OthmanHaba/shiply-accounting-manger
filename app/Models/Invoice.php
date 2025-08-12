<?php

namespace App\Models;

use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    use LogsActivity;

    protected $fillable = [
        'code',
        'customer_id',
        'type',
        'note',
        'discount',
    ];

    protected $with = [
        'customer',
        'invoicePrices',
        'items',
    ];

    public function respects(): BelongsToMany
    {
        return $this->belongsToMany(Receipt::class, 'respect_invoice', 'invoice_id', 'respect_id');
    }

    protected function casts(): array
    {
        return [
            'type' => InvoiceType::class,
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function item(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'invoice_items', 'invoice_id', 'item_id');
    }

    public function invoicePrices(): HasMany
    {
        return $this->hasMany(InvoicePrice::class, 'invoice_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'customer_id', 'type', 'note', 'discount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "invoice_{$eventName}")
            ->useLogName('invoices');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return match ($eventName) {
            'created' => 'تم إنشاء فاتورة جديدة',
            'updated' => 'تم تحديث بيانات الفاتورة',
            'deleted' => 'تم حذف الفاتورة',
            default => "تم {$eventName} الفاتورة",
        };
    }
}
