<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceType;
use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentInvoicesWidget extends BaseWidget
{
    protected static ?string $heading = 'الفواتير الأخيرة';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::query()
                    ->with(['customer', 'invoicePrices.currency'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('code')
                    ->label('رقم الفاتورة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color(fn (InvoiceType $state): string => match ($state) {
                        InvoiceType::Closed => 'danger',
                        InvoiceType::Shared => 'warning',
                    })
                    ->formatStateUsing(fn (InvoiceType $state): string => $state->getLabel()),

                TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->state(function (Invoice $record): string {
                        $total = $record->invoicePrices->sum('total_price');
                        $currency = $record->invoicePrices->first()?->currency?->code ?? 'USD';

                        return number_format($total, 2).' '.$currency;
                    })
                    ->sortable()
                    ->color('success'),

                TextColumn::make('note')
                    ->label('الملاحظة')
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 40) {
                            return null;
                        }

                        return $state;
                    }),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Invoice $record): string => route('filament.admin.resources.invoices.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('لا توجد فواتير')
            ->emptyStateDescription('قم بإنشاء أول فاتورة لرؤيتها هنا.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated(false);
    }
}
