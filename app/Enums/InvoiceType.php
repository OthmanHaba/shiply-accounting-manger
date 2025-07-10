<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoiceType: string implements HasColor, HasLabel
{
    case Closed = 'closed';
    case Shared = 'shared';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Closed => 'danger',
            self::Shared => 'warning',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Closed => trans('enums.invoice_type.closed'),
            self::Shared => trans('enums.invoice_type.shared'),
        };
    }
}
