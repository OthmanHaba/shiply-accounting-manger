<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyResource\Pages;
use App\Models\Currency;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $slug = 'currencies';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Currencies';

    protected static ?string $modelLabel = 'Currency';

    protected static ?string $pluralModelLabel = 'Currencies';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Currency Information')
                    ->description('Define currency details and codes')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Currency Name')
                                    ->placeholder('e.g. US Dollar, Euro, British Pound')
                                    ->prefixIcon('heroicon-o-globe-alt')
                                    ->prefixIconColor('primary')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                    ]),

                                TextInput::make('code')
                                    ->label('Currency Code')
                                    ->placeholder('e.g. USD, EUR, GBP')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->prefixIconColor('success')
                                    ->required()
                                    ->maxLength(3)
                                    ->minLength(3)
                                    ->unique(ignoreRecord: true)
                                    ->rules(['regex:/^[A-Z]{3}$/'])
                                    ->helperText('Must be a 3-letter ISO currency code')
                                    ->columnSpan(1),

                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('name')
                            ->label('Currency Name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable()
                            ->icon('heroicon-o-globe-alt')
                            ->iconColor('primary')
                            ->grow(false),

                        TextColumn::make('code')
                            ->label('Code')
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-hashtag')
                            ->grow(false),
                    ])
                        ->space(1),

                    TextColumn::make('symbol')
                        ->label('Symbol')
                        ->badge()
                        ->color('warning')
                        ->icon('heroicon-o-currency-dollar')
                        ->placeholder('—')
                        ->visibleFrom('md')
                        ->grow(false),
                ])
                    ->from('md'),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->iconButton()
                    ->tooltip('View Currency'),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Currency'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Currency')
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalIconColor('danger')
                    ->modalHeading('Delete Currency')
                    ->modalDescription('Are you sure you want to delete this currency? This action cannot be undone and will affect all associated accounts.')
                    ->modalSubmitActionLabel('Yes, delete currency'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'view' => Pages\ViewCurrency::route('/{record}'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code', 'symbol'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
