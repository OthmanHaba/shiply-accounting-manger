<?php

namespace App\Filament\Resources\CurrencyResource\Pages;

use App\Filament\Resources\CurrencyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCurrency extends EditRecord
{
    protected static string $resource = CurrencyResource::class;

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
                ->modalIconColor('danger')
                ->modalHeading('Delete Currency')
                ->modalDescription('Are you sure you want to delete this currency? This action cannot be undone and will affect all associated accounts.')
                ->modalSubmitActionLabel('Yes, delete currency')
                ->before(function (DeleteAction $action) {
                    // Check if currency has associated accounts
                    $currency = $this->getRecord();
                    if ($currency->accounts()->count() > 0) {
                        Notification::make()
                            ->warning()
                            ->title('Cannot delete currency')
                            ->body('This currency has associated accounts. Please remove all accounts first.')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->iconColor('warning')
                            ->persistent()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Currency updated successfully')
            ->body('The currency information has been updated.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure the currency code is always uppercase
        $data['code'] = strtoupper($data['code']);

        return $data;
    }
}
