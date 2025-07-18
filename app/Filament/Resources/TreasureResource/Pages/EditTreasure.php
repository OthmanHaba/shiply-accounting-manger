<?php

namespace App\Filament\Resources\TreasureResource\Pages;

use App\Filament\Resources\TreasureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTreasure extends EditRecord
{
    protected static string $resource = TreasureResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return __('resources.treasure_resource.messages.updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label(__('resources.treasure_resource.actions.view')),
            Actions\DeleteAction::make()
                ->label(__('resources.treasure_resource.actions.delete'))
                ->successNotificationTitle(__('resources.treasure_resource.messages.deleted')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
