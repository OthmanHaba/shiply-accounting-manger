<?php

namespace App\Filament\Resources;

use App\Enums\InvoiceItemsTypes;
use App\Enums\InvoiceType;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
use NunoMaduro\Collision\Adapters\Phpunit\State;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $slug = 'invoices';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Invoices';

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $pluralModelLabel = 'Invoices';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('resources.invoice_resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.invoice_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.invoice_resource.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('resources.invoice_resource.invoice_details_section.title'))
                    ->description(__('resources.invoice_resource.invoice_details_section.description'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                TextInput::make('code')
                                    ->label(__('resources.invoice_resource.fields.code'))
                                    ->default(function () {
                                        $lastInvoice = Invoice::latest()->first();

                                        if (is_null($lastInvoice)) {
                                            return 'INV-1';
                                        }

                                        // Extract numeric part from the last invoice code
                                        $lastCode = $lastInvoice->code;
                                        $numericPart = (int) preg_replace('/[^0-9]/', '', $lastCode);

                                        return 'INV-'.($numericPart + 1);
                                    })
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->prefixIconColor('gray')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn (Get $get) => ! $get('manual_edit_code'))
                                    ->dehydrated()
                                    ->suffixAction(
                                        Action::make('edit_code')
                                            ->icon('heroicon-o-pencil')
                                            ->tooltip('Edit invoice code manually')
                                            ->action(function (Set $set, Get $get, $state) {
                                                // Toggle the manual edit state
                                                $targetValue = $get('manual_edit_code');
                                                $set('manual_edit_code', ! $targetValue);
                                            })
                                    ),

                                // Hidden field to track manual edit state for code
                                TextInput::make('manual_edit_code')
                                    ->hidden()
                                    ->default(false)
                                    ->dehydrated(false),

                                Select::make('customer_id')
                                    ->label(__('resources.invoice_resource.fields.customer_id'))
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(
                                        [
                                            Grid::make([
                                                'default' => 1,
                                                'md' => 2,
                                            ])
                                                ->schema([
                                                    TextInput::make('name')
                                                        ->label('Full Name')
                                                        ->placeholder('Enter customer full name')
                                                        ->prefixIcon('heroicon-o-user')
                                                        ->prefixIconColor('primary')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->columnSpan([
                                                            'default' => 1,
                                                            'md' => 2,
                                                        ]),

                                                    TextInput::make('code')
                                                        ->label('Customer Code')
                                                        ->placeholder('e.g. CUST-001')
                                                        ->prefixIcon('heroicon-o-hashtag')
                                                        ->prefixIconColor('gray')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->unique(ignoreRecord: true)
                                                        ->columnSpan(1),

                                                    TextInput::make('phone')
                                                        ->label('Phone Number')
                                                        ->placeholder('e.g. +1 (555) 123-4567')
                                                        ->prefixIcon('heroicon-o-phone')
                                                        ->prefixIconColor('success')
                                                        ->tel()
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->unique(ignoreRecord: true)
                                                        ->columnSpan(1),
                                                ]),
                                        ]
                                    )
                                    ->createOptionUsing(function (array $data) {
                                        Customer::create($data);
                                    })
                                    ->required()
                                    ->prefixIcon('heroicon-o-user')
                                    ->prefixIconColor('primary'),

                                Select::make('type')
                                    ->label(__('resources.invoice_resource.fields.type'))
                                    ->native(false)
                                    ->reactive()
                                    ->options(InvoiceType::class)
                                    ->required()
                                    ->prefixIcon('heroicon-o-tag')
                                    ->prefixIconColor('info')
                                    ->columnSpan(1),

                                Textarea::make('notes')
                                    ->label(__('resources.invoice_resource.fields.notes'))
                                    ->placeholder(__('resources.invoice_resource.fields.notes_placeholder'))
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

                Section::make(__('resources.invoice_resource.invoice_items_section.title'))
                    ->description(__('resources.invoice_resource.invoice_items_section.description'))
                    ->icon('heroicon-o-shopping-cart')
                    ->visible(fn (Get $get) => $get('type') === InvoiceType::Shared->value)
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                    'lg' => 4,
                                ])
                                    ->schema([
                                        Select::make('item_id')
                                            ->label(__('resources.invoice_resource.fields.item_id'))
                                            ->searchable()
                                            ->relationship('item', 'name')
                                            ->preload()
                                            ->required()
                                            ->prefixIcon('heroicon-o-cube')
                                            ->prefixIconColor('primary')
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label(__('resources.invoice_resource.fields.name'))
                                                    ->required()
                                                    ->maxLength(255),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                Item::create([
                                                    'name' => $data['name'],
                                                ]);
                                            })
                                            ->live()
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 2,
                                                'lg' => 1,
                                            ]),

                                        Select::make('item_type')
                                            ->label(__('resources.invoice_resource.fields.item_type'))
                                            ->options(InvoiceItemsTypes::class)
                                            ->native(false)
                                            ->required()
                                            ->prefixIcon('heroicon-o-squares-2x2')
                                            ->prefixIconColor('info')
                                            ->columnSpan(1),

                                        TextInput::make('item_count')
                                            ->label(__('resources.invoice_resource.fields.item_count'))
                                            ->numeric()
                                            ->step(1)
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                self::calculateItemPrice($set, $get);
                                                self::calculateCurrencyTotals($set, $get);
                                            })
                                            ->default(1)
                                            ->required()
                                            ->prefixIcon('heroicon-o-hashtag')
                                            ->prefixIconColor('warning')
                                            ->live()
                                            ->columnSpan(1),

                                        TextInput::make('unit_price')
                                            ->label(__('resources.invoice_resource.fields.unit_price'))
                                            ->numeric()
                                            ->step(0.01)
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                self::calculateItemPrice($set, $get);
                                                self::calculateCurrencyTotals($set, $get);
                                            })
                                            ->required()
                                            ->prefixIcon('heroicon-o-banknotes')
                                            ->prefixIconColor('success')
                                            ->live()
                                            ->columnSpan(1),

                                        Select::make('currency_id')
                                            ->label(__('resources.invoice_resource.fields.currency_id'))
                                            ->options(fn () => Currency::all()->pluck('code', 'id'))
                                            ->native(false)
                                            ->required()
                                            ->prefixIcon('heroicon-o-currency-dollar')
                                            ->prefixIconColor('primary')
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                self::calculateCurrencyTotals($set, $get);
                                            })
                                            ->live()
                                            ->columnSpan(1),

                                        TextInput::make('weight')
                                            ->label(__('resources.invoice_resource.fields.weight'))
                                            ->numeric()
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                self::calculateItemPrice($set, $get);
                                                self::calculateCurrencyTotals($set, $get);
                                            })
                                            ->step(0.01)
                                            ->prefixIcon('heroicon-o-scale')
                                            ->prefixIconColor('gray')
                                            ->live()
                                            ->columnSpan(1),

                                        TextInput::make('total_price')
                                            ->label(__('resources.invoice_resource.fields.total_price'))
                                            ->prefixIcon('heroicon-o-hashtag')
                                            ->prefixIconColor('primary')
                                            ->required()
                                            ->disabled(fn (Get $get) => ! $get('manual_edit_price'))
                                            ->dehydrated()
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                self::calculateCurrencyTotals($set, $get);
                                            })
                                            ->live()
                                            ->maxLength(255)
                                            ->suffixAction(
                                                Action::make('edit_price')
                                                    ->icon('heroicon-o-pencil')
                                                    ->tooltip('Edit price manually')
                                                    ->action(function (Set $set, Get $get, $state) {
                                                        // Enable the field for editing
                                                        $targetValue = $get('manual_edit_price');
                                                        $set('manual_edit_price', ! $targetValue);
                                                    })
                                            ),

                                        // Hidden field to track manual edit state
                                        TextInput::make('manual_edit_price')
                                            ->hidden()
                                            ->default(false)
                                            ->dehydrated(false),

                                        Textarea::make('description')
                                            ->label(__('resources.invoice_resource.fields.description'))
                                            ->rows(2)
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 2,
                                                'lg' => 2,
                                            ]),
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? __('resources.invoice_resource.repeater.item_label'))
                            ->addActionLabel(__('resources.invoice_resource.repeater.add_action_label'))
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
                            ->deleteAction(
                                fn ($action) => $action->requiresConfirmation()
                            )
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::calculateCurrencyTotals($set, $get);
                            })
                            ->live()
                            ->defaultItems(1),
                    ])
                    ->columnSpanFull(),

                Section::make(__('resources.invoice_resource.summary_section.title'))
                    ->description(__('resources.invoice_resource.summary_section.description'))
                    ->icon('heroicon-o-calculator')
                    ->schema([
                        // Auto-calculated currency totals
                        ...collect(Currency::all())->map(function ($currency) {
                            return TextInput::make("prices.{$currency->id}")
                                ->label($currency->name.' ('.$currency->code.')')
                                ->prefixIcon('heroicon-o-currency-dollar')
                                ->prefixIconColor('primary')
                                ->disabled(fn (Get $get) => ! $get("manual_edit_currency_{$currency->id}"))
                                ->dehydrated()
                                ->live()
                                ->suffixAction(
                                    Action::make("edit_currency_{$currency->id}")
                                        ->icon('heroicon-o-pencil')
                                        ->tooltip('Edit total manually')
                                        ->action(function (Set $set, Get $get, $state) use ($currency) {
                                            $target = "manual_edit_currency_{$currency->id}";
                                            $targetValue = $get($target);
                                            $set("manual_edit_currency_{$currency->id}", ! $targetValue);
                                        })
                                );
                        })->toArray(),

                        // Hidden fields to track manual edit state for currencies
                        ...collect(Currency::all())->map(function ($currency) {
                            return TextInput::make("manual_edit_currency_{$currency->id}")
                                ->hidden()
                                ->default(false)
                                ->dehydrated(false);
                        })->toArray(),
                    ])
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ])
                    ->compact(),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                LayoutSplit::make([
                    Stack::make([
                        TextColumn::make('customer.name')
                            ->label(__('resources.invoice_resource.table.customer'))
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable()
                            ->icon('heroicon-o-user')
                            ->iconColor('primary')
                            ->grow(false),

                        TextColumn::make('type')
                            ->label(__('resources.invoice_resource.table.type'))
                            ->badge()
                            ->icon('heroicon-o-tag')
                            ->iconPosition(IconPosition::Before)
                            ->grow(false),
                    ])
                        ->space(1),

                    Stack::make(
                        collect(Currency::all())->map(function ($currency) {
                            return TextColumn::make('invoice_prices_'.$currency->code)
                                ->label($currency->code)
                                ->badge()
                                ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                                ->state(function (Invoice $record) use ($currency) {
                                    $balance = $record->invoicePrices()
                                        ->where('currency_id', $currency->id)
                                        ->first()?->total_price ?? 0;

                                    return number_format($balance, 2).' '.$currency->code;
                                })
                                ->grow(false);
                        })->toArray()
                    )
                        ->space(1)
                        ->visibleFrom('md'),

                    Stack::make([
                        TextColumn::make('items_count')
                            ->label(__('resources.invoice_resource.table.items_count'))
                            ->counts('items')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-shopping-cart')
                            ->grow(false),

                        TextColumn::make('created_at')
                            ->label(__('resources.invoice_resource.table.created'))
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
                    ->tooltip(__('resources.invoice_resource.actions.view')),
                EditAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.invoice_resource.actions.edit')),
                DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('resources.invoice_resource.actions.delete'))
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

    public static function canAccess(): bool
    {
        return true;
    }

    public static function calculateItemPrice(Set $set, Get $get): void
    {
        // Check if price is manually edited
        $isManuallyEdited = $get('manual_edit_price');

        // Only calculate if not manually edited
        if (! $isManuallyEdited) {
            $unitPrice = $get('unit_price');
            $weight = $get('weight');
            $itemCount = $get('item_count');

            if (! empty($unitPrice) && ! empty($weight) && ! empty($itemCount)) {
                $set('total_price', $unitPrice * $weight * $itemCount);
            }
        }
    }

    public static function calculateCurrencyTotals(Set $set, Get $get): void
    {
        // Get all items from the form
        $items = $get('../../items') ?? [];

        // Group items by currency and calculate totals
        $currencyTotals = [];

        foreach ($items as $item) {
            $currencyId = $item['currency_id'] ?? null;
            $totalPrice = $item['total_price'] ?? 0;

            if ($currencyId && $totalPrice) {
                if (! isset($currencyTotals[$currencyId])) {
                    $currencyTotals[$currencyId] = 0;
                }
                $currencyTotals[$currencyId] += floatval($totalPrice);
            }
        }

        // Set the currency totals in the form, but only if not manually edited
        foreach (Currency::all() as $currency) {
            $isManuallyEdited = $get("../../manual_edit_currency_{$currency->id}");

            // Only update if not manually edited
            if (! $isManuallyEdited) {
                $total = $currencyTotals[$currency->id] ?? 0;
                $set("../../prices.{$currency->id}", number_format($total, 2));
            }
        }
    }
}
