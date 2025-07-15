<?php

namespace App\Filament\Resources\TreasureResource\Pages;

use App\Filament\Resources\TreasureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTreasure extends CreateRecord
{
    protected static string $resource = TreasureResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
