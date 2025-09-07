<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Account;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove debt fields from customer data
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'debt_amount_')) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $currencies = Currency::all();
        $debtCreated = false;

        foreach ($currencies as $currency) {
            $debtAmount = $this->data['debt_amount_'.$currency->id] ?? 0;

            // Create debt account if amount > 0
            if ($debtAmount > 0) {
                /**
                 * @var Customer $customer
                 */
                $customer = $this->getRecord();

                $customer->accounts()->where('code', strtoupper($customer->code.'-'.$currency->code))
                    ->where('currency_id', $currency->id)
                    ->first()->withdraw($debtAmount);

                //                $this->getRecord()->accounts()->create([
                //                    'code' => 'DEBT_'.strtoupper(uniqid()),
                //                    'amount' => -$debtAmount, // Negative amount for debt
                //                    'currency_id' => $currency->id,
                //                ]);

                $debtCreated = true;
            }
        }
    }

    protected function getCreatedNotification(): ?Notification
    {
        $body = 'The customer has been created and accounts have been set up for all currencies.';

        // Check if any debt was created
        $currencies = Currency::all();
        $hasDebt = false;

        foreach ($currencies as $currency) {
            $debtAmount = $this->data['debt_amount_'.$currency->id] ?? 0;
            if ($debtAmount > 0) {
                $hasDebt = true;
                break;
            }
        }

        if ($hasDebt) {
            $body .= ' Debt accounts have been created for the specified currencies.';
        }

        return Notification::make()
            ->success()
            ->title('Customer created successfully')
            ->body($body)
            ->icon('heroicon-o-check-circle')
            ->iconColor('success');
    }
}
