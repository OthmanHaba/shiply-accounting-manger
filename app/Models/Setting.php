<?php

namespace App\Models;

use App\Enums\SettingKyeTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'value',
    ];

    protected function casts(): array
    {
        return [
            'type' => SettingKyeTypeEnum::class,
        ];
    }
}
