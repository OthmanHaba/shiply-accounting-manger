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

    protected static ?string $navigationLabel = 'العملات';

    protected static ?string $modelLabel = 'العملة';

    protected static ?string $pluralModelLabel = 'العملات';

    protected static ?string $navigationGroup = 'الاعدادات';

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
                                    ->label('الاسم')
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
                                    ->label('الرمز')
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
                            ->label('الاسم')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable()
                            ->icon('heroicon-o-globe-alt')
                            ->iconColor('primary')
                            ->grow(false),

                        TextColumn::make('code')
                            ->label('الرمز')
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-hashtag')
                            ->grow(false),
                    ])
                        ->space(1),
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
                    ->tooltip('عرض العملة'),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('تعديل العملة'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('حذف العملة')
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalIconColor('danger')
                    ->modalHeading('حذف العملة')
                    ->modalDescription('هل أنت متأكد من حذف هذه العملة؟ هذا الإجراء لا يمكن التراجع عنه وسيؤثر على جميع الحسابات المرتبطة.')
                    ->modalSubmitActionLabel('نعم, حذف العملة'),
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
