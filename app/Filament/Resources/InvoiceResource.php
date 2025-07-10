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
                Split::make([
                    Section::make(__('resources.invoice_resource.invoice_details_section.title'))
                        ->description(__('resources.invoice_resource.invoice_details_section.description'))
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                            ])
                                ->schema([
                                    Select::make('customer_id')
                                        ->label(__('resources.invoice_resource.fields.customer_id'))
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
                                        ->label(__('resources.invoice_resource.fields.type'))
                                        ->native(false)
                                        ->options(InvoiceType::class)
                                        ->required()
                                        ->prefixIcon('heroicon-o-tag')
                                        ->prefixIconColor('info')
                                        ->columnSpan(1),

                                    TextInput::make('discount')
                                        ->label(__('resources.invoice_resource.fields.discount'))
                                        ->numeric()
                                        ->step(0.01)
                                        ->suffix('%')
                                        ->prefixIcon('heroicon-o-receipt-percent')
                                        ->prefixIconColor('warning')
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

                    Section::make(__('resources.invoice_resource.summary_section.title'))
                        ->description(__('resources.invoice_resource.summary_section.description'))
                        ->icon('heroicon-o-calculator')
                        ->schema([
                            TextInput::make('total_price')
                                ->label(__('resources.invoice_resource.fields.total_price'))
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

                Section::make(__('resources.invoice_resource.invoice_items_section.title'))
                    ->description(__('resources.invoice_resource.invoice_items_section.description'))
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
                                            ->label(__('resources.invoice_resource.fields.item_id'))
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
                                            ->label(__('resources.invoice_resource.fields.name'))
                                            ->required()
                                            ->prefixIcon('heroicon-o-tag')
                                            ->prefixIconColor('gray')
                                            ->columnSpan(1),

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
                                            ->columnSpan(1),

                                        TextInput::make('weight')
                                            ->label(__('resources.invoice_resource.fields.weight'))
                                            ->numeric()
                                            ->step(0.01)
                                            ->prefixIcon('heroicon-o-scale')
                                            ->prefixIconColor('gray')
                                            ->columnSpan(1),

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
                            ->label(__('resources.invoice_resource.table.total'))
                            ->money('USD') // You might want to make this dynamic based on invoice currency
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-banknotes')
                            ->iconColor('success')
                            ->grow(false),

                        TextColumn::make('discount')
                            ->label(__('resources.invoice_resource.table.discount'))
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
}
