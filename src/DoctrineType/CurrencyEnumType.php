<?php

namespace App\DoctrineType;

use App\Enum\Currency;

final class CurrencyEnumType extends EnumType
{
    public const NAME = 'currencyEnum';

    public static function getClass(): string
    {
        return Currency::class;
    }
}