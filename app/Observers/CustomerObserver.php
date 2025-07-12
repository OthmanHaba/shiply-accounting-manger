<?php

namespace App\Observers;

use App\Models\Currency;
use App\Models\Customer;
use Filament\Notifications\Notification;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        $currencies = Currency::all();
        $accountsCreated = 0;

        foreach ($currencies as $currency) {
            $customer->accounts()->create([
                'amount' => 0,
                'code' => strtoupper($customer->code.'-'.$currency->code),
                'currency_id' => $currency->id,
            ]);
            $accountsCreated++;
        }

        Notification::make()
            ->info()
            ->title('Accounts created')
            ->body("Created {$accountsCreated} currency accounts for the customer.")
            ->icon('heroicon-o-banknotes')
            ->iconColor('info')
            ->send();
    }

    public function updated(Customer $customer): void {}

    public function deleted(Customer $customer): void {}

    public function restored(Customer $customer): void {}
}
