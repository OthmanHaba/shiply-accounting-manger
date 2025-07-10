<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Currency;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    public function afterCreate(): void
    {
        /**
         * @var Customer $customer
         */
        $customer = $this->record;

        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            $customer->accounts()->create([
                'amount' => 0,
                'code' => "$customer->name-$currency->code",
                'currency_id' => $currency->id,
            ]);
        }
    }
}
