<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[ObservedBy(CustomerObserver::class)]
class Customer extends Model
{
    protected $fillable = [
        'name',
        'code',
        'phone',
    ];

    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }
}
