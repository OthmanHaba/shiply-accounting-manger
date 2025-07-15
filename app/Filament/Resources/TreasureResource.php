<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreasureResource\Pages;
use App\Models\Currency;
use App\Models\Treasure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TreasureResource extends Resource
{
    protected static ?string $model = Treasure::class;

    protected static ?string $slug = 'treasures';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('المعلومات الأساسية')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('الاسم')
                                    ->required(),
                                TextInput::make('location')
                                    ->label('الموقع')
                                    ->required(),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(array_merge(
                [
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('location'),
                ],
                collect(Currency::all())->map(function ($currency) {
                    return TextColumn::make('currency'.$currency->code)
                        ->badge()
                        ->icon('heroicon-o-banknotes')
                        ->iconPosition(IconPosition::After)
                        ->state(function (Treasure $record) use ($currency) {
                            return $record->accounts()->where('currency_id', $currency->id)->first()?->amount ?? 0;
                        })
                        ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                        ->label($currency->code);

                })->toArray()
            ))
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
            'index' => Pages\ListTreasures::route('/'),
            'create' => Pages\CreateTreasure::route('/create'),
            'edit' => Pages\EditTreasure::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
