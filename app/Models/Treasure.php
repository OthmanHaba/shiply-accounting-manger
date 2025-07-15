<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
