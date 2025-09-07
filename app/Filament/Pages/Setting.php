<?php

namespace App\Filament\Pages;

use App\Enums\SettingKyeTypeEnum;
use App\Models\Setting as SettingModel;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Setting extends Page
{
    use InteractsWithActions,
        InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public array $data = [];

    protected static ?string $navigationGroup = 'الاعدادات';

    protected static ?string $navigationLabel = 'الاعدادات';

    protected static ?string $title = 'الاعدادات';

    protected static string $view = 'filament.pages.setting';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        // Super-admin has access to everything
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can('view'.class_basename(self::class));
    }

    public function mount(): void
    {
        $values = [];
        \App\Models\Setting::all()->each(function (\App\Models\Setting $setting) use (&$values) {
            $values[$setting->key] = $setting->value;
        });

        $this->data = [
            'data' => $values,
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                $this->generateSettingsKeysInputs()
            )->statePath('data');
    }

    private function generateSettingsKeysInputs(): array
    {
        $settings = SettingModel::all();
        if ($settings?->isEmpty()) {
            return [];
        }
        $inputs = [];
        foreach ($settings as $setting) {
            $inputs[$setting->key] = match ($setting->type) {
                SettingKyeTypeEnum::STRING => TextInput::make($setting->key)
                    ->label(trans('setting.'.$setting->key))
                    ->required()
                    ->statepath('data.'.$setting->key),
                SettingKyeTypeEnum::NUMBER => TextInput::make($setting->key)
                    ->required()
                    ->label(trans('setting.'.$setting->key))
                    ->numeric()
                    ->statepath('data.'.$setting->key),
                SettingKyeTypeEnum::BOOLEAN => Radio::make($setting->key)
                    ->required()
                    ->label(trans('setting.'.$setting->key))
                    ->statepath('data.'.$setting->key),
                default => null,
            };
        }

        return $inputs;
    }

    public function saveAction()
    {
        return Action::make('save')
            ->label('حفظ')
            ->action(function () {
                $data = $this->data['data'];
                foreach ($data as $key => $value) {
                    SettingModel::where('key', $key)->update([
                        'value' => $value,
                    ]);
                }
            })
            ->requiresConfirmation()
            ->after(function () {
                return to_route('filament.admin.pages.dashboard');
            });
    }

    public function save() {}
}
