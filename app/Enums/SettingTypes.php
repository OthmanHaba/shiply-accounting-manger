<?php

namespace App\Enums;

enum SettingTypes: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case BOOLEAN = 'boolean';
    case FLOAT = 'float';
    case ARRAY = 'array';
}
