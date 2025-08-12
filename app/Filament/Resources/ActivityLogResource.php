<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'الاعدادات';

    protected static ?int $navigationSort = 99;

    public static function getNavigationLabel(): string
    {
        return __('activity_log.messages.activity_log');
    }

    public static function getModelLabel(): string
    {
        return 'نشاط';
    }

    public static function getPluralModelLabel(): string
    {
        return __('activity_log.messages.activity_log');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('activity_log.messages.activity_log'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('event')
                                    ->label(__('activity_log.fields.event'))
                                    ->formatStateUsing(fn (string $state): string => __("activity_log.events.{$state}"))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'created' => 'success',
                                        'updated' => 'warning',
                                        'deleted' => 'danger',
                                        default => 'gray',
                                    }),

                                TextEntry::make('log_name')
                                    ->label(__('activity_log.fields.log_name'))
                                    ->formatStateUsing(fn (string $state): string => __("activity_log.log_names.{$state}"))
                                    ->badge(),

                                TextEntry::make('subject_type')
                                    ->label(__('activity_log.fields.subject_type'))
                                    ->formatStateUsing(function (?string $state): string {
                                        if (! $state) {
                                            return '-';
                                        }

                                        $modelName = class_basename($state);
                                        $key = strtolower($modelName);

                                        return trans("activity_log.subjects.{$key}");
                                    }),

                                TextEntry::make('subject_id')
                                    ->label(__('activity_log.fields.subject_id'))
                                    ->default('-'),

                                TextEntry::make('causer.name')
                                    ->label(__('activity_log.fields.causer'))
                                    ->default(__('activity_log.messages.system')),

                                TextEntry::make('created_at')
                                    ->label(__('activity_log.fields.created_at'))
                                    ->dateTime('d/m/Y H:i:s'),
                            ]),
                    ]),

                Section::make(__('activity_log.fields.description'))
                    ->schema([
                        TextEntry::make('description')
                            ->hiddenLabel()
                            ->formatStateUsing(function (string $state, Activity $record): string {
                                // Try to get custom description
                                $key = "activity_log.activities.{$state}";
                                $translated = __($key);

                                // If translation exists, use it with replacements
                                if ($translated !== $key) {
                                    $replacements = [
                                        'subject_name' => $record->subject?->name ?? $record->subject?->code ?? 'Unknown',
                                        'subject_id' => $record->subject_id,
                                        'amount' => $record->properties['attributes']['amount'] ?? '',
                                    ];

                                    return str_replace(
                                        array_map(fn ($k) => ":{$k}", array_keys($replacements)),
                                        array_values($replacements),
                                        $translated
                                    );
                                }

                                return $state;
                            }),
                    ]),

                Section::make(__('activity_log.messages.changes_made'))
                    ->schema([
                        ViewEntry::make('changes')
                            ->hiddenLabel()
                            ->view('filament.infolists.activity-changes')
                            ->viewData(fn (Activity $record) => ['activity' => $record]),
                    ])
                    ->visible(fn (Activity $record): bool => $record->properties->isNotEmpty()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('log_name')
                    ->label(__('activity_log.fields.log_name'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("activity_log.log_names.{$state}"))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('event')
                    ->label(__('activity_log.fields.event'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => trans("activity_log.events.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('subject_type')
                    ->label(__('activity_log.fields.subject_type'))
                    ->formatStateUsing(function (?string $state): string {
                        if (! $state) {
                            return '-';
                        }

                        $modelName = class_basename($state);
                        $key = strtolower($modelName);

                        return trans("activity_log.subjects.{$key}");
                        // return trans('activity_log.subjects.invoice');

                    })
                    ->sortable(),

                TextColumn::make('subject_id')
                    ->label(__('activity_log.fields.subject_id'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label(__('activity_log.fields.description'))
                    ->formatStateUsing(function (string $state, Activity $record): string {
                        // Try to get custom description
                        $key = "activity_log.activities.{$state}";
                        $translated = __($key);

                        // If translation exists, use it with replacements
                        if ($translated !== $key) {
                            $replacements = [
                                'subject_name' => $record->subject?->name ?? $record->subject?->code ?? 'Unknown',
                                'subject_id' => $record->subject_id,
                                'amount' => $record->properties['attributes']['amount'] ?? '',
                            ];

                            return str_replace(
                                array_map(fn ($k) => ":{$k}", array_keys($replacements)),
                                array_values($replacements),
                                $translated
                            );
                        }

                        return $state;
                    })
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                TextColumn::make('causer.name')
                    ->label(__('activity_log.fields.causer'))
                    ->default('-')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('activity_log.fields.created_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('activity_log.fields.log_name'))
                    ->options([
                        'customers' => __('activity_log.log_names.customers'),
                        'invoices' => __('activity_log.log_names.invoices'),
                        'receipts' => __('activity_log.log_names.receipts'),
                        'accounts' => __('activity_log.log_names.accounts'),
                        'users' => __('activity_log.log_names.users'),
                        'treasures' => __('activity_log.log_names.treasures'),
                        'currencies' => __('activity_log.log_names.currencies'),
                        'items' => __('activity_log.log_names.items'),
                    ]),

                SelectFilter::make('event')
                    ->label(__('activity_log.fields.event'))
                    ->options([
                        'created' => __('activity_log.events.created'),
                        'updated' => __('activity_log.events.updated'),
                        'deleted' => __('activity_log.events.deleted'),
                    ]),

                SelectFilter::make('causer_id')
                    ->label(__('activity_log.fields.causer'))
                    ->relationship('causer', 'name', fn (Builder $query) => $query->where('name', '!=', 'super_admin'))
                    ->searchable(),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label(__('activity_log.fields.date_from')),
                        DatePicker::make('created_until')
                            ->label(__('activity_log.fields.date_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('activity_log.messages.view_details'))
                    ->modalHeading(fn (Activity $record): string => __('activity_log.messages.activity_details')." #{$record->id}")
                    ->infolist([
                        Section::make(__('activity_log.messages.activity_log'))
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('event')
                                            ->label(__('activity_log.fields.event'))
                                            ->formatStateUsing(fn (string $state): string => __("activity_log.events.{$state}"))
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'created' => 'success',
                                                'updated' => 'warning',
                                                'deleted' => 'danger',
                                                default => 'gray',
                                            }),

                                        TextEntry::make('log_name')
                                            ->label(__('activity_log.fields.log_name'))
                                            ->formatStateUsing(fn (string $state): string => __("activity_log.log_names.{$state}"))
                                            ->badge(),

                                        TextEntry::make('subject_type')
                                            ->label(__('activity_log.fields.subject_type'))
                                            ->formatStateUsing(function (?string $state): string {
                                                if (! $state) {
                                                    return '-';
                                                }

                                                $modelName = class_basename($state);
                                                $key = strtolower($modelName);

                                                return trans("activity_log.subjects.{$key}");
                                            }),

                                        TextEntry::make('subject_id')
                                            ->label(__('activity_log.fields.subject_id'))
                                            ->default('-'),

                                        TextEntry::make('causer.name')
                                            ->label(__('activity_log.fields.causer'))
                                            ->default(__('activity_log.messages.system')),

                                        TextEntry::make('created_at')
                                            ->label(__('activity_log.fields.created_at'))
                                            ->dateTime('d/m/Y H:i:s'),
                                    ]),
                            ]),

                        Section::make(__('activity_log.fields.description'))
                            ->schema([
                                TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->formatStateUsing(function (string $state, Activity $record): string {
                                        // Try to get custom description
                                        $key = "activity_log.activities.{$state}";
                                        $translated = __($key);

                                        // If translation exists, use it with replacements
                                        if ($translated !== $key) {
                                            $replacements = [
                                                'subject_name' => $record->subject?->name ?? $record->subject?->code ?? 'Unknown',
                                                'subject_id' => $record->subject_id,
                                                'amount' => $record->properties['attributes']['amount'] ?? '',
                                            ];

                                            return str_replace(
                                                array_map(fn ($k) => ":{$k}", array_keys($replacements)),
                                                array_values($replacements),
                                                $translated
                                            );
                                        }

                                        return $state;
                                    }),
                            ]),

                        Section::make(__('activity_log.messages.changes_made'))
                            ->schema([
                                ViewEntry::make('changes')
                                    ->hiddenLabel()
                                    ->view('filament.infolists.activity-changes')
                                    ->viewData(fn (Activity $record) => ['activity' => $record]),
                            ])
                            ->visible(fn (Activity $record): bool => $record->properties->isNotEmpty()),
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getNavigationBadge();

        return match (true) {
            $count > 50 => 'danger',
            $count > 20 => 'warning',
            $count > 0 => 'success',
            default => null,
        };
    }
}
