<?php

namespace App\Enums;

enum ReceiptType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
}
