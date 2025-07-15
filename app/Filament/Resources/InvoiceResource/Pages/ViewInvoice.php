<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Currency;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label(__('resources.invoice_resource.actions.print'))
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn ($record) => route('invoice.print', $record))
                ->openUrlInNewTab(),
            Actions\EditAction::make()
                ->label(__('resources.invoice_resource.actions.edit')),
            Actions\DeleteAction::make()
                ->label(__('resources.invoice_resource.actions.delete'))
                ->requiresConfirmation(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Grid::make(2)
                        ->schema([
                            Section::make(__('resources.invoice_resource.invoice_details_section.title'))
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    TextEntry::make('code')
                                        ->label(__('resources.invoice_resource.fields.code'))
                                        ->icon('heroicon-o-hashtag')
                                        ->iconColor('gray')
                                        ->weight(FontWeight::Bold)
                                        ->copyable()
                                        ->copyMessage(__('resources.invoice_resource.info_list.invoice_code_copied'))
                                        ->copyMessageDuration(1500),

                                    TextEntry::make('type')
                                        ->label(__('resources.invoice_resource.fields.type'))
                                        ->badge()
                                        ->formatStateUsing(fn ($state) => $state->getLabel())
                                        ->color(fn ($state) => match ($state->value) {
                                            'closed' => 'success',
                                            'shared' => 'info',
                                            default => 'gray',
                                        }),

                                    TextEntry::make('discount')
                                        ->label(__('resources.invoice_resource.fields.discount'))
                                        ->icon('heroicon-o-receipt-percent')
                                        ->iconColor('warning')
                                        ->suffix('%')
                                        ->placeholder(__('resources.invoice_resource.info_list.no_discount'))
                                        ->weight(FontWeight::Medium),

                                    TextEntry::make('created_at')
                                        ->label(__('resources.invoice_resource.info_list.created_date'))
                                        ->icon('heroicon-o-calendar')
                                        ->iconColor('success')
                                        ->dateTime()
                                        ->since(),

                                    TextEntry::make('updated_at')
                                        ->label(__('resources.invoice_resource.info_list.last_updated'))
                                        ->icon('heroicon-o-clock')
                                        ->iconColor('gray')
                                        ->dateTime()
                                        ->since(),
                                ])
                                ->columnSpan(1),

                            Section::make(__('resources.invoice_resource.fields.customer_id'))
                                ->icon('heroicon-o-user')
                                ->schema([
                                    TextEntry::make('customer.name')
                                        ->label(__('resources.invoice_resource.info_list.customer_name'))
                                        ->icon('heroicon-o-user-circle')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::Bold)
                                        ->size('lg'),

                                    TextEntry::make('customer.code')
                                        ->label(__('resources.invoice_resource.info_list.customer_code'))
                                        ->icon('heroicon-o-hashtag')
                                        ->iconColor('gray')
                                        ->copyable(),

                                    TextEntry::make('customer.phone')
                                        ->label(__('resources.invoice_resource.info_list.phone_number'))
                                        ->icon('heroicon-o-phone')
                                        ->iconColor('success')
                                        ->copyable(),

                                    TextEntry::make('customer.created_at')
                                        ->label(__('resources.invoice_resource.info_list.customer_since'))
                                        ->icon('heroicon-o-calendar-days')
                                        ->iconColor('info')
                                        ->dateTime()
                                        ->since(),
                                ])
                                ->columnSpan(1),
                        ]),

                    Section::make(__('resources.invoice_resource.fields.notes'))
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            TextEntry::make('notes')
                                ->label(__('resources.invoice_resource.fields.notes'))
                                ->placeholder(__('resources.invoice_resource.info_list.no_notes'))
                                ->prose()
                                ->markdown()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(1)
                        ->hidden(fn ($record) => empty($record->notes)),
                ])
                    ->from('lg'),

                Section::make(__('resources.invoice_resource.summary_section.title'))
                    ->icon('heroicon-o-banknotes')
                    ->description(__('resources.invoice_resource.info_list.invoice_totals'))
                    ->schema([
                        Grid::make(['lg' => 3])
                            ->schema(
                                collect(Currency::all())->map(function ($currency) {
                                    return TextEntry::make('invoice_prices_'.$currency->code)
                                        ->label($currency->code.' Total')
                                        ->badge()
                                        ->icon('heroicon-o-currency-dollar')
                                        ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                                        ->state(function ($record) use ($currency) {
                                            $balance = $record->invoicePrices()
                                                ->where('currency_id', $currency->id)
                                                ->first()?->total_price ?? 0;

                                            return number_format($balance, 2).' '.$currency->code;
                                        })
                                        ->placeholder('0.00 '.$currency->code)
                                        ->weight(FontWeight::Bold)
                                        ->size('lg');
                                })->toArray()
                            ),
                    ])
                    ->collapsible(),

                Section::make(__('resources.invoice_resource.invoice_items_section.title'))
                    ->icon('heroicon-o-shopping-cart')
                    ->description(__('resources.invoice_resource.info_list.detailed_breakdown'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label(__('resources.invoice_resource.invoice_items_section.title'))
                            ->schema([
                                Grid::make(['lg' => 6])
                                    ->schema([
                                        TextEntry::make('item.name')
                                            ->label(__('resources.invoice_resource.info_list.item_name'))
                                            ->icon('heroicon-o-cube')
                                            ->iconColor('primary')
                                            ->weight(FontWeight::Bold)
                                            ->columnSpan(2),

                                        TextEntry::make('item_type')
                                            ->label(__('resources.invoice_resource.info_list.type'))
                                            ->badge()
                                            ->formatStateUsing(fn ($state) => $state->getLabel())
                                            ->color(fn ($state) => match ($state->value) {
                                                'box' => 'success',
                                                'package' => 'info',
                                                default => 'gray',
                                            })
                                            ->columnSpan(1),

                                        TextEntry::make('item_count')
                                            ->label(__('resources.invoice_resource.info_list.quantity'))
                                            ->icon('heroicon-o-hashtag')
                                            ->iconColor('warning')
                                            ->weight(FontWeight::Medium)
                                            ->columnSpan(1),

                                        TextEntry::make('unit_price')
                                            ->label(__('resources.invoice_resource.info_list.unit_price'))
                                            ->icon('heroicon-o-banknotes')
                                            ->iconColor('success')
                                            ->state(fn ($record) => number_format($record->unit_price, 2))
                                            ->columnSpan(1),

                                        TextEntry::make('total_price')
                                            ->label(__('resources.invoice_resource.info_list.total'))
                                            ->icon('heroicon-o-calculator')
                                            ->iconColor('primary')
                                            ->state(fn ($record) => number_format($record->total_price, 2).' '.($record->currency->code ?? ''))
                                            ->weight(FontWeight::Bold)
                                            ->columnSpan(1),

                                        TextEntry::make('weight')
                                            ->label(__('resources.invoice_resource.info_list.weight_kg'))
                                            ->icon('heroicon-o-scale')
                                            ->iconColor('gray')
                                            ->placeholder(__('resources.invoice_resource.info_list.not_specified'))
                                            ->columnSpan(2),

                                        TextEntry::make('currency.code')
                                            ->label(__('resources.invoice_resource.info_list.currency'))
                                            ->badge()
                                            ->icon('heroicon-o-currency-dollar')
                                            ->color('primary')
                                            ->columnSpan(1),

                                        TextEntry::make('description')
                                            ->label(__('resources.invoice_resource.info_list.description'))
                                            ->placeholder(__('resources.invoice_resource.info_list.no_description'))
                                            ->prose()
                                            ->columnSpan(3),
                                    ]),
                            ])
                            ->contained(false)
                            ->grid(['lg' => 1]),
                    ])
                    ->collapsible()
                    ->persistCollapsed(),

                Section::make(__('resources.invoice_resource.info_list.system_information'))
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('id')
                                    ->label(__('resources.invoice_resource.info_list.invoice_id'))
                                    ->icon('heroicon-o-key')
                                    ->iconColor('gray')
                                    ->copyable(),

                                TextEntry::make('items_count')
                                    ->label(__('resources.invoice_resource.info_list.total_items'))
                                    ->icon('heroicon-o-shopping-cart')
                                    ->iconColor('info')
                                    ->state(fn ($record) => $record->items()->count())
                                    ->badge()
                                    ->color('info'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
