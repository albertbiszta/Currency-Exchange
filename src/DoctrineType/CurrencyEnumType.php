<?php

namespace App\DoctrineType;

use App\Enum\Currency;

class CurrencyEnumType extends EnumType
{
    public const NAME = 'currencyEnum';

    public static function getClass(): string
    {
        return Currency::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}