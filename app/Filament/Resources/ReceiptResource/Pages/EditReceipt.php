<?php

namespace App\Filament\Resources\ReceiptResource\Pages;

use App\Filament\Resources\ReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return __('resources.receipt_resource.messages.updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label(__('resources.receipt_resource.actions.view')),
            Actions\DeleteAction::make()
                ->label(__('resources.receipt_resource.actions.delete'))
                ->successNotificationTitle(__('resources.receipt_resource.messages.deleted')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
