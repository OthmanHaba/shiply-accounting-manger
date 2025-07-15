<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreasureResource\Pages;
use App\Filament\Resources\TreasureResource\RelationManagers\AccountRelationManager;
use App\Models\Currency;
use App\Models\Treasure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TreasureResource extends Resource
{
    protected static ?string $model = Treasure::class;

    protected static ?string $slug = 'treasures';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'الاعدادات';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('resources.treasure_resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.treasure_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.treasure_resource.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('resources.treasure_resource.treasure_details_section.title'))
                    ->description(__('resources.treasure_resource.treasure_details_section.description'))
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('resources.treasure_resource.fields.name'))
                                    ->placeholder(__('resources.treasure_resource.fields.name_placeholder'))
                                    ->prefixIcon('heroicon-o-building-storefront')
                                    ->prefixIconColor('primary')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('location')
                                    ->label(__('resources.treasure_resource.fields.location'))
                                    ->placeholder(__('resources.treasure_resource.fields.location_placeholder'))
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->prefixIconColor('success')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(array_merge(
                [
                    TextColumn::make('name')
                        ->label(__('resources.treasure_resource.table.name'))
                        ->icon('heroicon-o-building-storefront')
                        ->iconColor('primary')
                        ->weight(FontWeight::Medium)
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('location')
                        ->label(__('resources.treasure_resource.table.location'))
                        ->icon('heroicon-o-map-pin')
                        ->iconColor('success')
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('accounts_count')
                        ->label('الحسابات')
                        ->counts('accounts')
                        ->badge()
                        ->color('info')
                        ->icon('heroicon-o-banknotes'),

                    TextColumn::make('created_at')
                        ->label(__('resources.treasure_resource.table.created'))
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
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
                        ->label($currency->code)
                        ->money($currency->code)
                        ->sortable(false);
                })->toArray()
            ))
            ->filters([

            ])
            ->actions([
                ViewAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.treasure_resource.actions.view')),
                EditAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.treasure_resource.actions.edit')),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.treasure_resource.actions.delete'))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            AccountRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreasures::route('/'),
            'create' => Pages\CreateTreasure::route('/create'),
            'view' => Pages\ViewTreasure::route('/{record}'),
            'edit' => Pages\EditTreasure::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'location'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();

        return $count > 10 ? 'success' : ($count > 5 ? 'warning' : 'primary');
    }
}
