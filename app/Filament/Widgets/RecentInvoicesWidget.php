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
    protected static ?string $heading = 'Recent Invoices';

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
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (InvoiceType $state): string => match ($state) {
                        InvoiceType::Closed => 'danger',
                        InvoiceType::Shared => 'warning',
                    })
                    ->formatStateUsing(fn (InvoiceType $state): string => $state->getLabel()),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->state(function (Invoice $record): string {
                        $total = $record->invoicePrices->sum('total_price');
                        $currency = $record->invoicePrices->first()?->currency?->code ?? 'USD';

                        return number_format($total, 2).' '.$currency;
                    })
                    ->sortable()
                    ->color('success'),

                TextColumn::make('note')
                    ->label('Note')
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 40) {
                            return null;
                        }

                        return $state;
                    }),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Invoice $record): string => route('filament.admin.resources.invoices.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('No invoices found')
            ->emptyStateDescription('Create your first invoice to see it here.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated(false);
    }
}
