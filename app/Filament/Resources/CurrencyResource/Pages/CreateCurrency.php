<?php

namespace App\Filament\Resources\CurrencyResource\Pages;

use App\Filament\Resources\CurrencyResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCurrency extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء العملة بنجاح')
            ->body('تم إضافة العملة إلى النظام.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure the currency code is always uppercase
        $data['code'] = strtoupper($data['code']);

        return $data;
    }
}
