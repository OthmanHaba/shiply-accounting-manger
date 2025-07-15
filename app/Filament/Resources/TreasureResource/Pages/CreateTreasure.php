<?php

namespace App\Filament\Resources\TreasureResource\Pages;

use App\Filament\Resources\TreasureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTreasure extends CreateRecord
{
    protected static string $resource = TreasureResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('resources.treasure_resource.messages.created');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
