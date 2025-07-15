<?php

namespace App\Filament\Resources\TreasureResource\Pages;

use App\Filament\Resources\TreasureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTreasure extends ViewRecord
{
    protected static string $resource = TreasureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label(__('resources.treasure_resource.actions.edit')),
            Actions\DeleteAction::make()
                ->label(__('resources.treasure_resource.actions.delete'))
                ->successNotificationTitle(__('resources.treasure_resource.messages.deleted')),
        ];
    }
}
