<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

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
            'all' => Tab::make('All Invoices')
                ->icon('heroicon-o-document-text')
                ->badge(Invoice::count())
                ->badgeColor('primary'),

            'sales' => Tab::make('Sales')
                ->icon('heroicon-o-arrow-trending-up')
                ->modifyQueryUsing(fn ($query) => $query->where('type', 'sale'))
                ->badge(Invoice::where('type', 'sale')->count())
                ->badgeColor('success'),

            'purchases' => Tab::make('Purchases')
                ->icon('heroicon-o-arrow-trending-down')
                ->modifyQueryUsing(fn ($query) => $query->where('type', 'purchase'))
                ->badge(Invoice::where('type', 'purchase')->count())
                ->badgeColor('info'),

            'services' => Tab::make('Services')
                ->icon('heroicon-o-wrench-screwdriver')
                ->modifyQueryUsing(fn ($query) => $query->where('type', 'service'))
                ->badge(Invoice::where('type', 'service')->count())
                ->badgeColor('warning'),

            'recent' => Tab::make('Recent')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn ($query) => $query->where('created_at', '>=', now()->subDays(7)))
                ->badge(Invoice::where('created_at', '>=', now()->subDays(7))->count())
                ->badgeColor('gray'),
        ];
    }
}
