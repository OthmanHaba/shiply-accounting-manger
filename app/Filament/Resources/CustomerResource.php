<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\AccountRelationManager;
use App\Models\Currency;
use App\Models\Customer;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
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
use Filament\Tables\Columns\Layout\Split as LayoutSplit;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $slug = 'customers';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('resources.customer_resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.customer_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.customer_resource.plural_model_label');
    }

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make(__('resources.customer_resource.customer_info_section.title'))
                        ->description(__('resources.customer_resource.customer_info_section.description'))
                        ->icon('heroicon-o-user-circle')
                        ->schema([
                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                            ])
                                ->schema([
                                    TextInput::make('name')
                                        ->label(__('resources.customer_resource.fields.name'))
                                        ->placeholder(__('resources.customer_resource.fields.name_placeholder'))
                                        ->prefixIcon('heroicon-o-user')
                                        ->prefixIconColor('primary')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan([
                                            'default' => 1,
                                            'md' => 2,
                                        ]),

                                    TextInput::make('code')
                                        ->label(__('resources.customer_resource.fields.code'))
                                        ->placeholder(__('resources.customer_resource.fields.code_placeholder'))
                                        ->prefixIcon('heroicon-o-hashtag')
                                        ->prefixIconColor('gray')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true)
                                        ->columnSpan(1),

                                    TextInput::make('phone')
                                        ->label(__('resources.customer_resource.fields.phone'))
                                        ->placeholder(__('resources.customer_resource.fields.phone_placeholder'))
                                        ->prefixIcon('heroicon-o-phone')
                                        ->prefixIconColor('success')
                                        ->tel()
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true)
                                        ->columnSpan(1),
                                ]),
                        ])
                        ->columnSpan([
                            'default' => 'full',
                            'lg' => 2,
                        ]),
                ])
                    ->from('lg')
                    ->columnSpanFull(),
            ])
            ->columns([
                'default' => 1,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                LayoutSplit::make([
                    Stack::make([
                        TextColumn::make('name')
                            ->label(__('resources.customer_resource.table.name'))
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable()
                            ->icon('heroicon-o-user')
                            ->iconColor('primary')
                            ->grow(false),

                        TextColumn::make('code')
                            ->label(__('resources.customer_resource.table.code'))
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-o-hashtag')
                            ->iconPosition(IconPosition::Before)
                            ->grow(false),
                    ])
                        ->space(1),

                    TextColumn::make('phone')
                        ->label(__('resources.customer_resource.table.phone'))
                        ->icon('heroicon-o-phone')
                        ->iconColor('success')
                        ->copyable()
                        ->copyMessage(__('resources.customer_resource.messages.phone_copied'))
                        ->visibleFrom('md')
                        ->grow(false),

                    Stack::make(
                        collect(Currency::all())->map(function ($currency) {
                            return TextColumn::make('account_balance_'.$currency->code)
                                ->label($currency->code)
                                ->badge()
                                ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                                ->state(function (Customer $customer) use ($currency) {
                                    $balance = $customer->accounts()
                                        ->where('currency_id', $currency->id)
                                        ->first()?->amount ?? 0;

                                    return number_format($balance, 2).' '.$currency->code;
                                })
                                ->grow(false);
                        })->toArray()
                    )
                        ->space(1)
                        ->visibleFrom('lg')
                        ->alignment('end'),
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
                    ->tooltip(__('resources.customer_resource.actions.view')),
                EditAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.customer_resource.actions.edit')),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.customer_resource.actions.delete')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
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
        return ['name', 'code', 'phone'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();

        return $count > 100 ? 'success' : ($count > 50 ? 'warning' : 'primary');
    }
}
