<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Enums\InvoiceType;
use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('Create New Invoice')
                ->color('primary'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->icon('heroicon-o-document-text')
                ->badge(Invoice::count())
                ->badgeColor('primary'),

            InvoiceType::Closed->value => Tab::make(InvoiceType::Closed->getLabel())
                ->modifyQueryUsing(fn ($query) => $query->where('type', InvoiceType::Closed))
                ->badge(Invoice::where('type', InvoiceType::Closed)->count())
                ->badgeColor('gray'),

            InvoiceType::Shared->value => Tab::make(InvoiceType::Shared->getLabel())
                ->modifyQueryUsing(fn ($query) => $query->where('type', InvoiceType::Shared))
                ->badge(Invoice::where('type', InvoiceType::Shared)->count())
                ->badgeColor('gray'),
        ];
    }
}
