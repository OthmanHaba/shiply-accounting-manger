<?php

namespace App\Filament\Resources;

use App\Enums\ReceiptType;
use App\Filament\Resources\ReceiptResource\Pages;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Treasure;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('resources.receipt_resource.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.receipt_resource.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.receipt_resource.plural_model_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('resources.receipt_resource.receipt_details_section.title'))
                    ->description(__('resources.receipt_resource.receipt_details_section.description'))
                    ->schema([
                        TextInput::make('note')
                            ->label(__('resources.receipt_resource.fields.note'))
                            ->placeholder(__('resources.receipt_resource.fields.note_placeholder'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Select::make('customer_id')
                            ->label(__('resources.receipt_resource.fields.customer_id'))
                            ->options(Customer::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(fn (Get $get, Set $set) => $set('invoices', []))
                            ->reactive()
                            ->required(),

                        Select::make('treasure_id')
                            ->label(__('resources.receipt_resource.fields.treasure_id'))
                            ->options(Treasure::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->preload(),

                        Select::make('type')
                            ->label(__('resources.receipt_resource.fields.type'))
                            ->options(ReceiptType::class)
                            ->required()
                            ->reactive()
                            ->native(false),

                        ViewField::make('accounts')
                            ->live()
                            ->viewData([
                                'accounts' => function (Get $get) {
                                    $customer = Customer::find($get('customer_id'));

                                    return $customer->accounts;
                                },
                            ])
                            ->view('components.forms.fields.accounts-helper', [
                                'accounts' => function (Get $get) {
                                    $customer = Customer::find($get('customer_id'));

                                    return $customer->accounts;
                                },
                            ]),
                        Placeholder::make('note')
                            ->dehydrated(false)
                            ->label(__('resources.receipt_resource.fields.note'))
                            ->columnSpan(2)
                            ->content(function (Get $get) {
                                $customer = Customer::find($get('customer_id'));

                                return $customer
                                    ? $customer->accounts
                                        ->map(fn (Account $account) => $account->currency->code.' '.$account->amount)
                                        ->implode("\n - \n") // newline-separated list
                                    : 'لم يتم العثور على حسابات.';
                            })
                            ->extraAttributes([
                                'class' => 'mt-2 bg-gray-50 p-4 rounded-md',
                            ])
                            ->reactive(),
                    ])
                    ->columns(2),

                Section::make(__('resources.receipt_resource.payment_section.title'))
                    ->description(__('resources.receipt_resource.payment_section.description'))
                    ->schema([
                        TextInput::make('amount')
                            ->label(__('resources.receipt_resource.fields.amount'))
                            ->placeholder(__('resources.receipt_resource.fields.amount_placeholder'))
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->required(),

                        Select::make('currency_id')
                            ->label(__('resources.receipt_resource.fields.currency_id'))
                            ->options(Currency::all()->pluck('code', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make(__('resources.receipt_resource.invoice_selection_section.title'))
                    ->description(__('resources.receipt_resource.invoice_selection_section.description'))
                    ->schema([
                        Select::make('invoices')
                            ->label(__('resources.receipt_resource.fields.invoices'))
                            ->visible(fn (Get $get) => $get('customer_id'))
                            ->multiple()
                            ->relationship(
                                name: 'invoices',
                                titleAttribute: 'code',
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    return $query->where('invoices.customer_id', $get('customer_id'));
                                }
                            )
                            ->preload()
                            ->searchable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note')
                    ->label(__('resources.receipt_resource.table.note'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('customer.name')
                    ->label(__('resources.receipt_resource.table.customer'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('resources.receipt_resource.table.type'))
                    ->sortable(),

                TextColumn::make('amount')
                    ->label(__('resources.receipt_resource.table.amount'))
                    ->money(fn ($record) => $record->currency->code ?? 'USD')
                    ->sortable(),

                TextColumn::make('currency.code')
                    ->label(__('resources.receipt_resource.table.currency'))
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-o-currency-dollar')
                    ->sortable(),

                TextColumn::make('invoices_count')
                    ->label(__('resources.receipt_resource.table.invoices_count'))
                    ->counts('invoices')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label(__('resources.receipt_resource.table.created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('resources.receipt_resource.actions.view')),
                Tables\Actions\EditAction::make()
                    ->label(__('resources.receipt_resource.actions.edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('resources.receipt_resource.actions.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceipts::route('/'),
            'create' => Pages\CreateReceipt::route('/create'),
            'view' => Pages\ViewReceipt::route('/{record}'),
            'edit' => Pages\EditReceipt::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['note', 'customer.name', 'amount'];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->note;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Customer' => $record->customer?->name,
            'Amount' => $record->amount.' '.($record->currency?->code ?? ''),
            'Type' => $record->type->getLabel(),
        ];
    }
}
