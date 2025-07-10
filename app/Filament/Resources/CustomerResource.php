<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\AccountRelationManager;
use App\Models\Currency;
use App\Models\Customer;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $slug = 'customers';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Details')
                    ->translateLabel()
                    ->schema([
                        TextInput::make('name')
                            ->placeholder('e.g. John Doe')
                            ->label('Full Name')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('code')
                            ->maxLength(255)
                            ->placeholder('e.g. JDOE123')
                            ->label('Code')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('phone')
                            ->maxLength(255)
                            ->placeholder('e.g. +1 (123) 456-7890')
                            ->label('Phone')
                            ->unique('customers', 'phone')
                            ->required()
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $accounts = collect();

        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            $accounts->push([
                TextColumn::make('account'.$currency->code)
                    ->label($currency->code)
                    ->state(function (Customer $customer) use ($currency) {
                        return $customer->accounts()->where('currency_id', $currency->id)->first()?->amount ?? 0;
                    }),
            ]);
        }

        return $table
            ->columns(components: array_merge([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code'),
                TextColumn::make('phone'),
            ], ...$accounts->toArray()))
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AccountRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
