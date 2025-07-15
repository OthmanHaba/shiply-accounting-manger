<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class AccountRelationManager extends RelationManager
{
    protected static string $relationship = 'accounts';

    protected static ?string $title = 'الحسابات';

    protected static ?string $icon = 'heroicon-o-banknotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resources.customer_resource.accounts_relation.title'))
                    ->description(__('resources.customer_resource.accounts_relation.description'))
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label(__('resources.customer_resource.accounts_relation.fields.code'))
                                    ->placeholder(__('resources.customer_resource.accounts_relation.fields.code_placeholder'))
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->prefixIconColor('gray')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Select::make('currency_id')
                                    ->label(__('resources.customer_resource.accounts_relation.fields.currency_id'))
                                    ->relationship('currency', 'code')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->prefixIconColor('success')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('amount')
                                    ->label(__('resources.customer_resource.accounts_relation.fields.amount'))
                                    ->placeholder(__('resources.customer_resource.accounts_relation.fields.amount_placeholder'))
                                    ->prefixIcon('heroicon-o-banknotes')
                                    ->prefixIconColor('primary')
                                    ->numeric()
                                    ->step(0.01)
                                    ->required()
                                    ->columnSpan(2),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('resources.customer_resource.accounts_relation.table.code'))
                    ->icon('heroicon-o-hashtag')
                    ->iconColor('gray')
                    ->weight(FontWeight::Medium)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency.code')
                    ->label(__('resources.customer_resource.accounts_relation.table.currency'))
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-currency-dollar')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('resources.customer_resource.accounts_relation.table.balance'))
                    ->money(fn ($record) => $record->currency->code ?? 'USD')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                    ->icon('heroicon-o-banknotes')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.customer_resource.accounts_relation.table.created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('resources.customer_resource.accounts_relation.table.updated'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('currency.code')
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship('currency', 'code')
                    ->label(__('resources.customer_resource.accounts_relation.filters.currency'))
                    ->multiple()
                    ->preload(),

                Tables\Filters\Filter::make('positive_balance')
                    ->label(__('resources.customer_resource.accounts_relation.filters.positive_balance'))
                    ->query(fn ($query) => $query->where('amount', '>', 0))
                    ->toggle(),

                Tables\Filters\Filter::make('negative_balance')
                    ->label(__('resources.customer_resource.accounts_relation.filters.negative_balance'))
                    ->query(fn ($query) => $query->where('amount', '<', 0))
                    ->toggle(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label(__('resources.customer_resource.accounts_relation.actions.add_account'))
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.customer_resource.accounts_relation.actions.edit_account')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.customer_resource.accounts_relation.actions.delete_account'))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-banknotes')
            ->emptyStateHeading(__('resources.customer_resource.accounts_relation.empty_state.heading'))
            ->emptyStateDescription(__('resources.customer_resource.accounts_relation.empty_state.description'));
    }
}
