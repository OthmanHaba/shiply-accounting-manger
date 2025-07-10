<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon('heroicon-o-eye')
                ->color('gray'),
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-exclamation-triangle')
                ->modalHeading('Delete Invoice')
                ->modalDescription('Are you sure you want to delete this invoice? This action cannot be undone.')
                ->modalSubmitActionLabel('Delete Invoice'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Invoice updated successfully')
            ->body('The invoice has been updated with the latest changes.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success');
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $invoice = $this->record;
        $itemsCount = $invoice->items()->count();

        // Send additional notification about items
        if ($itemsCount > 0) {
            Notification::make()
                ->info()
                ->title('Invoice items updated')
                ->body("Invoice now contains {$itemsCount} items.")
                ->icon('heroicon-o-shopping-cart')
                ->iconColor('info')
                ->send();
        }
    }
}
