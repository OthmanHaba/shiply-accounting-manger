<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Closed = 'closed';
    case Shared = 'shared';
}
