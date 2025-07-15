<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReceiptType: string implements HasColor, HasLabel
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DEPOSIT => 'success',
            self::WITHDRAWAL => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DEPOSIT => trans('enums.receipt_type.deposit'),
            self::WITHDRAWAL => trans('enums.receipt_type.withdrawal'),
        };
    }
}
