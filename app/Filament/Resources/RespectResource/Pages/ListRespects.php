<?php

namespace App\Filament\Resources\RespectResource\Pages;

use App\Filament\Resources\RespectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRespects extends ListRecords
{
    protected static string $resource = RespectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
