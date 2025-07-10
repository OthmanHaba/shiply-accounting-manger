<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Calculate total price from items
        $totalPrice = 0;

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (isset($item['unit_price']) && isset($item['item_count'])) {
                    $totalPrice += (float) $item['unit_price'] * (int) $item['item_count'];
                }
            }
        }

        // Apply discount if provided
        if (isset($data['discount']) && $data['discount'] > 0) {
            $discountAmount = ($totalPrice * (float) $data['discount']) / 100;
            $totalPrice -= $discountAmount;
        }

        $data['total_price'] = round($totalPrice, 2);

        return $data;
    }

    protected function afterCreate(): void
    {
        $invoice = $this->record;
        $itemsCount = $invoice->items()->count();

        // Send additional notification about items
        if ($itemsCount > 0) {
            Notification::make()
                ->info()
                ->title('Invoice items added')
                ->body("Added {$itemsCount} items to the invoice.")
                ->icon('heroicon-o-shopping-cart')
                ->iconColor('info')
                ->send();
        }
    }
}
