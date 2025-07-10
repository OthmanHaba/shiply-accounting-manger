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

    protected static ?string $title = 'Currency Accounts';

    protected static ?string $icon = 'heroicon-o-banknotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account Information')
                    ->description('Manage customer account details for specific currencies')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Account Code')
                                    ->placeholder('e.g. CUST-001-USD')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->prefixIconColor('gray')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Select::make('currency_id')
                                    ->label('Currency')
                                    ->relationship('currency', 'code')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->prefixIconColor('success')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Balance')
                                    ->placeholder('0.00')
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
                    ->label('Account Code')
                    ->icon('heroicon-o-hashtag')
                    ->iconColor('gray')
                    ->weight(FontWeight::Medium)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-currency-dollar')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Balance')
                    ->money(fn ($record) => $record->currency->code ?? 'USD')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                    ->icon('heroicon-o-banknotes')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('currency.code')
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship('currency', 'code')
                    ->label('Currency')
                    ->multiple()
                    ->preload(),

                Tables\Filters\Filter::make('positive_balance')
                    ->label('Positive Balance')
                    ->query(fn ($query) => $query->where('amount', '>', 0))
                    ->toggle(),

                Tables\Filters\Filter::make('negative_balance')
                    ->label('Negative Balance')
                    ->query(fn ($query) => $query->where('amount', '<', 0))
                    ->toggle(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Add Account')
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Account'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Account')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-banknotes')
            ->emptyStateHeading('No accounts yet')
            ->emptyStateDescription('Create an account to get started with currency management.');
    }
}
