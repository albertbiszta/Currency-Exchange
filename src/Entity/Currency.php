<?php

namespace App\Entity;

class Currency
{
    public const EURO_CODE = 'eur';
    public const POLISH_ZLOTY_CODE = 'pln';
    public const POUND_STERLING_CODE = 'gbp';
    public const SWISS_FRANC_CODE = 'chf';
    public const US_DOLLAR_CODE = 'usd';

    public const CURRENCY_NAMES = [
        self::EURO_CODE => 'Euro',
        self::POLISH_ZLOTY_CODE => 'Polish Zloty',
        self::POUND_STERLING_CODE => 'Pound Sterling',
        self::SWISS_FRANC_CODE => 'Swiss Franc',
        self::US_DOLLAR_CODE => 'U.S. Dollar',
    ];

    public static function getNameByCode(string $code): string
    {
       return self::CURRENCY_NAMES[$code];
    }

    public static function getFormChoices(): array
    {
        return array_flip(self::CURRENCY_NAMES);
    }
}