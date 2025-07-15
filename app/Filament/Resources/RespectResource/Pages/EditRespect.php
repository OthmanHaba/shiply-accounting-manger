<?php

namespace App\Filament\Resources\RespectResource\Pages;

use App\Filament\Resources\RespectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRespect extends EditRecord
{
    protected static string $resource = RespectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
