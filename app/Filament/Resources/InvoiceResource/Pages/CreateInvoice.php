<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoicePrice;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Invoice created successfully')
            ->body('The invoice has been created and is ready for processing.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success');
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the invoice
        $invoice = static::getModel()::create([
            'code' => $data['code'],
            'customer_id' => $data['customer_id'],
            'type' => $data['type'],
            'note' => $data['notes'],
        ]);

        // Create invoice items
        foreach ($data['items'] as $itemData) {
            $invoice->items()->create([
                'item_id' => $itemData['item_id'],
                'item_type' => $itemData['item_type'],
                'item_count' => $itemData['item_count'],
                'unit_price' => $itemData['unit_price'],
                'currency_id' => $itemData['currency_id'],
                'weight' => $itemData['weight'],
                'total_price' => $itemData['total_price'],
                'description' => $itemData['description'],
            ]);
        }

        if (isset($data['prices'])) {
            foreach ($data['prices'] as $currencyId => $price) {
                $cleanPrice = (float) str_replace(',', '', $price);
                if ($cleanPrice > 0) {
                    $invoice->invoicePrices()->create([
                        'currency_id' => $currencyId,
                        'total_price' => $cleanPrice,
                    ]);
                }
            }
        }

        return $invoice;
    }

    protected function afterCreate(): void
    {
        /**
         * @var Invoice $invoice
         */
        $invoice = $this->getRecord();

        /**
         * @var InvoicePrice[] $prices
         */
        $prices = $invoice->invoicePrices()->get();
        $customer = $invoice->customer;
        foreach ($prices as $price) {
            $customer->accounts()
                ->where('currency_id', $price->currency_id)
                ->first()
                ->withdraw($price->total_price);

            Notification::make()
                ->success()
                ->title('withdrawal successful')
                ->body('Customer Account Has been withdrawn by this amount'.$price->total_price)
                ->icon('heroicon-o-check-circle')
                ->iconColor('success');
        }

    }
}
