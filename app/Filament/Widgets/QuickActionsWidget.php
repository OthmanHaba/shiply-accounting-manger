<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    use InteractsWithActions;

    protected static string $view = 'filament.widgets.quick-actions';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getActions(): array
    {
        return [
            Action::make('create_invoice')
                ->label('فاتورة جديدة')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(route('filament.admin.resources.invoices.create'))
                ->openUrlInNewTab(),

            Action::make('create_customer')
                ->label('عميل جديد')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->url(route('filament.admin.resources.customers.create'))
                ->openUrlInNewTab(),

            Action::make('create_receipt')
                ->label('إيصال جديد')
                ->icon('heroicon-o-receipt-percent')
                ->color('info')
                ->url(route('filament.admin.resources.receipts.create'))
                ->openUrlInNewTab(),

            Action::make('view_invoices')
                ->label('جميع الفواتير')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(route('filament.admin.resources.invoices.index'))
                ->openUrlInNewTab(),

            Action::make('view_customers')
                ->label('جميع العملاء')
                ->icon('heroicon-o-users')
                ->color('gray')
                ->url(route('filament.admin.resources.currencies.index')) // confirm if this route is correct
                ->openUrlInNewTab(),

            Action::make('view_receipts')
                ->label('جميع الإيصالات')
                ->icon('heroicon-o-receipt-percent')
                ->color('gray')
                ->url(route('filament.admin.resources.receipts.index'))
                ->openUrlInNewTab(),

            Action::make('view_treasures')
                ->label('جميع الخزائن')
                ->icon('heroicon-o-banknotes')
                ->color('gray')
                ->url(route('filament.admin.resources.treasures.index'))
                ->openUrlInNewTab(),
        ];
    }
}
