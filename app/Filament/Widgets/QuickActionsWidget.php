<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('create_invoice')
                    ->label('New Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(route('filament.admin.resources.invoices.create'))
                    ->openUrlInNewTab(),

                Action::make('create_customer')
                    ->label('New Customer')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->url(route('filament.admin.resources.customers.create'))
                    ->openUrlInNewTab(),

                Action::make('create_receipt')
                    ->label('New Receipt')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('info')
                    ->url(route('filament.admin.resources.receipts.create'))
                    ->openUrlInNewTab(),
            ])
                ->label('Quick Actions')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->button(),

            ActionGroup::make([
                Action::make('view_invoices')
                    ->label('All Invoices')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->url(route('filament.admin.resources.invoices.index'))
                    ->openUrlInNewTab(),

                Action::make('view_customers')
                    ->label('All Customers')
                    ->icon('heroicon-o-users')
                    ->color('gray')
                    ->url(route('filament.admin.resources.customers.index'))
                    ->openUrlInNewTab(),

                Action::make('view_receipts')
                    ->label('All Receipts')
                    ->icon('heroicon-o-receipt-percent')
                    ->color('gray')
                    ->url(route('filament.admin.resources.receipts.index'))
                    ->openUrlInNewTab(),

                Action::make('view_treasures')
                    ->label('All Treasures')
                    ->icon('heroicon-o-banknotes')
                    ->color('gray')
                    ->url(route('filament.admin.resources.treasures.index'))
                    ->openUrlInNewTab(),
            ])
                ->label('View All')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->button(),
        ];
    }
}
