<?php

namespace App\Filament\Resources\RespectResource\Pages;

use App\Filament\Resources\RespectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRespect extends CreateRecord
{
    protected static string $resource = RespectResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
