<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
