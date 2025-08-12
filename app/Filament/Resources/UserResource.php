<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'المستخدمين';

    //    protected static ?string $navigationGroup = __('filament-shield::filament-shield.nav.group');

    public static function getNavigationGroup(): ?string
    {
        return __('filament-shield::filament-shield.nav.group');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات المستخدم')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('الاسم الكامل')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->email()
                        ->label('البريد الإلكتروني')
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),

            Section::make('الأمان')
                ->schema([
                    TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context) => $context === 'create')
                        ->maxLength(255),

                    Select::make('roles')
                        ->label('الصلاحية')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->required(),
                ]),

            Section::make('معلومات إضافية')
                ->schema([
                    DatePicker::make('email_verified_at')
                        ->label('تاريخ تأكيد البريد')
                        ->nullable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم الكامل')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email_verified_at')
                    ->label('تم التحقق منه')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('الصلاحية')
                    ->searchable(),

            ])
            ->actions([
                EditAction::make()->label('تعديل'),
                DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
