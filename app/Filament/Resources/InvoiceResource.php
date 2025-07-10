<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Currency;
use App\Models\Invoice;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $slug = 'invoices';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('notes')
                    ->required(),

                TextInput::make('type')
                    ->required(),

                TextInput::make('total_price')
                    ->required(),

                TextInput::make('discount'),

                Section::make('items')->schema(
                    [
                        Repeater::make('items')
                            ->schema([
                                TextInput::make('items.name'),
                                TextInput::make('items.item_type'),
                                TextInput::make('items.description'),
                                TextInput::make('items.weight'),
                                Select::make('items.currency')
                                    ->native(false)
                                    ->options(function () {
                                        return Currency::all()->pluck('code', 'id');
                                    }),
                                TextInput::make('items.item_count'),
                                TextInput::make('items.unit_price'),
                            ]),
                    ]
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('notes'),

                TextColumn::make('type'),

                TextColumn::make('total_price'),

                TextColumn::make('discount'),
            ])
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
