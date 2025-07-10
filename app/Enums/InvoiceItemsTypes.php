<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoiceItemsTypes: string implements HasColor, HasLabel
{
    case Box = 'box';
    case Package = 'package';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Box => trans('enums.invoice_items_types.box'),
            self::Package => trans('enums.invoice_items_types.package'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Box => 'primary',
            self::Package => 'secondary',
        };
    }
}
