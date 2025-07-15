<?php

namespace App\Filament\Resources\ReceiptResource\Pages;

use App\Enums\ReceiptType;
use App\Filament\Resources\ReceiptResource;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Treasure;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReceipt extends CreateRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('resources.receipt_resource.messages.created');
    }

    protected function handleRecordCreation(array $data): Model
    {
        /**
         * @var Receipt $receipt
         */
        $receipt = static::getModel()::create([
            'note' => $data['note'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'currency_id' => $data['currency_id'],
            'customer_id' => $data['customer_id'],
            'treasure_id' => $data['treasure_id'],
        ]);

        // Sync invoices if provided
        if (! empty($data['invoices'])) {
            $receipt->invoices()->sync($data['invoices']);
        }

        $customer = Customer::find($data['customer_id']);

        $treasury = Treasure::find($data['treasure_id']);

        // Handle account transactions based on receipt type
        if ($data['type'] == ReceiptType::WITHDRAWAL->value) {
            $account = $customer->accounts()->where('currency_id', $data['currency_id'])->first();
            if ($account) {
                $account->deposit($data['amount']);
                $treasury->accounts()->where('currency_id', $data['currency_id'])->first()->deposit($data['amount']);
            }
        }

        if ($data['type'] == ReceiptType::DEPOSIT->value) {
            $account = $customer->accounts()->where('currency_id', $data['currency_id'])->first();
            if ($account) {
                $account->deposit($data['amount']);
                $treasury->accounts()->where('currency_id', $data['currency_id'])->first()->withdraw($data['amount']);
            }
        }

        return $receipt;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
