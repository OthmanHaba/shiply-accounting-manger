<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Treasure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    use InteractsWithActions;

    protected static string $view = 'filament.widgets.quick-actions';

    public static function canView(): bool
    {
        return auth()->user()->can('widget_'.class_basename(QuickActionsWidget::class));
    }

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getActions(): array
    {
        return [
            Action::make('create_invoice')
                ->label('فاتورة جديدة')
                ->icon('heroicon-o-document-text')
                ->visible(auth()->user()->can('create', Invoice::class))
                ->color('success')
                ->url(route('filament.admin.resources.invoices.create'))
                ->openUrlInNewTab(),

            Action::make('create_customer')
                ->label('عميل جديد')
                ->icon('heroicon-o-user-plus')
                ->visible(auth()->user()->can('create', Customer::class))
                ->color('primary')
                ->url(route('filament.admin.resources.customers.create'))
                ->openUrlInNewTab(),

            Action::make('create_receipt')
                ->label('إيصال جديد')
                ->visible(auth()->user()->can('create', Receipt::class))
                ->icon('heroicon-o-receipt-percent')
                ->color('info')
                ->url(route('filament.admin.resources.receipts.create'))
                ->openUrlInNewTab(),

            Action::make('view_invoices')
                ->label('جميع الفواتير')
                ->icon('heroicon-o-document-text')
                ->disabled(auth()->user()->can('view-any', Invoice::class))
                ->color('gray')
                ->url(route('filament.admin.resources.invoices.index'))
                ->openUrlInNewTab(),

            Action::make('view_customers')
                ->label('جميع العملاء')
                ->icon('heroicon-o-users')
                ->visible(auth()->user()->can('view-any', Customer::class))
                ->color('gray')
                ->url(route('filament.admin.resources.currencies.index')) // confirm if this route is correct
                ->openUrlInNewTab(),

            Action::make('view_receipts')
                ->label('جميع الإيصالات')
                ->visible(auth()->user()->can('view-any', Receipt::class))
                ->icon('heroicon-o-receipt-percent')
                ->color('gray')
                ->url(route('filament.admin.resources.receipts.index'))
                ->openUrlInNewTab(),

            Action::make('view_treasures')
                ->label('جميع الخزائن')
                ->visible(auth()->user()->can('view-any', Treasure::class))
                ->icon('heroicon-o-banknotes')
                ->color('gray')
                ->url(route('filament.admin.resources.treasures.index'))
                ->openUrlInNewTab(),
        ];
    }
}
