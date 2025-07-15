<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\Currency;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TreasuryBalanceWidget extends BaseWidget
{
    protected static ?string $heading = 'أرصدة الخزانة';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Account::query()
                    ->select([
                        'accounts.id',
                        'accounts.amount',
                        'accounts.accountable_id',
                        'accounts.accountable_type',
                        'accounts.currency_id',
                        'currencies.code as currency_code',
                        'treasures.name as treasure_name',
                        'treasures.location as treasure_location',
                        'customers.name as customer_name',
                    ])
                    ->join('currencies', 'accounts.currency_id', '=', 'currencies.id')
                    ->leftJoin('treasures', function ($join) {
                        $join->on('accounts.accountable_id', '=', 'treasures.id')
                            ->where('accounts.accountable_type', '=', 'App\Models\Treasure');
                    })
                    ->leftJoin('customers', function ($join) {
                        $join->on('accounts.accountable_id', '=', 'customers.id')
                            ->where('accounts.accountable_type', '=', 'App\Models\Customer');
                    })
                    ->orderBy('accounts.accountable_type')
                    ->orderBy('treasure_name')
                    ->orderBy('customer_name')
                    ->orderBy('currency_code')
            )
            ->columns([
                TextColumn::make('account_type')
                    ->label('النوع')
                    ->state(function ($record): string {
                        return $record->accountable_type === 'App\Models\Treasure' ? 'خزانة' : 'عميل';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'خزانة' => 'warning',
                        'عميل' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'خزانة' => 'heroicon-o-banknotes',
                        'عميل' => 'heroicon-o-user',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('account_holder')
                    ->label('صاحب الحساب')
                    ->state(function ($record): string {
                        if ($record->accountable_type === 'App\Models\Treasure') {
                            return $record->treasure_name.($record->treasure_location ? ' ('.$record->treasure_location.')' : '');
                        }

                        return $record->customer_name ?? 'عميل غير معروف';
                    })
                    ->searchable(['treasures.name', 'treasures.location', 'customers.name'])
                    ->sortable(),

                TextColumn::make('currency_code')
                    ->label('العملة')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-currency-dollar')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('الرصيد')
                    ->state(function ($record): string {
                        $symbol = $record->currency_symbol ?? '$';
                        $amount = number_format($record->amount, 2);

                        return $symbol.' '.$amount;
                    })
                    ->color(fn ($record): string => match (true) {
                        $record->amount > 0 => 'success',
                        $record->amount < 0 => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn ($record): string => match (true) {
                        $record->amount > 0 => 'heroicon-o-arrow-trending-up',
                        $record->amount < 0 => 'heroicon-o-arrow-trending-down',
                        default => 'heroicon-o-minus',
                    })
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->since()
                    ->color('gray')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_transactions')
                    ->label('المعاملات')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->url(function ($record): string {
                        if ($record->accountable_type === 'App\Models\Treasure') {
                            return route('filament.admin.resources.treasures.view', $record->accountable_id);
                        }

                        return route('filament.admin.resources.customers.view', $record->accountable_id);
                    })
                    ->openUrlInNewTab(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('accountable_type')
                    ->label('نوع الحساب')
                    ->options([
                        'App\Models\Treasure' => 'خزانة',
                        'App\Models\Customer' => 'عميل',
                    ]),

                Tables\Filters\SelectFilter::make('currency_id')
                    ->label('العملة')
                    ->options(Currency::pluck('code', 'id')->toArray())
                    ->searchable(),
            ])
            ->emptyStateHeading('لا توجد حسابات')
            ->emptyStateDescription('قم بإنشاء خزائن وعملاء لرؤية أرصدة حساباتهم هنا.')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->defaultSort('amount', 'desc')
            ->paginated([10, 25, 50]);
    }
}
