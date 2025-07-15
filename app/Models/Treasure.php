<?php

namespace App\Models;

use App\Observers\TreasureObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[ObservedBy(TreasureObserver::class)]
class Treasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function accounts(): MorphMany
    {
        return $this->morphMany(Account::class, 'accountable');
    }
}
