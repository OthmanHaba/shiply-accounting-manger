<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\Treasure;

class TreasureObserver
{
    public function created(Treasure $treasure): void
    {
        foreach (Currency::all() as $currency) {
            $treasure->accounts()->create([
                'code' => strtoupper($treasure->name.'-'.$currency->code),
                'amount' => 0,
                'currency_id' => $currency->id,
            ]);
        }
    }

    public function updated(Treasure $treasure): void {}

    public function deleted(Treasure $treasure): void {}

    public function restored(Treasure $treasure): void {}
}
