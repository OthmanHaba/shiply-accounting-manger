<?php

namespace App\Filament\Resources;

use App\Enums\InvoiceItemsTypes;
use App\Enums\InvoiceType;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Item;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $slug = 'invoices';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Invoices';

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $pluralModelLabel = 'Invoices';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Invoice Details')
                        ->description('Basic invoice information and customer details')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                            ])
                                ->schema([
                                    Select::make('customer_id')
                                        ->label('Customer')
                                        ->relationship('customer', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-user')
                                        ->prefixIconColor('primary')
                                        ->columnSpan([
                                            'default' => 1,
                                            'md' => 2,
                                        ]),

                                    Select::make('type')
                                        ->label('Invoice Type')
                                        ->native(false)
                                        ->options(InvoiceType::class)
                                        ->required()
                                        ->prefixIcon('heroicon-o-tag')
                                        ->prefixIconColor('info')
                                        ->columnSpan(1),

                                    TextInput::make('discount')
                                        ->label('Discount (%)')
                                        ->numeric()
                                        ->step(0.01)
                                        ->suffix('%')
                                        ->prefixIcon('heroicon-o-receipt-percent')
                                        ->prefixIconColor('warning')
                                        ->columnSpan(1),

                                    Textarea::make('notes')
                                        ->label('Notes')
                                        ->placeholder('Additional notes or comments...')
                                        ->rows(3)
                                        ->columnSpan([
                                            'default' => 1,
                                            'md' => 2,
                                        ]),
                                ]),
                        ])
                        ->columnSpan([
                            'default' => 'full',
                            'lg' => 2,
                        ]),

                    Section::make('Summary')
                        ->description('Invoice totals and calculations')
                        ->icon('heroicon-o-calculator')
                        ->schema([
                            TextInput::make('total_price')
                                ->label('Total Amount')
                                ->numeric()
                                ->step(0.01)
                                ->prefixIcon('heroicon-o-banknotes')
                                ->prefixIconColor('success')
                                ->disabled()
                                ->dehydrated(false),
                        ])
                        ->columnSpan([
                            'default' => 'full',
                            'lg' => 1,
                        ])
                        ->compact()
                        ->aside(),
                ])
                    ->columnSpanFull()
                    ->from('lg'),

                Section::make('Invoice Items')
                    ->description('Add products and services to this invoice')
                    ->icon('heroicon-o-shopping-cart')
                    ->schema([
                        Repeater::make('items')
//                            ->relationship()
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                    'lg' => 4,
                                ])
                                    ->schema([
                                        Select::make('item_id')
                                            ->label('Product/Service')
//                                            ->relationship('item', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->prefixIcon('heroicon-o-cube')
                                            ->prefixIconColor('primary')
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                if ($state) {
                                                    $item = Item::find($state);
                                                    if ($item) {
                                                        $set('name', $item->name);
                                                        $set('description', $item->description ?? '');
                                                        $set('unit_price', $item->price ?? 0);
                                                    }
                                                }
                                            })
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 2,
                                                'lg' => 1,
                                            ]),

                                        TextInput::make('name')
                                            ->label('Item Name')
                                            ->required()
                                            ->prefixIcon('heroicon-o-tag')
                                            ->prefixIconColor('gray')
                                            ->columnSpan(1),

                                        Select::make('item_type')
                                            ->label('Type')
                                            ->options(InvoiceItemsTypes::class)
                                            ->native(false)
                                            ->required()
                                            ->prefixIcon('heroicon-o-squares-2x2')
                                            ->prefixIconColor('info')
                                            ->columnSpan(1),

                                        TextInput::make('item_count')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->step(1)
                                            ->default(1)
                                            ->required()
                                            ->prefixIcon('heroicon-o-hashtag')
                                            ->prefixIconColor('warning')
                                            ->live()
                                            ->columnSpan(1),

                                        TextInput::make('unit_price')
                                            ->label('Unit Price')
                                            ->numeric()
                                            ->step(0.01)
                                            ->required()
                                            ->prefixIcon('heroicon-o-banknotes')
                                            ->prefixIconColor('success')
                                            ->live()
                                            ->columnSpan(1),

                                        Select::make('currency_id')
                                            ->label('Currency')
                                            ->options(fn () => Currency::all()->pluck('code', 'id'))
                                            ->native(false)
                                            ->required()
                                            ->prefixIcon('heroicon-o-currency-dollar')
                                            ->prefixIconColor('primary')
                                            ->columnSpan(1),

                                        TextInput::make('weight')
                                            ->label('Weight (kg)')
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefixIcon('heroicon-o-scale')
                                            ->prefixIconColor('gray')
                                            ->columnSpan(1),

                                        Textarea::make('description')
                                            ->label('Description')
                                            ->rows(2)
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 2,
                                                'lg' => 2,
                                            ]),
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Item')
                            ->addActionLabel('Add Invoice Item')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
                            ->deleteAction(
                                fn ($action) => $action->requiresConfirmation()
                            )
                            ->defaultItems(1),
                    ])
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
                        TextColumn::make('customer.name')
                            ->label('Customer')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable()
                            ->icon('heroicon-o-user')
                            ->iconColor('primary')
                            ->grow(false),

                        TextColumn::make('type')
                            ->label('Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'sale' => 'success',
                                'purchase' => 'info',
                                'service' => 'warning',
                                'credit' => 'danger',
                                'debit' => 'gray',
                                default => 'primary',
                            })
                            ->icon('heroicon-o-tag')
                            ->iconPosition(IconPosition::Before)
                            ->grow(false),
                    ])
                        ->space(1),

                    Stack::make([
                        TextColumn::make('total_price')
                            ->label('Total')
                            ->money('USD') // You might want to make this dynamic based on invoice currency
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-banknotes')
                            ->iconColor('success')
                            ->grow(false),

                        TextColumn::make('discount')
                            ->label('Discount')
                            ->suffix('%')
                            ->badge()
                            ->color('warning')
                            ->icon('heroicon-o-receipt-percent')
                            ->placeholder('—')
                            ->grow(false),
                    ])
                        ->space(1)
                        ->visibleFrom('md'),

                    Stack::make([
                        TextColumn::make('items_count')
                            ->label('Items')
                            ->counts('items')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-shopping-cart')
                            ->grow(false),

                        TextColumn::make('created_at')
                            ->label('Created')
                            ->dateTime('M j, Y')
                            ->color('gray')
                            ->size('sm')
                            ->grow(false),
                    ])
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
                    ->tooltip('View Invoice'),
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Invoice'),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete Invoice')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['customer.name', 'notes', 'type'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();

        return $count > 50 ? 'success' : ($count > 20 ? 'warning' : 'primary');
    }
}
