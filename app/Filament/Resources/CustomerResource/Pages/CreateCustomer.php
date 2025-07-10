<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Currency;
use App\Models\Customer;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Customer created successfully')
            ->body('The customer has been created and accounts have been set up for all currencies.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success');
    }

    public function afterCreate(): void
    {
        /**
         * @var Customer $customer
         */
        $customer = $this->record;

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

        // Send additional notification about accounts created
        Notification::make()
            ->info()
            ->title('Accounts created')
            ->body("Created {$accountsCreated} currency accounts for the customer.")
            ->icon('heroicon-o-banknotes')
            ->iconColor('info')
            ->send();
    }
}
